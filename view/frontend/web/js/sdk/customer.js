/**
 * This file will check that customer is logged in
 */
define(
    ['jquery', 'Magento_Customer/js/customer-data'],
    function ($, customerData) {
        'use strict';

        var getCustomerInfo = function () {
            var customer = customerData.get('customer');

            return customer();
        };

        var isLoggedIn = function (customerInfo) {
            customerInfo = customerInfo || getCustomerInfo();

            return customerInfo && customerInfo.firstname;
        };

        return function () {
            var deferred = $.Deferred();
            var customerInfo = getCustomerInfo();

            if (customerInfo && customerInfo.data_id) {
                deferred.resolve(isLoggedIn(customerInfo));
            } else {
                customerData.reload(['customer'], false)
                    .done(function () {
                        deferred.resolve(isLoggedIn());
                    })
                    .fail(function () {
                        deferred.reject();
                    });
            }

            return deferred;
        };
    }
);
