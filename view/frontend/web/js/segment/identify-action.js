/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
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
                    let phone = self.quote.shippingAddress().telephone;
                    let name = self.getCustomerName(self.quote);
                    let email = self.getCustomerEmail(self.quote);
                    self.segment.identify(hokodoData.getCompanyId(), phone, name, email);
                    segment.trackLanding(
                        self.priceUtils.formatPrice(self.quote.getCalculatedTotal(), {pattern: '%s'}),
                        self.quote.totals().quote_currency_code,
                        position
                    );
                    this.fired = true;
                }
            })
        },

        getCustomerName(quote) {
            let name =  quote.shippingAddress().firstname + ' ' + quote.shippingAddress().lastname;
            if (typeof quote.shippingAddress().middlename !== 'undefined' && quote.shippingAddress().middlename !== null) {
                name =  quote.shippingAddress().firstname + ' ' + quote.shippingAddress().middlename + ' ' + quote.shippingAddress().lastname;
            }
            return name;
        },

        getCustomerEmail(quote) {
            let email = quote.guestEmail;
            if (window.isCustomerLoggedIn) {
                email = window.customerData.email;
            }
            return email;
        }
    })
})
