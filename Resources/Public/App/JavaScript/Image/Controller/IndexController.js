/* global angular */

(function () {
    'use strict';

				angular
				.module('imageApp')
				.controller('IndexController', IndexController);

				function IndexController($scope, Solr, ClipboardFactory) {

								var initMode = true;

								$scope.settings = Solr.getSettings();
								$scope.facets = Solr.getFacets();
								$scope.filterQueries = Solr.getFilterQueries();
								$scope.search = '';

								$scope.rmFilterQuery = function (name, value) {
												Solr.rmFilterQuery(name, value);
												update();
								};

								$scope.addFilterQuery = function(name, value) {
												Solr.addFilterQuery(name, value);
												update();
								};

								$scope.update = function(search) {
												update(search);
								};

								$scope.nextPage = function(pageNumber) {
												if (initMode === false) {
																Solr.setSetting('start', ((pageNumber - 1) * $scope.settings.rows).toString());
																update();
												}
												initMode = false;
								};

								update();

								function update(search) {
												if (search !== undefined) {
																if (search !== '') {
																				Solr.setSetting('q', search);
																} else {
																				Solr.setSetting('q', '*:*');
																}
												}
												Solr.getData().then(function(data) {
																$scope.data = data.data;
																$scope.docs = data.data.response.docs;
																$scope.total = data.data.response.numFound;
																ClipboardFactory.setSolrDocs(data.data.response.docs);
												});
								};

				}
}());
