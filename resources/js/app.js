var toastr = require('toastr');
var lang = require('./lang.js');
var $ = require('jquery');
var Cookies = require('js-cookie');
var Vue = require('vue');
var PerfectScrollbar = require('perfect-scrollbar').default;

Vue.component('converter', require('./components/Converter.vue'));
Vue.component('searcher', require('./components/Searcher.vue'));

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ajaxError(function (e, xhr) {
    var error = lang.get('js.unknown_error');
    if (typeof xhr.responseJSON.message !== 'undefined') {
        error = xhr.responseJSON.message;
    }
    toastr.error(error);
});

window.fetchTasksGroup = function () {

    $(document).on('click', '.js-remove-animation', function(){
        $(this).find('.animated').removeClass('animated');
    });

    var serverHtml = null;
    var timeout = 5000;

    var getData = function () {
        var xhr = $.get(window.location).done(function (html) {
            // do not update DOM if nothing has changed
            if (serverHtml === html) {
                setTimeout(function(){
                    getData();
                }, timeout);
                return;
            }
            serverHtml = html;
            $('#tasks-group').html(html);
            // do not poll if tasks group is processed
            if ($(html).hasClass('js-tasks-group-processed')) {
                return;
            }
            setTimeout(function(){
                getData();
            }, timeout);
        }).fail(function () {
            toastr.error(lang.get('js.unknown_error'));
        });
    };

    if (!$('.js-tasks-group-processed').length) {
        setTimeout(function(){
            getData();
        }, timeout);
    }
};

(function () {
    if (typeof Cookies.get('timezone') !== 'undefined') {
        return;
    }
    var timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    if (timezone) {
        Cookies.set('timezone', timezone, {
            expires: 365
        });
    }
})();

var scrollingSidebar = new PerfectScrollbar('#scrolling-sidebar', {
    wheelPropagation: false,
    suppressScrollX: true
});

$('.js-toggle-preview').click(function(){
    var $me = $(this);
    var $preview = $me.closest('tr').next();

    var toggle = function () {
        $me.find('span').toggle();
        $preview.toggle();
    };

    if ($me.data('loaded')) {
        toggle();
        return false;
    }

    if ($me.data('loading')) {
        return false;
    }
    $me.data('loading', true);
    var xhr = $.get($me.data('endpoint')).done(function (subtitle) {
        $preview.find('pre').html(subtitle.contents);
        toggle();
        $me.data('loaded', true);
    }).always(function(){
        $me.data('loading', false);
    });

    return false;
});

$('form#download').submit(function(){
    var $me = $(this);

    if ($me.data('loading')) {
        return false;
    }

    if (!$me.find('input[type=checkbox]:checked').length) {
        toastr.error(lang.get('js.empty_selection'));
        return false;
    }

    $me.data('loading', true);
    $me.find('button[type="submit"] span').toggle();
    var xhr = $.post($me.attr('action'), $me.serialize()).done(function (data) {
        window.location.href = data.url;
    }).fail(function(){
        $me.data('loading', false);
        $me.find('button[type="submit"] span').toggle();
    });

    return false;
});

var app = null;
if (document.getElementById('app')) {
    app = new Vue({
        el: '#app'
    });
}
