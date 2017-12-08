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
            window.location.replace($(this).data('filterurl'));
        });

        // category page filter radio button art|photo
        $("a.az-picture-page-material-img-a").click(function (event) {
            event.stopImmediatePropagation();
            window.location.replace($(this).data('filterurl'));
            return false;
        });
    });

    function onBefore(e, opts, outgoing, incoming, forward) {
        console.log('In on before')
    }


});