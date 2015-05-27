/* global angular */

(function () {
    'use strict';

				angular
				.module('mp3App')
				.controller('SearchController', SearchController);

				function SearchController($scope, Solr) {

        $scope.songs = [];

								function update(search) {
												if (search !== undefined) {
																if (search !== '') {
																				Solr.setSetting('q', search);
																} else {
																				Solr.setSetting('q', '*:*');
																}
												}
												Solr.getData().then(function(data) {
																angular.forEach(data.data.response.docs, function(doc) {
																				//console.log('foo');
																				var song = {
																								id: doc.identifier,
																								title: doc.id3Title,
																								artist: doc.id3Artist,
																								url: 'http://dev/' + doc.webPath
																				};
																				$scope.songs.push(song);
																});
																//console.log(data.data);
																$scope.data = data.data;
																
												});
								};



								$scope.settings = Solr.getSettings();
								$scope.facets = Solr.getFacets();
								$scope.filterQueries = Solr.getFilterQueries();
								$scope.search = '';

								$scope.finished = true;
								$scope.renameCategory = null;
								var currentFacetField = null;
								$scope.editCategory = function(facetName, facetValue) {};
								$scope.updateCategory = function(renameCategory) {};

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


				}
}());
