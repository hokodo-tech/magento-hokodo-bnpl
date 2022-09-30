define([
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'jquery',
    'ko',
    'underscore',
    'sidebar',
    'mage/translate',
    'mage/dropdown'
], function (Component, customerData, $, ko, _) {
    'use strict';

    var cartData = customerData.get('cart')();

    var mixin = {
        isHokodoBtnEnable: function () {
            if (cartData['hokodo_btn_show'] == '1') {
                return true;
            }

            return false;
        },
        hokodoBtnLabel: function () {
            if (cartData['hokodo_btn_label']) {
                return cartData['hokodo_btn_label'];
            }

            return null;
        },
        getCartParam: function (name) {
            if (name === 'possible_onepage_checkout') {
                $('#hokodo-btn-checkout').click(function () {
                    var checkoutUrl = window.checkout.checkoutUrl;
                    var url = checkoutUrl+'?payment_method='+cartData['hokodo_payment_method_code'];
                    window.location.replace(url);
                });
            }

            return this._super();
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
