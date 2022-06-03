define([
    'uiComponent',
    'Hokodo_BNPL/js/segment/core',
    'priceUtils',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/payment/method-list',
    'domReady!'
], function (Component, segment, priceUtils, quote, methodList) {
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
                    self.segment.identify(
                        self.priceUtils.formatPrice(self.quote.getCalculatedTotal(), self.quote.getPriceFormat()),
                        position
                    );
                    segment.trackLanding();
                    this.fired = true;
                }
            })
        }
    })
})
