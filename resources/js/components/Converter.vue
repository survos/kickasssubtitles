<script>

    var $ = require('jquery');
    var toastr = require('toastr');
    var lang = require('../lang.js');
    var List = require('../mixins/List.js');

    export default {

        template: '#converter-template',

        props: [
            'limit',
            'filesize',
            'endpoint',
            'options'
        ],

        mixins: [
            List
        ],

        data: function () {
            return {
            }
        },

        methods: {

            createItem: function (file) {
                var item = JSON.parse(JSON.stringify(this.options));
                item.file = file;
                item.filename = file.name;
                item.filesize = file.size;
                return item;
            },

            validateItem: function (file) {
                if (file.size > parseInt(this.filesize, 10)) {
                    toastr.error(lang.get('js.file_too_large') + ': ' + file.name);
                    return false;
                }
                return true;
            },

            submit: function () {
                var self = this;
                if (self.working) {
                    return;
                }
                self.working = true;

                var formData = new FormData();

                $.each(self.items, function (index, item) {
                    formData.append('items[' + index + '][file]', item.file);
                    formData.append('items[' + index + '][filename]', item.filename);
                    formData.append('items[' + index + '][filesize]', item.filesize);
                    formData.append('items[' + index + '][input_encoding]', (item.input_encoding === null ? '' : item.input_encoding));
                    formData.append('items[' + index + '][encoding]', item.encoding);
                    formData.append('items[' + index + '][format]', item.format);
                    formData.append('items[' + index + '][language]', (item.language === null ? '' : item.language));
                });

                var xhr = $.ajax({
                    url: self.endpoint,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false
                }).done(function (data) {
                    window.location.href = data.url;
                }).fail(function () {
                    self.working = false;
                });
            }

        }

    }

</script>
