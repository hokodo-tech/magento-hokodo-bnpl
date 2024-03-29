/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'jquery',
    'underscore',
    'uiComponent',
    'Hokodo_BNPL/js/sdk/hokodo-data-persistor',
    'Magento_Ui/js/modal/modal'
], function ($, _, Component, hokodoData) {
    return Component.extend({
        defaults: {
            template: 'Hokodo_BNPL/sdk/company-search',
            rendered: false,
            searchElementConfig: {}
        },

        initialize() {
            this._super();
            if (window.hokodoSdk) {
                this.initComponent();
            } else {
                $('body').on('hokodoSdkResolved', () => {
                    this.initComponent();
                })
            }
        },

        initComponent(){
            if (hokodoData.getCompanyId()) {
                this.searchElementConfig.companyId = hokodoData.getCompanyId();
            }
            this.companySearch = window.hokodoSdk.elements().create("companySearch", this.searchElementConfig);
            this.companySearch.on("companySelection", (company) => {
                if (company !== null) {
                    hokodoData.clearData();
                    hokodoData.setCompanyId(company.id);
                }
            });
            this.mountSearch();
        },

        mountSearch() {
            if (this.rendered && window.hokodoSdk) {
                this.companySearch.mount("#hokodoCompanySearch");
            }
        },

        onAfterRender() {
            this.rendered = true;
            this.mountSearch();
        }
    })
})
