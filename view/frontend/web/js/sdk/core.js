define([
    'jquery',
    'uiLayout'
], function ($, layout) {
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
                layout([{
                    name: 'hokodo-sdk-marketing',
                    component: 'Hokodo_BNPL/js/sdk/marketing/marketing'
                }])
                $('body').triggerHandler('hokodoSdkResolved');
            }
        })
    }
})
