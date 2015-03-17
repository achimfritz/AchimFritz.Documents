/* global angular */

(function () {
    'use strict';

				angular
				.module('solrApp')
				.factory('SolrFactory', SolrFactory);

				function SolrFactory($http, $q, PathService) {
								var response = {};
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
								var hFacets = ['hPaths', 'hLocations', 'hCategories'];
								/*
								var hFacets = {
												'locations': {
																'facet': 'hPaths',
																'name': 'locations',
																'prefix': '0/locations'
												},
												'navigation': {
																'facet': 'hPaths',
																'name': 'navigation',
																'prefix': ''
												}
								};
								*/
								var filterQueries = {};
								var facetPrefixes = {};

								var getSolrSettings = function() {
												var res = {};
												angular.forEach(settings, function (val, key) {
																var a = key.replace('_', '.');
																res[a] = val;

												});
												return res;
								};

								var isHFacet = function(name) {
												var index = hFacets.indexOf(name);
												if (index > -1) {
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

												angular.forEach(filterQueries, function(values, key) {
																angular.forEach(values, function(value) {
																				manager.store.addByValue('fq', key + ':' + value);
																});
												});
												angular.forEach(facetPrefixes, function(val, key) {
																manager.store.addByValue('f.' + key + '.facet.prefix', val);
												});
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
																response = data;
																defer.resolve(data);
												});
												return defer.promise;
								};

								var getData = function() {
												var defer = $q.defer();
												buildSolrValues();

												var url = manager.buildUrl();
												$http.jsonp(url).then(function(data) {
																response = data;
																defer.resolve(data);
												});
												return defer.promise;
								};

								var init = function() {
												manager.init();
												angular.forEach(facets, function(val) {
																manager.store.addByValue('facet.field', val);
												});
												angular.forEach(hFacets, function(val) {
																facetPrefixes[val] = '0';
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
												addFilterQuery: function(name, value) {
																if (filterQueries[name] === undefined) {
																				filterQueries[name] = [];
																}
																filterQueries[name].push(value);
												},
												rmFilterQuery: function(name, value) {
																var index = filterQueries[name].indexOf(value);
																filterQueries[name].splice(index, 1);
												},
												getFacets: function() {
																return facets;
												}
        };

				}
}());


