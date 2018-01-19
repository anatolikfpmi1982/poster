define(function (require, exports, module) {
    require('utils');
    require('ConstructorOverview')

    function init() {
        var test = require('test');
        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function () {
            scrollFunction()
        };
    }

    $(document).ready(function () {
        init();

        var files;

        $('#az-page-main-popular-div').show();

        $("#owl-example").owlCarousel({
            items: 6,
            dots: false,
            nav: true,
            navText: ["", ""],
            scrollPerPage: true,
            lazyLoad: true,
            slideBy: 5
        });

        $("#owl-template").owlCarousel({
            items: 5,
            dots: false,
            nav: true,
            navText: ["", ""],
            scrollPerPage: true,
            lazyLoad: true,
            slideBy: 4
        });

        $("#owl-frame").owlCarousel({
            items: 1,
            dots: false,
            nav: true,
            navText: ["", ""],
            lazyLoad: true
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

        // frames page redirect to frame page
        $("div.az-frames-page-div-item").click(function (event) {
            event.stopImmediatePropagation();
            window.location.replace($(this).data('url'));
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

        // picture page constructor frame
        $("img.az-picture-page-constructor-picture-thickness-img").click(function (event) {
            event.stopImmediatePropagation();
            $('#az-picture-constructor-frame-selected').val($(this).data('title'));
            $('#az-picture-constructor-frame-ratio-selected').val($(this).data('ratio'));
            $('#az-picture-constructor-frame-id-selected').val($(this).data('id'));
            setShowBoard();
        });

        // picture page constructor module
        $("img.az-picture-page-constructor-picture-module-type-img").click(function (event) {
            event.stopImmediatePropagation();
            $('#az-picture-constructor-module-selected').val($(this).data('title'));
            $('#az-picture-constructor-module-id-selected').val($(this).data('id'));
            $('#az-picture-constructor-module-ratio-selected').val($(this).data('ratio'));
            $('#az-picture-constructor-module-code-selected').val($(this).data('code'));
            setShowBoard();
        });

        // picture page constructor frame color
        $("li.az-picture-page-constructor-frame-color-item").click(function (event) {
            event.stopImmediatePropagation();
            $('#az-picture-constructor-frame-color-selected').val($(this).data('name'));
            setShowBoard();
        });

        // picture page constructor material
        $("input.az-picture-page-constructor-material-radio").click(function (event) {
            event.stopImmediatePropagation();
            setShowBoard();
        });

        // picture page constructor material picture
        $("input.az-picture-page-constructor-material-picture-radio").click(function (event) {
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

        // picture page constructor mat type
        $("select.az-picture-page-mat-type-select").change(function (event) {
            event.stopImmediatePropagation();
            setShowBoard();
        });

        // picture page constructor mat size
        $("select.az-picture-page-mat-size-select").change(function (event) {
            event.stopImmediatePropagation();
            setShowBoard();
        });

        // Any page
        // category menu main category event
        $("div.az-sidebar-catalog-item-header").click(function (event) {
            event.stopImmediatePropagation();
            var id = $(this).data('id'), active_id = $('#block_menu_active_category').val();
            if (id !== parseInt(active_id)) {
                window.location.replace($(this).data('url'));
            } else {
                var parent = $('div.children-category-' + id),
                    isDisplay = parent.css('display');
                $('div.category-menu').hide();
                $('div.subcategory-menu').hide();

                if (isDisplay == 'none') {
                    parent.show();
                } else {
                    parent.hide();
                }
            }
            return false;
        });

        // category menu main category event
        $("div.az-sidebar-subcategory-item").click(function (event) {
            event.stopImmediatePropagation();
            var id = $(this).data('id'), parent = $(this).data('parent-id'), active_id = $('#block_menu_active_category').val();
            if (id !== parseInt(active_id)) {
                window.location.replace($(this).data('url'));
            } else {
                var category = $('div.children-category-' + parent),
                    categorySub = $('div.children-subcategory-' + parent),
                    isDisplay = categorySub.css('display');
                $('div.category-menu').hide();
                $('div.subcategory-menu').hide();
                category.show();
                if (isDisplay == 'none') {
                    categorySub.show();
                    $('div.children-subsubcategory-' + id).show();
                } else {
                    categorySub.hide();
                    $('div.children-subsubcategory-' + id).show();
                }
            }
            return false;
        });

        // category menu main category event
        $("div.az-sidebar-subsubcategory-item").click(function (event) {
            event.stopImmediatePropagation();
            var id = $(this).data('id'), active_id = $('#block_menu_active_category').val();
            if (id !== parseInt(active_id)) {
                window.location.replace($(this).data('url'));
            }
            return false;
        });

        // picture cell defer button
        $("button.defer-bnt").click(function (event) {
            event.stopImmediatePropagation();
            var $this = $(this);
            var id = $this.attr('data-id');
            $.ajax({
                url: "/ajax/picture/defer/add",
                data: {'id': id}
            }).done(function () {
                $this.text("Отложено");
                $this.prop('disabled', true);
            });
            return false;
        });

        $('#myBtn').click(function (event) {
            event.stopImmediatePropagation();
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
            return false;
        });

        // picture page defer button
        $("button.picture_defer-bnt").click(function (event) {
            event.stopImmediatePropagation();
            var $this = $(this);
            var id = $this.attr('data-id');
            $.ajax({
                url: "/ajax/picture/defer/add",
                data: {'id': id}
            }).done(function (data) {
                var defferedBlock = $('#az-picture-page-sidebar-deffered-div'),
                    defferedSpan = $("button.az-btn-delayed-image span#az-picture-page-sidebar-deffered-div-count"),
                    defferedBtn = $("button.picture_defer-bnt");
                if (data != undefined && data && data.result != undefined && data.result) {
                    defferedSpan.text(parseInt(data.count));
                    defferedBtn.text("Отложено").prop('disabled', true);
                    defferedBlock.removeClass('hidden').show();
                } else {
                    defferedBlock.addClass('hidden').hide();
                }

            });
            return false;
        });

        // callback form
        $("#callback").submit(function (event) {
            event.stopImmediatePropagation();
            var $this = $(this);
            $.ajax({
                url: "/ajax/call/add",
                data: $this.serialize()
            }).done(function () {
                $('#myModal1').hide();
                $('.modal-backdrop').hide();
            });
            return false;
        });

        // picture page order button
        $("button.picture_order-bnt").click(function (event) {
            event.stopImmediatePropagation();
            var $this = $(this),
                id = $this.data('id'),
                cart_id = $this.data('cart-id'),
                price = $this.parent().parent().parent().find('.az-picture-page-sidebar-price-value').text(),
                sizes = $("#az-picture-page-constructor-size-select").val(),
                banner_material_id = $("input.az-picture-page-constructor-material-radio:checked").data("id"),
                banner_material_value = $("input.az-picture-page-constructor-material-radio:checked").data('title'),
                underframe_id = $("input.z-picture-page-thickness:checked").data("id"),
                underframe_value = $("input.z-picture-page-thickness:checked").val(),
                frame_material_id = $("input.az-picture-page-constructor-material-picture-radio:checked").data("id"),
                frame_material_value = $("input.az-picture-page-constructor-material-picture-radio:checked").data('title'),
                frame_id = $("#az-picture-constructor-frame-id-selected").val(),
                frame_value = $("#az-picture-constructor-frame-selected").val(),
                module_type_id = $('#az-picture-constructor-module-id-selected').val(),
                module_type_value = $('#az-picture-constructor-module-selected').val(),
                type_id = $("input.az-picture-page-constructor-type-radio:checked").data('type'),
                type_value = $("input.az-picture-page-constructor-type-radio:checked").data('title');
            $.ajax({
                url: "/ajax/cart/add",
                data: {
                    'id': id,
                    'cart_id': cart_id,
                    'price': price,
                    'sizes': sizes,
                    'banner_material_id': banner_material_id,
                    'banner_material_value': banner_material_value,
                    'underframe_id': underframe_id,
                    'underframe_value': underframe_value,
                    'frame_material_id': frame_material_id,
                    'frame_material_value': frame_material_value,
                    'frame_id': frame_id,
                    'frame_value': frame_value,
                    'module_type_id': module_type_id,
                    'module_type_value': module_type_value,
                    'type_id': type_id,
                    'type_value': type_value
                }
            }).done(function () {
                $("button.picture_order-bnt").text("В корзине");
                $("button.picture_order-bnt").prop('disabled', true);
                location.href = '/order';
            });
            return false;
        });

        // picture page defer delete button
        $("button.delete-defer-bnt").click(function (event) {
            event.stopImmediatePropagation();
            var $this = $(this);
            var id = $this.attr('data-id');
            $.ajax({
                url: "/ajax/picture/defer/delete",
                data: {'id': id}
            }).done(function () {
                $this.text("Удалено");
                $this.prop('disabled', true);

                location.reload();
            });
        });

        // picture page cart delete button
        $("button.delete_order-bnt").click(function (event) {
            event.stopImmediatePropagation();
            var $this = $(this);
            var id = $this.attr('data-id');
            $.ajax({
                url: "/ajax/cart/delete",
                data: {'id': id}
            }).done(function () {
                $this.text("Удалено");
                $this.prop('disabled', true);
            });

            location.reload();
        });

        $('input[type=file]').change(function () {
            files = this.files;
        });

        //upload file
        $('.az-form-btn-download button').click(function (event) {
            event.stopPropagation();
            event.preventDefault();

            var data = new FormData();
            $.each(files, function (key, value) {
                data.append(key, value);
            });

            $.ajax({
                url: '/app_dev.php/ajax/picture/upload',
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (respond, textStatus, jqXHR) {
                    // if( typeof respond.error === 'undefined' ){
                    //     var files_path = respond.files;
                    //     var html = '';
                    //     $.each( files_path, function( key, val ){ html += val +'<br>'; } )
                    //     $('.ajax-respond').html( html );
                    // }
                    // else{
                    //     console.log('ОШИБКИ ОТВЕТА сервера: ' + respond.error );
                    // }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('ОШИБКИ AJAX запроса: ' + textStatus);
                }
            });
        });

        setActiveCategoryInMenu();
        setShowBoard();
        setCartCount();
        setDeferredCount();
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

    function setCartCount() {
        $.ajax({
            url: "/ajax/cart/count"
        }).done(function (data) {
            $(".az-header-basket-count").text(data.count);
        });
        return false;
    }

    function setDeferredCount() {
        $.ajax({
            url: "/ajax/picture/defer/count"
        }).done(function (data) {
            var defferedBlock = $('#az-picture-page-sidebar-deffered-div');
            if (data != undefined && data && data.count != undefined && data.count > 0) {
                $("button.az-btn-delayed-image span").text(data.count);
                defferedBlock.removeClass('hidden').show();
            } else {
                defferedBlock.addClass('hidden').hide();
            }
        });
        return false;
    }

    function constructorUpdate() {

    }

    function constructorViewUpdate() {

    }

    function setActiveCategoryInMenu() {
        var activeCategoryId = parseInt($('#block_menu_active_category').val()),
            activeCategoryParentId = parseInt($('#block_menu_active_category_parent').val()),
            activeCategoryParentParentId = parseInt($('#block_menu_active_category_parent_parent').val());
        if (activeCategoryId != 0) {
            $('div.category-menu').hide();
            $('div.subcategory-menu').hide();
            if (activeCategoryParentParentId != 0 && activeCategoryParentId != 0) {
                $('div.children-category-' + activeCategoryParentParentId).show();
                $('div.children-subcategory-' + activeCategoryParentParentId).show();
                $('div.children-subsubcategory-' + activeCategoryParentId).show();
            } else if (activeCategoryParentId != 0) {
                $('div.children-category-' + activeCategoryParentId).show();
            }
        }
        return false;
    }


    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("myBtn").style.display = "block";
        } else {
            document.getElementById("myBtn").style.display = "none";
        }
    }

});

