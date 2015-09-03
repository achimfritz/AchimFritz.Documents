/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.solr')
        .provider('SolrSettings', SolrSettings);

    function SolrSettings() {

        var settings = {
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


        // Public API
        this.$get = function () {
            return {

                getSettings: function () {
                    return settings;
                },

                setParam: function (name, value) {
                    settings.params[name] = value;
                },
                setSetting: function (name, value) {
                    settings.settings[name] = value;
                },
                setHFacets: function (hFacets) {
                    settings.hFacets = hFacets;
                },
                setFacets: function (facets) {
                    settings.facets = facets;
                }
            }
        };

    }
}());
