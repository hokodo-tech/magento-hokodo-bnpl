define([
    'ko',
    'jquery',
    'uiComponent',
    'Magento_CheckoutAgreements/js/model/agreements-modal',
    'Magento_Checkout/js/checkout-data'
], function (ko, $, Component, agreementsModal, checkoutData) {
    'use strict';

    var mixin = {
        isAgreementRequired: function (element) {
            if (checkoutData.getSelectedPaymentMethod() == "hokodo_bnpl") {
                return false;
            }
            return this._super(element);
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
