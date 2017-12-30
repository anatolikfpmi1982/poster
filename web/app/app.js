define(function (require, exports, module) {
    require('utils');
    require('ConstructorOverview')

    function init() {
        var test = require('test');
    }

    $(document).ready(function () {
        init();

        $("#owl-example").owlCarousel({
            items: 6,
            dots: false,
            nav: true,
            navText: ["", ""]
        });

        $("#owl-template").owlCarousel({
            items: 5,
            dots: false,
            nav: true,
            navText: ["", ""]
        });

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

        // Reviews page
        // add review
        $("#add_review").submit(function (event) {
            event.stopImmediatePropagation();
            $.ajax({
                url: "/ajax/review/add",
                method: 'POST',
                data: {
                    'name': $('#az-add_review_name').val(),
                    'email': $('#az-add_review_email').val(),
                    'city': $('#az-add_review_city').val(),
                    'review': $('#az-add_review_description').val()
                }
            }).done(function (data) {
                if (data['result'])
                    alert('Спасибо! Отзыв отправлен на модерацию!');
                else
                    alert('Возникли проблемы при отправке отзыва.');
            });
            return false;
        });

        // Picture page
        // picture page constructor type
        $("input.az-picture-page-constructor-type-radio").click(function (event) {
            event.stopImmediatePropagation();
            setShowBoard();
        });

        // Picture page
        // picture page constructor material
        $("input.az-picture-page-constructor-material-radio").click(function (event) {
            event.stopImmediatePropagation();
            setShowBoard();
        });

        // Picture page
        // picture page constructor thickness
        $("input.z-picture-page-thickness").click(function (event) {
            event.stopImmediatePropagation();
            setShowBoard();
        });

        // Picture page
        // picture page constructor size
        $("select.az-picture-page-sidebar-size-select").change(function (event) {
            event.stopImmediatePropagation();
            setShowBoard();
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

        // picture cell defer button
        $("button.defer-bnt").click(function (event) {
            event.stopImmediatePropagation();
            var $this = $(this);
            var id = $this.attr('data-id');
            $.ajax({
                url: "/ajax/picture/defer",
                data: {'id': id}
            }).done(function () {
                $this.text("Отложено");
                $this.prop('disabled', true);
            });
            return false;
        });

        // picture page defer button
        $("button.picture_defer-bnt").click(function (event) {
            event.stopImmediatePropagation();
            var $this = $(this);
            var id = $this.attr('data-id');
            $.ajax({
                url: "/ajax/picture/defer",
                data: {'id': id}
            }).done(function () {
                $("button.picture_defer-bnt").text("Отложено");
                $("button.picture_defer-bnt").prop('disabled', true);
            });
            return false;
        });

        setActiveCategoryInMenu();
        setShowBoard();
    });

    function onBefore(e, opts, outgoing, incoming, forward) {
        console.log('In on before')
    }

    function setCostBoard() {

    }

    function setScreenBoard() {

    }

    function setShowBoard() {
        ConstructorOverview.init();
        ConstructorOverview.buildConstructor();
        ConstructorOverview.showPrice();
        ConstructorOverview.show();
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