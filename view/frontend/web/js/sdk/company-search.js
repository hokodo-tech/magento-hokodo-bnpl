define([
    'jquery',
    'underscore',
    'Hokodo_BNPL/js/sdk/core',
    'uiComponent',
    'Hokodo_BNPL/js/sdk/hokodo-data-persistor',
    'Magento_Ui/js/modal/modal'
], function ($, _, sdkCore, Component, hokodoData) {
    return Component.extend({
        defaults: {
            template: 'Hokodo_BNPL/sdk/company-search'
        },

        initialize() {
            this._super();
            if (hokodoData.getCompanyId()) {
                this.companySearch = sdkCore.getSdk().elements().create("companySearch", {companyId: hokodoData.getCompanyId()});
            } else {
                this.companySearch = sdkCore.getSdk().elements().create("companySearch");
            }
            const self = this;
            this.companySearch.on("ready", () => {

            });
            this.companySearch.on("failure", () => {

            });
            this.companySearch.on("countryInputChange", (country) => {

            });
            this.companySearch.on("companyTypeInputChange", (companyType) => {

            });
            this.companySearch.on("companySelection", (company) => {
                if (company !== null) {
                    hokodoData.setCompanyId(company.id);
                }
            });

            //this.openModal();
        },

        openModal() {
            $("#company-search-placeholder").modal({
                // buttons: []
            }).modal('openModal');
        },

        onAfterRender() {
            this.companySearch.mount("#hokodoCompanySearch");
        }
    })
})
