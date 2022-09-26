define([
    'jquery',
    "Hokodo_BNPL/js/sdk/hokodo-data-persistor",
    "Hokodo_BNPL/js/sdk/action/get-hokodo-customer",
    'Hokodo_BNPL/js/sdk/core'
], function ($, hokodoData, getHokodoCustomerAction) {

    const getHokodoCustomer = function() {
        getHokodoCustomerAction(hokodoData.getCompanyId())
            .done((result) => {
                window.hokodoSdk.update({
                    "companyId": hokodoData.getCompanyId(),
                    "organisationId": result.organisation_id,
                    "userId": result.user_id
                })
            }).fail((result) => {

        })
    }

    const initMarketing = function() {
        window.hokodoSdk.marketing();
        if (hokodoData.getCompanyId()) {
            getHokodoCustomer();
        }
    }

    document
        .querySelector("[data-element='credit-limit-banner']")
        .addEventListener("companySelection", (company) => {
            hokodoData.setCompanyId(company.detail.id);
            getHokodoCustomer();
        });

    if (!window.hokodoSdk) {
        $('body').on('hokodoSdkResolved', () => {
            initMarketing();
        });
    } else {
        initMarketing();
    }
})
