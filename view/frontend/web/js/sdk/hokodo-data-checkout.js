define([
        'jquery',
        'ko',
        'uiComponent',
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/model/quote',
        'Magento_Customer/js/customer-data',
        'Hokodo_BNPL/js/sdk/hokodo-data-persistor',
        'Hokodo_BNPL/js/sdk/action/create-hokodo-organisation',
        'Hokodo_BNPL/js/sdk/action/create-hokodo-user',
        'Hokodo_BNPL/js/sdk/action/request-hokodo-offer',
        'Magento_SalesRule/js/action/set-coupon-code',
        'Magento_SalesRule/js/action/cancel-coupon',
        'Magento_Checkout/js/model/error-processor'
    ],
    function (
        $,
        ko,
        Component,
        customer,
        quote,
        customerData,
        hokodoData,
        createOrganisationAction,
        createUserAction,
        requestOfferAction,
        setCouponCodeAction,
        cancelCouponAction,
        errorProcessor
    ) {
        return Component.extend({

            defaults: {
                modules: {
                    hokodoPaymentMethod: 'checkout.steps.billing-step.payment.payments-list.hokodo_bnpl'
                }
            },

            initialize() {
                this._super();
                console.log('data:initialize');
                hokodoData.reload();
                this.initSubscribers();

                if (customer.isLoggedIn()) {
                    this.initLoggedInCustomer();
                }

                return this;
            },

            initObservable() {
                this._super()
                    .observe({
                        companyId: ko.observable(hokodoData.storageSearchGet('companyId')),
                        organisationId: ko.observable(hokodoData.storageCheckoutGet('organisationId')),
                        userId: ko.observable(hokodoData.storageCheckoutGet('userId')),
                        offer: ko.observable(hokodoData.storageCheckoutGet('offer'))
                    });

                return this;
            },

            initSubscribers() {
                const self = this;
                this.companyId.subscribe((companyId) => {
                    self.companyIdChanged(companyId);
                })
                this.organisationId.subscribe((organisationId) => {
                    self.organisationIdChanged(organisationId);
                })
                this.userId.subscribe((userId) => {
                    self.userIdChanged(userId);
                })
                this.offer.subscribe((offer) => {
                    self.offerChanged(offer);
                })
                hokodoData.storageGetCheckoutObservable().subscribe((data) => {
                    console.log('checkout data changed')
                    if (data.organisationId !== this.organisationId()) {
                        this.organisationId(data.organisationId);
                        console.log('organisation Id changed')
                        return;
                    }
                    if (data.userId !== this.userId()) {
                        this.userId(data.userId);
                        console.log('User id changed')
                        return;
                    }
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
                        console.log('Offer changed')
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
                console.log('data.companyIdChanged: ' + id)
                this.organisationId(null);
                this.userId(null);
                this.offer(null);
                if (id) {
                    this.createOrganisationAction();
                }
            },

            organisationIdChanged(id) {
                console.log('data.organisationIdChanged: ' + id)
                if (id) {
                    this.createUserAction();
                }
            },

            userIdChanged(id) {
                console.log('data.userIdChanged: ' + id)
                if (id) {
                    this.createOfferAction();
                }
            },

            offerChanged(offer) {
                if (offer === '' && (this.organisationId() && this.userId())) {
                    this.createOfferAction();
                }
            },

            initLoggedInCustomer() {
                console.log('data:initLoggedInCustomer')
                if (this.companyId()) {
                    if (this.organisationId()) {
                        if (!this.userId()) {
                            this.createUserAction();
                        }
                    } else {
                        this.createOrganisationAction();
                    }
                }
            },

            createOrganisationAction() {
                console.log('data:createOrganisationAction')
                if (this.companyId()) {
                    console.log('data:createOrganisationAction:companyId')
                    createOrganisationAction(this.companyId()).done((response) => {
                        if (response.id !== '') {
                            hokodoData.setOrganisationId(response.id)
                            this.organisationId(response.id)
                        }
                    }).fail((response) => {
                        if (this.hokodoPaymentMethod()) {
                            errorProcessor.process(response, this.hokodoPaymentMethod().messageContainer)
                        }
                    })
                }
            },

            createUserAction() {
                console.log('data:createUserAction')
                if (this.organisationId()) {
                    console.log('data:createUserAction:this.organisationId()')
                    if (customer.isLoggedIn() || quote.shippingAddress()) {
                        console.log('data:createUserAction:this.organisationId():customer.isLoggedIn')
                        createUserAction(
                            this.organisationId(),
                            this.getCustomerEmail(),
                            this.getCustomerName()
                        ).done((response) => {
                            if (response.id !== '') {
                                hokodoData.setUserId(response.id)
                                this.userId(response.id)
                            }
                        }).fail((response) => {
                            if (this.hokodoPaymentMethod()) {
                                errorProcessor.process(response, this.hokodoPaymentMethod().messageContainer)
                            }
                        })
                    }
                } else {
                    console.log('data:createUserAction:!this.organisationId()')
                    this.createOrganisationAction();
                }
            },

            createOfferAction() {
                console.log('data:createOfferAction:')
                if (this.userId()) {
                    console.log('data:createOfferAction:this.userId()')
                    if (this.organisationId) {
                        console.log('data:createOfferAction:this.userId():this.organisationId()')
                        if (this.hokodoPaymentMethod() !== undefined) {
                            this.hokodoPaymentMethod().destroyCheckout();
                        }
                        requestOfferAction(
                            this.organisationId(),
                            this.userId(),
                            quote.getQuoteId()
                        ).done((response) => {
                            if (response.offer !== undefined) {
                                hokodoData.setOffer(response.offer);
                            }
                        }).fail((response) => {
                            if (this.hokodoPaymentMethod()) {
                                errorProcessor.process(response, this.hokodoPaymentMethod().messageContainer)
                            }
                        })

                    } else {
                        console.log('data:createOfferAction:!this.userId():!this.organisationId()')
                        this.createOrganisationAction();
                    }
                } else {
                    console.log('data:createOfferAction:!this.userId()')
                    this.createUserAction();
                }
            },

            // initGuestCustomer() {
            //     this.createUser().done((response) => {
            //         hokodoData().setUser(response.user);
            //         hokodoData().setOrganisation(response.organisation);
            //     }).bind(this);
            // },

            // initCreateUser(email, name) {
            //     if (!email && !name) {
            //         return;
            //     }
            //     const self = this;
            //     createUserAction(
            //         hokodoData().getOrganisationId(),
            //         email,
            //         name
            //     ).done((response) => {
            //         if (response.id !== '') {
            //             hokodoData().setUserId(response.id)
            //         }
            //     })
            // },

            // initCreateOrder() {
            //     if (hokodoData().getUserId()) {
            //         return requestOfferAction(
            //             hokodoData().getOrganisationId(),
            //             hokodoData().getUserId(),
            //             quote.getQuoteId()
            //         );
            //     }
            // },

            // resolvePaymentOffer() {
            //     const self = this;
            //     if (!hokodoData().getOrganisation().api_id) {
            //
            //     } else {
            //         this.createOrder().done((response) => {
            //             requestPaymentOfferAction(response, '').done((deferredPayment) => {
            //                 hokodoData().setOffer(deferredPayment);
            //             })
            //         })
            //     }
            // },

            // createOrder() {
            //     return setHokodoOrderAction(
            //         hokodoData().getUser(),
            //         hokodoData().getOrganisation(),
            //         ''
            //     )
            // }

            getCustomerEmail() {
                return customer.isLoggedIn ? customer.customerData.email : quote.shippingAddress().customerEmail
            },

            getCustomerName() {
                return customer.isLoggedIn ? customer.customerData.firstname + ' ' + customer.customerData.lastname :
                    quote.shippingAddress().firstname + ' ' + quote.shippingAddress().lastname;
            }
        })
    })
