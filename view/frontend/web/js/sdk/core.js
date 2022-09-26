define(['jquery'], function ($) {
    return function(config) {
        require([config.url], function() {
            window.hokodoSdk = Hokodo(config.key);
            console.log('loaded');
            $('body').triggerHandler('hokodoSdkResolved');
        })
    }
})
