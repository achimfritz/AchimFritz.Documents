/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.solr')
        .provider('SolrConfiguration', SolrConfiguration);

    function SolrConfiguration() {

        var getDefaults = function() {
            return {
                settings: {
                    'solrUrl': 'http://dev.dev.local.dev:8080/af/documents/',
                    'servlet': 'select',
                    'debug': true
                },
                params: {
                    'rows': 10,
                    'q': '*:*',
                    'facet_limit': 5,
                    'sort': 'mDateTime desc',
                    'start': 0,
                    'facet': true,
                    'json.nl': 'map',
                    'facet_mincount': 1
                },
                facets: ['hCategories', 'hPaths', 'paths'],
                hFacets: {
                    hPaths: '0',
                    hCategories: '1/categories'
                }
            };
        };

        var configuration = getDefaults();


        // Public API
        this.$get = function () {
            return {

                getConfiguration: function () {
                    return configuration;
                },
                setDefaults: function () {
                    configuration = getDefaults();
                },

                setParam: function (name, value) {
                    configuration.params[name] = value;
                },
                setSetting: function (name, value) {
                    configuration.settings[name] = value;
                },
                setHFacets: function (hFacets) {
                    configuration.hFacets = hFacets;
                },
                setFacets: function (facets) {
                    configuration.facets = facets;
                }
            }
        };

    }
}());
