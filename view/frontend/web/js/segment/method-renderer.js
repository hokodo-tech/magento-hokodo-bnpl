/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'Hokodo_BNPL/js/segment/core'
], function (segment) {
    'use strict'

    return function (Renderer) {
        return Renderer.extend({
            selectPaymentMethod() {
                segment.trackSelected();
                return this._super();
            },

            selectRegisteredCompany() {
                segment.trackCompanyType('Limited');
                return this._super();
            },

            selectSoleTrader() {
                segment.trackCompanyType('Sole Trader');
                return this._super();
            },

            afterPlaceOrder() {
                segment.trackRedirection(this.selectedPlan().name);
                return this._super();
            }
        });
    }
});
