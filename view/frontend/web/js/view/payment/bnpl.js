/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
], function (
        Component,
        rendererList
        ) {
    'use strict';

    var config = window.checkoutConfig.payment;

    if (config.hokodo_bnpl.isActive) {
        if (!config.hokodo_bnpl.replace_place_order_hooks) {
            rendererList.push(
                {
                    type: 'hokodo_bnpl',
                    component: 'Hokodo_BNPL/js/view/payment/method-renderer/bnpl'
                }
            );
        } else if (config.hokodo_bnpl.magentoVersion < 241 || config.hokodo_bnpl.replace_place_order_hooks) {
            rendererList.push(
                    {
                        type: 'hokodo_bnpl',
                        component: 'Hokodo_BNPL/js/view/payment/method-renderer/old-bnpl'
                    }
            );
        }
    }

    /** Add view logic here if needed */
    return Component.extend({});
});
