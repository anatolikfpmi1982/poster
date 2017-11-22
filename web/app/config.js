require.config({
    baseUrl: '/app',
    cacheSuffix: ".buildNumber",
    'paths': {
        'jquery': '../bower_components/jquery/dist/jquery',
        'validate': '../bower_components/jquery.validation/dist/jquery.validate',
        'validate/localization': '../bower_components/jquery.validation/src/localization/messages_ru',
        'underscore': '../bower_components/underscore/underscore',
        'bootstrap': '../bower_components/bootstrap/dist/js/bootstrap',
        'cycle2': '../bower_components/jquery.cycle2/index'
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
        }
    },
    urlArgs: "bust3=" + (new Date()).getTime()
});