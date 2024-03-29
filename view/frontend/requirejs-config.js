/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

config = {
    config: {
        mixins: {
            'Hokodo_BNPL/js/view/payment/method-renderer/bnpl-sdk': {
                'Hokodo_BNPL/js/segment/method-renderer': true
            },
            'Magento_Checkout/js/model/shipping-save-processor': {
                'Hokodo_BNPL/js/sdk/mixin/shipping-save-processor-mixin': true
            },
            'Magento_Checkout/js/model/shipping-save-processor/payload-extender': {
                'Hokodo_BNPL/js/sdk/mixin/shipping-save-payload-extender-mixin': true
            },
            'Magento_CheckoutAgreements/js/view/checkout-agreements': {
                'Hokodo_BNPL/js/mixin/checkout-agreements-mixin': true
            },
            'Magento_Checkout/js/model/checkout-data-resolver': {
                'Hokodo_BNPL/js/sdk/mixin/checkout-data-resolver': true
            }
        }
    }
};
