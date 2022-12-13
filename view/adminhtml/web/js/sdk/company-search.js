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
            entityIdSelector: 'customer.entity_id'
        },

        initialize() {
            this._super();
            const self = this;

            let counter = 0;
            let timer = setInterval(() => {
                counter++;
                if (counter > 10 || typeof Hokodo !== 'undefined'){
                    let currentCompanyId = this.source.data.hokodo.company_id;
                    let hokodoSubmitUrl = this.source.data.hokodo.submit_url;
                    let customerId = this.source.data[this.entityIdSelector];

                    if (currentCompanyId) {
                        this.companySearch = this.getSdk().elements().create("companySearch", {companyId: currentCompanyId});
                    } else {
                        this.companySearch = this.getSdk().elements().create("companySearch");
                    }

                    this.companySearch.on("companySelection", (company) => {
                        if (company !== null && company.id !== currentCompanyId) {
                            currentCompanyId = company.id;
                            $.ajax({
                                'url' : hokodoSubmitUrl,
                                'type' : 'POST',
                                'data' : {
                                    'customerId' : customerId,
                                    'companyId' : company.id,
                                    'form_key': window.FORM_KEY
                                },
                                dataType:'json'
                            }).done(function (data) {
                                self.addNotification(data.message, !data.success)
                            });
                        }
                    });

                    clearInterval(timer)
                }
            }, 500);
        },

        onAfterRender() {
            this.companySearch.mount("#hokodoCompanySearch");
        },

        getSdk() {
            return Hokodo();
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
