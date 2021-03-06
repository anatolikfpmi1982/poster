require.config({
    baseUrl: '/app',
    cacheSuffix: ".buildNumber",
    'paths': {
        'jquery': '../bower_components/jquery/dist/jquery',
        'validate': '../bower_components/jquery.validation/dist/jquery.validate',
        'validate/localization': '../bower_components/jquery.validation/src/localization/messages_ru',
        'underscore': '../bower_components/underscore/underscore',
        'bootstrap': '../bower_components/bootstrap/dist/js/bootstrap',
        'cycle2': '../bower_components/jquery.cycle2/index',
        'cycle2.center': '../bower_components/jquery-cycle2/src/jquery.cycle2.center',
        'ConstructorOverview': '../bundles/app/js/ConstructorOverview',
        //'owl': '../bower_components/owl.carousel/dist/owl.carousel.min',
        'owl': '../bower_components/owl.carousel/dist/owl.carousel',
        //'elevatezoom': '../bower_components/elevatezoom/jquery.elevateZoom-2.2.3.min'
        'elevatezoom': '../bower_components/elevatezoom/jquery.elevatezoom',
        'mousewheel': '../bower_components/jquery-mousewheel/jquery.mousewheel'
    },
    'shim': {
        'jsrender': {
            'deps': ['jquery']
        },
        'bootstrap': {
            'deps': ['jquery']
        },
        'bootstrap-multiselect': {
            'deps': ['jquery', 'bootstrap']
        },
        'validate': {
            'deps': ['jquery']
        },
        'validate/localization': {
            'deps': ['jquery', 'validate']
        },
        'cycle2': {
            'deps': ['bootstrap']
        },
        'cycle2.center': {
            'deps': ['cycle2', 'bootstrap']
        },
        'ConstructorOverview': {
            'deps': ['jquery']
        },
        'owl': {
            'deps': ['jquery']
        },
        'elevatezoom': {
            'deps': ['jquery']
        },
        'mousewheel': {
            'deps': ['jquery']
        }
    },
    urlArgs: "bust3=" + (new Date()).getTime()
});