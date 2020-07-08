<script>

    var $ = require('jquery');
    var List = require('../mixins/List.js');
    var SparkMD5 = require('spark-md5');
    var Hash = require('hash');
    var async = require('async');

    Hash.SparkMD5ArrayBufferFactory = function () {
        return new SparkMD5.ArrayBuffer();
    };

    export default {

        template: '#finder-template',

        props: [
            'limit',
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
                item.hashes = {};
                return item;
            },

            validateItem: function (file) {
                return true;
            },

            hashItems: function (callback) {
                var self = this;
                var tasks = [];

                var taskFunctionFactory = function (i, provider, file) {
                    return function (taskCallback) {
                        Hash.browser[provider](file, function (f, hash) {
                            taskCallback(null, {
                                index: i,
                                provider: provider,
                                hash: hash
                            });
                        });
                    };
                };

                for (var i = 0; i < self.items.length; i++) {
                    for (var provider in Hash.browser) {
                        tasks.push(taskFunctionFactory(i, provider, self.items[i].file));
                    }
                }

                async.parallelLimit(tasks, 5, function (err, results) {
                    for (var i = 0; i < results.length; i++) {
                        var result = results[i];
                        self.items[result.index].hashes[result.provider] = result.hash;
                    }
                    callback();
                });
            },

            submit: function () {
                var self = this;
                if (self.working) {
                    return;
                }
                self.working = true;

                self.hashItems(function() {

                    var formData = new FormData();

                    $.each(self.items, function (index, item) {
                        formData.append('items[' + index + '][filename]', item.filename);
                        formData.append('items[' + index + '][filesize]', item.filesize);
                        formData.append('items[' + index + '][encoding]', item.encoding);
                        formData.append('items[' + index + '][format]', item.format);
                        formData.append('items[' + index + '][language]', item.language);
                        for (var provider in item.hashes) {
                            formData.append(
                                'items[' + index + '][hash_' + provider + ']',
                                item.hashes[provider]
                            );
                        }
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
                });
            }

        }

    }

</script>
