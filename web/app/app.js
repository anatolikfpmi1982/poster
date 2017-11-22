define(function (require, exports, module) {
    require('utils');


    function init() {
        var test = require('test');
    }

    $(document).ready(function () {
        init();

        $('#myCarousel2').cycle({
            fx: 'fadeout',
            speed: 300,
            timeout: 1000,
            autoHeight: 'calc',
            slides: "img",
            nextEvent: 'cycle-after',
            pager: '.carousel-indicators',
            pagerActiveClass: 'active',
            next: '#carousel-control-right',
            prev: '#carousel-control-left'
        }).on('cycle-after', function (e, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag) {
            $('.carousel-inner div').removeClass('active');
            $('#data-slide-' + $(incomingSlideEl).data('slide-num')).addClass('active');
        });
    });

    function onBefore(e, opts, outgoing, incoming, forward) {
        console.log('In on before')
    }


});