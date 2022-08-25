/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/url-builder',
    'mageUtils'
], function (
	customer,
	quote,
	urlBuilder,
	utils
) {
    'use strict';

    return {
        getCreateOrganisationUrl() {
            return urlBuilder.createUrl('/carts/mine/hokodo-create-organisation', {});
        },

        getCreateUserUrl() {
            return urlBuilder.createUrl('/carts/mine/hokodo-create-user', {});
        },

        getCreateOfferUrl() {
            return urlBuilder.createUrl('/carts/mine/hokodo-create-offer', {});
        },

        getCreateOrderUrl() {
            return urlBuilder.createUrl('/carts/mine/hokodo-create-order', {});
        },
    };
});
