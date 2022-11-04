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
            bannerTypeStatic: 'top-strip',
            bannerTypeCredit: 'credit-limit-banner',
            customerGroupsEnabled: false,
            customerGroups: [],
            totalAmount: 100000,
            topBannerTheme: 'light',
            staticBannersEnabled: false,
            creditBannersEnabled: true
        },


        initialize() {
            this._super();
            customerData.get('customer').subscribe((data) => {
                this.getBannerType(data);
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
                if (this.creditBannersEnabled && this.isAllowedCustomerGroup(data.hokodoCustomerGroup)) {
                    this.bannerType(this.bannerTypeCredit)
                } else if (this.staticBannersEnabled && this.isAllowedCustomerGroup(data.hokodoCustomerGroup)) {
                    this.bannerType(this.bannerTypeStatic)
                }
            } else if (this.staticBannersEnabled && this.isAllowedCustomerGroup(data.hokodoCustomerGroup)) {
                this.bannerType(this.bannerTypeStatic)
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
        }
    })
})
