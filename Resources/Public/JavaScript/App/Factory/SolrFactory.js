/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.factory('SolrFactory', SolrFactory);

				function SolrFactory($http, $q, SettingsFactory, FacetFactory) {
								var manager;
								var response = {};

								manager = new AjaxSolr.Manager({
												solrUrl: 'http://localhost:8080/solr/documents2/',
												servlet: 'select',
												debug: true
								});
								manager.init();

								// solr settings
								//wrapper.manager.store.addByValue('q', '*:*');
								//wrapper.manager.store.addByValue('rows', 10);
								//wrapper.manager.store.addByValue('facet.limit', 3);
								//wrapper.manager.store.addByValue('sort', 'fileName asc');

								// page browser
								//manager.store.addByValue('start', 0);

								// defaults:
								manager.store.addByValue('facet', true);
								manager.store.addByValue('json.nl', 'map');
								manager.store.addByValue('facet.mincount', 1);


								// simple facets
								//manager.store.addByValue('facet.field', 'mainDirectoryName');
								//manager.store.addByValue('facet.field', 'year');

								// hierarchical facets
								//manager.store.addByValue('facet.field', 'paths');

								var buildSolrValues = function() {

												var settings = SettingsFactory.getSolrSettings();
												angular.forEach(settings, function(val, key) {
																manager.store.addByValue(key, val);
												});

												// remove all fq
												manager.store.remove('fq');

												var facets = FacetFactory.getFacets();
												angular.forEach(facets, function(val) {
																manager.store.addByValue('facet.field', val);
												});

												var hFacets = FacetFactory.getHFacets();
												angular.forEach(hFacets, function(val) {
																// remove all facetPrefixes
																manager.store.remove('f.' + val + '.facet.prefix');
												});

												var filterQueries = FacetFactory.getFilterQueries();
												angular.forEach(filterQueries, function(values, key) {
																angular.forEach(values, function(value) {
																				manager.store.addByValue('fq', key + ':' + value);
																});
												});

												var facetPrefixes = FacetFactory.getFacetPrefixes();
												angular.forEach(facetPrefixes, function(val, key) {
																manager.store.addByValue('f.' + key + '.facet.prefix', val);
												});
												response = {};
								};

								buildSolrValues();

								var getDataSave = function() {
												var url = manager.buildUrl();
												console.log(url);
												return $http.jsonp(url);
								};

								var getData = function() {
												var defer = $q.defer();
												if(response.status ) {
																defer.resolve(response);
												}
												else {
																var url = manager.buildUrl();
																console.log(url);
																$http.jsonp(url).then(function(data) {
																				response = data;
																				defer.resolve(data);
																});
												}
												return defer.promise;
								};

        // Public API
        return {
												buildSolrValues: function() {
																buildSolrValues();
												},
												getData: function() {
																return getData();
												}
        };

				}
}());


