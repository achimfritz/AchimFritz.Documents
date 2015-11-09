/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.filter')
        .provider('FilterConfiguration', FilterConfiguration);

    function FilterConfiguration() {

        var filters = {};
        var configuration = {};

        // Public API
        this.$get = function () {
            return {

                getFilters: function () {
                    return filters;
                },
                setFilters: function (newFilters) {
                    filters = newFilters;
                },
                getConfiguration: function() {
                    return configuration;
                },
                setConfiguration: function(newConfiguration) {
                    configuration = newConfiguration;
                }
            }
        };

    }
}());
