/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'jquery',
    'ko',
    'Magento_Customer/js/customer-data',
    'uiComponent'
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
            storage.set('hokodo-search', hokodoData);
            return this;
        },

        getCompanyId() {
            return this.storageSearchGet('companyId');
        },

        setCompanyId(id) {
            this.storageSearchSet('companyId', id);
            return this;
        },

        getOrganisationId() {
            return this.storageCheckoutGet('organisationId');
        },

        setOrganisationId(id) {
            this.storageCheckoutSet('organisationId', id);
            return this;
        },

        getUserId() {
            return this.storageCheckoutGet('userId');
        },

        setUserId(id) {
            this.storageCheckoutSet('userId', id);
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
            this.setOrganisationId(null);
            this.setUserId(null);
            this.setOffer(null);
        },

        storageGetCheckoutObservable() {
            return storage.get('hokodo-checkout');
        },

        storageGetSearchObservable() {
            return storage.get('hokodo-search');
        },

        reload() {
            storage.reload(['hokodo-checkout'], false);
        }
    }
});
