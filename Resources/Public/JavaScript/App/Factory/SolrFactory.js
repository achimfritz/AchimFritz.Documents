/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.factory('SolrFactory', SolrFactory);

				function SolrFactory($http, $q) {
								var wrapper = {};
								var apiData = {};
								var running = false;
								//var manager;
								wrapper.manager = new AjaxSolr.Manager({
												solrUrl: 'http://localhost:8080/solr/documents2/',
												servlet: 'select',
												debug: true
								});
								wrapper.manager.init();
								wrapper.manager.store.addByValue('q', '*:*');
								wrapper.manager.store.addByValue('rows', 30);
								wrapper.manager.store.addByValue('facet', true);
								wrapper.manager.store.addByValue('json.nl', 'map');
								wrapper.manager.store.addByValue('facet.mincount', 1);
								wrapper.manager.store.addByValue('facet.limit', 3);
								wrapper.manager.store.addByValue('facet.field', 'mainDirectoryName');
								wrapper.manager.store.addByValue('sort', 'fileName asc');

								wrapper.addByValue = function(key, value) {
												wrapper.manager.store.addByValue(key, value);
								};
								wrapper.getData = function() {
												var itemsDefer=$q.defer();
												if(apiData.status ) {
																itemsDefer.resolve(apiData);
												}
												else {
																if (running === false) {
																				//running = true;
																				var url = wrapper.manager.buildUrl();
																				console.log(url);
																				$http.jsonp(url).then(function(data) {
																								//return data;
																								apiData=data;
																								itemsDefer.resolve(data);
																								running = false;
																				});
																}
												}
												return itemsDefer.promise;
								};

        // Public API
        return {
												getData: function() {
																return wrapper.getData();
												},
												resetApiData: function() {
																apiData = {};
																running = false;
												},
            addByValue: function (key, value) {
																wrapper.addByValue(key, value);
            }
        };

				}
}());


