define([
], function() {
    'use strict';

    return {
        identify() {
            if (this.isAnalyticsLoaded()) {
                this.userId = analytics.user().anonymousId();
                analytics.identify(this.userId, {
                    Merchant: window.location.host,
                    LoggedIn: window.checkoutConfig.isCustomerLoggedIn
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

        trackLanding(amount, position) {
            this.track(
                'Initiation',
                {
                    totalAmount: amount,
                    Impression: position !== undefined,
                    Position: position !== undefined ? position : null
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

        trackCompanyType(companyType) {
            this.track(
                'Company Type',
                {Type: companyType}
            )
        },

        trackCompanySearch(data) {
            this.track(
                'Company Search',
                {Search: data}
            )
        },

        trackCompanyMatch(data) {
            this.track(
                'Company Match',
                {Company: data}
            )
        },

        trackEligibility(data) {
            this.track(
                'Eligibility Check',
                data
            )
        },

        trackRedirection(data) {
            this.track(
                'Redirection',
                {Selected: data}
            )
        },

        trackOrderPlaced(method, id) {
            analytics.track(
                'Order Placed',
                {
                    PaymentMethod: method,
                    OrderId: id
                }
            )
        }
    }
});
