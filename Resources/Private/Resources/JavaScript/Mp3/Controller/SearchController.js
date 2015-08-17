/* global angular */

(function () {
    'use strict';

    angular
        .module('mp3App')
        .controller('SearchController', SearchController);

    function SearchController($scope, Solr, angularPlayer, CddbRestService, RatingRestService, ExportRestService, FlashMessageService, DocumentListRestService, DocumentCollectionRestService, Mp3DocumentId3TagRestService) {

        $scope.songs = [];
        $scope.letterNav = [];
        $scope.form = '';
        $scope.tagPath = '';
        $scope.infoDoc = null;

        $scope.settings = Solr.getSettings();
        $scope.facets = Solr.getFacets();
        $scope.filterQueries = Solr.getFilterQueries();
        $scope.search = '';
        $scope.finished = true;
        $scope.zip = ExportRestService.zip();
        $scope.cddb = CddbRestService.cddb();
        $scope.filterNavView = 'artist';
        $scope.cddbSearch = '';


        var currentFacetField = null;
        var currentLetter = null;

        $scope.setFilterNavView = function(filter) {
            $scope.filterNavView = filter;
        };

        $scope.updateId3Tag = function (data, tagName, identifier) {
            $scope.finished = false;
            var mp3DocumentId3Tag = {
                'document': identifier,
                'tagValue': data,
                'tagName': tagName
            };
            Mp3DocumentId3TagRestService.update(mp3DocumentId3Tag).then(
                function (data) {
                    $scope.finished = true;
                    FlashMessageService.show(data.data.flashMessages);
                    update();
                },
                function (data) {
                    $scope.finished = true;
                    FlashMessageService.error(data);
                }
            );
        };

        $scope.showForm = function (form) {
            $scope.form = form;
        };

        $scope.showInfo = function (doc) {
            $scope.infoDoc = doc;
        };

        $scope.hideInfo = function () {
            $scope.infoDoc = null;
        };

        $scope.cddbUpdate = function () {
            $scope.finished = false;

            CddbRestService.update($scope.cddb).then(function (data) {
                $scope.finished = true;
                FlashMessageService.show(data.data.flashMessages);
                update();
            }, function (data) {
                $scope.finished = true;
                FlashMessageService.error(data);
            });
        };

        $scope.addToList = function () {
            var documentList = {
		'documentListItems': [
			{
				'sorting': 1,
				'document': {
					'__identity': $scope.infoDoc.identifier
				}
			}
		],
		'category': {
			'path': $scope.infoDoc.listItemPath
		}

            };
/*
            DocumentListRestService.merge(documentList).then(function (data) {
                $scope.finished = true;
                FlashMessageService.show(data.data.flashMessages);
                //update();
            }, function (data) {
                $scope.finished = true;
                FlashMessageService.error(data);
            });
*/
        };

        $scope.zipDownload = function () {
            $scope.finished = false;

            var docs = [];
            var playlist = angularPlayer.getPlaylist();
            angular.forEach(playlist, function (val, key) {
                docs.push(val.doc);
            });

            ExportRestService.zipDownload($scope.zip, docs).then(function (data) {
                $scope.finished = true;
                var blob = new Blob([data.data], {
                    type: 'application/zip'
                });
                saveAs(blob, $scope.zip.name + '.zip');
            }, function (data) {
                $scope.finished = true;
                FlashMessageService.error(data);
            });
        };

        $scope.rate = function (name, rate, value) {
            var rating = {
                'name': name,
                'value': value,
                'rate': rate
            };
            RatingRestService.update(rating).then(
                function (data) {
                    $scope.finished = true;
                    FlashMessageService.show(data.data.flashMessages);
                    update();
                },
                function (data) {
                    $scope.finished = true;
                    FlashMessageService.error(data);
                }
            );
        };

        $scope.writeTag = function () {
            console.log($scope.tagPath);
            $scope.finished = false;

            var playlist = angularPlayer.getPlaylist();

            var docs = [];
            angular.forEach(playlist, function (val, key) {
                docs.push(val.doc);
            });
            DocumentCollectionRestService.writeTag($scope.tagPath, docs).then(
                function (data) {
                    $scope.finished = true;
                    FlashMessageService.show(data.data.flashMessages);
                    update();
                },
                function (data) {
                    $scope.finished = true;
                    FlashMessageService.error(data);
                }
            );
        };

        $scope.rmFilterQuery = function (name, value) {
            Solr.rmFilterQuery(name, value);
            update();
        };

        $scope.selectLetter = function (value) {
            if (currentLetter !== null) {
                Solr.rmFilterQuery('artistLetter', currentLetter);
            }
            currentLetter = value;
            if (currentLetter !== null) {
                Solr.addFilterQuery('artistLetter', value);
            }
            update();
        };

        $scope.addFilterQuery = function (name, value) {
            Solr.addFilterQuery(name, value);
            update();
        };

        $scope.update = function (search) {
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
            Solr.getData().then(function (data) {
                $scope.songs = [];
                $scope.zip.name = '';
                $scope.cddb.path = '';
                angular.forEach(data.data.response.docs, function (doc) {
                    if ($scope.zip.name === '') {
                        $scope.zip.name = doc.fsArtist + '_' + doc.fsAlbum;
                        $scope.zip.name = $scope.zip.name.replace(/ /g, '');
                    }
                    if ($scope.cddb.path === '') {
                        $scope.cddb.path = doc.mainDirectoryName;
                        $scope.cddbSearch = doc.fsArtist + ' ' + doc.fsAlbum;
                    }
                    if ($scope.infoDoc !== null && doc.identifier === $scope.infoDoc.identifier) {
                        $scope.infoDoc = doc;
                    }
                    var song = {
                        id: doc.identifier,
                        title: doc.title,
                        artist: doc.artist,
                        doc: doc,
                        url: '/' + doc.webPath
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
