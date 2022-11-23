define([
    'jquery',
    'underscore',
    'uiComponent',
    'Hokodo_BNPL/js/sdk/hokodo-data-persistor',
    'Magento_Ui/js/modal/modal'
], function ($, _, Component, hokodoData) {
    return Component.extend({
        defaults: {
            template: 'Hokodo_BNPL/sdk/company-search'
        },

        initialize() {
            this._super();

            let counter = 0;
            let timer = setInterval(() => {
                counter++;
                if (counter > 10 || typeof Hokodo !== 'undefined'){
                    let currentCompanyId = this.source.data.hokodo.company_id;
                    let hokodoSubmitUrl = this.source.data.hokodo.submit_url;
                    let customerId = this.source.data.customer.entity_id;

                    if (hokodoData.getCompanyId()) {
                        this.companySearch = this.getSdk().elements().create("companySearch", {companyId: hokodoData.getCompanyId()});
                    } else {
                        this.companySearch = this.getSdk().elements().create("companySearch");
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
                            hokodoData.clearData();
                            hokodoData.setCompanyId(company.id);
                        }
                    });

                    //this.openModal();

                    clearInterval(timer)
                }
            }, 500);
        },

        openModal() {
            $("#company-search-placeholder").modal({
                // buttons: []
            }).modal('openModal');
        },

        onAfterRender() {
            this.companySearch.mount("#hokodoCompanySearch");
        },

        getSdk() {
            return Hokodo();
        }
    })
})
