/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'jquery',
    'ko',
    'Magento_Customer/js/customer-data'
], function (
    $,
    ko,
    storage,
) {
    'use strict';

    return {
        storageSearchGet(key) {
            const data = storage.get('hokodo-search')()[key];
            return data !== undefined ? data : null;
        },

        storageCheckoutGet(key) {
            const data = storage.get('hokodo-checkout')()[key];
            return data !== undefined ? data : null;
        },

        storageCheckoutSet(key, data) {
            let hokodoData = storage.get('hokodo-checkout')();
            hokodoData[key] = data;
            storage.set('hokodo-checkout', hokodoData);
            return this;
        },

        storageSearchSet(key, data) {
            let hokodoData = storage.get('hokodo-search')();
            hokodoData[key] = data;
            storage.set('hokodo-search', {companyId: data});
            return this;
        },

        getCompanyId() {
            return this.storageSearchGet('companyId');
        },

        setCompanyId(id) {
            this.storageSearchSet('companyId', id);
            return this;
        },

        getOffer() {
            return this.storageCheckoutGet('offer');
        },

        setOffer(offer) {
            this.storageCheckoutSet('offer', offer);
            return this;
        },

        clearData() {
            this.setCompanyId(null);
            this.setOffer(null);
        },

        storageGetCheckoutObservable() {
            return storage.get('hokodo-checkout');
        },

        storageGetSearchObservable() {
            return storage.get('hokodo-search');
        },

        reload() {
            storage.reload(['hokodo-checkout'], true);
        }
    }
});
