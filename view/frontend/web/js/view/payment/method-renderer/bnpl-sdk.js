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
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/error-processor',
    'Magento_Ui/js/modal/modal'
], function (
    $,
    _,
    ko,
    Component,
    hokodoData,
    customer,
    errorProcessor
) {
    'use strict';

    let paymentConfig = window.checkoutConfig.payment.hokodo_bnpl;
    return Component.extend({
        defaults: {
            template: 'Hokodo_BNPL/payment/bnpl-sdk',
            additionalData: {},
            modules: {
                hokodoCheckout: 'checkout.steps.shipping-methods-step.hokodo-checkout'
            },
            //temp SDK search event fix
            searchInitialized: false,
            isCompanyIdAssignedByComponent: false
        },
        isReadyToShow: ko.observable(false),
        isOfferLoading: ko.observable(false),
        hokodoElements: window.hokodoSdk.elements(),
        getLogos: ko.observableArray(paymentConfig.logos),

        /**
         * Init component
         */
        initialize: function () {
            this._super();
            const self = this;
            console.log('bnpl:initialize');

            this.hokodoCheckout().isLoading.subscribe((value) => {
                this.isOfferLoading(value);
            })

            this.hokodoCheckout().offer.subscribe((offer) => {
                if (offer) {
                    console.log('bnpl:offer.changed: ' + offer.id);
                    this.mountCheckout();
                }
            })

            if (typeof hokodoData.getOffer() === 'undefined' || hokodoData.getOffer() === '') {
                this.isReadyToShow(true);
            } else {
                let self = this
                hokodoData.getOffer().offered_payment_plans.forEach(function (item, index) {
                    if (item.status === 'offered') {
                        self.isReadyToShow(true);
                    }
                })
            }

            if (this.hokodoCheckout().companyId()) {
                this.companySearch = this.hokodoElements.create("companySearch", {companyId: this.hokodoCheckout().companyId()});
                this.isCompanyIdAssignedByComponent = true;
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
                    self.isCompanyIdAssignedByComponent = true;
                    hokodoData.setCompanyId(company.id);
                    self.destroyCheckout();
                }

            });

            self.hokodoCheckout().companyId.subscribe((companyId) => {
                self.onCompanyChange(companyId);
            })

            return this;
        },

        onCompanyChange: function(companyId) {
            if(!this.isCompanyIdAssignedByComponent) {
                this.companySearch.destroy();
                this.companySearch = this.hokodoElements.create("companySearch", {companyId: companyId});
                this.mountSearch();
                this.isCompanyIdAssignedByComponent = true;
            }
        },

        getCode: function () {
            return 'hokodo_bnpl';
        },

        getSubTitle: function () {
            return paymentConfig.subtitle;
        },

        getHokodoLogo: function () {
            return paymentConfig.hokodoLogo;
        },

        getInfo: function () {
            return paymentConfig.moreInfo;
        },

        mountSearch: function () {
            console.log('bnpl:mountSearch')
            this.companySearch.mount("#hokodoCompanySearch");
        },

        mountCheckout: function () {
            if (this.hokodoCheckout().offer()) {
                console.log('bnpl:mountCheckout:offer: ' + this.hokodoCheckout().offer().id)
                this._mountCheckout()
            } else {
                console.log('bnpl:mountCheckout: offer - false')
                this.hokodoCheckout().createOfferAction();
            }
        },

        _mountCheckout: function () {
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
                });

                this.userCheckout.on('success', () => {
                    self.additionalData.hokodo_payment_offer_id = this.hokodoCheckout().offer().id;
                    self.additionalData.hokodo_order_id = this.hokodoCheckout().offer().order;
                    self.placeOrder()
                });

                this.userCheckout.mount("#hokodoCheckout");
            } else {
                console.log('bnpl:_mountCheckout:else')
                if (this.userCheckout) {
                    this.userCheckout.destroy();
                    this.userCheckout = null;
                }
                this._mountCheckout();
            }
        },

        afterPlaceOrder() {
            this._super();
        },

        onSDKCompanySelection: function (company) {
            var self = this;
        },

        /**
         * Get payment method data
         * @returns {Object}
         */
        getData: function () {
            return {
                'method': this.getCode(),
                'additional_data': this.additionalData
            };
        },

        selectPaymentMethod: function () {
            this._super();
            this.mountCheckout();

            return true;
        },

        destroyCheckout() {
            if (this.userCheckout) {
                this.userCheckout.destroy();
            }
        },

        openModal: function () {
            let modalOpener = $("#hokodo-marketing-lightbox");
            modalOpener.modal({
                modalClass: 'hokodo-modal',
                buttons: []
            }).modal('openModal');
            modalOpener.show();
        }
    });
});
