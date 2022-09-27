define([
    'jquery',
    'uiComponent',
    'Hokodo_BNPL/js/sdk/hokodo-data-persistor',
    'Hokodo_BNPL/js/sdk/action/get-hokodo-customer',
    'Hokodo_BNPL/js/sdk/marketing/marketing',
    'Hokodo_BNPL/js/sdk/core'
], function ($, Component, hokodoData, getHokodoCustomerAction, marketing) {

    return Component.extend({
        initialize() {
            this._super();
            if (!window.hokodoSdk) {
                $('body').on('hokodoSdkResolved', () => {
                    marketing.initMarketing();
                });
            } else {
                marketing.initMarketing();
            }
        }
    })
})
