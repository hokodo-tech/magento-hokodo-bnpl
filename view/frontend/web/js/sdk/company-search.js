define([
    'jquery',
    'Hokodo_BNPL/js/sdk/core',
    'uiComponent',
    'Hokodo_BNPL/js/sdk/hokodo-data',
    'Magento_Ui/js/modal/modal'
], function ($, sdkCore, Component, hokodoData, modal) {
    return Component.extend({
        defaults: {
            template: 'Hokodo_BNPL/sdk/company-search'
        },

        initialize() {
            this._super();
            this.comapnySearch = sdkCore.getSdk().elements().create("companySearch");
            const self = this;
            this.comapnySearch.on("ready", () => {

            });
            this.comapnySearch.on("failure", () => {

            });
            this.comapnySearch.on("countryInputChange", (country) => {

            });
            this.comapnySearch.on("companyTypeInputChange", (companyType) => {

            });
            this.comapnySearch.on("companySelection", (company) => {
                hokodoData.setCompany(company);
            });

            //this.openModal();
        },

        openModal() {
            $("#company-search-placeholder").modal({
                // buttons: []
            }).modal('openModal');
        },

        onAfterRender() {
            this.comapnySearch.mount("#hokodoCompanySearch");
        }
    })
})
