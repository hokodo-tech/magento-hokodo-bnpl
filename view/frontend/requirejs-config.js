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
