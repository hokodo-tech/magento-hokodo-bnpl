define(['jquery'], function ($) {
    return function(config) {
        require([config.url], function() {
            let sdkConfig = {};
            if (config.faq) {
                sdkConfig.faqLink = config.faq
            }
            window.hokodoSdk = Hokodo(config.key, sdkConfig);
            console.log('hokodo Core loaded');
            $('body').triggerHandler('hokodoSdkResolved');
        })
    }
})
