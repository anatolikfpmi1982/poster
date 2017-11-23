define(function (require, exports, module) {
    require('utils');


    function init() {
        var test = require('test');
    }

    $(document).ready(function () {
        init();


    });

    function onBefore(e, opts, outgoing, incoming, forward) {
        console.log('In on before')
    }


});