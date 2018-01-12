var ConstructorOverview = new function () {
    this.type = "";
    this.size = "";
    this.material = "";
    this.material_id = 0;
    this.thickness = "";
    this.color = "";
    this.frame = "";
    this.mat_type = "";
    this.mat_size = "";
    this.mat_color = "";
    this.module = "";

    this.thickness_ratio = 0;
    this.square = 0;
    this.perimeter = 0;

    this.init = function () {
        this.type = $("input.az-picture-page-constructor-type-radio:checked").data('title');
        this.size = $("select.az-picture-page-sidebar-size-select").val();
        var material = $("input.az-picture-page-constructor-material-radio:checked"),
            material_picture = $("input.az-picture-page-constructor-material-picture-radio:checked");
        this.material = this.type != 'В раме' ?
            material.val() :
            material_picture.val();
        this.material_id = this.type != 'В раме' ?
            material.data('id') :
            material_picture.data('id');
        var thickness = $("input.z-picture-page-thickness:checked");
        this.thickness = thickness.val();
        this.thickness_ratio = thickness.data('ratio');
        this.color = '';
        this.frame = $("#az-picture-constructor-frame-selected").val();
        this.mat_color = $("#az-picture-constructor-frame-color-selected").val();
        this.mat_type = $("#az-picture-page-mat-type-select").val();
        this.mat_size = $("#az-picture-page-constructor-mat-size-select").val();
        this.module = $("#az-picture-constructor-module-selected").val();
    };

    this.show = function () {
        this.preShowBuild();
        $('#az-constructor-choose-type').html(this.type);
        $('#az-constructor-choose-size').html(this.size);
        $('#az-constructor-choose-material').html(this.material);
        $('#az-constructor-choose-thickness').html(this.thickness);
        $('#az-constructor-choose-color').html(this.color);
        $('#az-constructor-choose-frame').html(this.frame);
        $('#az-constructor-choose-mat-type').html(this.mat_type);
        $('#az-constructor-choose-mat-size').html(this.mat_size + ' см');
        $('#az-constructor-choose-mat-color').html(this.mat_color);
        $('#az-constructor-choose-module').html(this.module);
        this.showMonitor();
    };

    this.showMonitor = function () {
        var banner = $('div.az-picture-page-picture-main-banner-div'),
            picture = $('div.az-picture-page-picture-main-picture-div'),
            panel = $('div.az-picture-page-picture-main-panel-div');
        switch (this.type) {
            case 'Баннер':
                banner.show();
                picture.hide();
                panel.hide();
                break;
            case 'В раме':
                banner.hide();
                picture.show();
                panel.hide();
                break;
            case 'Панно':
                banner.hide();
                picture.hide();
                this.showPanelMonitor();
                panel.show();
                break;
        }
    };

    this.showPanelMonitor = function () {
        var imgPath = $('#input-az-picture-page-panel-img').val(),
            monitor = $('div.az-picture-page-panel-img');


        var img = $('<img id="dynamic" class="az-picture-page-constructor-main-img">'); //Equivalent: $(document.createElement('img'))
        img.attr('src', imgPath);
        img.appendTo(monitor);
    };

    this.getPanelInfo = function () {
        var panel_code = $('input#az-picture-constructor-module-code-selected').val(),
            panelObject = {};

        return panelObject;
    };

    this.preShowBuild = function () {
        var subframe = $('div.az-picture-page-sidebar-choose-subframe'),
            subframe_color = $('div.az-picture-page-sidebar-choose-subframe-color'),
            frame = $('div.az-picture-page-sidebar-choose-frame'),
            mat_type = $('div.az-picture-page-sidebar-choose-mat-type'),
            mat_size = $('div.az-picture-page-sidebar-choose-mat-size'),
            module = $('div.az-picture-page-sidebar-choose-module'),
            mat_color = $('div.az-picture-page-sidebar-choose-mat-color');
        switch (this.type) {
            case 'Баннер':
                subframe.show();
                subframe_color.show();
                frame.hide();
                mat_type.hide();
                mat_size.hide();
                mat_color.hide();
                module.hide();
                break;
            case 'В раме':
                subframe.hide();
                subframe_color.hide();
                frame.show();
                mat_type.show();
                mat_size.show();
                mat_color.show();
                module.hide();
                break;
            case 'Панно':
                subframe.show();
                subframe_color.show();
                frame.hide();
                mat_type.hide();
                mat_size.hide();
                mat_color.hide();
                module.show();
                break;
        }
    };

    this.buildConstructor = function () {
        var thickness_div = $('div.az-picture-thickness-div'),
            thickness_picture_div = $('div.az-picture-page-slider-material-div'),
            banner_material_div = $('div.az-picture-banner-material-div'),
            picture_material_div = $('div.az-picture-picture-material-div'),
            template_div = $('div.az-picture-page-constructor-template-div'),
            picture_paspartu_div = $('div.az-picture-page-slider-paspartu-div');

        switch (this.type) {
            case 'Баннер':
                thickness_div.show();
                thickness_picture_div.hide();
                banner_material_div.show();
                picture_material_div.hide();
                picture_paspartu_div.hide();
                template_div.hide();
                break;
            case 'В раме':
                thickness_div.hide();
                thickness_picture_div.show();
                banner_material_div.hide();
                picture_material_div.show();
                picture_paspartu_div.show();
                template_div.hide();
                break;
            case 'Панно':
                thickness_div.show();
                thickness_picture_div.hide();
                banner_material_div.show();
                picture_material_div.hide();
                picture_paspartu_div.hide();
                template_div.show();
                break;
        }
    };

    this.showPrice = function () {
        var price = 0;
        switch (this.type) {
            case 'Баннер':
                price = this.calculateBanner();
                break;
            case 'В раме':
                price = this.calculatePicture();
                break;
            case 'Панно':
                price = this.calculatePanel();
                break;
        }
        $('span.az-picture-page-sidebar-price-value').html(price);
    };

    this.calculatePanel = function () {
        var average_price = this.calculateSquare(),
            banner_add_price = $('input#constructor_banner_' + this.material_id + '_additional_price').val(),
            banner_add_ratio = $('input#constructor_banner_' + this.material_id + '_additional_ratio').val(),
            panel_ratio = $('input#az-picture-constructor-module-ratio-selected').val(),
            panel_code = $('input#az-picture-constructor-module-code-selected').val(),
            picture_price = $('#constructor_picture_price').val(),
            picture_ratio = $('#constructor_picture_ratio').val();

        var num = panel_code.split(':');
        return (((this.square * average_price + parseInt(picture_price) + parseInt(num.length) * parseInt(panel_ratio)) * parseInt(picture_ratio) +
        parseInt(banner_add_price)) * this.thickness_ratio * parseInt(banner_add_ratio)).toFixed(2);
    };

    this.calculateBanner = function () {
        var average_price = this.calculateSquare(),
            banner_add_price = $('input#constructor_banner_' + this.material_id + '_additional_price').val(),
            banner_add_ratio = $('input#constructor_banner_' + this.material_id + '_additional_ratio').val(),
            picture_price = $('#constructor_picture_price').val(),
            picture_ratio = $('#constructor_picture_ratio').val();

        return (((this.square * average_price + parseInt(picture_price)) * parseInt(picture_ratio) + parseInt(banner_add_price)) *
        this.thickness_ratio * parseInt(banner_add_ratio)).toFixed(2);
    };

    this.calculatePicture = function () {
        var average_price = this.calculateSquare(),
            add_price = $('input#constructor_pic_' + this.material_id + '_additional_price').val(),
            add_ratio = $('input#constructor_pic_' + this.material_id + '_additional_ratio').val(),
            frame_ratio = $('#az-picture-constructor-frame-ratio-selected').val(),
            picture_price = $('#constructor_picture_price').val(),
            picture_ratio = $('#constructor_picture_ratio').val();

        return (((this.square * average_price + parseInt(picture_price) + (this.perimeter * this.calculateFrameSquare())) * parseInt(picture_ratio) +
        parseInt(add_price)) * frame_ratio * parseInt(add_ratio)).toFixed(2);
    };

    this.calculateSquare = function () {
        var type = this.type != this.type != 'В раме' ? 'banner' : 'pic';
        var minSquare = $('#constructor_' + type + '_' + this.material_id + '_min_square').val(),
            maxSquare = $('#constructor_' + type + '_' + this.material_id + '_max_square').val(),
            minPrice = $('#constructor_' + type + '_' + this.material_id + '_min_price').val(),
            maxPrice = $('#constructor_' + type + '_' + this.material_id + '_max_price').val(),
            final_price = 0;

        if (this.size) {
            var size = this.size.split('x');
            this.square = size[0] * size[1] * 0.0001;
            this.perimeter = size[0] * 0.02 + 0.02 * size[1];
        }

        if (this.square <= minSquare) {
            final_price = maxPrice;
        } else if (this.square >= maxSquare) {
            final_price = minPrice;
        } else {
            final_price = (this.square - parseInt(minSquare)) * ((minPrice - maxPrice) / (maxSquare - minSquare)) + parseInt(maxPrice);
        }
        return final_price;
    };

    this.calculateFrameSquare = function () {
        var minSquare = $('#constructor_min_square').val(),
            maxSquare = $('#constructor_max_square').val(),
            minPrice = $('#constructor_min_price').val(),
            maxPrice = $('#constructor_max_price').val(),
            final_price = 0;

        if (this.square <= minSquare) {
            final_price = maxPrice;
        } else if (this.square >= maxSquare) {
            final_price = minPrice;
        } else {
            final_price = (this.square - parseInt(minSquare)) * ((minPrice - maxPrice) / (maxSquare - minSquare)) + parseInt(maxPrice);
            final_price = Math.ceil(final_price);
        }
        return final_price;
    };
};


