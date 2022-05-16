/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/url-builder',
    'mageUtils'
], function (
	customer,
	quote,
	urlBuilder,
	utils
) {
    'use strict';

    return {
    	getUrlForSearchCompany: function () {
    		var params = {},
			urls = {
                'default': '/hokodo-company/search'
            };

    		return this.getUrl(urls, params);
    	},

    	getUrlForCreateUser: function () {
    		var params = this.getCheckoutMethod() == 'guest' ? //eslint-disable-line eqeqeq
                    {
                        cartId: quote.getQuoteId()
                    } : {},
    			urls = {
                    'guest': '/guest-customer/:cartId/set-hokodo-user',
                    'customer': '/carts/mine/set-hokodo-user'
                };

            return this.getUrl(urls, params);
    	},

    	getUrlForSetOrganisation: function () {
    		var params = this.getCheckoutMethod() == 'guest' ? //eslint-disable-line eqeqeq
                    {
                        cartId: quote.getQuoteId()
                    } : {},
			urls = {
                'guest': '/guest-customer/:cartId/set-hokodo-user-organisation',
                'customer': '/carts/mine/set-hokodo-user-organisation'
            };

    		return this.getUrl(urls, params);
    	},

    	getUrlForSetOrder: function () {
    		var params = this.getCheckoutMethod() == 'guest' ? //eslint-disable-line eqeqeq
                    {
                        cartId: quote.getQuoteId()
                    } : {},
                urls = {
                    'guest': '/guest-customer/:cartId/set-hokodo-order',
                    'customer': '/carts/mine/set-hokodo-order'
                };
            return this.getUrl(urls, params);
    	},

    	getUrlForRequestNewOffer: function () {
    		var params = this.getCheckoutMethod() == 'guest' ? //eslint-disable-line eqeqeq
                    {
                        cartId: quote.getQuoteId()
                    } : {},
                urls = {
                    'guest': '/guest-customer/:cartId/request-new-hokodo-offer',
                    'customer': '/carts/mine/request-new-hokodo-offer'
                };

    		return this.getUrl(urls, params);
    	},

    	getUrlForPaymentOffer: function (offerId) {
    		var params = this.getCheckoutMethod() == 'guest' ? //eslint-disable-line eqeqeq
                {
                    cartId: quote.getQuoteId()
                } : {},
    		urls = {
				'guest': '/guest-customer/:cartId/get-hokodo-payment-offers/:offerId',
				'customer': '/carts/mine/get-hokodo-payment-offers/:offerId'
    		};

            params.offerId = offerId;

    		return this.getUrl(urls, params);
    	},

    	/**
         * Get url for service.
         *
         * @param {*} urls
         * @param {*} urlParams
         * @return {String|*}
         */
        getUrl: function (urls, urlParams) {
            var url;

            if (utils.isEmpty(urls)) {
                return 'Provided service call does not exist.';
            }

            if (!utils.isEmpty(urls['default'])) {
                url = urls['default'];
            } else {
                url = urls[this.getCheckoutMethod()];
            }

            return urlBuilder.createUrl(url, urlParams);
        },

    	/**
         * @return {String}
         */
        getCheckoutMethod: function () {
            return customer.isLoggedIn() ? 'customer' : 'guest';
        }
    };

});
