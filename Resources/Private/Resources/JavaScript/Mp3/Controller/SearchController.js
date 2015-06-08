/* global angular */

(function () {
    'use strict';

				angular
				.module('mp3App')
				.controller('SearchController', SearchController);

				function SearchController($scope, Solr, ExportRestService, FlashMessageService) {

        $scope.songs = [];
        $scope.letterNav = [];
								$scope.showAlbums = false;
								$scope.hideArtists = false;
								$scope.form = '';

								$scope.settings = Solr.getSettings();
								$scope.facets = Solr.getFacets();
								$scope.filterQueries = Solr.getFilterQueries();
								$scope.search = '';
								$scope.finished = true;
								$scope.tagPath = '';
								$scope.zip = ExportRestService.zip();

								var currentFacetField = null;
								var currentLetter = null;

								$scope.showForm = function(form) {
												$scope.form = form;
								};

								$scope.zipDownload = function() {
												$scope.finished = false;

												var docs = [];
												angular.forEach($scope.songs, function (val, key) {
																docs.push(val.doc);
												});

												ExportRestService.zipDownload($scope.zip, docs).then(function(data) {
																$scope.finished = true;
																var blob = new Blob([data.data], {
																				type: 'application/zip'
																});
																saveAs(blob, $scope.zip.name + '.zip');
												}, function(data) {
																$scope.finished = true;
																FlashMessageService.error(data);
												});
								};

								$scope.writeTag = function() {
												//$scope.finished = false;

												var docs = [];
												angular.forEach($scope.songs, function (val, key) {
																docs.push(val.doc);
												});
												console.log($scope.tagPath);
												/*

												ExportRestService.zipDownload($scope.zip, docs).then(function(data) {
																$scope.finished = true;
																var blob = new Blob([data.data], {
																				type: 'application/zip'
																});
																saveAs(blob, $scope.zip.name + '.zip');
												}, function(data) {
																$scope.finished = true;
																FlashMessageService.error(data);
												});
												*/
								};


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
																$scope.zip.name = '';
																angular.forEach(data.data.response.docs, function(doc) {
																				if ($scope.zip.name === '') {
																								$scope.zip.name = doc.fsArtist + '_' + doc.fsAlbum;
																								$scope.zip.name = $scope.zip.name.replace(/ /g, '');
																				}
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

				}
}());
