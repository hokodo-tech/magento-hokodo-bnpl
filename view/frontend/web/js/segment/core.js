/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'Magento_Customer/js/customer-data'
], function(customerData) {
    'use strict';

    return {
        identify(companyId, phone, name, email) {
            if (this.isAnalyticsLoaded() && this.canPushAnalytics()) {
                this.userId = analytics.user().anonymousId();
                analytics.identify(this.userId, {
                    Merchant: window.location.host,
                    LoggedIn: window.checkoutConfig.isCustomerLoggedIn,
                    Module_version: window.bnpl_version,
                    Company_identified: !!companyId,
                    Phone: phone,
                    Name: name,
                    Email: email
                })
            }
        },

        track(event, data) {
            if (this.isAnalyticsLoaded() && this.canPushAnalytics()) {
                analytics.track(event, data);
            }
        },

        canPushAnalytics() {
            let canPush = true;
            if (customerData.get('customer')().canPushAnalytics !== undefined) {
                canPush = customerData.get('customer')().canPushAnalytics;
            }
            return canPush;
        },

        isAnalyticsLoaded() {
            return typeof analytics !== 'undefined' && analytics.VERSION !== undefined;
        },

        trackLanding(amount, currency, position, quote_id) {
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
                    Cart_ID: quote_id,
                    Hokodo_logo: window.checkoutConfig.payment.hokodo_bnpl.hokodoLogo
                }
            );
            this.initialized = true;
        },

        trackSelected(quote_id) {
            this.track(
                'Hokodo Selected',
                {
                    Cart_ID: quote_id
                }
            )
        },

        trackOrderPlaced(method, id, amount, currencyCode, quote_id) {
            analytics.track(
                'Order Placed',
                {
                    currency: currencyCode,
                    total_amount: amount,
                    OrderId: id,
                    PaymentMethod: method,
                    Cart_ID: quote_id
                }
            )
        }
    }
});
