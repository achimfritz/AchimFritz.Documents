/* global angular */

(function () {
    'use strict';

				angular
				.module('mp3App')
				.controller('SearchController', SearchController);

				function SearchController($scope, Solr, angularPlayer, ExportRestService, FlashMessageService, DocumentCollectionRestService, Mp3DocumentId3TagRestService) {

        $scope.songs = [];
        $scope.letterNav = [];
								$scope.showAlbums = false;
								$scope.hideArtists = false;
								$scope.form = '';
								$scope.tagPath = '';
								$scope.infoDoc = null;

								$scope.settings = Solr.getSettings();
								$scope.facets = Solr.getFacets();
								$scope.filterQueries = Solr.getFilterQueries();
								$scope.search = '';
								$scope.finished = true;
								$scope.zip = ExportRestService.zip();
								//$scope.currentPage = ($scope.settings['start']/$scope.settings['rows']) + 1;


								var currentFacetField = null;
								var currentLetter = null;

								$scope.updateId3Tag = function(data, tagName, identifier) {
												$scope.finished = false;
												var mp3DocumentId3Tag = {
																'document': identifier,
																'tagValue': data,
																'tagName': tagName
												};
												Mp3DocumentId3TagRestService.update(mp3DocumentId3Tag).then(
																function(data) {
																				$scope.finished = true;
																				FlashMessageService.show(data.data.flashMessages);
																}, 
																function(data) {
																				$scope.finished = true;
																				FlashMessageService.error(data);
																}
												);
								};

								$scope.showForm = function(form) {
												$scope.form = form;
								};

								$scope.showInfo = function(doc) {
												$scope.infoDoc = doc;
								};

								$scope.hideInfo = function() {
												$scope.infoDoc = null
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

        $scope.rate = function(prefix, rate, doc) {
												var docs = [];
             if (prefix === 'track') {
              docs.push(doc.doc);
             } else {
                var playlist = angularPlayer.getPlaylist();

                angular.forEach(playlist, function (val, key) {
                    docs.push(val.doc);
                });
             }

            // TODO need new Controller: overwrite
            /*
             fixedPathDepth = 2
            */
												DocumentCollectionRestService.merge('rating/' + prefix + '/' + rate, docs).then(
																function(data) {
																				$scope.finished = true;
																				FlashMessageService.show(data.data.flashMessages);
																}, 
																function(data) {
																				$scope.finished = true;
																				FlashMessageService.error(data);
																}
												);
        };

								$scope.writeTag = function() {
												$scope.finished = false;

												var playlist = angularPlayer.getPlaylist();

												var docs = [];
												angular.forEach(playlist, function (val, key) {
																docs.push(val.doc);
												});
												DocumentCollectionRestService.writeTag($scope.tagPath, docs).then(
																function(data) {
																				$scope.finished = true;
																				FlashMessageService.show(data.data.flashMessages);
																}, 
																function(data) {
																				$scope.finished = true;
																				FlashMessageService.error(data);
																}
												);
								};


								$scope.rmFilterQuery = function (name, value) {
												if (name === 'fsGenre' && value === 'soundtrack') {
																$scope.hideArtists = false;
																$scope.showAlbums = false;
												} else if (name === 'artist') {
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
												} else if (name === 'artist') {
																$scope.showAlbums = true;
												}
												Solr.addFilterQuery(name, value);
												update();
								};

								$scope.update = function(search) {
												update(search);
								};

								$scope.nextPage = function(pageNumber) {
												//Solr.setSetting('start', ((pageNumber - 1) * $scope.settings.rows).toString());
												console.log(pageNumber);
												//$scope.settings.start = newPageNumber;
												//update();
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
																								title: doc.title,
																								artist: doc.artist,
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
