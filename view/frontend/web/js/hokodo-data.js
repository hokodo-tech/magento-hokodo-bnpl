/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'jquery',
    'Magento_Customer/js/customer-data',
    'mageUtils',
    'jquery/jquery-storageapi'
], function (
        $,
        storage,
        utils
        ) {
    'use strict';

    var cacheKey = 'hokodo-data',
            saveData = function (data) {
                storage.set(cacheKey, data);
            },
            initData = function () {
                return {
                    'user': null,
                    'organisation': null,
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

        clearData: function () {
            var data = initData();
            saveData(data);
        },

        rebuildErrorMessage: function (response, jsonflag) {
            var errorText = "Unfortunately you are not eligible for Buy Now Pay Later. Please select another payment method.";
            if (jsonflag == 1) {
                response.responseText = JSON.stringify({"message": errorText});
            } else {
                for (var i = 0; i < response.offered_payment_plans.length; i++) {
                    if (response.offered_payment_plans[i].hasOwnProperty('rejection_reason'))
                    {
                        response.offered_payment_plans[i].rejection_reason.detail = errorText;
                    }
                }
            }
            return response;
        }
    };

});
