/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
        'jquery',
        'ko',
        'uiComponent',
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/model/quote',
        'Magento_Customer/js/customer-data',
        'Hokodo_BNPL/js/sdk/hokodo-data-persistor',
        'Hokodo_BNPL/js/sdk/action/request-hokodo-offer',
        'Magento_SalesRule/js/action/set-coupon-code',
        'Magento_SalesRule/js/action/cancel-coupon',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/payment/place-order-hooks'
    ],
    function (
        $,
        ko,
        Component,
        customer,
        quote,
        customerData,
        hokodoData,
        requestOfferAction,
        setCouponCodeAction,
        cancelCouponAction,
        errorProcessor,
        placeOrderHooks
    ) {
        return Component.extend({

            defaults: {
                isGuestUserChanged: false,
                isWaitingForPaymentInformationUpdateHook: false,
                modules: {
                    hokodoPaymentMethod: 'checkout.steps.billing-step.payment.payments-list.hokodo_bnpl',
                },
                listens: {
                    'checkout.steps.shipping-step.shippingAddress.customer-email:email': 'guestUserEmailChangedHandler',
                }
            },

            initialize() {
                let self = this;
                this._super();
                hokodoData.reload();
                this.initSubscribers();

                if (customer.isLoggedIn()) {
                    this.initLoggedInCustomer();
                }

                placeOrderHooks.afterRequestListeners.push(function() {
                    self.paymentInformationUpdateHook();
                })

                return this;
            },

            initObservable() {
                this._super()
                    .observe({
                        companyId: ko.observable(hokodoData.storageSearchGet('companyId')),
                        offer: ko.observable(hokodoData.storageCheckoutGet('offer')),
                        isLoading: ko.observable(false),
                    });

                return this;
            },

            initSubscribers() {
                const self = this;
                this.companyId.subscribe((companyId) => {
                    self.companyIdChanged(companyId);
                })
                this.offer.subscribe((offer) => {
                    self.offerChanged(offer);
                })
                hokodoData.storageGetCheckoutObservable().subscribe((data) => {
                    let offerChanged = false;
                    if (!!data.offer) {
                        if (!!this.offer()) {
                            if (data.offer.id !== this.offer().id) {
                                offerChanged = true;
                            }
                        } else {
                            offerChanged = true;
                        }
                    } else {
                        if (!!this.offer()) {
                            offerChanged = true;
                        }
                    }
                    if (offerChanged) {
                        this.offer(data.offer);
                    }
                })
                hokodoData.storageGetSearchObservable().subscribe((data) => {
                    if (data.companyId !== this.companyId) {
                        this.companyId(data.companyId);
                    }
                })

                setCouponCodeAction.registerSuccessCallback(hokodoData.reload);
                cancelCouponAction.registerSuccessCallback(hokodoData.reload);
            },

            companyIdChanged(id) {
                this.offer(null);
                if (id) {
                    this.createOfferAction();
                }
            },

            offerChanged(offer) {
                if (this.isGuestUserChanged) {
                    this.isGuestUserChanged = false;
                    this.isWaitingForPaymentInformationUpdateHook = true;
                    this.hokodoPaymentMethod().selectPaymentMethod();
                } else if (offer === '') {
                    this.createOfferAction();
                }
            },

            initLoggedInCustomer() {
                // this.createOfferAction();
            },

            createOfferAction() {
                if (this.companyId() && !this.isWaitingForPaymentInformationUpdateHook && !this.isLoading()) {
                    if (this.hokodoPaymentMethod() !== undefined) {
                        this.hokodoPaymentMethod().destroyCheckout();
                    }
                    this.isLoading(true);
                    requestOfferAction(
                        this.companyId()
                    ).done((response) => {
                        if (response.offer !== undefined) {
                            hokodoData.setOffer(response.offer);
                        }
                    }).fail((response) => {
                        if (this.hokodoPaymentMethod()) {
                            errorProcessor.process(response, this.hokodoPaymentMethod().messageContainer)
                        }
                        hokodoData.setOffer('');
                    }).always(() => {
                        this.isLoading(false);
                    })
                }
            },

            guestUserEmailChangedHandler() {
                if (!customer.isLoggedIn() && !!this.hokodoPaymentMethod() && this.hokodoPaymentMethod().isChecked()) {
                    this.isGuestUserChanged = true;
                }
            },

            paymentInformationUpdateHook() {
                if (this.isWaitingForPaymentInformationUpdateHook) {
                    this.isWaitingForPaymentInformationUpdateHook = false;
                    this.createOfferAction();
                }
            }
        })
    })
