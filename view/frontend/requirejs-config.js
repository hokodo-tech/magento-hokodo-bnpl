/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

config = {
    config: {
        mixins: {
            'Hokodo_BNPL/js/view/payment/method-renderer/bnpl-sdk': {
                'Hokodo_BNPL/js/segment/method-renderer': true
            },
            'Magento_Checkout/js/view/minicart': {
                'Hokodo_BNPL/js/mixins/minicart-mixin': true
            },
            'Magento_Checkout/js/model/shipping-save-processor': {
                'Hokodo_BNPL/js/sdk/mixin/shipping-save-processor-mixin': true
            }
        }
    }
};
