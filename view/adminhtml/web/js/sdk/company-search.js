define([
    'jquery',
    'underscore',
    'uiComponent',
    'Magento_Ui/js/modal/modal',
    'mage/translate',
    'mage/backend/notification'
], function ($, _, Component) {
    return Component.extend({
        defaults: {
            entityIdSelector: 'customer_id',
            isCompanySearchMounted: false,
            isRendered: false,
            isSearchElementCreated: false
        },

        initialize() {
            this._super();
            const self = this;
            if (!window.hokodoSdk) {
                $('body').on('hokodoSdkResolved', () => {
                    self.initComponent();
                })
            } else {
                this.initComponent();
            }
        },

        initComponent() {
            let self = this;
            let currentCompanyId = this.source.data.hokodo.company_id;
            let entityId = this.source.data[this.entityIdSelector];

            let payload = {};
            if (currentCompanyId) {
                payload.companyId = currentCompanyId;
            }
            this.companySearch = this.getSdk().elements().create("companySearch", payload);

            this.companySearch.on("companySelection", (company) => {
                if (company !== null && company.id !== currentCompanyId) {
                    currentCompanyId = company.id;
                    $.ajax({
                        'url' : self.source.data.hokodo.submit_url,
                        'type' : 'POST',
                        'data' : {
                            'entityId' : entityId,
                            'companyId' : company.id,
                            'form_key': window.FORM_KEY
                        },
                        dataType:'json'
                    }).done(function (data) {
                        self.addNotification(data.message, !data.success)
                    });
                }
            });

            this.isSearchElementCreated = true;
            this.mountCompanySearch();
        },

        onAfterRender() {
            this.isRendered = true;
            this.mountCompanySearch();
        },

        getSdk() {
            return window.hokodoSdk;
        },

        mountCompanySearch() {
            if (this.isReadyToMount()) {
                this.companySearch.mount("#hokodoCompanySearch");
                this.isCompanySearchMounted = true;
            }
        },

        isReadyToMount() {
            return this.isSearchElementCreated && this.isRendered && !this.isCompanySearchMounted;
        },

        addNotification(text, isError) {
            $('body').notification('clear').notification('add', {
                error: isError,
                message: $.mage.__(text),

                /**
                 * @param {*} message
                 */
                insertMethod: function (message) {
                    $('.page-main-actions').after(message);
                }
            });
        }
    })
})
