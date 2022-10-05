/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list',
    'Magento_Customer/js/model/customer'
], function (
        Component,
        rendererList,
        customer
        ) {
    'use strict';

    var config = window.checkoutConfig.payment;
    if (config.hokodo_bnpl.isActive && customer.isLoggedIn()) {
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
