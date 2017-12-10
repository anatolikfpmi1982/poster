define(function (require, exports, module) {
    require('utils');


    function init() {
        var test = require('test');
    }

    $(document).ready(function () {
        init();

        // Category page
        // category page filter radio button art|photo
        $("input.az-category-page-filter-radio").click(function (event) {
            event.stopImmediatePropagation();
            window.location.replace($(this).data('filterurl'));
        });

        // category page filter form
        $("a.az-picture-page-material-img-a").click(function (event) {
            event.stopImmediatePropagation();
            window.location.replace($(this).data('filterurl'));
            return false;
        });

        // category page filter clear
        $("button.az-category-page-filter-clear-btn").click(function (event) {
            event.stopImmediatePropagation();
            window.location.replace($(this).data('filterurl'));
            return false;
        });


        // Picture page
        // picture page constructor type
        $("input.az-picture-page-constructor-type-radio").click(function (event) {
            event.stopImmediatePropagation();
            setShowBoard();
            return false;
        });
    });

    function onBefore(e, opts, outgoing, incoming, forward) {
        console.log('In on before')
    }

    function setCostBoard() {

    }

    function setScreenBoard() {

    }

    function setShowBoard() {
        $("input.az-picture-page-constructor-type-radio").data('title');
    }

    function constructorUpdate() {

    }

    function constructorViewUpdate() {

    }


});