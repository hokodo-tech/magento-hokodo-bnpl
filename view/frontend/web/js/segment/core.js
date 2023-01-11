/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
], function() {
    'use strict';

    return {
        identify(companyId) {
            if (this.isAnalyticsLoaded()) {
                this.userId = analytics.user().anonymousId();
                analytics.identify(this.userId, {
                    Merchant: window.location.host,
                    LoggedIn: window.checkoutConfig.isCustomerLoggedIn,
                    Module_version: window.bnpl_version,
                    Company_identified: !!companyId
                })
            }
        },

        track(event, data) {
            if (this.isAnalyticsLoaded()) {
                analytics.track(event, data);
            }
        },

        isAnalyticsLoaded() {
            return typeof analytics !== 'undefined' && analytics.VERSION !== undefined;
        },

        trackLanding(amount, currency, position) {
            const logos = window.checkoutConfig.payment.hokodo_bnpl.logos;
            this.track(
                'Initiation',
                {
                    Impression: position !== undefined,
                    Position: position !== undefined ? position : null,
                    total_amount: amount,
                    currency: currency,
                    Payment_method_title: window.checkoutConfig.payment.hokodo_bnpl.title,
                    Payment_method_subtitle: window.checkoutConfig.payment.hokodo_bnpl.subtitle,
                    Directdebit_logo: logos.includes('direct_eu') || logos.includes('direct_uk'),
                    Creditcard_logo: logos.includes('visa'),
                    Hokodo_logo: window.checkoutConfig.payment.hokodo_bnpl.hokodoLogo
                }
            );
            this.initialized = true;
        },

        trackSelected() {
            this.track(
                'Hokodo Selected',
                {}
            )
        },

        trackOrderPlaced(method, id, amount, currencyCode) {
            analytics.track(
                'Order Placed',
                {
                    currency: currencyCode,
                    total_amount: amount,
                    OrderId: id,
                    PaymentMethod: method
                }
            )
        }
    }
});
