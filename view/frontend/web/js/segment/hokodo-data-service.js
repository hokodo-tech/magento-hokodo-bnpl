/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'mage/utils/wrapper',
    'Hokodo_BNPL/js/segment/core'
], function (wrapper, segment) {
    'use strict'

    return function (DataService) {
        DataService.onPaymentOfferDone = wrapper.wrapSuper(
            DataService.onPaymentOfferDone,
            function (deferred, payment, response) {
                if (!response.id) {
                    segment.trackEligibility({
                        Eligible: false
                    })
                } else {
                    segment.trackEligibility({
                        Eligible: true,
                        PaymentPlan: response.offered_payment_plans.map((plan) => {
                            return plan.name
                        })
                    })
                }
                this._super(deferred, payment, response);
        });

        return DataService;
    }
});
