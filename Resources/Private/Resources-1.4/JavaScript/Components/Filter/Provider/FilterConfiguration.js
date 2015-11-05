/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.filter')
        .provider('FilterConfiguration', FilterConfiguration);

    function FilterConfiguration() {

        var filters = {};

        // Public API
        this.$get = function () {
            return {

                getFilters: function () {
                    return filters;
                },
                setFilters: function (newFilters) {
                    filters = newFilters;
                }
            }
        };

    }
}());
