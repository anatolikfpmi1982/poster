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
    this.right_width_portrait = 20;
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
    this.imgBigPath = '';
    this.picWidth = 0;
    this.picHeight = 0;
    this.formulaInput = '';
    this.maxWidth = 0;
    this.maxHeight = 0;
    this.shadow = 3;
    this.fill = false;
    this.isLink = false;
    this.linkHref = '';
    this.imgCorner = '';
    this.imgSideT = '';
    this.imgSideR = '';
    this.imgSideB = '';
    this.imgSideL = '';

    // main params
    this.isConstructor = false;
    this.isZoom = false;
    this.zoomType = '';
    this.elemId = '';

    // show sizes for constructor
    this.showSizes = false;
    this.showSizesHeight = 10;
    this.showSizesWidth = 100;


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
            this.imgBigPath = params.imgBigPath != undefined ? params.imgBigPath : $('#input-az-picture-page-panel-big-img').val();
            this.picWidth = params.picWidth;
            this.picHeight = params.picHeight;
            this.formulaInput = params.formulaInput;
            this.top_deviation = params.top_deviation != undefined ? params.top_deviation : this.top_deviation;
            this.left_deviation = params.left_deviation != undefined ? params.left_deviation : this.left_deviation;
            this.padding_left = params.padding_left != undefined ? params.padding_left : this.padding_left;
            this.padding_top = params.padding_top != undefined ? params.padding_top : this.padding_top;
            this.right_width = params.right_width != undefined ? params.right_width : this.right_width;
            this.right_width_portrait = params.right_width_portrait != undefined ? params.right_width_portrait : this.right_width_portrait;
            this.max_width = params.panel_max_width != undefined ? params.panel_max_width : this.max_width;
            this.max_height = params.panel_max_height != undefined ? params.panel_max_height : this.max_height;
            this.shadow = params.shadow != undefined ? params.shadow : this.shadow;
            this.fill = params.fill != undefined ? params.fill : this.fill;
            this.isLink = params.link != undefined ? params.link : this.isLink;
            this.linkHref = params.href != undefined ? params.href : this.linkHref;
            this.imgCorner = params.imgCorner != undefined ? params.imgCorner : $('#az-picture-constructor-frame-img-corner-selected').val();
            this.imgSideT = params.imgSideT != undefined ? params.imgSideT : $('#az-picture-constructor-frame-img-side-t-selected').val();
            this.imgSideR = params.imgSideR != undefined ? params.imgSideR : $('#az-picture-constructor-frame-img-side-r-selected').val();
            this.imgSideB = params.imgSideB != undefined ? params.imgSideB : $('#az-picture-constructor-frame-img-side-b-selected').val();
            this.imgSideL = params.imgSideL != undefined ? params.imgSideL : $('#az-picture-constructor-frame-img-side-l-selected').val();
            this.isZoom = params.imgZoom != undefined ? params.imgZoom : this.isZoom;
            this.zoomType = params.zoomType != undefined ? params.zoomType : this.zoomType;
            this.elemId = params.elemId != undefined ? params.elemId : this.elemId;
            this.debug = params.debug != undefined ? params.debug : this.debug;
        } else {
            this.initDefault();
        }
    };

    this.initDefault = function () {
        this.type = $("input.az-picture-page-constructor-type-radio:checked").data('title');
        var sizeSelector = $("select.az-picture-page-sidebar-size-select");
        this.size = sizeSelector.val() != 'own_size' ? sizeSelector.val() :
        $('#own_width').val() + 'x' + $('#own_height').val();
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
        this.imgBigPath = $('#input-az-picture-page-panel-big-img').val();
        this.picWidth = parseInt($('#input-az-picture-page-img-thumb-width').val());
        this.picHeight = parseInt($('#input-az-picture-page-img-thumb-height').val());
        this.formulaInput = $('input#az-picture-constructor-module-code-selected').val();
        this.padding_left = 15;
        this.padding_top = 10;
        this.right_width = 6;
        this.right_width_portrait = parseInt($('#az-picture-constructor-frame-thickness-selected').val()) > 0 ?
            parseInt($('#az-picture-constructor-frame-thickness-selected').val()) :
            this.right_width_portrait;
        this.top_deviation = 20;
        this.left_deviation = 10;
        this.max_width = parseInt($('.az-picture-page-constructor-global-div').css('width'));
        this.max_height = this.picHeight + (2 * this.top_deviation);
        this.shadow = 3;
        this.imgCorner = $('#az-picture-constructor-frame-img-corner-selected').val();
        this.imgSideT = $('#az-picture-constructor-frame-img-side-t-selected').val();
        this.imgSideR = $('#az-picture-constructor-frame-img-side-r-selected').val();
        this.imgSideB = $('#az-picture-constructor-frame-img-side-b-selected').val();
        this.imgSideL = $('#az-picture-constructor-frame-img-side-l-selected').val();
        this.isConstructor = true;
    };

    this.show = function () {
        this.preShowBuild();
        $('#az-constructor-choose-type').html(this.type);
        $('#az-constructor-choose-size').html(this.size + ' см');
        $('#az-constructor-choose-material').html(this.material);
        $('#az-constructor-choose-thickness').html(this.thickness);
        $('#az-constructor-choose-color').html(this.color);
        var frameA = $('<a href="' + $('#az-picture-constructor-frame-url-selected').val() + '">' + this.frame + '</a>');
        $('#az-constructor-choose-frame').html(frameA);
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
                this.buildPortrait();
                panel.hide();
                if (this.isConstructor) {
                    $('.az-picture-page-constructor-picture-thickness-img').each(function (index) {
                        if ($(this).data('zoom-image')) {

                            var additionalData =
                                '<div class="row">' +
                                '<div class="col-md-4 col-sm-4">Артикул:</div><div class="col-md-8 col-sm-8 text-left">' + $(this).data('title') + '</div>' +
                                '<div class="col-md-4 col-sm-4">Цвет:</div><div class="col-md-8 col-sm-8 text-left">' + $(this).data('frame-color') + '</div>' +
                                '<div class="col-md-4 col-sm-4">Материал:</div><div class="col-md-8 col-sm-8 text-left">' + $(this).data('frame-material') + '</div>' +
                                '<div class="col-md-4 col-sm-4">Ширина:</div><div class="col-md-8 col-sm-8 text-left">' + $(this).data('frame-width') + '</div>' +
                                '<div class="col-md-4 col-sm-4">Высота:</div><div class="col-md-8 col-sm-8 text-left">' + $(this).data('frame-height') + '</div>' +
                                '</div>';


                            $(this).elevateZoom({
                                zoomWindowPosition: 5,
                                zoomWindowHeight: 250,
                                zoomWindowWidth: 220,
                                borderSize: 1,
                                easing: true,
                                zoomWindowContainerClass: "frame-constructor-zoom",
                                zoomContainerClass: "frame-constructor-zoom",
                                additionalData: additionalData,
                                myid: '#az-picture-page-constructor-picture-thickness-img-' + $(this).data('id')
                            })
                        }
                    });
                }
                break;
            case 'Модульная':
                banner.hide();
                picture.hide();
                this.getPanelInfo();
                this.buildPanelMonitor();
                panel.show();
                break;
        }
    };

    this.buildPortrait = function () {
        this.calculateWidthAndHeightPortrait();

        var newDivString = '<div></div>',
            right_width = this.right_width_portrait;
        var imgPath = this.imgPath,
            picWidth = this.picWidth,
            picHeight = this.picHeight,
            top_deviation = this.top_deviation,
            screen_height = picHeight + (2 * top_deviation) + 2 * right_width + 5,
            imgCorner = this.imgCorner,
            imgSideT = this.imgSideT,
            imgSideR = this.imgSideR,
            imgSideB = this.imgSideB,
            imgSideL = this.imgSideL;

        this.monitor.html('');

        var divMain = $(newDivString);
        divMain.addClass('module').addClass('portrait-block');
        divMain.css('left', this.left_deviation + right_width);
        divMain.css('top', this.top_deviation + right_width);
        divMain.css('width', picWidth + 'px');
        divMain.css('height', picHeight + 'px');
        divMain.css('background-image', 'url(' + imgPath + ')');
        divMain.css('background-repeat', 'no-repeat');
        divMain.css('background-size', picWidth + 'px ' + picHeight + 'px');
        divMain.css('background-position', '0px 0px');
        divMain.css('box-shadow', 'rgba(0, 0, 0, 0.4) ' + this.shadow + 'px ' + this.shadow + 'px ' + this.shadow + 'px');

        var isZoom = this.isConstructor && this.type != 'Модульная';
        isZoom = isZoom || (this.isZoom && this.elemId);
        isZoom = isZoom && this.imgBigPath;
        if (isZoom) {
            divMain.data('zoom-image', this.imgBigPath);
            var id = 'constructorActive' + (new Date()).getTime();
            divMain.attr('id', id);
        }

        var divCornerRT = $(newDivString);
        divCornerRT.addClass('az-picture-page-portrait-frame-corner').addClass('az-picture-page-portrait-frame-corner-rt');
        divCornerRT.css('right', '-' + right_width + 'px');
        divCornerRT.css('top', '-' + right_width + 'px');
        divCornerRT.css('width', right_width + 'px');
        divCornerRT.css('height', right_width + 'px');
        divCornerRT.css('background-image', 'url(' + imgCorner + ')');
        divCornerRT.css('background-repeat', 'no-repeat');
        divCornerRT.css('background-size', right_width + 'px ' + right_width + 'px');
        divCornerRT.css('background-position', '0px 0px');
        divCornerRT.css('transform', 'rotate(270deg)');
        divCornerRT.appendTo(divMain);

        var divCornerLT = $(newDivString);
        divCornerLT.addClass('az-picture-page-portrait-frame-corner').addClass('az-picture-page-portrait-frame-corner-lt');
        divCornerLT.css('left', '-' + right_width + 'px');
        divCornerLT.css('top', '-' + right_width + 'px');
        divCornerLT.css('width', right_width + 'px');
        divCornerLT.css('height', right_width + 'px');
        divCornerLT.css('background-image', 'url(' + imgCorner + ')');
        divCornerLT.css('background-repeat', 'no-repeat');
        divCornerLT.css('background-size', right_width + 'px ' + right_width + 'px');
        divCornerLT.css('background-position', '0px 0px');
        divCornerLT.css('transform', 'rotate(180deg)');
        divCornerLT.appendTo(divMain);

        var divCornerLB = $(newDivString);
        divCornerLB.addClass('az-picture-page-portrait-frame-corner').addClass('az-picture-page-portrait-frame-corner-lb');
        divCornerLB.css('left', '-' + right_width + 'px');
        divCornerLB.css('bottom', '-' + right_width + 'px');
        divCornerLB.css('width', right_width + 'px');
        divCornerLB.css('height', right_width + 'px');
        divCornerLB.css('background-image', 'url(' + imgCorner + ')');
        divCornerLB.css('background-repeat', 'no-repeat');
        divCornerLB.css('background-size', right_width + 'px ' + right_width + 'px');
        divCornerLB.css('background-position', '0px 0px');
        divCornerLB.css('transform', 'rotate(90deg)');
        divCornerLB.appendTo(divMain);

        var divCornerRB = $(newDivString);
        divCornerRB.addClass('az-picture-page-portrait-frame-corner').addClass('az-picture-page-portrait-frame-corner-rb');
        divCornerRB.css('right', '-' + right_width + 'px');
        divCornerRB.css('bottom', '-' + right_width + 'px');
        divCornerRB.css('width', right_width + 'px');
        divCornerRB.css('height', right_width + 'px');
        divCornerRB.css('background-image', 'url(' + imgCorner + ')');
        divCornerRB.css('background-repeat', 'no-repeat');
        divCornerRB.css('background-size', right_width + 'px ' + right_width + 'px');
        divCornerRB.css('background-position', '0px 0px');
        divCornerRB.appendTo(divMain);

        var divSideT = $(newDivString);
        divSideT.addClass('az-picture-page-portrait-frame-side').addClass('az-picture-page-portrait-frame-side-t');
        divSideT.css('left', -2);
        divSideT.css('top', '-' + right_width + 'px');
        divSideT.css('width', (picWidth + 4) + 'px');
        divSideT.css('height', right_width + 'px');
        divSideT.css('background-image', 'url(' + imgSideT + ')');
        divSideT.css('background-size', right_width + 'px ' + right_width + 'px');
        divSideT.css('background-position', '0px 0px');
        divSideT.appendTo(divMain);

        var divSideB = $(newDivString);
        divSideB.addClass('az-picture-page-portrait-frame-side').addClass('az-picture-page-portrait-frame-side-t');
        divSideB.css('left', -2);
        divSideB.css('bottom', '-' + right_width + 'px');
        divSideB.css('width', (picWidth + 4) + 'px');
        divSideB.css('height', right_width + 'px');
        divSideB.css('background-image', 'url(' + imgSideB + ')');
        divSideB.css('background-size', right_width + 'px ' + right_width + 'px');
        divSideB.css('background-position', '0px 0px');
        divSideB.appendTo(divMain);

        var divSideR = $(newDivString);
        divSideR.addClass('az-picture-page-portrait-frame-side').addClass('az-picture-page-portrait-frame-side-r');
        divSideR.css('right', '-' + right_width + 'px');
        divSideR.css('top', -2);
        divSideR.css('width', right_width + 'px');
        divSideR.css('height', (picHeight + 4) + 'px');
        divSideR.css('background-image', 'url(' + imgSideR + ')');
        divSideR.css('background-size', right_width + 'px ' + right_width + 'px');
        divSideR.css('background-position', '0px 0px');
        divSideR.appendTo(divMain);

        var divSideL = $(newDivString);
        divSideL.addClass('az-picture-page-portrait-frame-side').addClass('az-picture-page-portrait-frame-side-l');
        divSideL.css('left', '-' + right_width + 'px');
        divSideL.css('top', -2);
        divSideL.css('width', right_width + 'px');
        divSideL.css('height', (picHeight + 4) + 'px');
        divSideL.css('background-image', 'url(' + imgSideL + ')');
        divSideL.css('background-size', right_width + 'px ' + right_width + 'px');
        divSideL.css('background-position', '0px 0px');
        divSideL.appendTo(divMain);

        if (!this.isLink) {
            divMain.appendTo(this.monitor);
        } else {
            var aLink = $('<a href="' + this.linkHref + '"></a>');
            divMain.appendTo(aLink);
            aLink.appendTo(this.monitor);
        }

        var isZoom2 = this.isConstructor && this.type != 'Модульная';
        isZoom2 = isZoom2 || (this.isZoom && this.elemId);
        isZoom2 = isZoom2 && this.imgBigPath;
        if (isZoom2) {
            if (!this.isZoom) {
                var myid = this.type == 'Баннер' ? '.az-picture-page-picture-main-banner-div div div' : '.az-picture-page-picture-main-picture-div div div';
                $('#' + id).elevateZoom({
                    constrainType: "height",
                    constrainSize: 274,
                    zoomType: "lens",
                    containLensZoom: true,
                    zoomWindowPosition: id,
                    cursor: 'pointer',
                    zoomWindowContainerClass: "dynamic-constructor-zoom",
                    zoomContainerClass: "dynamic-constructor-zoom",
                    myid: myid
                });
            } else {
                if (!this.zoomType) {
                    $('#' + id).elevateZoom({
                        zoomWindowPosition: 7,
                        borderSize: 4,
                        zoomWindowWidth: 600,
                        zoomWindowHeight: 600,
                        easing: true,
                        cursor: 'pointer',
                        zoomWindowContainerClass: "constructor-zoom",
                        zoomContainerClass: "constructor-zoom",
                        myid: '#' + id
                    });
                } else {
                    $('#' + id).elevateZoom({
                        borderSize: 1,
                        zoomType: "inner",
                        cursor: 'pointer',
                        zoomWindowContainerClass: "constructor-zoom",
                        zoomContainerClass: "constructor-zoom",
                        myid: '#' + id
                    });
                }
            }
        }

        $('.az-picture-page-picture-main-picture-div').css('height', screen_height + 'px');
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

    this.calculateWidthAndHeightPortrait = function () {

        if (this.max_width > 0 && this.max_height > 0) {
            if (this.debug) {
                console.log('------------START P-----------');
                console.log('this.picWidth', this.picWidth);
                console.log('this.picHeight', this.picHeight);
                console.log('this.max_width', this.max_width);
                console.log('this.max_height', this.max_height);
            }

            var maxHeight = this.max_height - 2 * this.top_deviation - 2 * this.right_width_portrait - this.shadow;
            var maxWidth = this.max_width - 2 * this.left_deviation - 2 * this.right_width_portrait - this.shadow;

            if (this.picHeight > this.picWidth) {
                if (this.picHeight > maxHeight || this.fill) {
                    this.picWidth = Math.round((this.picWidth * maxHeight) / this.picHeight);
                    if (this.picWidth > maxWidth) {
                        this.picHeight = Math.round((maxWidth * maxHeight) / this.picWidth);
                        this.picWidth = maxWidth;
                    } else {
                        this.picHeight = maxHeight;
                    }
                } else if (this.picWidth > maxWidth) {
                    this.picHeight = Math.round((maxWidth * maxHeight) / this.picWidth);
                    this.picWidth = maxWidth;
                }
            } else {
                if (this.picWidth > maxWidth || this.fill) {
                    this.picHeight = Math.round((this.picHeight * maxWidth) / this.picWidth);
                    if (this.picHeight > maxHeight) {
                        this.picWidth = Math.round((maxWidth * maxHeight) / this.picHeight);
                        this.picHeight = maxHeight;
                    } else {
                        this.picWidth = maxWidth;
                    }
                } else if (this.picHeight > maxHeight) {
                    this.picWidth = Math.round((this.picWidth * maxHeight) / this.picHeight);
                    this.picHeight = maxHeight;
                }
            }

            if (this.debug) {
                console.log('this.top_deviation', this.top_deviation);
                console.log('this.left_deviation', this.left_deviation);
                console.log('this.right_width_portrait', this.right_width_portrait);
                console.log('maxHeight', maxHeight);
                console.log('maxWidth', maxWidth);
            }
            this.top_deviation = Math.round((this.max_height - this.picHeight - 2 * this.right_width_portrait - this.shadow  ) / 2);
            this.left_deviation = Math.round((this.max_width - this.picWidth - 2 * this.right_width_portrait - this.shadow ) / 2);

            if (this.debug) {
                console.log('this.picWidth', this.picWidth);
                console.log('this.picHeight', this.picHeight);
                console.log('this.top_deviation', this.top_deviation);
                console.log('this.right_width', this.right_width);
                console.log('this.padding_top', this.padding_top);
                console.log('this.panelNumberVertical', this.panelNumberVertical);
                console.log('this.left_deviation', this.left_deviation);
                console.log('this.padding_left', this.padding_left);
                console.log('this.shadow', this.shadow);
                console.log('this.panelNumberHorizontal', this.panelNumberHorizontal);
                console.log('------------END-----------');
            }

        }
    };

    this.calculateWidthAndHeight = function () {
        if (this.max_width > 0 && this.max_height > 0) {
            if (this.debug) {
                console.log('------------START-----------');
                console.log('this.picWidth', this.picWidth);
                console.log('this.picHeight', this.picHeight);
                console.log('this.max_width', this.max_width);
                console.log('this.max_height', this.max_height);
                console.log('this.top_deviation', this.top_deviation);
                console.log('this.left_deviation', this.left_deviation);
                console.log('this.right_width', this.right_width);
                console.log('this.showSizes', this.showSizes);
                console.log('this.showSizesWidth', this.showSizesWidth);
                console.log('this.showSizesHeight', this.showSizesHeight);
            }
            var isHeight = this.picHeight > this.picWidth;

            var maxHeight = this.max_height - 2 * this.top_deviation - (this.right_width + this.shadow) * this.panelNumberVertical -
                this.padding_top * (this.panelNumberVertical - 1) - (this.showSizes ? this.showSizesHeight : 0);
            var maxWidth = this.max_width - 2 * this.left_deviation - (this.right_width + this.shadow) * this.panelNumberHorizontal -
                (this.padding_left - this.right_width - this.shadow) * (this.panelNumberHorizontal - 1) - (this.showSizes ? this.showSizesHeight : 0);
            if (isHeight) {
                if (this.picHeight > maxHeight || this.fill) {
                    this.picWidth = Math.round((this.picWidth * maxHeight) / this.picHeight);
                    if (this.picWidth > maxWidth) {
                        this.picHeight = Math.round((maxWidth * maxHeight) / this.picWidth);
                        this.picWidth = maxWidth;
                    } else {
                        this.picHeight = maxHeight;
                    }
                } else if (this.picWidth > maxWidth) {
                    this.picHeight = Math.round((maxWidth * maxHeight) / this.picWidth);
                    this.picWidth = maxWidth;
                }
            } else {
                if (this.picWidth > maxWidth || this.fill) {
                    this.picHeight = Math.round((this.picHeight * maxWidth) / this.picWidth);
                    if (this.picHeight > maxHeight) {
                        this.picWidth = Math.round((maxWidth * maxHeight) / this.picHeight);
                        this.picHeight = maxHeight;
                    } else {
                        this.picWidth = maxWidth;
                    }
                } else if (this.picHeight > maxHeight) {
                    this.picWidth = Math.round((this.picWidth * maxHeight) / this.picHeight);
                    this.picHeight = maxHeight;
                }
            }

            if (this.debug) {
                console.log('this.top_deviation', this.top_deviation);
                console.log('this.left_deviation', this.left_deviation);
                console.log('maxHeight', maxHeight);
                console.log('maxWidth', maxWidth);
                console.log('this.showSizes', this.showSizes);
            }

            this.top_deviation = Math.round((this.max_height - this.picHeight - (this.showSizes ? this.showSizesHeight : 0) -
                (this.right_width + this.shadow) * this.panelNumberVertical - this.padding_top * (this.panelNumberVertical - 1)  ) / 2);
            this.left_deviation = Math.round((this.max_width - this.picWidth - (this.showSizes ? this.showSizesHeight : 0) -
                (this.right_width + this.shadow) * this.panelNumberHorizontal - (this.padding_left - this.right_width - this.shadow) *
                (this.panelNumberHorizontal - 1) ) / 2);

            if (this.debug) {
                console.log('this.picWidth', this.picWidth);
                console.log('this.picHeight', this.picHeight);
                console.log('this.top_deviation', this.top_deviation);
                console.log('this.right_width', this.right_width);
                console.log('this.padding_top', this.padding_top);
                console.log('this.panelNumberVertical', this.panelNumberVertical);
                console.log('this.left_deviation', this.left_deviation);
                console.log('this.padding_left', this.padding_left);
                console.log('this.shadow', this.shadow);
                console.log('this.panelNumberHorizontal', this.panelNumberHorizontal);
                console.log('------------END-----------');
            }

        }
    };

    this.showPanelMonitor = function (type, settings, mainIndex) {
        var imgPath = this.imgPath,
            picWidth = this.picWidth,
            picHeight = this.picHeight,
            deviation = 0,
            deviation_top = 0,
            mainIdEl = null;

        var right_width = this.right_width,
            padding_left = this.padding_left,
            padding_top = this.padding_top,
            top_deviation = this.top_deviation,
            panel_type = type,
            deviationRight = 0 - this.right_width,
            screen_height = picHeight + (2 * top_deviation) + (this.panelNumberVertical * this.right_width),
            mainTop = top_deviation,
            size = this.size != undefined ? this.size.split('x') : [],
            that = this, panelActiveInnerBlockNum = 0;

        Object.keys(settings).map(function (objectKey, index) {
            that.panelActiveBlockNum++;
            if (index == 0) {
                panelActiveInnerBlockNum = 1;
            } else {
                panelActiveInnerBlockNum++;
            }

            var value = settings[objectKey],
                newDivString = '<div></div>',
                newWidth = Math.round((value.width * picWidth ) / 100),
                newHeight = Math.round((value.height * picHeight ) / 100),
                showWidth = 0,
                showHeight = 0,
                ind = 0, heightVerticalPercent = 0;

            switch (panel_type) {
                case 'vertical':
                    showHeight = picHeight;
                    if (panelActiveInnerBlockNum == Object.keys(settings).length) {
                        ind = index;
                        newHeight = showHeight;
                        while (ind >= 0) {
                            if (settings[ind - 1] != undefined) {
                                newHeight = newHeight - Math.round((settings[ind - 1].height * showHeight ) / 100);
                            }
                            ind--;
                        }
                    } else {
                        newHeight = Math.round((value.height * showHeight ) / 100);
                    }
                    showWidth = picWidth;
                    ind = index;
                    deviation_top = 0;
                    while (ind > 0) {
                        if (settings[ind - 1] != undefined) {
                            deviation_top = deviation_top - Math.round((settings[ind - 1].height * showHeight ) / 100);
                            heightVerticalPercent = heightVerticalPercent + parseInt(settings[ind - 1].height);
                        }
                        ind--;
                    }
                    heightVerticalPercent = heightVerticalPercent + parseInt(value.height);
                    break;
                case 'horizontal':
                default:
                    showWidth = picWidth;
                    if (panelActiveInnerBlockNum == that.panelNumberHorizontal) {
                        ind = index;
                        newWidth = showWidth;
                        while (ind >= 0) {
                            if (settings[ind - 1] != undefined) {
                                newWidth = newWidth - Math.round((settings[ind - 1].width * showWidth ) / 100);
                            }
                            ind--;
                        }

                    } else {
                        newWidth = Math.round((value.width * showWidth ) / 100);
                    }

                    showHeight = picHeight;
                    mainTop = top_deviation + (value.up > 0 ? Math.round((value.up * picHeight ) / 100) : 0);
                    deviation_top = -(value.up > 0 ? Math.round((value.up * picHeight ) / 100) : 0);

                    ind = index;
                    deviation = 0;
                    while (ind >= 0) {
                        if (settings[ind - 1] != undefined) {
                            deviation = deviation - Math.round((settings[ind - 1].width * showWidth ) / 100);
                        }
                        ind--;
                    }
                    break;
            }

            ind = index;
            deviationRight = right_width;
            while (ind >= 0) {
                if (settings[ind] != undefined) {
                    deviationRight = deviationRight + picWidth - Math.round((settings[ind].width * showWidth ) / 100);
                    if (panel_type == 'vertical') {
                        ind = 0;
                    }
                }
                ind--;
            }

            if (that.panel_type == 'multi') {
                deviation = 0;
                ind = mainIndex;
                var cause = '';
                while (ind > 0) {
                    cause = that.panelSettings[ind - 1] != undefined;
                    cause = cause && that.panelSettings[ind - 1]['data'] != undefined;
                    cause = cause && that.panelSettings[ind - 1]['type'] != undefined;
                    if (cause) {
                        if (that.panelSettings[ind - 1]['type'] == 'vertical' && that.panelSettings[ind - 1]['data'][0] != undefined) {
                            deviation = deviation - Math.round((that.panelSettings[ind - 1]['data'][0].width * showWidth ) / 100);
                            deviationRight = deviationRight - Math.round((that.panelSettings[ind - 1]['data'][0].width * showWidth ) / 100);
                        } else {
                            Object.keys(that.panelSettings[ind - 1]['data']).map(function (key) {
                                deviation = deviation - Math.round((that.panelSettings[ind - 1]['data'][key].width * showWidth ) / 100);
                                deviationRight = deviationRight - Math.round((that.panelSettings[ind - 1]['data'][key].width * showWidth ) / 100);
                            });
                        }
                    }
                    ind--;
                }

                var compare = showWidth + deviation;
                if (that.panelNumberHorizontal == (mainIndex + 1) && Math.round((settings[ind].width * showWidth ) / 100) != compare) {
                    compare = Math.round((settings[ind].width * showWidth ) / 100) - compare;
                    deviation = deviation + compare;
                }

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
            divRight.css('height', newHeight + 'px');
            divRight.css('background-image', 'url(' + imgPath + ')');
            divRight.css('background-size', showWidth + 'px ' + showHeight + 'px');
            divRight.css('background-position', deviationRight + 'px ' + deviation_top + 'px');
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
            divDown.css('bottom', '-' + 2 * right_width + 'px');
            divDown.css('left', right_width + 'px');
            divDown.css('box-shadow', 'rgba(0, 0, 0, 0.4) 0 ' + '-' + that.shadow + 'px ' + that.shadow + 'px');
            if ((value.up > 0 && ((parseInt(value.up) + parseInt(value.height)) == 100) || (parseInt(value.up) + parseInt(value.height)) == 99)
                || heightVerticalPercent == 100 || heightVerticalPercent == 99) {
                deviation_top = 0;
            } else if (value.up > 0 && (parseInt(value.up) + parseInt(value.height)) < 100) {
                deviation_top = Math.round(((parseInt(value.up) + parseInt(value.height)) * picHeight ) / 100);
            } else if (value.up <= 0 && parseInt(value.height) < 100 && (panel_type != 'vertical' ||
                (panel_type == 'vertical' && panelActiveInnerBlockNum == 1))) {
                deviation_top = picHeight - Math.round(((100 - parseInt(value.height)) * picHeight ) / 100);
            } else if (value.up <= 0 && parseInt(value.height) < 100 && panel_type == 'vertical') {
                ind = index;
                deviation_top = 0;
                while (ind > 0) {
                    if (settings[ind - 1] != undefined) {
                        deviation_top = deviation_top - Math.round((settings[ind - 1].height * showHeight ) / 100);
                    }
                    ind--;
                }
            }
            divDown.css('background-position', deviation + 'px ' + (right_width - deviation_top) + 'px');
            divDown.appendTo(divMain);
            if (!that.isLink) {
                divMain.appendTo(that.monitor);
            } else {
                var aLink = $('<a href="' + that.linkHref + '"></a>');
                divMain.appendTo(aLink);
                aLink.appendTo(that.monitor);
            }

            var isZoom = that.isConstructor && that.type != 'Модульная';
            isZoom = isZoom || (that.isZoom && that.elemId);
            isZoom = isZoom && that.imgBigPath;
            if (isZoom) {
                divMain.data('zoom-image', that.imgBigPath);
                var id = 'constructorActive' + (new Date()).getTime();
                divMain.attr('id', id);
                mainIdEl = id;
            }

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

        switch (this.type) {
            case 'Баннер':
                $('.az-picture-page-picture-main-banner-div').css('height', screen_height + 'px');
                break;
            case 'В раме':
                break;
            case 'Модульная':
                $('.az-picture-page-picture-main-panel-div').css('height', screen_height + 'px');
                break;
        }

        var isZoom2 = this.isConstructor && this.type != 'Модульная';
        isZoom2 = isZoom2 || (this.isZoom && this.elemId);
        isZoom2 = isZoom2 && this.imgBigPath;
        if (isZoom2) {
            if (!this.isZoom) {
                var myid = this.type == 'Баннер' ? '.az-picture-page-picture-main-banner-div div div' : '.az-picture-page-picture-main-picture-div div div';
                $('#' + mainIdEl).elevateZoom({
                    constrainType: "height",
                    constrainSize: 274,
                    zoomType: "lens",
                    containLensZoom: true,
                    zoomWindowPosition: mainIdEl,
                    cursor: 'pointer',
                    zoomWindowContainerClass: "dynamic-constructor-zoom",
                    zoomContainerClass: "dynamic-constructor-zoom",
                    myid: myid
                });
            } else {
                if (!this.zoomType) {
                    $('#' + mainIdEl).elevateZoom({
                        zoomWindowPosition: 7,
                        borderSize: 4,
                        zoomWindowWidth: 600,
                        zoomWindowHeight: 600,
                        easing: true,
                        cursor: 'pointer',
                        zoomWindowContainerClass: "constructor-zoom",
                        zoomContainerClass: "constructor-zoom",
                        myid: '#' + mainIdEl
                    });
                } else {
                    $('#' + mainIdEl).elevateZoom({
                        borderSize: 1,
                        zoomType: "inner",
                        cursor: 'pointer',
                        zoomWindowContainerClass: "constructor-zoom",
                        zoomContainerClass: "constructor-zoom",
                        myid: '#' + mainIdEl
                    });
                }
            }
        }

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
            mat_color = $('div.az-picture-page-sidebar-choose-mat-color'),
            zoom_div = $('div.az-picture-page-slider-zoom-div');
        if (this.isConstructor) {
            $(".zoomContainer").remove();
            $(".zoomWindowContainer").remove();
            if (this.type == 'Баннер') {
                this.showSizes = true;
            }
        }
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
                zoom_div.show();
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
                zoom_div.show();
                break;
            case 'Модульная':
                subframe.show();
                subframe_color.show();
                frame.hide();
                mat_type.hide();
                mat_size.hide();
                mat_color.hide();
                module.show();
                panel_sizes.show();
                zoom_div.hide();
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
            case 'Модульная':
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
        var price = 0,
            picturePriceDiv = $('span.az-picture-page-sidebar-price-value');
        switch (this.type) {
            case 'Баннер':
                price = this.calculateBanner();
                break;
            case 'В раме':
                price = this.calculatePicture();
                break;
            case 'Модульная':
                price = this.calculatePanel();
                break;
        }
        var min_price = picturePriceDiv.data('min-price');
        price = price < min_price ? Math.round(min_price) : price;

        picturePriceDiv.html(price).data('price', price);
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
        return Math.round(((this.square * average_price + parseFloat(picture_price) + parseFloat(num.length) * parseFloat(panel_ratio)) *
            parseFloat(picture_ratio) + parseFloat(banner_add_price)) * this.thickness_ratio * parseFloat(banner_add_ratio));
    };

    this.calculateBanner = function () {
        var average_price = this.calculateSquare(),
            banner_add_price = $('input#constructor_banner_' + this.material_id + '_additional_price').val(),
            banner_add_ratio = $('input#constructor_banner_' + this.material_id + '_additional_ratio').val(),
            picture_price = $('#constructor_picture_price').val(),
            picture_ratio = $('#constructor_picture_ratio').val();

        return Math.round(((this.square * average_price + parseFloat(picture_price)) * parseFloat(picture_ratio) + parseFloat(banner_add_price)) *
            this.thickness_ratio * parseFloat(banner_add_ratio));
    };

    this.calculatePicture = function () {
        var average_price = this.calculateSquare(),
            add_price = $('input#constructor_pic_' + this.material_id + '_additional_price').val(),
            add_ratio = $('input#constructor_pic_' + this.material_id + '_additional_ratio').val(),
            frame_ratio = $('#az-picture-constructor-frame-ratio-selected').val(),
            picture_price = $('#constructor_picture_price').val(),
            picture_ratio = $('#constructor_picture_ratio').val();

        return Math.round(((this.square * average_price + parseFloat(picture_price) + (this.perimeter * this.calculateFrameSquare())) * parseFloat(picture_ratio) +
            parseFloat(add_price)) * frame_ratio * parseFloat(add_ratio));
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
            final_price = (this.square - parseFloat(minSquare)) * ((minPrice - maxPrice) / (maxSquare - minSquare)) + parseFloat(maxPrice);
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
            final_price = (this.square - parseFloat(minSquare)) * ((minPrice - maxPrice) / (maxSquare - minSquare)) + parseFloat(maxPrice);
            final_price = Math.ceil(final_price);
        }
        return final_price;
    };
}


