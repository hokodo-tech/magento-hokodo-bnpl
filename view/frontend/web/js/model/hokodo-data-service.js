/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'jquery',
    'underscore',
    'uiRegistry',
    'Hokodo_BNPL/js/action/create-hokodo-user',
    'Hokodo_BNPL/js/action/get-payment-offer',
    'Hokodo_BNPL/js/action/request-payment-offer',
    'Hokodo_BNPL/js/action/set-hokodo-order',
    'Hokodo_BNPL/js/action/set-user-organisation',
    'Hokodo_BNPL/js/model/plan-list',
    'Hokodo_BNPL/js/hokodo-data',
    'Magento_Checkout/js/model/quote',
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/full-screen-loader',
    'Magento_Checkout/js/checkout-data',
    'Magento_Ui/js/model/messageList',
    'mage/translate'
], function (
        $,
        _,
        registry,
        createHokodoUserAction,
        getPaymentOfferAction,
        requestPaymentOfferAction,
        setHokodoOrderAction,
        setUserOrganisationAction,
        planList,
        hokodoData,
        quote,
        customerModel,
        fullScreenLoader,
        checkoutData,
        messageContainer,
        $t
        ) {
    'use strict';

    return {
        checkoutProvider: registry.get('checkoutProvider'),

        setUserData: function (user) {
            hokodoData.setUser(user);
            return this;
        },

        setOrganisationData: function (organisation) {
            hokodoData.setOrganisation(organisation);
            this.checkoutProvider.set('hokodoOrganisation', hokodoData.getOrganisation());
            return this;
        },

        clearData: function () {
            hokodoData.clearData();
            return this;
        },

        selectRegisteredCompany: function (payment) {
            return this.resolveRegisteredCompany(payment).done(function (payment) {
                this.setUserData(payment.user());
                this.setOrganisationData(payment.organisation());
            }.bind(this));
        },

        selectCustomerOrganisation: function (organisation, payment) {
            return this.resolveCustomerOrganisation(organisation, payment).done(function (payment) {
                this.setUserData(payment.user());
                this.setOrganisationData(payment.organisation());
            }.bind(this));
        },

        setupFullScreenLoader: function (deferred) {
            fullScreenLoader.startLoader();
            deferred.always(function () {
                fullScreenLoader.stopLoader();
            });
            return this;
        },

        resolveHokodoData: function (payment) {
            if (!_.isEmpty(hokodoData.getUser()) && hokodoData.getUser().email) {
                payment.user(hokodoData.getUser());
            } else {
                if (!payment.user().email) {
                    payment.user().email = quote.guestEmail ? quote.guestEmail : checkoutData.getInputFieldEmailValue();
                }
            }

            this.setUserData(payment.user());

            if (!_.isEmpty(hokodoData.getOrganisation())) {
                payment.organisation(hokodoData.getOrganisation());
            } else {
                if (!payment.organisation().country) {
                    payment.organisation().country = quote.billingAddress().countryId;
                }
            }
            this.setOrganisationData(payment.organisation());
        },

        resolveRegisteredCompany: function (payment) {
            var deferred = $.Deferred();
            deferred.progress(this.notifyOrderResolver.bind(this));

            if (!payment.user().id) {
                this.setupFullScreenLoader(deferred);
                this.createUser(deferred, payment);
            } else if (payment.organisation().api_id) {
                deferred.notify(deferred, payment, true);
            } else {
                deferred.resolve(payment);
            }

            return deferred.promise();
        },

        resolveCustomerOrganisation: function (payment, organisation) {
            var deferred = $.Deferred();
            if (payment.user().id) {
                this.setupFullScreenLoader(deferred);
                this.setUserOrganisation(deferred, payment, organisation);
            } else {
                deferred.reject(payment);
            }
            return deferred.promise();
        },

        resolveOrder: function (payment, showLoader) {
            var deferred = $.Deferred();
            deferred.progress(this.notifyPaymentOfferResolver.bind(this));

            if (!payment.order().id) {
                if (payment.user().id && payment.organisation().api_id) {
                    if (showLoader) {
                        this.setupFullScreenLoader(deferred);
                    }
                    this.setHokodoOrder(deferred, payment);
                } else {
                    deferred.reject(payment);
                }
            } else {
                deferred.notify(deferred, payment, payment.planList().length == 0);
            }

            return deferred.promise();
        },

        resolvePaymentOffer: function (payment, showLoader) {
            var deferred = $.Deferred();

            if (!payment.order().id) {
                deferred.reject(payment);
                return deferred.promise();
            }

            if (showLoader) {
                this.setupFullScreenLoader(deferred);
            }

            if (!payment.order().payment_offer) {
                return this.requestPaymentOffer(deferred, payment);
            } else {
                return this.getPaymentOffer(deferred, payment);
            }
        },

        notifyOrderResolver: function (deferred, payment, showLoader) {
            this.resolveOrder(payment, showLoader).done(function (response) {
                deferred.resolve(payment, response);
            }).fail(function (response) {
                deferred.reject(payment, response);
            });
        },

        notifyPaymentOfferResolver: function (deferred, payment, showLoader) {
            this.resolvePaymentOffer(payment, showLoader).done(function (response) {
                deferred.resolve(payment, response);
            }).fail(function (response) {
                deferred.reject(payment, response);
            });
        },

        createUser: function (deferred, payment) {
            return createHokodoUserAction(payment.user(), payment.messageContainer).done(function (response) {
                if (!response.user || !response.user.id) {
                    deferred.reject(payment, response);
                    return;
                }

                payment.user(response.user);

                if (!response.organisation || !response.organisation.api_id) {
                    deferred.resolve(payment, response); //show input for search company
                    return;
                }

                payment.organisation(response.organisation);
                payment.resetOrderData();

                deferred.notify(deferred, payment, false);

            }.bind(this)).fail(function (response) {
                deferred.reject(payment, response);
            });
        },

        setUserOrganisation: function (deferred, payment, organisation) {
            deferred.progress(this.notifyOrderResolver.bind(this));
            return setUserOrganisationAction(payment.user(), organisation, payment.messageContainer).done(function (response) {

                if (!response.user || !response.user.id) {
                    deferred.reject(payment, response);
                    return;
                }

                payment.user(response.user);

                if (!response.organisation || !response.organisation.api_id) {
                    deferred.reject(payment, response);
                    return;
                }

                payment.organisation(response.organisation);
                payment.resetOrderData();

                deferred.notify(deferred, payment, false);

            }.bind(this)).fail(function (response) {
                deferred.reject(payment, response);
            });
        },

        setHokodoOrder: function (deferred, payment) {
            return setHokodoOrderAction(payment.user(), payment.organisation(), payment.messageContainer).done(function (response) {
                if (response.id) {
                    var order = payment.order();
                    order.id = response.id;

                    if (response.payment_offer) {
                        order.payment_offer = response.payment_offer;
                    }
                    payment.order(order);
                    deferred.notify(deferred, payment, false);
                } else {
                    deferred.reject(payment, response);
                }
            }.bind(this)).fail(function (response) {
                deferred.reject(payment, response);
            });
        },

        requestPaymentOffer: function (deferred, payment) {
            deferred = deferred || $.Deferred();
            if (!payment.order().id) {
                deferred.reject(payment);
            } else {
                requestPaymentOfferAction(payment.order(), payment.messageContainer).done(this.onPaymentOfferDone.bind(this, deferred, payment));
            }
            return deferred.promise();
        },

        getPaymentOffer: function (deferred, payment) {
            deferred = deferred || $.Deferred();
            if (!payment.order().payment_offer) {
                deferred.reject(payment);
            } else {
                if (_.isEmpty(payment.planList())) {
                    getPaymentOfferAction(payment.order().payment_offer, payment.messageContainer).done(this.onPaymentOfferDone.bind(this, deferred, payment));
                } else {
                    deferred.resolve(payment);
                }
            }
            return deferred.promise();
        },

        onPaymentOfferDone: function (deferred, payment, response) {
            if (!response.id) {
                deferred.reject(payment, response);
            } else {
                var order = payment.order();
                order.payment_offer = response.id;
                payment.order(order);

                if (_.isArray(response.offered_payment_plans)) {

                    var messages = {};
                    payment.planList([]);
                    _.each(response.offered_payment_plans, function (plan) {
                        if (plan.status == 'declined') {
                            if (plan.rejection_reason) {
                                messages[plan.rejection_reason.code] = $t(
                                        plan.rejection_reason.detail
                                        );
                            }
                        } else {
                            payment.planList.push(plan);
                        }
                    });

                    if (payment.planList().length > 0) {
                        payment.selectedPlan(payment.planList()[0]);
                    }

                    if (!_.isEmpty(messages)) {
                        _.each(messages, function (message) {
                            payment.messageContainer.addErrorMessage({message: message});
                        });
                    }
                }

                deferred.resolve(payment, response);
            }
        },
    };
});
