/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/url-builder',
    'mageUtils'
], function (
    customer,
    quote,
    urlBuilder,
) {
    'use strict';

    return {
        getRequestOfferUrl() {
            let url = '/guest-customer/:quoteId/hokodo-request-offer',
                params = { quoteId: quote.getQuoteId() };
            if (customer.isLoggedIn()) {
                url = '/carts/mine/hokodo-request-offer';
                params = {};
            }
            return urlBuilder.createUrl(url, params);
        },
    };
});
