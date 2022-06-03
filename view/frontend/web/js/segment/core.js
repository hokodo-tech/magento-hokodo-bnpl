define([
], function() {
    'use strict';

    return {
        identify(amount, position) {
            if (this.isAnalyticsLoaded()) {
                this.userId = analytics.user().anonymousId();
                analytics.identify(this.userId, {
                    Merchant: window.location.host,
                    totalAmount: amount,
                    Impression: position !== undefined,
                    Position: position !== undefined ? position : null
                })
            }
        },

        track(event, data) {
            if (this.isAnalyticsLoaded()) {
                analytics.track(event, data);
            }
        },

        isAnalyticsLoaded() {
            return analytics.VERSION !== undefined;
        },

        trackLanding() {
            this.track(
                'Initiation',
                {}
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

        trackOrderPlaced(data) {
            this.track(
                'Order Placed',
                {PaymentMethod: data}
            )
        }
    }
});
