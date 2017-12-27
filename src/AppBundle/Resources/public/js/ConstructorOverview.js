var ConstructorOverview = new function () {
    this.type = "";
    this.size = "";
    this.material = "";
    this.thickness = "";
    this.color = "";

    this.thickness_ratio = 0;
    this.square = 0;

    this.init = function () {
        this.type = $("input.az-picture-page-constructor-type-radio:checked").data('title');
        this.size = $("select.az-picture-page-sidebar-size-select").val();
        this.material = this.type == 'Баннер' ?
            $("input.az-picture-page-constructor-material-radio:checked").val() :
            $("input.az-picture-page-constructor-material-picture-radio:checked").val();
        var thickness = $("input.z-picture-page-thickness:checked");
        this.thickness = thickness.val();
        this.thickness_ratio = thickness.data('ratio');
        this.color = '';
    };

    this.show = function () {
        $('#az-constructor-choose-type').html(this.type);
        $('#az-constructor-choose-size').html(this.size);
        $('#az-constructor-choose-material').html(this.material);
        $('#az-constructor-choose-thickness').html(this.thickness);
        $('#az-constructor-choose-color').html(this.color);
    };

    this.buildConstructor = function () {
        var thickness_div = $('div.az-picture-thickness-div');
        var banner_material_div = $('div.az-picture-banner-material-div');
        var picture_material_div = $('div.az-picture-picture-material-div');
        switch (this.type) {
            case 'Баннер':
                thickness_div.show();
                banner_material_div.show();
                picture_material_div.hide();
                break;
            case 'Картина':
                thickness_div.hide();
                banner_material_div.hide();
                picture_material_div.show();
                break;
            case 'Модульная картина':
                thickness_div.hide();
                banner_material_div.hide();
                picture_material_div.show();
                break;
        }
    };

    this.showPrice = function () {
        var price = 0;
        if (this.type == 'Баннер') {
            price = this.calculateBanner();
        } else if (this.type == 'Картина') {

        } else if (this.type == 'Модульная картина') {

        }
        $('span.az-picture-page-sidebar-price-value').html(price);
    };

    this.calculateBanner = function () {
        var average_price = this.calculateBannerSquare(),
            banner_add_price = $("input#constructor_banner_additional_price").val();

        return this.square * average_price * this.thickness_ratio + parseInt(banner_add_price);
    };

    this.calculateBannerSquare = function () {
        var minSquare = $('#constructor_banner_min_square').val(),
            maxSquare = $('#constructor_banner_max_square').val(),
            minPrice = $('#constructor_banner_min_price').val(),
            maxPrice = $('#constructor_banner_max_price').val(),
            square = 0, final_price = 0;

        if (this.size) {
            var size = this.size.split('x');
            this.square = size[0] * size[1];
        }

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


