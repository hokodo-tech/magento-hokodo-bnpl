define([
    'mage/utils/wrapper',
    'Magento_Checkout/js/checkout-data'
], function (wrapper, checkoutData) {
    'use strict';

    return function (getPayloadExtended) {
        return wrapper.wrap(getPayloadExtended, function (getPayload, config, element) {
            let payload = getPayload(config, element);
            return payload.addressInformation['extension_attributes'].email = checkoutData.getValidatedEmailValue();
        });
    };
});
