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

								// defaults:
								manager.store.addByValue('facet', true);
								manager.store.addByValue('json.nl', 'map');
								manager.store.addByValue('facet.mincount', 1);

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
												// only jpgs
												manager.store.addByValue('fq', 'extension:jpg');
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
												/*
												if(response.statusAA ) {
																defer.resolve(response);
												}
												else {
												*/
																buildSolrValues();
																var url = manager.buildUrl();
																console.log(url);
																$http.jsonp(url).then(function(data) {
																				response = data;
																				defer.resolve(data);
																});
											//	}
												return defer.promise;
								};

        // Public API
        return {
												getData: function() {
																return getData();
												}
        };

				}
}());


