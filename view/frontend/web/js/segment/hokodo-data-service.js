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
                let plans = [];
                if (response.id) {
                    response.offered_payment_plans.forEach((plan) => {
                        if (plan.status !== 'declined') {
                            plans.push(plan.name);
                        }
                    })
                }
                segment.trackEligibility(plans)
                this._super(deferred, payment, response);
        });

        return DataService;
    }
});
