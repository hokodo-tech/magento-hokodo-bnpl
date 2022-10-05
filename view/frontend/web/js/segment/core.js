define([
], function() {
    'use strict';

    return {
        identify() {
            if (this.isAnalyticsLoaded()) {
                this.userId = analytics.user().anonymousId();
                analytics.identify(this.userId, {
                    Merchant: window.location.host,
                    LoggedIn: window.checkoutConfig.isCustomerLoggedIn,
                    Module_version: window.bnpl_version
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
            this.track(
                'Initiation',
                {
                    Impression: position !== undefined,
                    Position: position !== undefined ? position : null,
                    total_mount: amount,
                    currency: currency
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
                    totalAmount: amount,
                    OrderId: id,
                    PaymentMethod: method
                }
            )
        }
    }
});
