var lang = require('lang.js');
var messages = require('./messages');

var L = new lang({
    messages
});

L.setLocale(document.documentElement.lang);

module.exports = L;