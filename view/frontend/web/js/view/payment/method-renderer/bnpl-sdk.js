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
    'HokodoSDK'
], function (
        $,
        _,
        ko,
        Component,
        hokodoData
        ) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Hokodo_BNPL/payment/bnpl-sdk',
            additionalData: {},
            modules: {
                hokodoCheckout: 'checkout.steps.shipping-methods-step.hokodo-checkout'
            },
            //temp SDK search event fix
            searchInitialized: false
        },

        // TODO: Get key from backend
        hokodoElements: Hokodo("pk_test_g7ziU-hyBnm6oQmALykxnnwliwWmRj-TukvjZ3iKNvU").elements(),

        /**
         * Init component
         */
        initialize: function() {
            this._super();
            const self = this;
            console.log('bnpl:initialize');


            this.hokodoCheckout().offer.subscribe((offer) => {
                if (offer) {
                    console.log('bnpl:offer.changed: ' + offer.id);
                    this.mountCheckout();
                }
            })

            if (this.hokodoCheckout().companyId()) {
                this.companySearch = this.hokodoElements.create("companySearch", {companyId: this.hokodoCheckout().companyId()});
            } else {
                this.companySearch = this.hokodoElements.create("companySearch");
            }
            this.companySearch.on("companySelection", (company) => {
                if (company === null && !self.searchInitialized) {
                    //skip initializing call
                    self.searchInitialized = !self.searchInitialized;
                    return;
                }
                if (company !== null && company.id !== self.hokodoCheckout().companyId()) {
                    hokodoData.clearData();
                    hokodoData.setCompanyId(company.id);
                    self.hokodoCheckout().companyId(company.id);
                    self.userCheckout.destroy();
                }
            });

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
            console.log('bnpl:mountSearch')
            this.companySearch.mount("#hokodoCompanySearch");
        },

        mountCheckout: function() {
            if (this.hokodoCheckout().offer()) {
                console.log('bnpl:mountCheckout:offer: ' + this.hokodoCheckout().offer().id)
                this._mountCheckout()
            } else {
                console.log('bnpl:mountCheckout: offer - false')
                this.hokodoCheckout().createOfferAction();
            }
        },

        reMountCheckout: function() {

        },

        _mountCheckout: function() {
            var self = this;
            if (!this.userCheckout) {
                console.log('bnpl:_mountCheckout:!this.userCheckout')
                console.log(this.hokodoCheckout().offer())
                this.userCheckout = this.hokodoElements.create("checkout", {
                    paymentOffer: this.hokodoCheckout().offer()
                });

                this.userCheckout.on("failure", () => {
                    hokodoData.setOffer(null);
                    self.hokodoCheckout().offer(null);
                    self.mountCheckout();
                });

                this.userCheckout.on('declined', () => {
                    hokodoData.setOffer(null);
                    self.hokodoCheckout().offer(null);
                    self.mountCheckout();
                });

                this.userCheckout.on('success', () => {
                    self.additionalData.hokodo_payment_offer_id = this.hokodoCheckout().offer().id;
                    self.additionalData.hokodo_order_id = this.hokodoCheckout().offer().order;
                    self.additionalData.hokodo_user_id = this.hokodoCheckout().userId();
                    self.additionalData.hokodo_organisation_id = this.hokodoCheckout().organisationId();
                    self.placeOrder()
                });

                this.userCheckout.on('destroy', () => {
                    console.log('destroy');
                })

                this.userCheckout.mount("#hokodoCheckout");
            } else {
                console.log('bnpl:_mountCheckout:else')
                this.userCheckout.update({
                    paymentOffer: this.hokodoCheckout().offer()
                })
            }
        },

        afterPlaceOrder() {
            // hokodoData.clearOrderData();
            this._super();
        },

        onSDKCompanySelection: function(company) {
            var self = this;
        },

        /**
         * Get payment method data
         * @returns {Object}
         */
        getData: function () {
            var data = {
                'method': this.getCode(),
                'additional_data': this.additionalData
            };

            return data;
        },

        selectPaymentMethod: function() {
            this._super();
            this.mountCheckout();
        }
    });
});
