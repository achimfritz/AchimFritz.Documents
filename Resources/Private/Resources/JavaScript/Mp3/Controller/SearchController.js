/* global angular */

(function () {
    'use strict';

				angular
				.module('mp3App')
				.controller('SearchController', SearchController);

				function SearchController($scope, Solr) {

        $scope.songs = [];
        $scope.letterNav = [];
								$scope.showAlbums = false;
								$scope.hideArtists = false;

								var currentLetter = null;

								function update(search) {
												if (search !== undefined) {
																if (search !== '') {
																				Solr.setSetting('q', search);
																} else {
																				Solr.setSetting('q', '*:*');
																}
												}
												Solr.getData().then(function(data) {
																$scope.songs = [];
																angular.forEach(data.data.response.docs, function(doc) {
																				var song = {
																								id: doc.identifier,
																								title: doc.id3Title,
																								artist: doc.id3Artist,
																								doc: doc,
																								url: 'http://dev/' + doc.webPath
																				};
																				$scope.songs.push(song);
																});
																$scope.data = data.data;
																if ($scope.letterNav.length === 0) {
																				$scope.letterNav = data.data.facet_counts.facet_fields.artistLetter;
																}
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
												if (name === 'fsGenre' && value === 'soundtrack') {
																$scope.hideArtists = false;
																$scope.showAlbums = false;
												} else if (name === 'id3Artist') {
																$scope.showAlbums = false;
												}
												Solr.rmFilterQuery(name, value);
												update();
								};

								$scope.selectLetter= function(value) {
												if (currentLetter !== null) {
																Solr.rmFilterQuery('artistLetter', currentLetter);
												}
												currentLetter = value;
												if (currentLetter !== null) {
																Solr.addFilterQuery('artistLetter', value);
												}
												update();
								};

								$scope.addFilterQuery = function(name, value) {
												if (name === 'fsGenre' && value === 'soundtrack') {
																$scope.hideArtists = true;
																$scope.showAlbums = true;
												} else if (name === 'id3Artist') {
																$scope.showAlbums = true;
												}
												Solr.addFilterQuery(name, value);
												update();
								};

								$scope.update = function(search) {
												update(search);
								};

								update();


				}
}());
