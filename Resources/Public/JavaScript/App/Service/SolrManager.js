/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.service('SolrManager', SolrManager);

				function SolrManager($http) {
								var manager;
								this.init = function() {
												manager = new AjaxSolr.Manager({
																solrUrl: 'http://localhost:8080/solr/documents2/',
																servlet: 'select',
																debug: true
												});
												manager.init();
												manager.store.addByValue('q', '*:*');
												manager.store.addByValue('rows', 30);
												manager.store.addByValue('facet', true);
												manager.store.addByValue('json.nl', 'map');
												manager.store.addByValue('facet.mincount', 1);
												manager.store.addByValue('facet.limit', 3);
												manager.store.addByValue('facet.field', 'mainDirectoryName');
												manager.store.addByValue('sort', 'fileName asc');
								};
								this.fq = function(key, facet) {
												manager.store.addByValue('sort', 'fileName desc');
												//manager.store.addByValue('rows', 2);
												//manager.store.addByValue('fq', facet + ':' + key);
												
								};
								this.request = function() {
												var url = manager.buildUrl();
												console.log(url);
												return $http.jsonp(url);
								};
				}
}());


