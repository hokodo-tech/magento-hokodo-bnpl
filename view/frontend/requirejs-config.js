/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
var amasty_mixin_enabled = false, //!window.amasty_checkout_disabled,
        config;

config = {
    config: {
        mixins: {
            'Amasty_Checkout/js/view/place-button': {
                'Hokodo_BNPL/js/mixin/amasty/checkout/view/place-button': amasty_mixin_enabled
            },
            'Hokodo_BNPL/js/view/payment/method-renderer/bnpl': {
                'Hokodo_BNPL/js/segment/method-renderer': true
            },
            'Hokodo_BNPL/js/view/company-information/ui-select': {
                'Hokodo_BNPL/js/segment/ui-select': true
            },
            'Hokodo_BNPL/js/model/hokodo-data-service': {
                'Hokodo_BNPL/js/segment/hokodo-data-service': true
            },
            'Magento_Checkout/js/view/minicart': {
                'Hokodo_BNPL/js/view/minicart-mixin': true
            },
            'Magento_Checkout/js/model/shipping-save-processor': {
                'Hokodo_BNPL/js/sdk/mixin/shipping-save-processor-mixin': true
            }
        },
    },
    map: {
        '*': {
            'Magento_SalesRule/js/action/set-coupon-code': 'Hokodo_BNPL/js/salerule/set-coupon-code',
            'Magento_SalesRule/js/action/cancel-coupon': 'Hokodo_BNPL/js/salerule/cancel-coupon'
        }
    }
};
