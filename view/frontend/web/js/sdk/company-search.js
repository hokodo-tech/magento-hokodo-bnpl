define([
    'jquery',
    'Hokodo_BNPL/js/sdk/core',
    'uiComponent',
    'Magento_Ui/js/modal/modal'
], function ($, sdkCore, Component, modal) {
    return Component.extend({
        defaults: {
            template: 'Hokodo_BNPL/sdk/company-search'
        },

        initialize() {
            this._super();
            this.comapnySearch = sdkCore.getSdk().elements().create("companySearch");
            this.openModal();
        },

        openModal() {
            $("#hokodoCompanySearch").modal('openModal');
        },

        onAfterRender() {
            this.comapnySearch.mount("#hokodoCompanySearch");
        }
    })
})
