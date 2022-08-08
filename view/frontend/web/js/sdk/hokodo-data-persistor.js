/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'jquery',
    'Magento_Customer/js/customer-data',
    'jquery/jquery-storageapi'
], function (
    $,
    storage
) {
    'use strict';

    var cacheKey = 'hokodo-data',
        saveData = function (data) {
            storage.set(cacheKey, data);
        },
        initData = function () {
            return {
                'userId': '',
                'organisationId': '',
                'offerId': '',
                'companyId': ''
            };
        },
        getData = function () {
            var data = storage.get(cacheKey)();

            if ($.isEmptyObject(data)) {
                data = $.initNamespaceStorage('mage-cache-storage').localStorage.get(cacheKey);

                if ($.isEmptyObject(data)) {
                    data = initData();
                    saveData(data);
                }
            }

            return data;
        };

    return {
        setUser: function (user) {
            var obj = getData();

            obj.user = user;
            saveData(obj);
        },

        getUser: function () {
            return getData().user;
        },

        setOrganisation: function (organisation) {
            var obj = getData();

            obj.organisation = organisation;
            saveData(obj);
        },

        getOrganisation: function () {
            return getData().organisation;
        },

        setOffer: function (offer) {
            var obj = getData();

            obj.offer = offer;
            saveData(obj);
        },

        getOffer: function () {
            return getData().offer;
        },

        setCompanyId: function (companyId) {
            var obj = getData();

            obj.companyId = companyId;
            saveData(obj);
        },

        getCompanyId: function () {
            return getData().companyId;
        },

        clearData: function () {
            var data = initData();
            saveData(data);
        }
    };

});
