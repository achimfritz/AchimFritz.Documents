/* global angular */

(function () {
    'use strict';

				angular
				.module('solrApp')
				.factory('SolrFactory', SolrFactory);

				function SolrFactory($http, $q, FacetFactory) {
								var response = {};
								var manager = new AjaxSolr.Manager({
												solrUrl: 'http://localhost:8080/solr/documents2/',
												servlet: 'select',
												debug: true
								});
								var settings = {
												'rows': 10,
												'q': '*:*',
												'facet_limit': 50,
												'sort': 'mDateTime desc',
												'start': 0,
												'facet': true,
												'json.nl': 'map',
												'facet_mincount': 1

								};

								var getSolrSettings = function() {
												var res = {};
												angular.forEach(settings, function (val, key) {
																var a = key.replace('_', '.');
																res[a] = val;

												});
												return res;
								};



								var buildSolrValues = function() {

												//var settings = SettingsFactory.getSolrSettings();
												angular.forEach(settings, function(val, key) {
																manager.store.addByValue(key, val);
												});

												// remove all fq
												manager.store.remove('fq');

												var facets = FacetFactory.getFacets();
												angular.forEach(facets, function(val) {
																manager.store.addByValue('facet.field', val);
												});

												var filterQueries = FacetFactory.getFilterQueries();
												angular.forEach(filterQueries, function(values, key) {
																angular.forEach(values, function(value) {
																				manager.store.addByValue('fq', key + ':' + value);
																});
												});
								};


								var getData = function() {
												var defer = $q.defer();
												buildSolrValues();
												var url = manager.buildUrl();
												//console.log(url);
												$http.jsonp(url).then(function(data) {
																response = data;
																defer.resolve(data);
												});
												return defer.promise;
								};


								manager.init();
								buildSolrValues();

        // Public API
        return {
												getSettings: function() {
																return settings;
												},
												setSetting: function(name, value) {
																settings[name] = value;
												},
												getSolrSettings: function() {
																return getSolrSettings();
												},
												getData: function() {
																return getData();
												}
        };

				}
}());


