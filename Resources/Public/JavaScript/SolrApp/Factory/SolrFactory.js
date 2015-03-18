/* global angular */

(function () {
    'use strict';

				angular
				.module('solrApp')
				.factory('SolrFactory', SolrFactory);

				function SolrFactory($http, $q, PathService) {
								var manager = new AjaxSolr.Manager({
												solrUrl: 'http://localhost:8080/solr4/documents/',
												servlet: 'select',
												debug: true
								});
								var settings = {
												'rows': 10,
												'q': '*:*',
												'facet_limit': 5,
												'sort': 'mDateTime desc',
												'start': 0,
												'facet': true,
												'json.nl': 'map',
												'facet_mincount': 1

								};

								var facets = ['mainDirectoryName', 'year', 'collections', 'parties', 'tags', 'locations', 'categories', 'paths', 'hPaths', 'hLocations', 'hCategories'];
								var filterQueries = {};
								var facetPrefixes = {
												'hPaths': '0',
												'hLocations': '1/locations',
												'hCategories': '1/categories'
								};

								var getSolrSettings = function() {
												var res = {};
												angular.forEach(settings, function (val, key) {
																var a = key.replace('_', '.');
																res[a] = val;

												});
												return res;
								};

								var getAutocomplete = function(search, searchField) {
												var defer = $q.defer();
												buildSolrValues();
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
												$http.jsonp(url).then(function(data) {
																defer.resolve(data);
												});
												return defer.promise;
								};

								var getData = function() {
												var defer = $q.defer();
												buildSolrValues();

												var url = manager.buildUrl();
												$http.jsonp(url).then(function(data) {
																defer.resolve(data);
												});
												return defer.promise;
								};

								var isHFacet = function(name) {
												if (facetPrefixes[name] !== undefined) {
																return true;
												} else {
																return false;
												}
								};

								var buildSolrValues = function() {

												// settings
												var solrSettings = getSolrSettings();
												angular.forEach(solrSettings, function(val, key) {
																manager.store.addByValue(key, val);
												});

												// remove all fq
												manager.store.remove('fq');

												// fq
												angular.forEach(filterQueries, function(values, key) {
																angular.forEach(values, function(value) {
																				manager.store.addByValue('fq', key + ':' + value);
																});
												});

												// facet.prefix
												angular.forEach(facetPrefixes, function(val, key) {
																manager.store.addByValue('f.' + key + '.facet.prefix', val);
												});
								};


								var init = function() {
												manager.init();
												angular.forEach(facets, function(val) {
																manager.store.addByValue('facet.field', val);
												});
												buildSolrValues();
								};

								init();

        // Public API
        return {
												getSettings: function() {
																return settings;
												},
												getSetting: function(name) {
																return settings[name];
												},
												setSetting: function(name, value) {
																settings[name] = value;
												},
												getSolrSettings: function() {
																return getSolrSettings();
												},
												getAutocomplete: function(search, searchField) {
																return getAutocomplete(search, searchField);
												},
												getData: function() {
																return getData();
												},
												getFilterQueries: function() {
																return filterQueries;
												},
												isHFacet: function(name) {
																return isHFacet(name);
												},
												addFilterQuery: function(name, value) {
																if (filterQueries[name] === undefined) {
																				filterQueries[name] = [];
																}
																if (isHFacet(name) === true) {
																				facetPrefixes[name] = PathService.increase(value);
																				filterQueries[name] = [];
																}
																filterQueries[name].push(value);
												},
												rmFilterQuery: function(name, value) {
																if (isHFacet(name) === true) {
																				filterQueries[name] = [];
																				var fq = PathService.decreaseFq(value);
																				if (fq !== '') {
																								filterQueries[name].push(fq);
																				}
																				facetPrefixes[name] = PathService.decrease(value);
																} else {
																				var index = filterQueries[name].indexOf(value);
																				filterQueries[name].splice(index, 1);
																}
												},
												getFacets: function() {
																return facets;
												}
        };

				}
}());


