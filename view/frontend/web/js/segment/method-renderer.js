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
                this._super();
            },

            selectRegisteredCompany() {
                segment.trackCompanyType('Limited');
                this._super();
            },

            selectSoleTrader() {
                segment.trackCompanyType('Sole Trader');
                this._super();
            },

            afterPlaceOrder() {
                segment.trackRedirection(this.selectedPlan().name);
                this._super();
            }
        });
    }
});
