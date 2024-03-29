define([
    'jquery',
    'ko',
    'uiComponent',
], function($, ko, Component) {
    return Component.extend({
        defaults: {
            template: 'Hokodo_BNPL/form/element/company-credit-limit',
            entityIdSelector: 'customer_id',
            prices: {}
        },

        initialize() {
            this._super();
            this.hokodoData = this.source.data.hokodo;
            let self = this;
            $('body').on('hokodo-company-updated', () => {
                $.post(this.hokodoData.company_credit_url, {
                    id: this.source.data[this.entityIdSelector]
                }).done((result) => {
                    self.prices(result);
                }).fail((result) => {

                })
            })
            if (this.hokodoData.company_id) {
                $('body').trigger('hokodo-company-updated');
            }
        },

        initObservable() {
            this._super().observe(['companyId', 'prices']);

            return this;
        }
    })
})
