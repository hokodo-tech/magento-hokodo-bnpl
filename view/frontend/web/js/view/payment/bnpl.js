/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
], function (
        Component,
        rendererList,
        ) {
    'use strict';

    var config = window.checkoutConfig.payment;
    if (config.hokodo_bnpl.isActive) {
        rendererList.push(
            {
                type: 'hokodo_bnpl',
                component: 'Hokodo_BNPL/js/view/payment/method-renderer/bnpl-sdk'
            }
        );
    }

    /** Add view logic here if needed */
    return Component.extend({});
});
