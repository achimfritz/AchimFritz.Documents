/* global angular */

(function () {
    'use strict';

				angular
				.module('mp3App')
				.controller('SearchController', SearchController);

				function SearchController($scope, Solr) {

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
												});
								};

				}
}());
