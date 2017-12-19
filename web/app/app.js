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

        // Any page
        // category menu main category event
        $("div.az-sidebar-catalog-item-header").click(function (event) {
            event.stopImmediatePropagation();
            var id = $(this).data('id'), active_id = $('#block_menu_active_category').val();
            if (id !== active_id) {
                window.location.replace($(this).data('url'));
            } else {
                $('div.category-menu').hide();
                $('div.subcategory-menu').hide();
                $('div.children-category-' + id).show();
            }
            return false;
        });

        // category menu main category event
        $("div.az-sidebar-subcategory-item").click(function (event) {
            event.stopImmediatePropagation();
            var id = $(this).data('id'), parent = $(this).data('parent-id'), active_id = $('#block_menu_active_category').val();
            if (id !== active_id) {
                window.location.replace($(this).data('url'));
            } else {
                $('div.category-menu').hide();
                $('div.subcategory-menu').hide();
                $('div.children-category-' + parent).show();
                $('div.children-subcategory-' + parent).show();
                $('div.children-subsubcategory-' + id).show();
            }
            return false;
        });

        setActiveCategoryInMenu();
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

    function setActiveCategoryInMenu() {
        var activeCategoryId = $('#block_menu_active_category').val(),
            activeCategoryParentId = $('#block_menu_active_category_parent').val(),
            activeCategoryParentParentId = $('#block_menu_active_category_parent_parent').val();
        if (activeCategoryId != 0) {
            $('div.category-menu').hide();
            $('div.subcategory-menu').hide();
            if (activeCategoryParentParentId != 0 && activeCategoryParentId != 0) {
                $('div.children-category-' + activeCategoryParentParentId).show();
                $('div.children-subcategory-' + activeCategoryParentParentId).show();
                $('div.children-subsubcategory-' + activeCategoryParentId).show();
            } else if (activeCategoryParentId != 0) {
                $('div.children-category-' + activeCategoryParentId).show();
                $('div.children-subcategory-' + activeCategoryParentId).show();
                $('div.children-subsubcategory-' + activeCategoryId).show();
            } else {
                $('div.children-category-' + activeCategoryId).show();
                $('div.children-subcategory-' + activeCategoryId).show();
            }
        }
        return false;
    }

});