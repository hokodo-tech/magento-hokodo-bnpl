/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'Hokodo_BNPL/js/segment/core'
], function (segment) {
    'use strict'

    return function (UiSelect) {
        return UiSelect.extend({
            search() {
                this._super();
                segment.trackCompanySearch(this.filterInputValue().trim());
            },

            selectCustomerOrganisation(organisation) {
                this._super(organisation);
                segment.trackCompanyMatch(organisation.name);
            }
        });
    }
});
