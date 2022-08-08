define([
        'jquery',
        'ko',
        'uiComponent',
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/model/quote',
        'Hokodo_BNPL/js/sdk/hokodo-data-persistor',
        'Hokodo_BNPL/js/sdk/action/create-hokodo-user',
        'Hokodo_BNPL/js/sdk/action/set-user-organisation',
        'Hokodo_BNPL/js/sdk/action/set-hokodo-order',
        'Hokodo_BNPL/js/sdk/action/request-payment-offer',
    ],
    function ($, ko, Component, customer, quote, hokodoData, createUserAction, setUserOrganisationAction, setHokodoOrderAction, requestPaymentOfferAction) {
        return Component.extend({
            defaults: {
                listens: {
                    customerEmailShipping: 'reloadHokodoData'
                },
                imports: {
                    customerEmailShipping: 'checkout.steps.shipping-step.shippingAddress.customer-email:email'
                },
            },

            initialize() {
                this._super();
                quote.shippingAddress.subscribe(function() {
                    this.reloadHokodoData();
                }, this)
                return this;
            },

            initObservable() {
                this._super()
                    .observe({
                        paymentOffer: {}
                    });
                return this;
            },

            reloadHokodoData() {
                if (quote.shippingAddress() && this.customerEmailShipping && this.hasCustomerEmailChanged()) {
                    hokodoData.setUser({})
                    hokodoData.setOrganisation({})
                    if (customer.isLoggedIn()) {
                        this.initLoggedInCustomer();
                    } else {
                        //this.initGuestCustomer();
                    }
                } else {

                }

            },

            hasCustomerEmailChanged() {
                return this.customerEmailShipping !== null && (hokodoData.getUser().id === undefined || hokodoData.getUser().email !== this.customerEmailShipping);
            },

            createUser(user) {
                return createUserAction(user, '');
            },

            getLoggedInCustomerPayload() {
                return {
                    email: customer.customerData.email,
                    name: quote.shippingAddress().firstname + ' ' + quote.shippingAddress().lastname,
                    phone: quote.shippingAddress().telephone ? quote.shippingAddress().telephone : ''
                }
            },

            initLoggedInCustomer() {
                const self = this;
                this.createUser(this.getLoggedInCustomerPayload()).done((response) => {
                    hokodoData.setUser(response.user);
                    hokodoData.setOrganisation(response.organisation);
                    self.resolvePaymentOffer();
                })
            },

            initGuestCustomer() {
                this.createUser().done((response) => {
                    hokodoData.setUser(response.user);
                    hokodoData.setOrganisation(response.organisation);
                }).bind(this);
            },

            resolvePaymentOffer() {
                const self = this;
                if (!hokodoData.getOrganisation().api_id) {

                } else {
                    this.createOrder().done((response) => {
                        requestPaymentOfferAction(response, '').done((deferredPayment) => {
                            hokodoData.setOffer(deferredPayment);
                        })
                    })
                }
            },

            createOrder() {
                return setHokodoOrderAction(
                    hokodoData.getUser(),
                    hokodoData.getOrganisation(),
                    ''
                )
            }
        })
    })
