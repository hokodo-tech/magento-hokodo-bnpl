define([
    'ko',
    'jquery',
    'uiComponent',
    'Magento_CheckoutAgreements/js/model/agreements-modal',
    'Magento_Checkout/js/checkout-data'
], function (ko, $, Component, agreementsModal, checkoutData) {
    'use strict';

    var mixin = {
        defaults: {
            template: 'Hokodo_BNPL/checkout/checkout-agreements'
        },

        isShow: function (element) {
            if (checkoutData.getSelectedPaymentMethod() == "hokodo_bnpl" && this.isAgreementRequired(element)) {
                return false;
            }

            return true;
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
