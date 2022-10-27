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
                    customerData.reload(['hokodo-checkout'], true);
                };

                return this._super(type).done(invalidateHokodoOffer);
            }
        );

        return shippingSaveProcessor;
    };
});
