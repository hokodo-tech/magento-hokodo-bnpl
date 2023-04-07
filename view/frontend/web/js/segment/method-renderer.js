/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'Hokodo_BNPL/js/segment/core',
    'Magento_Checkout/js/model/quote',
], function (segment, quote) {
    'use strict'

    return function (Renderer) {
        return Renderer.extend({
            selectPaymentMethod() {
                segment.trackSelected(quote.getQuoteId());
                return this._super();
            }
        });
    }
});
