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

        // category page filter random
        $("button.az-category-page-filter-random-btn").click(function (event) {
            event.stopImmediatePropagation();
            window.location.replace($(this).data('filterurl'));
            return false;
        });

        // Picture page
        // picture page constructor type
        $("div.az-picture-page-sidebar-type-block-selector").click(function (event) {
            var formulaInput = $('input#az-picture-constructor-module-code-selected');
            event.stopImmediatePropagation();
            $('div.az-picture-page-sidebar-type-block-selector').removeClass('active');
            $(this).addClass('active');
            $('input[name="az-picture-page-type"][data-type="' + $(this).data('type') + '"]').prop('checked', true);
            if ($("input.az-picture-page-constructor-type-radio:checked").data('title') == 'Баннер') {
                formulaInput.val('single|{horizontal|100-100-0}');
            } else {
                formulaInput.val($('.az-picture-page-constructor-picture-module-type-img:eq( 1 )').data('code'));
            }
            setShowBoard();
        });

        $("div.az-picture-page-sidebar-material-banner-block-selector").click(function (event) {
            event.stopImmediatePropagation();
            $('div.az-picture-page-sidebar-material-banner-block-selector').removeClass('active');
            $(this).addClass('active');
            $('input[name="az-picture-page-material"][data-title="' + $(this).data('title') + '"]').prop('checked', true);
            setShowBoard();
        });

        $("div.az-picture-page-sidebar-thickness-block-selector").click(function (event) {
            event.stopImmediatePropagation();
            $('div.az-picture-page-sidebar-thickness-block-selector').removeClass('active');
            $(this).addClass('active');
            $('input[name="az-picture-page-thickness"][data-id="' + $(this).data('id') + '"]').prop('checked', true);
            setShowBoard();
        });

        $("div.az-picture-page-sidebar-material-picture-block-selector").click(function (event) {
            event.stopImmediatePropagation();
            $('div.az-picture-page-sidebar-material-picture-block-selector').removeClass('active');
            $(this).addClass('active');
            $('input[name="az-picture-page-material-picture"][data-id="' + $(this).data('id') + '"]').prop('checked', true);
            setShowBoard();
        });

        // picture page constructor frame
        $("img.az-picture-page-constructor-picture-thickness-img").click(function (event) {
            event.stopImmediatePropagation();
            $('#az-picture-constructor-frame-selected').val($(this).data('title'));
            $('#az-picture-constructor-frame-ratio-selected').val($(this).data('ratio'));
            $('#az-picture-constructor-frame-id-selected').val($(this).data('id'));
            $('#az-picture-constructor-frame-img-corner-selected').val($(this).data('img-corner'));
            $('#az-picture-constructor-frame-img-side-t-selected').val($(this).data('img-side-t'));
            $('#az-picture-constructor-frame-img-side-r-selected').val($(this).data('img-side-r'));
            $('#az-picture-constructor-frame-img-side-b-selected').val($(this).data('img-side-b'));
            $('#az-picture-constructor-frame-img-side-l-selected').val($(this).data('img-side-l'));
            $('img.az-picture-page-constructor-picture-thickness-img').removeClass('active');
            $(this).addClass('active');
            setShowBoard();
        });

        // picture page constructor module
        $("img.az-picture-page-constructor-picture-module-type-img").click(function (event) {
            event.stopImmediatePropagation();
            $('#az-picture-constructor-module-selected').val($(this).data('title'));
            $('#az-picture-constructor-module-id-selected').val($(this).data('id'));
            $('#az-picture-constructor-module-ratio-selected').val($(this).data('ratio'));
            $('#az-picture-constructor-module-code-selected').val($(this).data('code'));
            $('img.az-picture-page-constructor-picture-module-type-img').removeClass('active');
            $(this).addClass('active');
            setShowBoard();
        });

        // picture page constructor frame color
        $("li.az-picture-page-constructor-frame-color-item").click(function (event) {
            event.stopImmediatePropagation();
            $('#az-picture-constructor-frame-color-selected').val($(this).data('name'));
            setShowBoard();
        });

        // Picture page
        // picture page constructor size
        $("select.az-picture-page-sidebar-size-select").change(function (event) {
            event.stopImmediatePropagation();
            if ($("select.az-picture-page-sidebar-size-select").val() == 'own_size') {
                var size = $("select.az-picture-page-sidebar-size-select option:last").val();
                size = size.split('x');
                $('#own_width').val(size[0]);
                $('#own_height').val(size[1]);
                $('div.az-picture-page-sidebar-size-div-own-div').removeClass('hidden').show();
            } else {
                $('div.az-picture-page-sidebar-size-div-own-div').addClass('hidden').hide();
            }
            setShowBoard();
        });

        $('#own_width').keyup(function () {
            var width = parseInt($(this).val());
            if (width > 0) {
                var orWidth = parseInt($('#input-az-picture-page-img-thumb-width').val()),
                    orHeight = parseInt($('#input-az-picture-page-img-thumb-height').val());

                $('#own_height').val(Math.round((width * orHeight) / orWidth));
            }
            setShowBoard();
        });

        $('#own_height').keyup(function () {
            var height = parseInt($(this).val());
            if (height > 0) {
                var orWidth = parseInt($('#input-az-picture-page-img-thumb-width').val()),
                    orHeight = parseInt($('#input-az-picture-page-img-thumb-height').val());
                $('#own_width').val(Math.round((height * orWidth) / orHeight));
            }
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
            }
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
                if (data != undefined && data && data.result != undefined && data.result) {
                    $('#successReviewForm').removeClass('hidden').modal('show');
                } else
                    $('#successReviewForm').removeClass('hidden').modal('show');
            });
            return false;
        });

        // category menu main category event
        $("div.az-sidebar-subcategory-item").click(function (event) {
            event.stopImmediatePropagation();
            var id = $(this).data('id'), parent = $(this).data('parent-id'), active_id = $('#block_menu_active_category').val();
            if (id !== parseInt(active_id)) {
                window.location.replace($(this).data('url'));
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
            }).done(function (data) {
                $this.text("Отложено");
                $this.prop('disabled', true);
                var deferredBlock = $('#az-picture-page-sidebar-deffered-div'),
                    deferredSpan = $("button.az-btn-delayed-image span#az-picture-page-sidebar-deffered-div-count");
                if (data != undefined && data && data.result != undefined && data.result) {
                    deferredSpan.text(parseInt(data.count));
                    $this.text("Отложено").prop('disabled', true);
                    deferredBlock.removeClass('hidden').show();
                } else {
                    deferredBlock.addClass('hidden').hide();
                }
            });
            return false;
        });

        $('#myBtn').click(function (event) {
            event.stopImmediatePropagation();
            $('html, body').animate({scrollTop: 0}, 'slow');
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
            }).done(function (data) {
                if (data != undefined && data && data.result != undefined && data.result == 1) {
                    $('#myModal1').hide();
                    $('.modal-backdrop').hide();
                    $('#successCallbackForm').removeClass('hidden').modal('show');
                } else {
                    $('#errorCallbackForm').removeClass('hidden').modal('show');
                }
            });
            return false;
        });

        // picture page order button
        $("button.picture_order-bnt").click(function (event) {
            var materialDiv = $("input.az-picture-page-constructor-material-radio:checked"),
                underframeDiv = $("input.az-picture-page-thickness:checked"),
                materialPictureDiv = $("input.az-picture-page-constructor-material-picture-radio:checked"),
                typeDiv = $("input.az-picture-page-constructor-type-radio:checked"),
                btn = $("button.picture_order-bnt");

            event.stopImmediatePropagation();
            var $this = $(this),
                id = $this.data('id'),
                title = $this.data('title'),
                titleOwn = $this.data('title-own'),
                own_picture_id = $this.data('own-id'),
                cart_id = $this.data('cart-id'),
                price = $('span.az-picture-page-sidebar-price-value').first().data('price'),
                sizeSelector = $("select.az-picture-page-sidebar-size-select"),
                sizes = sizeSelector.val() != 'own_size' ? sizeSelector.val() :
                $('#own_width').val() + 'x' + $('#own_height').val(),
                isOwnSize = sizeSelector.val() != 'own_size' ? 0 : 1,
                banner_material_id = materialDiv.data("id"),
                banner_material_value = materialDiv.data('title'),
                underframe_id = underframeDiv.data("id"),
                underframe_value = underframeDiv.val(),
                frame_material_id = materialPictureDiv.data("id"),
                frame_material_value = materialPictureDiv.data('title'),
                frame_material_corner = $("#az-picture-constructor-frame-img-corner-selected").val(),
                frame_material_side_t = $("#az-picture-constructor-frame-img-side-t-selected").val(),
                frame_material_side_r = $("#az-picture-constructor-frame-img-side-r-selected").val(),
                frame_material_side_b = $("#az-picture-constructor-frame-img-side-b-selected").val(),
                frame_material_side_l = $("#az-picture-constructor-frame-img-side-l-selected").val(),
                frame_id = $("#az-picture-constructor-frame-id-selected").val(),
                frame_value = $("#az-picture-constructor-frame-selected").val(),
                module_type_id = $('#az-picture-constructor-module-id-selected').val(),
                module_type_value = $('#az-picture-constructor-module-selected').val(),
                module_formula = $('#az-picture-constructor-module-code-selected').val(),
                module_size = $('#az-picture-constructor-module-sizes-selected').val(),
                type_id = typeDiv.data('type'),
                type_value = typeDiv.data('title');
            $.ajax({
                url: "/ajax/cart/add",
                data: {
                    'id': id,
                    'own_picture_id': own_picture_id,
                    'cart_id': cart_id,
                    'price': price,
                    'sizes': sizes,
                    'isOwnSize': isOwnSize,
                    'banner_material_id': banner_material_id,
                    'banner_material_value': banner_material_value,
                    'underframe_id': underframe_id,
                    'underframe_value': underframe_value,
                    'frame_material_id': frame_material_id,
                    'frame_material_value': frame_material_value,
                    'frame_material_corner': frame_material_corner,
                    'frame_material_side_t': frame_material_side_t,
                    'frame_material_side_r': frame_material_side_r,
                    'frame_material_side_b': frame_material_side_b,
                    'frame_material_side_l': frame_material_side_l,
                    'frame_id': frame_id,
                    'frame_value': frame_value,
                    'module_type_id': module_type_id,
                    'module_type_value': module_type_value,
                    'module_formula': module_formula,
                    'module_size': module_size,
                    'type_id': type_id,
                    'type_value': type_value
                }
            }).done(function () {
                btn.text("В корзине");
                btn.prop('disabled', true);

                showInnerMessage('success', 'Успешно добавили картину с названием "' + title + '" в корзину.');

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
            }).done(function (data) {
                if (data != undefined && data && data.result != undefined && data.result == 1) {
                    $this.text("Удалено");
                    $this.prop('disabled', true);
                    showInnerMessage('success', 'Успешно удалили из отложенных картину с ID ' + id);

                    window.setTimeout(function () {
                        location.reload();
                    }, 3000);

                } else {
                    showInnerMessage('error', 'Произошли технические неполатки. Попробуйте еще раз через пару минут.');
                }

            });
        });

        $("button.delete-myfiles-bnt").click(function (event) {
            event.stopImmediatePropagation();
            var $this = $(this);
            var id = $this.attr('data-id');
            $.ajax({
                url: "/ajax/myfiles/delete",
                data: {'id': id}
            }).done(function (data) {
                if (data != undefined && data && data.result != undefined && data.result == 1) {
                    $this.text("Удалено");
                    $this.prop('disabled', true);

                    showInnerMessage('success', 'Успешно удалили загруженную картину');

                    window.setTimeout(function () {
                        location.reload();
                    }, 3000);
                } else {
                    showInnerMessage('error', 'Произошли технические неполатки. Попробуйте еще раз через пару минут.');
                }
            });
        });

        // picture page cart delete button
        $("button.delete_cart-btn").click(function (event) {
            event.stopImmediatePropagation();
            var $this = $(this);
            var id = $this.attr('data-id');
            $.ajax({
                url: "/ajax/cart/delete",
                data: {'id': id}
            }).done(function (data) {
                if (data != undefined && data && data.result != undefined && data.result == 1) {
                    $this.text("Удалено");
                    $this.prop('disabled', true);

                    showInnerMessage('success', 'Успешно удалили картину из корзины');
                    window.setTimeout(function () {
                        location.reload();
                    }, 1000);
                } else {
                    showInnerMessage('error', 'Произошли технические неполатки. Попробуйте еще раз через пару минут.');

                }
            });

            location.reload();
        });

        // cart block clear button
        $("button.clear_cart-btn").click(function (event) {
            event.stopImmediatePropagation();
            $.ajax({
                url: "/ajax/cart/clear"
            }).done(function (data) {
                if (data != undefined && data && data.result != undefined && data.result == 1) {
                    showInnerMessage('success', 'Успешно очистили корзину');

                    window.setTimeout(function () {
                        location.reload();
                    }, 3000);
                } else {
                    showInnerMessage('error', 'Произошли технические неполатки. Попробуйте еще раз через пару минут.');
                }
            });
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
                url: '/ajax/picture/upload',
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (respond, textStatus, jqXHR) {
                    location.href = '/myfiles';
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    showInnerMessage('error', 'Произошли технические неполатки. Попробуйте еще раз через пару минут.');
                    console.log('ОШИБКИ AJAX запроса: ' + textStatus);
                }
            });
        });

        $('div.pagination .page').click(function (event) {
            event.stopPropagation();
            event.preventDefault();
            var href = $(this).find('a').attr("href");
            window.location.replace(href);

        }).hover(function () {
            $(this).css("cursor", "pointer");
        }, function () {
            $(this).css("cursor", "none");
        });

        $('div.az-main-popular-item-img-div:has("div.category-next")').click(function (event) {
            event.stopPropagation();
            event.preventDefault();
            var href = $(this).find('a.az-nextpage').attr("href");
            window.location.replace(href);

        }).hover(function () {
            $(this).css("cursor", "pointer");
            $(this).find('a.az-nextpage').toggleClass("hovered");
        }, function () {
            $(this).css("cursor", "none");
            $(this).find('a.az-nextpage').toggleClass("hovered");
        });

        setShowBoard();
        setCartCount();
        setDeferredCount();
        setMyFilesCount();
        $('.az-picture-page-constructor-picture-thickness-img').removeClass('hidden').show();
        $('.az-picture-page-constructor-picture-module-type-img').removeClass('hidden').show();
        $('.az-main-page-slider-img').removeClass('hidden').show();

    });

    function setShowBoard() {
        var constructor = new ConstructorOverview();
        //constructor.debug = true;
        constructor.init();
        constructor.buildConstructor();
        constructor.showPrice();
        constructor.show();
    }

    function setCartCount() {
        $.ajax({
            url: "/ajax/cart/count"
        }).done(function (data) {
            $(".az-header-basket-count").text(data.count);
            if (data.count > 0) {
                $('.az-basket-title-img').addClass('blink');
            } else {
                $('.az-basket-title-img').removeClass('blink');
            }
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

    function setMyFilesCount() {
        $.ajax({
            url: "/ajax/myfiles/count"
        }).done(function (data) {
            var myfilesBlock = $('#az-picture-page-sidebar-myfiles-div');
            if (data != undefined && data && data.count != undefined && data.count > 0) {
                $("button.az-btn-myfiles-image span").text(data.count);
                myfilesBlock.removeClass('hidden').show();
            } else {
                myfilesBlock.addClass('hidden').hide();
            }
        });
        return false;
    }

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("myBtn").style.display = "block";
        } else {
            document.getElementById("myBtn").style.display = "none";
        }
    }

    function showInnerMessage(type, message) {
        var messageBlock = $('div.message-inner-div'),
            type_info = '',
            typeClass = '';
        if (message != undefined && message) {
            switch (type) {
                case 'success':
                    type_info = 'Успешно!';
                    typeClass = 'alert-success';
                    break;
                case 'error':
                    type_info = 'Ошибка!';
                    typeClass = 'alert-danger';
                    break;
                case 'info':
                    type_info = 'Инфо!';
                    typeClass = 'alert-info';
                    break;
            }

            $('#message-inner-div-type').html(type_info);
            $('#message-inner-div-info').html(message);
            messageBlock.removeClass('hidden').addClass(typeClass).show();
            $('html,body').animate({scrollTop: messageBlock.offset().top}, 'slow');

            window.setTimeout(function () {
                messageBlock.addClass('hidden').removeClass(typeClass).hide();
            }, 3000);
        }


    }

    function lazyLoad() {
        var pictures = $('div.show-constructor-img-lazy'),
            constructor;
        if (pictures.length > 0) {
            $.each(pictures, function (key, value) {
                constructor = new ConstructorOverview();
                //constructor.debug = true;
                var params = {};
                params['type'] = $(value).data('type');
                params['monitor'] = $(value);
                params['imgPath'] = $(value).data('src');
                params['picWidth'] = $(value).data('width');
                params['picHeight'] = $(value).data('height');
                params['left_deviation'] = $(value).data('left');
                params['top_deviation'] = $(value).data('top');
                params['formulaInput'] = $(value).data('code');
                params['padding_left'] = $(value).data('pad-left');
                params['padding_top'] = $(value).data('pad-top');
                params['right_width'] = $(value).data('butt');
                params['right_width_portrait'] = $(value).data('butt-portrait');
                params['panel_max_width'] = $(value).data('max-width');
                params['panel_max_height'] = $(value).data('max-height');
                params['shadow'] = $(value).data('shadow');
                params['fill'] = $(value).data('fill-in');
                params['href'] = $(value).data('href');
                params['link'] = params['href'] ? true : false;
                params['imgCorner'] = $(value).data('img-corner');
                params['imgSideT'] = $(value).data('img-side-t');
                params['imgSideR'] = $(value).data('img-side-r');
                params['imgSideB'] = $(value).data('img-side-b');
                params['imgSideL'] = $(value).data('img-side-l');
                constructor.init(params);
                constructor.show();
            });
        }
    }

    lazyLoad();
});

