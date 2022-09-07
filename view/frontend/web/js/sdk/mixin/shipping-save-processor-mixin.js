/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'mage/utils/wrapper',
    'Magento_Customer/js/customer-data'
], function (wrapper, customerData) {
    'use strict';

    return function (shippingSaveProcessor) {
        shippingSaveProcessor.saveShippingInformation = wrapper.wrapSuper(
            shippingSaveProcessor.saveShippingInformation,
            function (type) {
                var invalidateHokodoOffer;

                /**
                 * Update coupon form
                 */
                invalidateHokodoOffer = function () {
                    customerData.invalidate(['hokodo-checkout']);
                    console.log('shipping mixin invalidate');
                };

                return this._super(type).done(invalidateHokodoOffer);
            }
        );

        return shippingSaveProcessor;
    };
});
