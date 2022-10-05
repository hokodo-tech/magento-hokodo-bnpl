define([
    'uiComponent',
    'Hokodo_BNPL/js/segment/core',
    'priceUtils',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/payment/method-list',
    'Hokodo_BNPL/js/sdk/hokodo-data-persistor',
    'domReady!'
], function (Component, segment, priceUtils, quote, methodList, hokodoData) {
    'use strict';

    return Component.extend({
        fired: false,
        initialize() {
            this._super();
            this.segment = segment;
            this.priceUtils = priceUtils;
            this.quote = quote;
            this.methodList = methodList;
            var self = this;
            methodList.subscribe(function () {
                if (!self.fired && self.methodList().length !== 0) {
                    let position;
                    self.methodList().forEach((method, key) => {
                            if (method.method === 'hokodo_bnpl') {
                                position = key;
                            }
                        }
                    )
                    self.segment.identify(hokodoData.getCompanyId());
                    segment.trackLanding(
                        self.priceUtils.formatPrice(self.quote.getCalculatedTotal(), {pattern: '%s'}),
                        self.quote.totals().quote_currency_code,
                        position
                    );
                    this.fired = true;
                }
            })
        }
    })
})
