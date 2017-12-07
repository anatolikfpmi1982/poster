define(function (require, exports, module) {
    require('utils');


    function init() {
        var test = require('test');
    }

    $(document).ready(function () {
        init();

        // category page filter radio button art|photo
        $("input.az-category-page-filter-radio").click(function (event) {
            event.stopImmediatePropagation();
            window.location.replace($("input.az-category-page-filter-radio:checked").data('filerurl'));
        });
    });

    function onBefore(e, opts, outgoing, incoming, forward) {
        console.log('In on before')
    }


});