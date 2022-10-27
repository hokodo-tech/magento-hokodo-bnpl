define([
    'Hokodo_BNPL/js/sdk/hokodo-data-persistor',
    'Hokodo_BNPL/js/sdk/action/get-hokodo-customer',
    'Hokodo_BNPL/js/sdk/customer',
    'Hokodo_BNPL/js/sdk/core'
], function (hokodoData, getHokodoCustomerAction, customer) {

    return {
        getHokodoCustomer() {
            getHokodoCustomerAction(hokodoData.getCompanyId())
                .done((result) => {
                    window.hokodoSdk.update({
                        "companyId": hokodoData.getCompanyId(),
                        "organisationId": result.organisation_id,
                        "userId": result.user_id
                    })
                }).fail((result) => {
                console.log(result);
            })
        },

        initMarketing() {
            window.hokodoSdk.marketing();
            if (hokodoData.getCompanyId() && customer.name) {
                this.getHokodoCustomer();
            }
        }
    }
})
