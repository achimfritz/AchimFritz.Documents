/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3')
        .controller('Mp3Controller', Mp3Controller);

    /* @ngInject */
    function Mp3Controller (CONFIG, $rootScope, angularPlayer, RatingRestService, Mp3DocumentId3TagRestService, DocumentCollectionRestService, FlashMessageService, Solr, DocumentListRestService, DownloadRestService, ExportRestService) {

        var vm = this;
        vm.templatePaths = {};

        // V2
        vm.infoDoc = null;
        vm.finished = true;
        vm.random = 0;
        vm.cddb = {};
        vm.zip = {};
        vm.tagPath = '';
        vm.cddbSearch = '';
        vm.playListForm = false;

        // used by the view
        vm.showInfoDoc = showInfoDoc;
        vm.hideInfoDoc = hideInfoDoc;
        vm.rate = rate;
        vm.updateId3Tag = updateId3Tag;
        vm.addToList = addToList;
        vm.cddbUpdate = cddbUpdate;
        vm.zipDownload = zipDownload;
        vm.writeTag = writeTag;
        vm.getTemplate = getTemplate;
        vm.setPlayListForm = setPlayListForm;
        vm.folderUpdate = folderUpdate;

        // not used by the view
        vm.initController = initController;
        vm.restSuccess = restSuccess;
        vm.restError = restError;
        vm.getPlaylistDocs = getPlaylistDocs;

        vm.initController();

        function initController() {
            vm.random = 'random_' + Math.floor((Math.random() * 100000) + 1) + ' asc';
            vm.cddb = DownloadRestService.cddb();
            vm.zip = ExportRestService.zip();
        }

        function getTemplate(name) {
            return CONFIG.templatePath + 'Mp3/' + name + '.html';
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
