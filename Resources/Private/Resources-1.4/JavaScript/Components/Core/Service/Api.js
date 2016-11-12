/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.core')
        .service('CoreApiService', CoreApiService);

    /* @ngInject */
    function CoreApiService(IntegrityRestService, JobRestService, ExportRestService, DocumentListRestService, DocumentCollectionRestService, CategoryRestService, Mp3DocumentId3TagRestService, DownloadRestService, RatingRestService, $rootScope, PathService, Solr) {

        var self = this;
        var $scope = $rootScope.$new();

        self.pdfDownload = pdfDownload;
        self.zipDownload = zipDownload;

        self.categoryRemove = categoryRemove;
        self.categoryMerge = categoryMerge;
        self.categoryMergeOne = categoryMergeOne;
        self.deleteFiles = deleteFiles;

        self.categoryUpdate = categoryUpdate;
        self.id3TagUpdate = id3TagUpdate;

        self.rate = rate;
        self.updateId3Tag = updateId3Tag;
        self.cddbUpdate = cddbUpdate;
        self.writeId3Tag = writeId3Tag;
        self.folderUpdate = folderUpdate;

        // integrity
        this.integrityList = function() {
            $rootScope.$broadcast('core:apiCallStart');
            IntegrityRestService.list().then(restSuccess, restError);

        };

        this.integrityShow = function(directory) {
            $rootScope.$broadcast('core:apiCallStart');
            IntegrityRestService.show(directory).then(restSuccess, restError);

        };

        // job
        this.jobShow = function (identifier) {
            $rootScope.$broadcast('core:apiCallStart', {noSpinner: true});
            JobRestService.show(identifier).then(restSuccess, restError);
        };

        this.jobList = function () {
            $rootScope.$broadcast('core:apiCallStart');
            JobRestService.list().then(restSuccess, restError);
        };

        this.jobCreate = function (job) {
            $rootScope.$broadcast('core:apiCallStart');
            JobRestService.create(job).then(restSuccess, restError);
        };

        /* documentList */
        this.listList = function() {
            $rootScope.$broadcast('core:apiCallStart');
            DocumentListRestService.list().then(restSuccess, restError);
        };
        this.listShow = function(identifier) {
            $rootScope.$broadcast('core:apiCallStart');
            DocumentListRestService.show(identifier).then(restSuccess, restError);
        };
        this.listUpdate = function(list) {
            $rootScope.$broadcast('core:apiCallStart');
            DocumentListRestService.update(list).then(restSuccess, restError);
        };
        this.listDelete = function(identifier) {
            $rootScope.$broadcast('core:apiCallStart');
            DocumentListRestService.delete(identifier).then(restSuccess, restError);
        };

        // TODO listRemoveDocs
        this.listRemove = function (path, docs) {
            $rootScope.$broadcast('core:apiCallStart');
            DocumentListRestService.remove(path, docs).then(restSuccess, restError);
        };
        // listMergeDocs
        this.listMerge = function (path, docs) {
            $rootScope.$broadcast('core:apiCallStart');
            DocumentListRestService.merge(path, docs).then(restSuccess, restError);
        };
        // listMergeDoc
        this.listMergeOne = function (path, doc) {
            return this.listMerge(path, [ doc ]);
        };


        function categoryMerge(path, docs) {
            $rootScope.$broadcast('core:apiCallStart');
            DocumentCollectionRestService.merge(path, docs).then(restSuccess, restError);
        }
        function categoryRemove(path, docs) {
            $rootScope.$broadcast('core:apiCallStart');
            DocumentCollectionRestService.remove(path, docs).then(restSuccess, restError);
        }
        function deleteFiles(docs) {
            $rootScope.$broadcast('core:apiCallStart');
            DocumentCollectionRestService.deleteFiles(docs).then(restSuccess, restError);
        }

        function categoryMergeOne(path, doc) {
            return categoryMerge(path, [ doc ]);
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


        /* documents */

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


        /* category */

        function categoryUpdate(renameCategory) {
            $rootScope.$broadcast('core:apiCallStart');
            CategoryRestService.update(renameCategory).then(restSuccess, restError);
        }

        /* mp3 only */

        function writeId3Tag(tagPath, docs) {
            $rootScope.$broadcast('core:apiCallStart');
            DocumentCollectionRestService.writeTag(tagPath, docs).then(restSuccess, restError);
        }

        function updateId3Tag(mp3DocumentId3Tag) {
            $rootScope.$broadcast('core:apiCallStart');
            Mp3DocumentId3TagRestService.update(mp3DocumentId3Tag).then(restSuccess, restError);
        }

        function cddbUpdate(cddb) {
            $rootScope.$broadcast('core:apiCallStart');
            DownloadRestService.updateCddb(cddb).then(restSuccess, restError);
        }

        function folderUpdate(cddb) {
            $rootScope.$broadcast('core:apiCallStart');
            DownloadRestService.updateFolder(cddb).then(restSuccess, restError);
        }

        function rate(rating) {
            $rootScope.$broadcast('core:apiCallStart');
            RatingRestService.update(rating).then(restSuccess, restError);
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
