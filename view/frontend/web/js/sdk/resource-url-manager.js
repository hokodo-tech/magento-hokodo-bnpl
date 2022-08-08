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
        getCreateOrganisationUrl() {

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
            }

            return urlBuilder.createUrl(url, urlParams);
        },
    };

});
