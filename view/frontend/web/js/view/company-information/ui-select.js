/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'jquery',
    'underscore',
    'mage/translate',
    'mage/url',
    'Hokodo_BNPL/js/model/hokodo-data-service',
    'Hokodo_BNPL/js/model/resource-url-manager',
    'Magento_Ui/js/form/element/ui-select',
    'Magento_Ui/js/lib/key-codes',
    'ko',
], function (
        $,
        _,
        $t,
        urlBuilder,
        hokodoDataService,
        resourceUrlManager,
        UiSelect,
        keyCodes,
        ko
        ) {
    'use strict';

    return UiSelect.extend({
        defaults: {
            countryDataScope: '',
            country: null,
            optionTmpl: 'Hokodo_BNPL/company-information/company',
            disableLabel: true,
            filterOptions: true,
            filterOptionsFocus: true,
            filterPlaceholder: $t('Search for company'),
            searchOptions: true,
            showPath: false,
            levelsVisibility: 1,
            multiple: false,
            rootListSelector: '.admin__action-multiselect-search-wrap .search-autocomplete-results',
            selectedPlaceholders: {
                defaultPlaceholder: $t('Search for company'),
                lotPlaceholders: $t('Search for company'),
            },
            missingValuePlaceholder: $t('Company %s doesn\'t exist'),
            isDisplayMissingValuePlaceholder: true,
            isDisplayEmptyPlaceholder: true,
            isRemoveSelectedIcon: false,
            value: {},
            minSearchLength: 1,
            filterRateLimit: 0,
            filterRateLimitMethod: 'notifyWhenChangesStop',
            emptyOptionsHtml: $t('No results'),
            imports: {
                disabled: 'checkout.steps.billing-step.payment.payments-list.hokodo_bnpl:disabledSelection',
                country: '${ $.provider }:${ $.countryDataScope }'
            },
            tracks: {
                country: true
            },
            modules: {
                payment: 'checkout.steps.billing-step.payment.payments-list.hokodo_bnpl'
            },
            listens: {
                filterInputValue: '', // no auto search
            },
        },
        gTimeout: 0,
        searchRequested: ko.observable(false),
        isSelected: function (value) {
            return this.multiple ? _.contains(this.value(), value) : this.value().company_api_id === value;
        },
        isSelectedValue: function (option) {
            if (_.isUndefined(option)) {
                return false;
            }

            return this.isSelected(option.company_api_id);
        },
        getSelected: function () {
            var selected = this.value();
            if (selected.api_id) {
                return [selected];
            }

            return this.cacheOptions.plain.filter(function (opt) {
                return _.isArray(selected) ?
                        _.contains(selected, opt.company_api_id) :
                        selected.company_api_id == opt.company_api_id; //eslint-disable-line eqeqeq
            });
        },
        setCaption: function () {
            var selected = this.getSelected();
            var searchValue = this.filterInputValue().trim();

            if (selected.length > 0 && selected[0].company_api_id) {
                this.placeholder(selected[0].name);
            } else if (searchValue) {
                this.placeholder(searchValue);
            } else {
                this.placeholder(this.selectedPlaceholders.defaultPlaceholder);
            }

            return this.placeholder();
        },
        isHovered: function (data) {
            var element = this.hoveredElement,
                elementData;

            if (!element) {
                return false;
            }

            elementData = ko.dataFor(this.hoveredElement);

            if (_.isUndefined(elementData)) {
                return false;
            }

            if (!data.company_api_id || !elementData.company_api_id) {
                return false;
            }

            return data.company_api_id === elementData.company_api_id;
        },
        toggleListVisible: function () {
            this.listVisible(!this.disabled() && !this.listVisible());
            if (this.listVisible()) {
                this.options.latestValue = {};
                this.filterOptionsFocus(this.listVisible());
            }

            var selected = $('._selected').closest('._root')[0];
            if (selected) {
                this._hoverTo(selected);
            }
            return this;
        },
        toggleOptionSelected: function (data) {
            return this.selectCustomerOrganisation(data);
        },
        selectCustomerOrganisation: function (organisation) {
            var p = this.payment();

            hokodoDataService.selectCustomerOrganisation(p, organisation)
                    .done(this._toggleOptionSelected.bind(this, organisation))
                    .fail(this._toggleOptionSelected.bind(this, organisation));
            return this;
        },
        _toggleOptionSelected: function (data, response) {

            var isSelected = this.isSelected(data);
            if (this.lastSelectable && data.hasOwnProperty(this.separator)) {
                return this;
            }

            if (!this.multiple) {
                if (!isSelected) {
                    this.value(data);
                }
                this.listVisible(false);
            } else {
                if (!isSelected) { /*eslint no-lonely-if: 0*/
                    this.value.push(data);
                } else {
                    this.value(_.without(this.value(), data));
                }
            }

            return this;
        },
        enterKeyHandler: function (_, event) {
            if (event.target.tagName === 'BUTTON') {
                this.search();
            }

            if (this.listVisible()) {
                var hoveredData = ko.dataFor(this.hoveredElement);
                if (hoveredData && hoveredData.company_api_id) {
                    this.toggleOptionSelected(hoveredData);
                }
            }
        },
        keydownSwitcher: function (data, event) {
            if (this.listVisible() && this.isTabKey(event) && event.shiftKey) {
                var inputWrapper = $(event.target).closest('.action-select-wrap')[0];
                if (inputWrapper) {
                    inputWrapper.removeAttribute('tabindex');
                }
            }
            return this._super(data, event);
        },
        onFocusIn: function (ctx, event) {
            var input = $(event.target).find('input[type="text"]')[0];
            if (!this.listVisible() && !this.filterOptionsFocus() && input) {
                this.toggleListVisible();
                input.focus();
            }
            this._super(ctx, event);
        },
        onFocusOut: function (_, event) {
            var inputWrapper = $(event.target).closest('.action-select-wrap')[0];
            if (inputWrapper && !inputWrapper.hasAttribute('tabindex')) {
                inputWrapper.setAttribute('tabindex', 0);
            }
            this._super();
        },
        keyDownHandlers: function () {
            return {
                enterKey: this.enterKeyHandler,
                escapeKey: this.escapeKeyHandler,
                pageUpKey: this.pageUpKeyHandler,
                pageDownKey: this.pageDownKeyHandler
            };
        },
        filterOptionsKeydown: function (data, event) {
            var keyName = keyCodes[event.keyCode];
            if (keyName === 'enterKey') {
                event.preventDefault();
                this.search();
            }

            return this._super(data, event);
        },
        filterOptionsList: function () {
            this.loading(true);
            if (this.searchOptions) {
                var value = this.filterInputValue().trim();
                this.currentSearchKey = value;
                if (value.length >= parseInt(this.minSearchLength, 10)) {
                    return this.loadOptions(value);
                } else {
                    this.renderPath = false;
                    this.options([]);
                    this._setItemsQuantity(false);
                    return false;
                }
            } else {
                return this._super();
            }
        },
        processRequest: function (searchKey, page) {
            this.internalProcessRequest(searchKey, page);
        },
        internalProcessRequest: function (searchKey, page) {
            jQuery('.admin__data-grid-loading-mask').show()
            this.currentSearchKey = searchKey;
            $.ajax({
                url: urlBuilder.build(resourceUrlManager.getUrlForSearchCompany()),
                type: 'get',
                dataType: 'json',
                context: this,
                data: {
                    companyName: searchKey,
                    countryName: this.country
                },
                success: $.proxy(this.success, this),
                error: $.proxy(this.error, this),
                beforeSend: $.proxy(this.beforeSend, this),
                complete: $.proxy(this.complete, this, searchKey, page)
            });
        },
        success: function (response) {
            var existingOptions = [];
            _.each(response, function (opt) {
                if (opt.country !== '') {
                    existingOptions.push(opt);
                }
            });
            this.total = existingOptions.length;
            this.cacheOptions.plain = [];
            this.options(existingOptions);
        },
        search: function () {
            if (!this.loading() && this.filterInputValue().trim().length > 0) {
                this.searchRequested(true);
                this.filterOptionsList();
                this.listVisible(true);
            }
        },
        clear: function() {
            this.value({ country: this.value().country });
            this.filterInputValue('');
            this.searchRequested(false);
            this.filterOptionsFocus(true);
            this.payment().planList([]);
            this.options([]);
        }
    });
});
