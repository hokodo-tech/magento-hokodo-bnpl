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
    'Magento_Checkout/js/model/quote',
    'Magento_Ui/js/modal/modal',
], function (
    $,
    _,
    ko,
    Component,
    hokodoData,
    customer,
    errorProcessor,
    visibilityValidator,
    quote,
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
            isValidated: false,
            isCheckoutMounted: false
        },
        getLogos: ko.observableArray(paymentConfig.logos),
        hokodoElements: null,

        initObservable() {
            this._super().observe({
                isVisible: false,
                isOfferLoading: false,
            });

            return this;
        },
        /**
         * Initialize
         */
        initialize: function () {
            this._super();
            if (window.hokodoSdk) {
                this.initComponent();
            } else {
                jQuery('body').on('hokodoSdkResolved', () => {
                    this.initComponent();
                })
            }
        },

        /**
         * Init component
         */
        initComponent: function () {
            const self = this;
            this.hokodoElements = window.hokodoSdk.elements();
            this.searchConfig = paymentConfig.searchConfig

            this.hokodoCheckout().isLoading.subscribe((value) => {
                this.isOfferLoading(value);
            })

            this.hokodoCheckout().offer.subscribe((offer) => {
                if (offer) {
                    this.mountCheckout();
                }
            })

            if (this.hokodoCheckout().companyId()) {
                this.searchConfig.companyId = this.hokodoCheckout().companyId();
                this.isCompanyIdAssignedByComponent = true;
            }
            if (quote && quote.billingAddress() && quote.billingAddress().countryId) {
                this.searchConfig.country = quote.billingAddress().countryId;
            }
            this.companySearch = this.hokodoElements.create("companySearch", this.searchConfig);
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
                this.isValidated = true;
            }

            this.mountCheckout();

            return this;
        },

        isCustomerLoggedIn: function () {
            return customer.isLoggedIn();
        },

        onCompanyChange: function(companyId) {
            if(!this.isCompanyIdAssignedByComponent) {
                this.companySearch.destroy();
                this.searchConfig.companyId = companyId;
                this.companySearch = this.hokodoElements.create("companySearch", this.searchConfig);
                this.mountSearch();
                this.isCompanyIdAssignedByComponent = true;
            }
        },

        isDisabled: function() {

            // Marty put logic here
            return false;
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
            this.companySearch.mount("#hokodoCompanySearch");
        },

        mountCheckout: function () {
            if (!this.isCheckoutMounted) {
                if (this.hokodoCheckout().offer()) {
                    if (!this.isValidated) {
                        this.isVisible(visibilityValidator.validate());
                        this.isValidated = true;
                    }
                    this._mountCheckout()
                } else {
                    this.hokodoCheckout().createOfferAction();
                }
            }
        },

        _mountCheckout: function () {
            var self = this;
            if ($("#hokodoCheckout").length === 1) {
                if (!this.userCheckout && this.hokodoCheckout().offer() && !this.isCheckoutMounted) {
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
                    this.isCheckoutMounted = true;
                }
            } else {
                setTimeout(this._mountCheckout.bind(this), 1000);
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
            this.destroyCheckout();
            this.mountCheckout();

            return true;
        },

        destroyCheckout() {
            if ($("#hokodoCheckout").length === 1 && this.isCheckoutMounted) {
                this.userCheckout.destroy();
            }
            if (this.userCheckout) {
                this.userCheckout = null;
            }
            this.isCheckoutMounted = false;
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
