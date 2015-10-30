/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3')
        .controller('Mp3Controller', Mp3Controller);

    /* @ngInject */
    function Mp3Controller ($rootScope, angularPlayer, WidgetConfiguration, SolrConfiguration, AppConfiguration, RatingRestService, Mp3DocumentId3TagRestService, DocumentCollectionRestService, FlashMessageService, Solr, DocumentListRestService, DownloadRestService, ExportRestService) {

        var vm = this;

        // V2
        var $scope = $rootScope.$new();
        vm.infoDoc = null;
        vm.finished = true;
        vm.random = 0;
        vm.cddb = {};
        vm.zip = {};
        vm.tagPath = '';
        vm.cddbSearch = '';
        vm.playListForm = false;
        vm.artistSearch = '';

        // used by the view
        vm.showInfoDoc = showInfoDoc;
        vm.hideInfoDoc = hideInfoDoc;
        vm.rate = rate;
        vm.updateId3Tag = updateId3Tag;
        vm.addToList = addToList;
        vm.cddbUpdate = cddbUpdate;
        vm.zipDownload = zipDownload;
        vm.writeTag = writeTag;
        vm.setPlayListForm = setPlayListForm;
        vm.folderUpdate = folderUpdate;

        // not used by the view
        vm.initController = initController;
        vm.restSuccess = restSuccess;
        vm.restError = restError;
        vm.getPlaylistDocs = getPlaylistDocs;

        vm.initController();

        function initController() {
            AppConfiguration.setNamespace('mp3');
            WidgetConfiguration.setNamespace('Mp3');
            WidgetConfiguration.setWidgets({
                letterNav: false,
                filterNav: false,
                filterNavSelect: false,
                integrity: false,
                lists: false,
                docs: true
            });
            SolrConfiguration.setFacets(['artist', 'album', 'fsArtist', 'fsAlbum', 'artistLetter', 'genre', 'year', 'fsProvider', 'fsGenre', 'hPaths']);
            SolrConfiguration.setHFacets({});
            SolrConfiguration.setParam('sort', 'track asc, fsTrack asc, fsTitle asc');
            SolrConfiguration.setParam('rows', 15);
            SolrConfiguration.setParam('facet_limit', 15);
            SolrConfiguration.setParam('facet_sort', 'count');
            SolrConfiguration.setParam('f_artistLetter_facet_sort', 'index');
            SolrConfiguration.setParam('f_artistLetter_facet_limit', 35);
            SolrConfiguration.setParam('f_hPaths_facet_prefix', '2/');
            SolrConfiguration.setParam('f_hPaths_facet_limit', 35);
            SolrConfiguration.setSetting('servlet', 'mp3');

            vm.random = 'random_' + Math.floor((Math.random() * 100000) + 1) + ' asc';
            vm.cddb = DownloadRestService.cddb();
            vm.zip = ExportRestService.zip();
        }

        function showInfoDoc(doc) {
            vm.infoDoc = doc;
            vm.zip.name = doc.fsArtist + '_' + doc.fsAlbum;
            vm.zip.name = vm.zip.name.replace(/ /g, '');
            vm.cddb.path = doc.mainDirectoryName;
            vm.cddbSearch = doc.fsArtist + ' ' + doc.fsAlbum;
        }

        function hideInfoDoc() {
            vm.infoDoc = null;
        }

        function rate(rating) {
            vm.finished = false;
            RatingRestService.update(rating).then(vm.restSuccess, vm.restError);
        }

        function cddbUpdate() {
            vm.finished = false;
            DownloadRestService.updateCddb(vm.cddb).then(vm.restSuccess, vm.restError);
        }

        function folderUpdate() {
            vm.finished = false;
            DownloadRestService.updateFolder(vm.cddb).then(vm.restSuccess, vm.restError);
        }

        function getPlaylistDocs() {
            var docs = [];
            var playlist = angularPlayer.getPlaylist();
            angular.forEach(playlist, function (val, key) {
                docs.push(val.doc);
            });
            return docs;
        }

        function zipDownload () {
            vm.finished = false;
            var docs = vm.getPlaylistDocs();
            ExportRestService.zipDownload(vm.zip, docs).then(
                function (data) {
                    vm.finished = true;
                    var blob = new Blob([data.data], {
                        type: 'application/zip'
                    });
                    saveAs(blob, vm.zip.name + '.zip');
                },
                vm.restError
            );
        }

        function setPlayListForm(val) {
            vm.playListForm = val;
        }

        function writeTag  () {
            vm.finished = false;
            var docs = vm.getPlaylistDocs();
            DocumentCollectionRestService.writeTag(vm.tagPath, docs).then(vm.restSuccess, vm.restError);
        }

        function addToList() {
            vm.finished = false;
            DocumentListRestService.merge(vm.infoDoc.listItemPath, [ vm.infoDoc ]).then(vm.restSuccess, vm.restError);
        }

        function updateId3Tag (mp3DocumentId3Tag) {
            vm.finished = false;
            Mp3DocumentId3TagRestService.update(mp3DocumentId3Tag).then(vm.restSuccess, vm.restError);
        }

        function restSuccess(data) {
            vm.finished = true;
            FlashMessageService.show(data.data.flashMessages);
            Solr.forceRequest().then(function (response) {
                $rootScope.$emit('solrDataUpdate', response.data);
            })
        }

        function restError(data) {
            vm.finished = true;
            FlashMessageService.error(data);
        }

        $rootScope.$on('solrDataUpdate', function (event, data) {
            vm.zip.name = '';
            vm.cddb.path = '';
            var found = false;
            vm.random = 'random_' + Math.floor((Math.random() * 100000) + 1) + ' asc';
            if (vm.infoDoc !== null) {
                angular.forEach(data.response.docs, function(doc) {
                    if (doc.identifier === vm.infoDoc.identifier) {
                        vm.infoDoc = doc;
                        found = true;
                    }
                })
            }
            if (found === false) {
                vm.infoDoc = null;
            }
        });

    }
})();
