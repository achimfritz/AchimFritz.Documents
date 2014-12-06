/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.factory('SolrFactory', SolrFactory);

				function SolrFactory($http, $q, SolrSettingsFactory, $timeout ) {
								var wrapper = {};
								var apiData = {};
								var running = false;

								wrapper.manager = new AjaxSolr.Manager({
												solrUrl: 'http://localhost:8080/solr/documents2/',
												servlet: 'select',
												debug: true
								});
								wrapper.manager.init();


								// solr settings
								//wrapper.manager.store.addByValue('q', '*:*');
								//wrapper.manager.store.addByValue('rows', 10);
								//wrapper.manager.store.addByValue('facet.limit', 3);
								//((wrapper.manager.store.addByValue('sort', 'fileName asc');

								// page browser
								wrapper.manager.store.addByValue('start', 0);


								// defaults:
								wrapper.manager.store.addByValue('facet', true);
								wrapper.manager.store.addByValue('json.nl', 'map');
								wrapper.manager.store.addByValue('facet.mincount', 1);


								// simple facets
								wrapper.manager.store.addByValue('facet.field', 'mainDirectoryName');
								wrapper.manager.store.addByValue('facet.field', 'year');

								// hierarchical facets
								wrapper.manager.store.addByValue('facet.field', 'paths');

								var buildSolrValues = function() {
												var settings = SolrSettingsFactory.getModSettings();
												angular.forEach(settings, function(val, key) {
																wrapper.manager.store.addByValue(key, val);
												});
								};

								wrapper.addByValue = function(key, value) {
	//											wrapper.manager.store.addByValue(key, value);
								};
								wrapper.getByValue = function(key) {
												var param = wrapper.manager.store.get(key);
												return param.val();
								};

								var getData = function() {
												var url = wrapper.manager.buildUrl();
												console.log(url);
												return $http.jsonp(url);
								};

        // Public API
        return {
												buildSolrValues: function() {
																buildSolrValues();
												},
												getData: function() {
																//console.log(SolrSettingsFactory.getSettings());
																return getData();
												},
												resetApiData: function() {
																apiData = {};
																running = false;
												},
            getByValue: function (key) {
																return wrapper.getByValue(key);
            },
            addByValue: function (key, value) {
																wrapper.addByValue(key, value);
            }
        };

				}
}());


