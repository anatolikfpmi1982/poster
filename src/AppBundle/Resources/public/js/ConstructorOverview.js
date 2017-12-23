var ConstructorOverview = new function () {
    this.type = "";
    this.size = "";
    this.material = "aaa";
    this.thickness = "";
    this.color = "";

    this.init = function () {
        this.type = $('input.az-picture-page-constructor-type-radio:checked').data('title');
        this.size = $('select.az-picture-page-sidebar-size-select').val();
        this.material = $('input.az-picture-page-constructor-material-radio:checked').data('title');
        this.thickness = $('input.az-picture-page-constructor-type-radio:checked').data('title');
        this.color = '';
    };

    this.show = function () {
        $('#az-constructor-choose-type').html(this.type);
        $('#az-constructor-choose-size').html(this.size);
        $('#az-constructor-choose-material').html(this.material);
        $('#az-constructor-choose-thickness').html(this.thickness);
        $('#az-constructor-choose-color').html(this.color);
    };
};


