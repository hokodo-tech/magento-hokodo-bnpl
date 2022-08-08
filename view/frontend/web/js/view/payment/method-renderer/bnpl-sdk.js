/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'jquery',
    'underscore',
    'ko',
    'Magento_Checkout/js/view/payment/default',
    'Hokodo_BNPL/js/sdk/hokodo-data-persistor',
    'Hokodo_BNPL/js/sdk/hokodo-data-checkout',
    'HokodoSDK'
], function (
        $,
        _,
        ko,
        Component,
        hokodoData,
        hokodoCheckout
        ) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Hokodo_BNPL/payment/bnpl-sdk'
        },

        // TODO: Get key from backend
        hokodoElements: Hokodo("pk_test_g7ziU-hyBnm6oQmALykxnnwliwWmRj-TukvjZ3iKNvU").elements(),

        /**
         * Init component
         */
        initialize: function() {
            this._super();

            if (hokodoData.getOrganisation().api_id) {
                this.companySearch = this.hokodoElements.create("companySearch", {companyId: hokodoData.getOrganisation().company_api_id});
            } else {
                this.companySearch = this.hokodoElements.create("companySearch");
            }
            this.companySearch.on("companySelection", this.onSDKCompanySelection.bind(this));

            return this;
        },

        /**
         * Get payment method code.
         * @returns {String}
         */
        getCode: function() {
            return 'hokodo_bnpl';
        },

        // /**
        //  * Get payment method data
        //  * @returns {Object}
        //  */
        // getData: function () {
        //     return this._super();
        // },

        mountSearch: function() {
            this.companySearch.mount("#hokodoCompanySearch");
        },

        mountCheckout: function() {
            if (hokodoData.getOffer().id) {
                this.userCheckout = this.hokodoElements.create("checkout", {
                    paymentOffer: hokodoData.getOffer()
                });
                this.userCheckout.mount("#hokodoCheckout");
            }
        },

        onSDKCompanySelection: function(company) {
            var self = this;
        }
    });
});
