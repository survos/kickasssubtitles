var $ = require('jquery');
var toastr = require('toastr');
var lang = require('../lang.js');

module.exports = {

    data: function () {
        return {
            working: false,
            items: [],
        }
    },

    computed: {
        items_left: function () {
            return this.limit - this.items.length;
        }
    },

    created: function () {
        this.bindDragDropEvents();
    },

    methods: {

        bindDragDropEvents: function () {
            var self = this;
            var dragDropOverlay = $('#drag-drop-overlay');
            $(window).on('dragover', function (e) {
                e.preventDefault();
                dragDropOverlay.addClass('-active');
            });
            $(window).on('dragleave', function (e) {
                e.preventDefault();
                dragDropOverlay.removeClass('-active');
            });
            $(window).on('drop', function (e) {
                e.preventDefault();
                dragDropOverlay.removeClass('-active');
                var files = e.originalEvent.dataTransfer.files;
                self.addFiles(files);
            });
        },

        change: function (e) {
            var self = this;
            this.addFiles(e.target.files);
            this.resetFileInput(e.target);
        },

        addFiles: function (files) {
            var self = this;
            $.each(files, function (index, file) {
                self.addFile(file);
            });
        },

        addFile: function (file) {
            if (this.items_left === 0) {
                toastr.error(lang.get('js.file_limit_reached'));
                return;
            }
            if (this.exists(file)) {
                toastr.error(lang.get('js.file_already_added') + ': ' + file.name);
                return;
            }
            valid = this.validateItem(file);
            if (!valid) {
                return;
            }
            var item = this.createItem(file);
            this.items.push(item);
        },

        exists: function (file) {
            var exists = false;
            $.each(this.items, function (index, item) {
                if (
                    item.file.name === file.name &&
                    item.file.size === file.size &&
                    item.file.lastModified === file.lastModified
                ) {
                    exists = true;
                    return false;
                }
            });
            return exists;
        },

        resetFileInput: function (input) {
            $(input).wrap('<form>').closest('form').get(0).reset();
            $(input).unwrap();
        },

        remove: function(index) {
            this.items.splice(index, 1);
        },

        bulkRemove: function () {
            this.items = [];
        },

        bulkSet: function (e, field) {
            var val = $(e.target).val();
            if (val === '') {
                val = null;
            }
            var self = this;

            // http://stackoverflow.com/questions/10934664/convert-string-in-dot-notation-to-get-the-object-reference
            var set = function (obj, str, val) {
                str = str.split(".");
                while (str.length > 1) {
                    obj = obj[str.shift()];
                }
                return obj[str.shift()] = val;
            };

            $.each(this.items, function (index, item) {
                set(self.items[index], field, val);
            });
        }

    }

};
