/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'uiComponent',
    'jquery',
    'ko',
    'Hokodo_BNPL/js/sdk/hokodo-data-persistor',
    'Hokodo_BNPL/js/sdk/action/get-hokodo-customer',
    'Hokodo_BNPL/js/sdk/customer',
    'Magento_Customer/js/customer-data',
    'Hokodo_BNPL/js/sdk/core'
], function (Component, $, ko, hokodoData, getHokodoCustomerAction, customer, customerData) {

    return Component.extend({

        initialize() {
            this._super();
            if (window.hokodoSdk) {
                this.initListener();
            } else {
                jQuery('body').on('hokodoSdkResolved', () => {
                    this.initListener();
                })
            }
        },

        getHokodoCustomer(customer) {
            if (customer && customer.fullname && this.companyId) {
                getHokodoCustomerAction(hokodoData.getCompanyId())
                    .done((result) => {
                        window.hokodoSdk.update({
                            "companyId": hokodoData.getCompanyId(),
                            "organisationId": result.organisation_id,
                            "userId": result.user_id
                        })
                        $('body').trigger('hokodo-marketing-updated');
                    }).fail((result) => {
                    console.log(result);
                })
            }
        },

        initListener() {
            window.hokodoSdk.marketing();
            this.companyId = hokodoData.getCompanyId();
            customerData.get('hokodo-search').subscribe((data) => {
                if (data.companyId !== undefined && this.companyId !== data.companyId) {
                    this.companyId = data.companyId;
                    this.getHokodoCustomer(customerData.get('customer')());
                }
            })

            customerData.get('customer').subscribe((data) => {
                this.getHokodoCustomer(data);
            })
            this.getHokodoCustomer(customerData.get('customer')());
        },
    })
})
