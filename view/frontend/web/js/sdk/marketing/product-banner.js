define([
    'uiComponent',
    'jquery',
    'Hokodo_BNPL/js/sdk/hokodo-data-persistor',
    'Hokodo_BNPL/js/sdk/action/get-hokodo-customer',
    'Magento_Customer/js/customer-data',
    'Hokodo_BNPL/js/sdk/marketing/marketing',
    'uiRegistry',
    'Hokodo_BNPL/js/sdk/core'
], function (Component, $, hokodoData, getHokodoCustomerAction, customerData, marketing, uiRegistry) {

    return Component.extend({
        initialize() {
            this._super();

            document
                .querySelector("[data-element='credit-limit-banner']")
                .addEventListener("companySelection", (company) => {
                    hokodoData.setCompanyId(company.detail.id);
                    marketing.getHokodoCustomer();
                });

            if (!uiRegistry.has('hokodo-credit-limit-banner-top')) {
                if (!window.hokodoSdk) {
                    $('body').on('hokodoSdkResolved', () => {
                        marketing.initMarketing();
                    });
                } else {
                    marketing.initMarketing();
                }
            }
        }
    })
})
