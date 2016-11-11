/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .service('ImageJobService', ImageJobService);

    /* @ngInject */
    function ImageJobService() {

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
        self.deleteFiles = deleteFiles;

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
        function deleteFiles(docs) {
            $rootScope.$broadcast('core:apiCallStart');
            DocumentCollectionRestService.deleteFiles(docs).then(restSuccess, restError);
        }

        function categoryMergeOne(path, doc) {
            return categoryMerge(path, [ doc ]);
        }
        

    }
})();
