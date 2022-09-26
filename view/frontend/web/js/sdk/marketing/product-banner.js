define([
    'uiComponent',
    'jquery',
    "Hokodo_BNPL/js/sdk/hokodo-data-persistor",
    "Hokodo_BNPL/js/sdk/action/get-hokodo-customer",
    'Magento_Customer/js/customer-data',
    'Hokodo_BNPL/js/sdk/core'
], function (Component, $, hokodoData, getHokodoCustomerAction, customerData) {

    return Component.extend({
        initialize() {
            this._super();
            const self = this;

            document
                .querySelector("[data-element='credit-limit-banner']")
                .addEventListener("companySelection", (company) => {
                    hokodoData.setCompanyId(company.detail.id);
                    self.getHokodoCustomer();
                });

            if (!window.hokodoSdk) {
                $('body').on('hokodoSdkResolved', () => {
                    self.initMarketing();
                });
            } else {
                self.initMarketing();
            }
        },

        getHokodoCustomer() {
            getHokodoCustomerAction(hokodoData.getCompanyId())
                .done((result) => {
                    window.hokodoSdk.update({
                        "companyId": hokodoData.getCompanyId(),
                        "organisationId": result.organisation_id,
                        "userId": result.user_id
                    })
                }).fail((result) => {

            })
        },

        initMarketing() {
            window.hokodoSdk.marketing();
            if (hokodoData.getCompanyId()) {
                this.getHokodoCustomer();
            }
        }
    })
})
