/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'uiComponent',
    'jquery',
    'ko',
    'Hokodo_BNPL/js/sdk/hokodo-data-persistor',
    'Hokodo_BNPL/js/sdk/customer',
    'Magento_Customer/js/customer-data'
], function (Component, $, ko, hokodoData, Customer, customerData) {

    return Component.extend({
        defaults: {
            template: 'Hokodo_BNPL/sdk/marketing/banner',
            bannerTypeNonLoggedIn: 'top-strip',
            bannerTypeLoggedIn: 'credit-limit-banner',
            bannerTypeLoggedInCompanyAssigned: null,
            customerGroupsEnabled: false,
            customerGroups: [],
            advertisedCreditAmount: null,
            topBannerTheme: 'light',
            staticBannersEnabled: false,
            creditBannersEnabled: true,
            isMarketingUpdated: false,
            replaceBannerOnTheFirstCompanySearchRun: true
        },


        initialize() {
            this._super();
            customerData.get('customer').subscribe((data) => {
                this.getBannerType(data);
            })
            $('body').on('hokodo-marketing-updated', () => {
                this.isMarketingUpdated = true;
                this.getBannerType(customerData.get('customer')());
            })
            this.getBannerType(customerData.get('customer')());
        },

        initObservable() {
            this._super()
                .observe({
                    bannerType: null
                });

            return this;
        },

        getBannerType(data) {
            if (data.fullname) {
                if (this.isUserCompanyIdentifiedOnInit === undefined) {
                    this.isUserCompanyIdentifiedOnInit = !!hokodoData.getCompanyId();
                }
                if (this.creditBannersEnabled && this.isAllowedCustomerGroup(data.hokodoCustomerGroup)) {
                    if (this.bannerTypeLoggedInCompanyAssigned && this.isMarketingUpdated && this.isAllowedToReplace()) {
                        this.bannerType(this.bannerTypeLoggedInCompanyAssigned)
                    } else {
                        this.bannerType(this.bannerTypeLoggedIn)
                    }
                } else if (this.staticBannersEnabled && this.isAllowedCustomerGroup(data.hokodoCustomerGroup)) {
                    this.bannerType(this.bannerTypeNonLoggedIn)
                }
            } else if (this.staticBannersEnabled && this.isAllowedCustomerGroup(data.hokodoCustomerGroup)) {
                this.bannerType(this.bannerTypeNonLoggedIn)
            }
        },

        selectCompany(data, event) {
            hokodoData.setCompanyId(event.detail.id);
        },

        getTheme() {
            return this.topBannerTheme ? this.topBannerTheme : undefined
        },

        isAllowedCustomerGroup(groupId) {
            if (this.customerGroupsEnabled) {
                return this.customerGroups.includes(groupId);
            }
            return true;
        },

        isAllowedToReplace() {
            if (this.isUserCompanyIdentifiedOnInit) {
                return true;
            }
            return  this.replaceBannerOnTheFirstCompanySearchRun;
        }
    })
})
