function ConstructorOverview() {
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

    this.debug = false;

    // constructor panel
    this.panelNumber = 0;
    this.panelNumberVertical = 0;
    this.panelNumberHorizontal = 0;
    this.padding_left = 0;
    this.padding_top = 0;
    this.right_width = 3;
    this.top_deviation = 0;
    this.left_deviation = 0;
    this.panel_type = 'horizontal';
    this.panelSizes = [];
    this.panelActiveBlockNum = 0;
    this.panelSettings = {};
    this.panelMainLeft = this.left_deviation;
    this.panelWidthForMultiVertical = 0;
    this.panelTemplateDefault = 'single|{horizontal|100-100-0}';
    this.monitor = '';
    this.imgPath = '';
    this.picWidth = 0;
    this.picHeight = 0;
    this.formulaInput = '';
    this.maxWidth = 0;
    this.maxHeight = 0;
    this.shadow = 3;
    this.fill = false;

    this.init = function (params) {
        if (params != undefined) {
            this.type = params.type;
            this.size = params.size;
            this.material = params.material;
            this.material_id = params.material_id;
            this.thickness = params.thickness;
            this.thickness_ratio = params.thickness_ratio;
            this.frame = params.frame;
            this.module = params.module;
            this.monitor = params.monitor;
            this.imgPath = params.imgPath;
            this.picWidth = params.picWidth;
            this.picHeight = params.picHeight;
            this.formulaInput = params.formulaInput;
            this.top_deviation = params.top_deviation != undefined ? params.top_deviation : this.top_deviation;
            this.left_deviation = params.left_deviation != undefined ? params.left_deviation : this.left_deviation;
            this.padding_left = params.padding_left != undefined ? params.padding_left : this.padding_left;
            this.padding_top = params.padding_top != undefined ? params.padding_top : this.padding_top;
            this.right_width = params.right_width != undefined ? params.right_width : this.right_width;
            this.max_width = params.panel_max_width != undefined ? params.panel_max_width : this.max_width;
            this.max_height = params.panel_max_height != undefined ? params.panel_max_height : this.max_height;
            this.shadow = params.shadow != undefined ? params.shadow : this.shadow;
        } else {
            this.initDefault();
        }
    };

    this.initDefault = function () {
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
        var thickness = $("input.az-picture-page-thickness:checked");
        this.thickness = thickness.val();
        this.thickness_ratio = thickness.data('ratio');
        this.color = '';
        this.frame = $("#az-picture-constructor-frame-selected").val();
        this.mat_color = $("#az-picture-constructor-frame-color-selected").val();
        this.mat_type = $("#az-picture-page-mat-type-select").val();
        this.mat_size = $("#az-picture-page-constructor-mat-size-select").val();
        this.module = $("#az-picture-constructor-module-selected").val();
        this.monitor = $('div.az-picture-page-panel-img');
        this.imgPath = $('#input-az-picture-page-panel-img').val();
        this.picWidth = parseInt($('#input-az-picture-page-img-thumb-width').val());
        this.picHeight = parseInt($('#input-az-picture-page-img-thumb-height').val());
        this.formulaInput = $('input#az-picture-constructor-module-code-selected').val();
        this.padding_left = 20;
        this.padding_top = 10;
        this.right_width = 5;
        this.top_deviation = 40;
        this.left_deviation = 10;
        this.max_width = parseInt($('.az-picture-page-constructor-global-div').css('width'));
        this.max_height = this.picHeight + (2 * this.top_deviation);
        this.shadow = 3;
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
                this.formulaInput = this.panelTemplateDefault;
                this.getPanelInfo();
                this.buildPanelMonitor();
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
                this.getPanelInfo();
                this.buildPanelMonitor();
                panel.show();
                break;
        }
    };

    this.buildPanelMonitor = function () {
        var settings = this.panelSettings,
            that = this;

        this.calculateWidthAndHeight();
        this.monitor.html('');
        this.panelMainLeft = this.left_deviation;
        this.panelSizes = [];
        that.panelActiveBlockNum = 0;

        Object.keys(settings).map(function (objectKey, index) {
            var value = settings[objectKey];
            that.showPanelMonitor(value.type, value.data, index);
            if (that.panel_type == 'multi' && value.type == 'vertical') {
                that.panelMainLeft = that.panelMainLeft + that.padding_left + that.panelWidthForMultiVertical;
            }
        });
        this.showPanelSizes();
    };

    this.calculateWidthAndHeight = function () {
        if (this.debug) {
            console.log('START');
            console.log('isHeight', this.picHeight > this.picWidth);
            console.log('this.max_width', this.max_width);
            console.log('this.max_height', this.max_height);
            console.log('this.picHeight', this.picHeight);
            console.log('this.picWidth', this.picWidth);
            console.log('this.right_width', this.right_width);
            console.log('this.padding_top', this.padding_top);
            console.log('this.padding_left', this.padding_left);
            console.log('this.left_deviation', this.left_deviation);
            console.log('this.panelNumberVertical', this.panelNumberVertical);
            console.log('this.panelNumberHorizontal', this.panelNumberHorizontal);
        }

        if (this.max_width > 0 && this.max_height > 0) {
            var isHeight = this.picHeight > this.picWidth;
            if (isHeight) {
                var maxHeight = this.max_height - 2 * this.top_deviation - (this.right_width + this.padding_top) * (this.panelNumberVertical - 1);
                if (this.debug) {
                    console.log('maxHeight', maxHeight);
                    console.log('this.picHeight > maxHeight', this.picHeight > maxHeight);
                }
                if (this.picHeight > maxHeight) {
                    this.picWidth = Math.round((this.picWidth * maxHeight) / this.picHeight);
                    this.picHeight = maxHeight;
                }
            } else {
                var maxWidth = this.max_width - 2 * this.left_deviation - (this.padding_left + this.right_width + this.shadow) * (this.panelNumberHorizontal - 1);
                if (this.debug) {
                    console.log('maxWidth', maxWidth);
                    console.log('this.picWidth > maxWidth', this.picWidth > maxWidth);
                }
                if (this.picWidth > maxWidth) {
                    this.picHeight = Math.round((this.picHeight * maxWidth) / this.picWidth);
                    this.picWidth = maxWidth;
                }

            }

            this.top_deviation = this.top_deviation + Math.round((this.max_height - this.picHeight -
                    (this.right_width + this.padding_top) * (this.panelNumberVertical - 1) - this.shadow  ) / 2);
            this.left_deviation = this.left_deviation + Math.round((this.max_width - this.picWidth -
                    (this.right_width + this.padding_left + this.shadow) * (this.panelNumberHorizontal - 1) ) / 2);

            if (this.debug) {
                console.log('this.picWidth', this.picWidth);
                console.log('this.picHeight', this.picHeight);
                console.log('this.left_deviation', this.left_deviation);
                console.log('this.top_deviation', this.top_deviation);
            }
        }

        if (this.debug) {
            console.log('END');
        }
    };

    this.showPanelMonitor = function (type, settings, mainIndex) {

        var imgPath = this.imgPath,
            picWidth = this.picWidth,
            picHeight = this.picHeight,
            deviation = 0,
            deviation_top = 0;

        var right_width = this.right_width,
            padding_left = this.padding_left,
            padding_top = this.padding_top,
            top_deviation = this.top_deviation,
            panel_type = type,
            deviationRight = 0 - this.right_width,
            screen_height = picHeight + (2 * top_deviation),
            mainTop = top_deviation,
            size = this.size != undefined ? this.size.split('x') : [],
            that = this;

        Object.keys(settings).map(function (objectKey, index) {
            that.panelActiveBlockNum++;
            var value = settings[objectKey],
                newDivString = '<div></div>',
                newWidth = Math.round((value.width * picWidth ) / 100),
                newHeight = Math.round((value.height * picHeight ) / 100),
                showWidth = 0,
                showHeight = 0,
                ind = 0;

            switch (panel_type) {
                case 'vertical':
                    if (that.panel_type != 'multi') {
                        showHeight = Math.round((value.width * picHeight ) / 100);
                        newHeight = Math.round((value.height * showHeight ) / 100);
                    } else {
                        showHeight = picHeight;
                        newHeight = Math.round((value.height * showHeight ) / 100);
                    }
                    showWidth = picWidth;
                    ind = index;
                    deviation_top = 0;
                    while (ind > 0) {
                        if (settings[ind - 1] != undefined) {
                            deviation_top = deviation_top - Math.round((settings[ind - 1].height * showHeight ) / 100);
                        }
                        ind--;
                    }
                    break;
                case 'horizontal':
                default:
                    showWidth = picWidth;
                    newWidth = Math.round((value.width * showWidth ) / 100);
                    showHeight = picHeight;
                    mainTop = top_deviation + (value.up > 0 ? Math.round((value.up * picHeight ) / 100) : 0);
                    deviation_top = -(value.up > 0 ? Math.round((value.up * picHeight ) / 100) : 0);
                    ind = index;
                    deviation = 0;
                    deviationRight = 0;
                    while (ind > 0) {
                        if (settings[ind - 1] != undefined) {
                            deviation = deviation - Math.round((settings[ind - 1].width * showWidth ) / 100);
                            deviationRight = deviationRight + Math.round((settings[ind - 1].width * showWidth ) / 100);
                        }
                        ind--;
                    }
                    break;
            }

            if (that.panel_type == 'multi') {
                deviation = 0;
                deviationRight = 0;
                ind = mainIndex;
                var cause = '';
                while (ind > 0) {
                    cause = that.panelSettings[ind - 1] != undefined;
                    cause = cause && that.panelSettings[ind - 1]['data'] != undefined;
                    cause = cause && that.panelSettings[ind - 1]['type'] != undefined;
                    if (cause) {
                        if (that.panelSettings[ind - 1]['type'] == 'vertical' && that.panelSettings[ind - 1]['data'][0] != undefined) {
                            deviation = deviation - Math.round((that.panelSettings[ind - 1]['data'][0].width * showWidth ) / 100);
                            deviationRight = deviationRight + Math.round((that.panelSettings[ind - 1]['data'][0].width * showWidth ) / 100);
                        } else {
                            Object.keys(that.panelSettings[ind - 1]['data']).map(function (key) {
                                deviation = deviation - Math.round((that.panelSettings[ind - 1]['data'][key].width * showWidth ) / 100);
                                deviationRight = deviationRight + Math.round((that.panelSettings[ind - 1]['data'][key].width * showWidth ) / 100);
                            });
                        }

                    }
                    ind--;
                }
            }

            if (that.debug) {
                console.log('showWidth', showWidth + 'px');
                console.log('showHeight', showHeight + 'px');
                console.log('newWidth', newWidth + 'px');
                console.log('newHeight', newHeight + 'px');
            }

            var divMain = $(newDivString);
            divMain.addClass('module').addClass('constructor-block' + that.panelActiveBlockNum);
            divMain.css('left', that.panelMainLeft);
            divMain.css('top', mainTop);
            divMain.css('width', newWidth + 'px');
            divMain.css('height', newHeight + 'px');
            divMain.css('background-image', 'url(' + imgPath + ')');
            divMain.css('background-repeat', 'no-repeat');
            divMain.css('background-size', showWidth + 'px ' + showHeight + 'px');
            divMain.css('background-position', deviation + 'px ' + deviation_top + 'px');

            var divRight = $(newDivString);
            divRight.addClass('edge edger the_last');
            divRight.css('width', right_width + 'px');
            divRight.css('height', (newHeight - right_width) + 'px');
            divRight.css('background-image', 'url(' + imgPath + ')');
            divRight.css('background-size', showWidth + 'px ' + showHeight + 'px');
            divRight.css('background-position', '-' + deviationRight + 'px ' + deviation_top + 'px');
            divRight.css('right', '0');
            divRight.css('box-shadow', 'rgba(0, 0, 0, 0.4) -' + that.shadow + 'px 0px ' + that.shadow + 'px');
            divRight.css('-webkit-transform', 'rotateY(180deg) skewY(-45deg)');
            divRight.css('-webkit-transform-origin', 'right');
            divRight.css('transform', 'rotateY(180deg) skewY(-45deg)');
            divRight.css('transform-origin', 'right');
            divRight.appendTo(divMain);

            var divDown = $(newDivString);
            divDown.addClass('edge edgeb');
            divDown.css('height', right_width + 'px');
            divDown.css('background-image', 'url(' + imgPath + ')');
            divDown.css('background-size', showWidth + 'px ' + showHeight + 'px');
            divDown.css('bottom', '0');
            divDown.css('box-shadow', 'rgba(0, 0, 0, 0.4) 0 ' + that.shadow + 'px ' + that.shadow + 'px');
            divDown.css('background-position', '-' + deviationRight + 'px 5px');
            divDown.appendTo(divMain);

            divMain.appendTo(that.monitor);

            switch (panel_type) {
                case 'vertical':
                    mainTop = mainTop + newHeight + padding_top;
                    if (that.panel_type == 'multi') {
                        that.panelWidthForMultiVertical = newWidth;
                    }
                    break;
                case 'horizontal':
                default:
                    that.panelMainLeft = that.panelMainLeft + padding_left + newWidth;
                    break;
            }

            that.panelSizes[that.panelActiveBlockNum] = Math.round((value.width * size[0] ) / 100) + 'x' + Math.round((value.height * size[1] ) / 100);
        });

        $('.az-picture-page-picture-main-panel-div').css('height', screen_height + 'px');

    };

    this.getPanelInfo = function () {
        var panel_code = this.formulaInput,
            panelObject = {},
            typeArr = panel_code.split('|'),
            panelArray = [],
            count = 0,
            that = this;
        this.panelNumberVertical = 0;
        this.panelNumber = 0;

        this.panel_type = typeArr[0];
        typeArr.shift();
        var str = typeArr.join('|');
        var panelBlocks = str.split(';');

        panelBlocks.forEach(function (el, indexMain) {
            var element = el.replace(new RegExp('{', 'g'), '');
            element = element.replace(new RegExp('}', 'g'), '');
            var types = element.split('|'),
                numVerticalElements = 0,
                numHorizontalElements = 0;

            panelObject[indexMain] = {};
            panelObject[indexMain]['type'] = types[0];
            panelObject[indexMain]['data'] = {};

            panelArray = types[1].split(':');
            panelArray.forEach(function (el, index) {
                var data = el.split('-');
                panelObject[indexMain]['data'][index] = {};
                panelObject[indexMain]['data'][index]['width'] = data[0];
                panelObject[indexMain]['data'][index]['height'] = data[1];
                panelObject[indexMain]['data'][index]['up'] = data[2];
                count++;
                that.panelNumber++;
                if (panelObject[indexMain]['type'] == 'vertical') {
                    numVerticalElements++;
                } else {
                    numHorizontalElements++;
                    numVerticalElements = 1;
                }
            });

            if (panelObject[indexMain]['type'] == 'vertical' && that.panel_type == 'multi'
                && that.panelNumberVertical < numVerticalElements) {
                that.panelNumberVertical = numVerticalElements;
            } else if (that.panel_type == 'single') {
                that.panelNumberVertical = numVerticalElements;
            }

            if (panelObject[indexMain]['type'] == 'vertical') {
                that.panelNumberHorizontal++;
            } else {
                that.panelNumberHorizontal = that.panelNumberHorizontal + numHorizontalElements;
            }
        });
        this.panelSettings = panelObject;
        if (this.debug) {
            console.log('this.panelSettings', this.panelSettings);
        }
    };

    this.showPanelSizes = function () {
        var contentEl = $('div#az-picture-page-sidebar-size-panel-div-content'),
            mainEl = '', subEl = '';
        contentEl.html('<div class="row"><div class="col-12 az-picture-page-sidebar-size-panel-div-content-item">Размеры модулей</div></div>');

        this.panelSizes.forEach(function (element, index) {
            mainEl = $('<div class="row"></div>');
            subEl = $('<div class="col-12 az-picture-page-sidebar-size-panel-div-content-item"></div>');
            subEl.html(index + '. ' + element + ' см');
            subEl.appendTo(mainEl);
            mainEl.appendTo(contentEl);
        });
        var subInputEl = $('<input type="hidden" id="az-picture-constructor-module-sizes-selected" name="az-picture-constructor-module-sizes-selected" />');
        subInputEl.val(this.panelSizes.join('|'));
        subInputEl.appendTo(contentEl);
    };

    this.preShowBuild = function () {
        var subframe = $('div.az-picture-page-sidebar-choose-subframe'),
            subframe_color = $('div.az-picture-page-sidebar-choose-subframe-color'),
            frame = $('div.az-picture-page-sidebar-choose-frame'),
            mat_type = $('div.az-picture-page-sidebar-choose-mat-type'),
            mat_size = $('div.az-picture-page-sidebar-choose-mat-size'),
            module = $('div.az-picture-page-sidebar-choose-module'),
            panel_sizes = $('div#az-picture-page-sidebar-size-panel-div'),
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
                panel_sizes.hide();
                break;
            case 'В раме':
                subframe.hide();
                subframe_color.hide();
                frame.show();
                mat_type.show();
                mat_size.show();
                mat_color.show();
                module.hide();
                panel_sizes.hide();
                break;
            case 'Панно':
                subframe.show();
                subframe_color.show();
                frame.hide();
                mat_type.hide();
                mat_size.hide();
                mat_color.hide();
                module.show();
                panel_sizes.show();
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
        min_price = $('span.az-picture-page-sidebar-price-value').data('min-price');
        price = price < min_price ? min_price.toFixed(2) : price;

        $('span.az-picture-page-sidebar-price-value').html(price).data('price', price);
    };

    this.calculatePanel = function () {
        var average_price = this.calculateSquare(),
            banner_add_price = $('input#constructor_banner_' + this.material_id + '_additional_price').val(),
            banner_add_ratio = $('input#constructor_banner_' + this.material_id + '_additional_ratio').val(),
            panel_ratio = $('input#az-picture-constructor-module-ratio-selected').val(),
            panel_code = this.formulaInput,
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
}


