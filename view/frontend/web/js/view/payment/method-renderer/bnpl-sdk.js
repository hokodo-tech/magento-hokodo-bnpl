/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
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
    'Hokodo_BNPL/js/view/payment/validator/visibility',
    'Magento_Ui/js/modal/modal',
], function (
    $,
    _,
    ko,
    Component,
    hokodoData,
    customer,
    errorProcessor,
    visibilityValidator
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
            isCompanyIdAssignedByComponent: false,
            isValidated: false
        },
        hokodoElements: window.hokodoSdk.elements(),

        initObservable() {
            this._super().observe({
                isVisible: true,
                isDisabled: true,
                isOfferLoading: false,
            });

            return this;
        },

        getLogos() {
            return paymentConfig.logos;
        },

        /**
         * Init component
         */
        initialize: function () {
            this._super();
            const self = this;

            this.hokodoCheckout().isLoading.subscribe((value) => {
                this.isOfferLoading(value);
            })

            this.hokodoCheckout().offer.subscribe((offer) => {
                if (offer) {
                    this.mountCheckout();
                }
            })

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

            if (!visibilityValidator.isVisibilityValidationRequired()) {
                this.isVisible(true);
                this.isDisabled(false);
                this.isValidated = true;
            }

            this.mountCheckout();

            return this;
        },

        isMustShow: function () {
            return paymentConfig.hideHokodoPaymentType === 'dont_hide';
        },

        isCompanyAttached: function () {
            let isCompanyAttached = false;
            if (paymentConfig.hideHokodoPaymentType === 'company_is_not_attached' && this.hokodoCheckout().companyId()) {
                isCompanyAttached = true;
            }
            return isCompanyAttached;
        },

        isOrderEligible: function () {
            let isOrderEligible = false;
            if (paymentConfig.hideHokodoPaymentType === 'order_is_not_eligible' &&
                (this.hasOfferedPlan() === true || !this.isCustomerLoggedIn() || !this.hasOffer())
            ) {
                isOrderEligible = true;
            }
            return isOrderEligible;
        },

        isBothCompanyAttachedAndOrderEligible: function () {
            let isBothCompanyAttachedAndOrderEligible = false;
            if (paymentConfig.hideHokodoPaymentType === 'order_is_not_eligible_or_company_is_not_attached'
                && this.hokodoCheckout().companyId() &&
                (this.hasOfferedPlan() === true || !this.isCustomerLoggedIn() || !this.hasOffer())
            ) {
                isBothCompanyAttachedAndOrderEligible = true;
            }
            return isBothCompanyAttachedAndOrderEligible;
        },

        hasOffer: function ()
        {
            return typeof hokodoData.getOffer() !== 'undefined' && hokodoData.getOffer() !== '';
        },

        hasOfferedPlan: function ()
        {
            let isOffered = false;
            if (typeof hokodoData.getOffer() !== 'undefined' && hokodoData.getOffer() !== '') {
                hokodoData.getOffer().offered_payment_plans.forEach(function (item, index) {
                    if (item.status === 'offered') {
                        isOffered = true;
                    }
                })
            }
            return isOffered;
        },

        isCustomerLoggedIn: function () {
            return customer.isLoggedIn();
        },

        onCompanyChange: function(companyId) {
            if(!this.isCompanyIdAssignedByComponent) {
                this.companySearch.destroy();
                this.companySearch = this.hokodoElements.create("companySearch", {companyId: companyId});
                this.mountSearch();
                this.isCompanyIdAssignedByComponent = true;
            }
        },

        /*isDisabled: function() {

            // Marty put logic here
            return false;
        },*/

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
            this.companySearch.mount("#hokodoCompanySearch");
        },

        mountCheckout: function () {
            if (this.hokodoCheckout().offer()) {
                if (!this.isValidated) {
                    this.isVisible(visibilityValidator.validate());
                    this.isDisabled(!visibilityValidator.validate());
                    this.isValidated = true;
                }
                this._mountCheckout()
            } else {
                this.hokodoCheckout().createOfferAction();
            }
        },

        _mountCheckout: function () {
            var self = this;
            if (!this.userCheckout && this.hokodoCheckout().offer()) {
                this.userCheckout = this.hokodoElements.create("checkout", {
                    paymentOffer: this.hokodoCheckout().offer()
                });

                this.userCheckout.on("failure", () => {
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
