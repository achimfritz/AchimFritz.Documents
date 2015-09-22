/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.solr')
        .service('Solr', Solr);

    function Solr(SolrConfiguration, Request, PathService) {

        var self = this;

        var solrConfiguration = SolrConfiguration.getConfiguration();
        var settings = solrConfiguration.settings;
        var params = solrConfiguration.params;
        var facets = solrConfiguration.facets;
        var hFacets = solrConfiguration.hFacets;
        var facetPrefixes = {};
        var filterQueries = {};
        var manager = new AjaxSolr.Manager(settings);

        self.request = request;
        self.forceRequest = forceRequest;
        self.setFacets = setFacets;
        self.setHFacets = setHFacets;
        self.setParam = setParam;
        self.isHFacet = isHFacet;
        self.addFilterQuery = addFilterQuery;
        self.rmFilterQuery = rmFilterQuery;
        self.getFilterQueries = getFilterQueries;
        self.getHFacet = getHFacet;
        self.getParams = getParams;
        self.getAutocomplete = getAutocomplete;
        self.changeFacetCount = changeFacetCount;
        self.changeRows = changeRows;
        self.overrideFilterQuery = overrideFilterQuery;
        self.resetFilterQueries = resetFilterQueries;
        self.changeFacetSorting = changeFacetSorting;
        self.init = init;

        init();

        function init() {
            manager.init();
            angular.forEach(facets, function (val) {
                manager.store.addByValue('facet.field', val);
            });
            angular.forEach(hFacets, function (val, key) {
                manager.store.addByValue('f.' + key + '.facet.prefix', val);
            });
        }

        function getHFacet(name) {
            return hFacets[name];
        }

        function getFilterQueries() {
            return filterQueries;
        }

        function resetFilterQueries() {
            filterQueries = [];
        }

        function isHFacet(name) {
            if (hFacets[name] !== undefined) {
                return true;
            } else {
                return false;
            }
        }


        function changeFacetSorting(facetName, sorting) {
            var solrKey = 'f_' + facetName + '_facet_sort';
            params[solrKey] = sorting;
        }

        function changeFacetCount(facetName, diff) {
            var solrKey = 'f_' + facetName + '_facet_limit';
            var newVal = params['facet_limit'] + diff;
            if (angular.isDefined(params[solrKey])) {
                newVal = params[solrKey] + diff;
            }
            if (newVal > 0) {
                params[solrKey] = newVal;
            }
        }

        function changeRows(diff) {
            var newVal = params['rows'] + diff;
            if (newVal > 0) {
                params['rows'] = newVal;
            }
        }

        function rmFilterQuery(name, value) {
            if (isHFacet(name) === true) {
                filterQueries[name] = [];
                var fq = PathService.decreaseFq(value);
                if (fq !== '' && PathService.decreaseLevel(hFacets[name]) !== fq) {
                    filterQueries[name].push(fq);
                }
                facetPrefixes[name] = PathService.decrease(value);
            } else {
                var index = filterQueries[name].indexOf(value);
                filterQueries[name].splice(index, 1);
            }
        }

        function addFilterQuery(name, value) {
            if (filterQueries[name] === undefined) {
                filterQueries[name] = [];
            }
            if (isHFacet(name) === true) {
                facetPrefixes[name] = PathService.increase(value);
                filterQueries[name] = [];
            }
            filterQueries[name].push(value);
        }


        function overrideFilterQuery(name, value) {
            if (isHFacet(name) === true) {
                // not implemented
            } else {
                filterQueries[name] = [];
                filterQueries[name].push(value);
            }
        }

        function setFacets(newFacets) {
            facets = newFacets;
        }

        function setHFacets(newHFacets) {
            hFacets = newHFacets;
        }

        function setParam (name, value) {
            params[name] = value;
        }

        function getParams() {
            return params;
        }

        function getSolrParams () {
            var res = {};
            angular.forEach(params, function (val, key) {
                var a = key.replace(/_/g, '.');
                res[a] = val;

            });
            return res;
        }

        function forceRequest () {
            var url = buildUrl();
            return Request.forceRequest(url);
        }

        function request() {
            var url = buildUrl();
            return Request.request(url);
        }

        function getAutocomplete (search, searchField, global) {
            if (global === true) {
                // remove all fq
                manager.store.remove('fq');
            } else {
                buildUrl();
            }
            manager.store.addByValue('rows', 0);
            var words = search.split(' ');
            var lastWord = words.pop();
            var searchWord = words.join(' ');
            if (searchWord !== '') {
                manager.store.addByValue('q', searchWord);
            } else {
                manager.store.addByValue('q', '*:*');
            }
            manager.store.addByValue('f.' + searchField + '.facet.prefix', lastWord);
            manager.store.addByValue('facet.field', searchField);
            var url = manager.buildUrl();
            manager.store.removeByValue('f.' + searchField + '.facet.prefix', lastWord);
            manager.store.removeByValue('facet.field', searchField);
            return Request.forceRequest(url);
        };

        function buildUrl() {
            var params = getSolrParams();
            angular.forEach(params, function (val, key) {
                manager.store.addByValue(key, val);
            });
            // remove all fq
            manager.store.remove('fq');
            // fq
            angular.forEach(filterQueries, function (values, key) {
                angular.forEach(values, function (value) {
                    manager.store.addByValue('fq', key + ':"' + value + '"');
                });
            });
            // facet.prefix
            angular.forEach(facetPrefixes, function (val, key) {
                manager.store.addByValue('f.' + key + '.facet.prefix', val);
            });
            var url = manager.buildUrl();
            return url
        }

    }
}());
