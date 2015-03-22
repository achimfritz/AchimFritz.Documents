/* global angular */

(function () {
    'use strict';

				angular
				.module('solrApp')
				.provider('Solr', Solr);

				function Solr() {

								var solrSettings = {
												'solrUrl': 'http://localhost:8080/solr4/documents/',
												'servlet': 'select',
												'debug': true
								};
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
								var facets = ['hCategories', 'hPaths'];
								var hFacets = {
												'hPaths': '0',
												'hCategories': '1/categories'
								};

								var facetPrefixes = {};
								var filterQueries = {};
								var manager = new AjaxSolr.Manager(solrSettings);

								this.setFacets = function (newFacets) {
												facets = newFacets;
								};
								this.setHFacets = function (newHFacets) {
												hFacets = newHFacets;
								};
								this.setSolrSetting = function(name, value) {
												solrSettings[name] = value;
								};
								this.setSetting = function(name, value) {
												settings[name] = value;
								};

								var getSolrSettings = function() {
												var res = {};
												angular.forEach(settings, function (val, key) {
																var a = key.replace('_', '.');
																res[a] = val;

												});
												return res;
								};

								var getAutocomplete = function(search, searchField, $q, $http) {
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
												manager.store.removeByValue('f.' + searchField + '.facet.prefix', lastWord);
												manager.store.removeByValue('facet.field', searchField);
												$http.jsonp(url).then(function(data) {
																defer.resolve(data);
												});
												return defer.promise;
								};

								var getData = function($q, $http) {
												var defer = $q.defer();
												buildSolrValues();

												var url = manager.buildUrl();
												$http.jsonp(url).then(function(data) {
																defer.resolve(data);
												});
												return defer.promise;
								};

								var isHFacet = function(name) {
												if (hFacets[name] !== undefined) {
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
												angular.forEach(hFacets, function(val, key) {
																manager.store.addByValue('f.' + key + '.facet.prefix', val);
												});
												buildSolrValues();
								};


        // Public API
								this.$get = function($http, $q, PathService) {
												init();
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
																				return getAutocomplete(search, searchField, $q, $http);
																},
																getData: function() {
																				return getData($q, $http);
																},
																getFilterQueries: function() {
																				return filterQueries;
																},
																isHFacet: function(name) {
																				return isHFacet(name);
																},
																getHFacet: function(name) {
																				return hFacets[name];
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
																								if (fq !== '' && PathService.decreaseLevel(hFacets[name]) !== fq) {
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
												}
								};

				}
}());


