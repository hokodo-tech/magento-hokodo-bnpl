define([
    'jquery'
], function ($) {
    return function (config) {
        require([config.url], function () {
            if (!window.hokodoSdk) {
                let sdkConfig = {
                    locale: config.locale,
                    currency: config.currency
                };
                if (config.faq) {
                    sdkConfig.faqLink = config.faq
                }
                window.hokodoSdk = Hokodo(config.key, sdkConfig);
                $('body').triggerHandler('hokodoSdkResolved');
            }
        })
    }
})
