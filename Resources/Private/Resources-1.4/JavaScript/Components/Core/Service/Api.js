/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.core')
        .service('CoreApiService', CoreApiService);

    /* @ngInject */
    function CoreApiService(ExportRestService, DocumentListRestService, DocumentCollectionRestService, CategoryRestService, Mp3DocumentId3TagRestService, DownloadRestService, RatingRestService, $rootScope, PathService, Solr) {

        var self = this;
        var $scope = $rootScope.$new();

        self.pdfDownload = pdfDownload;
        self.zipDownload = zipDownload;

        self.listMerge = listMerge;
        self.listRemove = listRemove;
        self.listMergeOne = listMergeOne;

        self.categoryRemove = categoryRemove;
        self.categoryMerge = categoryMerge;
        self.categoryMergeOne = categoryMergeOne;

        self.categoryUpdate = categoryUpdate;
        self.id3TagUpdate = id3TagUpdate;

        self.rate = rate;
        self.updateId3Tag = updateId3Tag;
        self.cddbUpdate = cddbUpdate;
        self.writeId3Tag = writeId3Tag;
        self.folderUpdate = folderUpdate;


        function categoryMerge(path, docs) {
            $rootScope.$broadcast('core:apiCallStart');
            DocumentCollectionRestService.merge(path, docs).then(restSuccess, restError);
        }
        function categoryRemove(path, docs) {
            $rootScope.$broadcast('core:apiCallStart');
            DocumentCollectionRestService.remove(path, docs).then(restSuccess, restError);
        }
        function writeId3Tag(tagPath, docs) {
            $rootScope.$broadcast('core:apiCallStart');
            DocumentCollectionRestService.writeTag(tagPath, docs).then(restSuccess, restError);
        }
        function categoryMergeOne(path, doc) {
            return categoryMerge(path, [ doc ]);
        }


        function listRemove(path, docs) {
            $rootScope.$broadcast('core:apiCallStart');
            DocumentListRestService.remove(path, docs).then(restSuccess, restError);
        }
        function listMerge(path, docs) {
            $rootScope.$broadcast('core:apiCallStart');
            DocumentListRestService.merge(path, docs).then(restSuccess, restError);
        }
        function listMergeOne(path, doc) {
            return listMerge(path, [ doc ]);
        }


        function updateId3Tag (mp3DocumentId3Tag) {
            $rootScope.$broadcast('core:apiCallStart');
            Mp3DocumentId3TagRestService.update(mp3DocumentId3Tag).then(restSuccess, restError);
        }

        function rate(rating) {
            $rootScope.$broadcast('core:apiCallStart');
            RatingRestService.update(rating).then(restSuccess, restError);
        }

        function cddbUpdate(cddb) {
            $rootScope.$broadcast('core:apiCallStart');
            DownloadRestService.updateCddb(cddb).then(restSuccesse, restError);
        }

        function folderUpdate(cddb) {
            $rootScope.$broadcast('core:apiCallStart');
            DownloadRestService.updateFolder(cddb).then(restSuccess, restError);
        }

        function zipDownload(zip, docs) {
            $rootScope.$broadcast('core:apiCallStart');
            ExportRestService.zipDownload(zip, docs).then(
                function (data) {
                    restSuccess(data);
                    var blob = new Blob([data.data], {
                        type: 'application/zip'
                    });
                    saveAs(blob, zip.name + '.zip');
                },
                restError
            );
        }

        function pdfDownload(pdf, docs) {
            $rootScope.$broadcast('core:apiCallStart');
            ExportRestService.pdfDownload(pdf, docs).then(
                function (data) {
                    restSuccess(data);
                    var blob = new Blob([data.data], {
                        type: 'application/pdf'
                    });
                    saveAs(blob, 'out.pdf');
                },
                restError
            );
        }

        function categoryUpdate(renameCategory) {
            $rootScope.$broadcast('core:apiCallStart');
            CategoryRestService.update(renameCategory).then(restSuccess, restError);
        }

        function id3TagUpdate(renameCategory) {
            $rootScope.$broadcast('core:apiCallStart');
            Mp3DocumentId3TagRestService.massTag(renameCategory).then(restSuccess, restError);
        }

        function restSuccess(data) {
            $rootScope.$broadcast('core:apiCallSuccess', data);
        }

        function restError(data) {
            $rootScope.$broadcast('core:apiCallError', data);
        }

    }
})();
