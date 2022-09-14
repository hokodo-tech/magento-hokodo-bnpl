/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'jquery',
    'underscore',
    'ko',
    'Magento_Checkout/js/view/payment/default',
    'Hokodo_BNPL/js/sdk/hokodo-data-persistor'
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

        hokodoElements: Hokodo(window.checkoutConfig.hokodoSdkKey).elements(),

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
                    hokodoData.setCompanyId(company.id);
                    self.destroyCheckout();
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
            if (!this.userCheckout && this.hokodoCheckout().offer()) {
                console.log('bnpl:_mountCheckout:!this.userCheckout')
                console.log(this.hokodoCheckout().offer())
                this.userCheckout = this.hokodoElements.create("checkout", {
                    paymentOffer: this.hokodoCheckout().offer()
                });

                this.userCheckout.on("failure", () => {
                    console.log('bnpl:_mountCheckout:!this.userCheckout:failure')
                    hokodoData.setOffer(null);
                    self.hokodoCheckout().offer(null);
                    self.mountCheckout();
                });

                this.userCheckout.on('declined', () => {
                    console.log('bnpl:_mountCheckout:!this.userCheckout:declined')
                    hokodoData.setOffer(null);
                    self.hokodoCheckout().offer(null);
                    self.mountCheckout();
                });

                this.userCheckout.on('success', () => {
                    self.additionalData.hokodo_payment_offer_id = this.hokodoCheckout().offer().id;
                    self.additionalData.hokodo_order_id = this.hokodoCheckout().offer().order;
                    self.placeOrder()
                });

                this.userCheckout.mount("#hokodoCheckout");
            } else {
                console.log('bnpl:_mountCheckout:else')
                this.userCheckout.destroy();
                this.userCheckout = null;
                this._mountCheckout();
                // this.userCheckout.update({
                //     paymentOffer: this.hokodoCheckout().offer()
                // })
            }
        },

        afterPlaceOrder() {
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
        },

        destroyCheckout() {
            if (this.userCheckout) {
                this.userCheckout.destroy();
            }
        }
    });
});
