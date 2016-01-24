/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.solr')
        .service('Solr', Solr);

    function Solr(SolrConfiguration, Request, PathService, $rootScope) {

        var self = this;

        var solrConfiguration = {};
        var settings = {};
        var params = {};
        var facets = {};
        var hFacets = {};
        var manager = {};

        var facetPrefixes = {};
        var filterQueries = {};
        var initialized = false;
        var data = {};

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
        self.facetsToKeyValue = facetsToKeyValue;
        self.facetsToLabelValue = facetsToLabelValue;
        self.hasFilterQuery = hasFilterQuery;
        self.setFacetPrefix = setFacetPrefix;

        self.setSearch = setSearch;
        self.clearSearch = clearSearch;
        self.nextPage = nextPage;
        self.newRandom = newRandom;
        self.showAllRows = showAllRows;
        self.newRandomAndUpdate = newRandomAndUpdate;
        self.nextPageAndUpdate = nextPageAndUpdate;
        self.changeRowsAndUpdate = changeRowsAndUpdate;
        self.showAllRowsAndUpdate = showAllRowsAndUpdate;
        self.resetFilterQueriesAndUpdate = resetFilterQueriesAndUpdate;
        self.changeFacetCountAndUpdate = changeFacetCountAndUpdate;
        self.changeFacetSortingAndUpdate = changeFacetSortingAndUpdate;
        self.rmFilterQueryAndUpdate = rmFilterQueryAndUpdate;
        self.addFilterQueryAndUpdate = addFilterQueryAndUpdate;
        self.overrideFilterQueryAndUpdate = overrideFilterQueryAndUpdate;
        self.setSearchAndUpdate = setSearchAndUpdate;
        self.clearSearchAndUpdate = clearSearchAndUpdate;

        self.init = init;
        self.reset = reset;
        self.getData = getData;
        self.setData = setData;
        self.update = update;

        function update() {
            forceRequest().then(function (response) {
                setData(response.data);
            })
        }

        function getData() {
            return data;
        }

        function setData(newData) {
            data = newData;
            $rootScope.$broadcast('solrDataUpdate', data);
        }

        function init() {
            solrConfiguration = SolrConfiguration.getConfiguration();
            settings = solrConfiguration.settings;
            params = solrConfiguration.params;
            facets = solrConfiguration.facets;
            hFacets = solrConfiguration.hFacets;
            manager = new AjaxSolr.Manager(settings);
            manager.init();
            angular.forEach(hFacets, function (val, key) {
                setFacetPrefix(key, val);
            });
            initialized = true;
        }

        function reset() {
            filterQueries = {};
            hFacets = {};
            facetPrefixes = {};
        }

        function facetsToKeyValue(facets) {
            var res = [];
            angular.forEach(facets, function(key, val) {
                res.push({key: val, value: val + ' (' + key + ')'});
            });
            return res;
        }

        function facetsToLabelValue(facets, q) {
            var res = [];
            angular.forEach(facets, function(key, val) {
                if (q === '*:*') {
                    res.push({label: val + ' (' + key + ')', value: val});
                } else {
                    res.push({label: q + ' ' + val + ' (' + key + ')', value: q + ' ' + val});
                }
            });
            return res;
        }

        function getHFacet(name) {
            return hFacets[name];
        }

        function getFilterQueries() {
            return filterQueries;
        }

        function resetFilterQueries() {
            filterQueries = {};
        }

        function isHFacet(name) {
            if (hFacets[name] !== undefined) {
                return true;
            } else {
                return false;
            }
        }

        function nextPage(pageNumber) {
            setParam('start', ((pageNumber - 1) * params.rows).toString());
        }

        function newRandom(random) {
            setParam('sort', random);
        }

        function newRandomAndUpdate(random) {
            newRandom(random);
            update();
        }

        function nextPageAndUpdate(pageNumber) {
            nextPage(pageNumber);
            update();
        }

        function changeRowsAndUpdate(diff) {
            changeRows(diff);
            update();
        }

        function showAllRowsAndUpdate() {
            showAllRows();
            update();
        }

        function resetFilterQueriesAndUpdate() {
            resetFilterQueries();
            update();
        }

        function showAllRows() {
            setParam('rows', data.response.numFound);
        }

        function changeFacetCountAndUpdate(facetName, diff) {
            changeFacetCount(facetName, diff);
            update();
        }

        function changeFacetSortingAndUpdate(facetName, sorting) {
            changeFacetSorting(facetName, sorting);
            update();
        }

        function rmFilterQueryAndUpdate(name, value) {
            rmFilterQuery(name, value);
            update();
        }

        function addFilterQueryAndUpdate(name, value) {
            addFilterQuery(name, value);
            update();
        }

        function overrideFilterQueryAndUpdate(name, value) {
            overrideFilterQuery(name, value);
            update();
        }

        function setSearchAndUpdate(search) {
            setSearch(search);
            update();
        }

        function setSearch(search) {
            setParam('q', search);
        }

        function clearSearchAndUpdate() {
            clearSearch();
            update();
        }

        function clearSearch() {
            setParam('q', '*:*');
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

        function hasFilterQuery(name, value) {
            if (angular.isDefined(value) === true && angular.isDefined(filterQueries[name]) === true) {
                var filterQueryValues = filterQueries[name];
                angular.forEach(filterQueryValues, function (val) {
                   if (val === value) {
                       return true;
                   }
                });
                return false;
            } else {
                return angular.isDefined(filterQueries[name]);
            }
        }

        function setFacetPrefix(name, value) {
            facetPrefixes[name] = value;
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
            if (initialized === false) {
                self.init();
            }
            buildUrl();
            if (global === true) {
                // remove all fq
                manager.store.remove('fq');
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
        }

        function buildUrl() {
            if (initialized === false) {
                self.init();
            }
            var solrParams = getSolrParams();

            angular.forEach(solrParams, function (val, key) {
                manager.store.addByValue(key, val);
            });
            angular.forEach(facets, function (val) {
                manager.store.addByValue('facet.field', val);
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
