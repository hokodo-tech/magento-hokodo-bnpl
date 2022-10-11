define([
    'jquery',
    'Hokodo_BNPL/js/sdk/customer'
], function ($, customer) {
    return function (config) {
        customer().done((name) => {
            if (name !== undefined) {
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
                        console.log('hokodo Core loaded');
                        $('body').triggerHandler('hokodoSdkResolved');
                    }
                })
            }
        })
    }
})
