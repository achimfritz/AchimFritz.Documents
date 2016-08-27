/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.core')
        .controller('ApiController', ApiController);

    /* @ngInject */
    function ApiController(FlashMessageService, ExportRestService, DocumentListRestService, DocumentCollectionRestService, CategoryRestService, Mp3DocumentId3TagRestService, DownloadRestService, RatingRestService, $rootScope, PathService, Solr) {

        var vm = this;

        vm.finished = true;

        // used by the view
        vm.pdfDownload = pdfDownload;
        vm.zipDownload = zipDownload;

        vm.listMerge = listMerge;
        vm.listRemove = listRemove;
        vm.listMergeOne = listMergeOne;

        vm.categoryRemove = categoryRemove;
        vm.categoryMerge = categoryMerge;
        vm.categoryMergeOne = categoryMergeOne;
        vm.deleteFiles = deleteFiles;

        vm.categoryUpdate = categoryUpdate;
        vm.id3TagUpdate = id3TagUpdate;

        vm.rate = rate;
        vm.updateId3Tag = updateId3Tag;
        vm.cddbUpdate = cddbUpdate;
        vm.writeId3Tag = writeId3Tag;
        vm.folderUpdate = folderUpdate;

        // not used by the view
        vm.restSuccess = restSuccess;
        vm.restError = restError;
        vm.restSuccessAndUpdate = restSuccessAndUpdate;

        function deleteFiles(docs) {
            vm.finished = false;
            DocumentCollectionRestService.deleteFiles(docs).then(vm.restSuccess, vm.restError);
        }

        function categoryMerge(path, docs) {
            vm.finished = false;
            DocumentCollectionRestService.merge(path, docs).then(vm.restSuccess, vm.restError);
        }
        function categoryRemove(path, docs) {
            vm.finished = false;
            DocumentCollectionRestService.remove(path, docs).then(vm.restSuccessAndUpdate, vm.restError);
        }
        function writeId3Tag(tagPath, docs) {
            vm.finished = false;
            DocumentCollectionRestService.writeTag(tagPath, docs).then(vm.restSuccessAndUpdate, vm.restError);
        }
        function categoryMergeOne(path, doc) {
            return vm.categoryMerge(path, [ doc ]);
        }


        function listRemove(path, docs) {
            vm.finished = false;
            DocumentListRestService.remove(path, docs).then(vm.restSuccess, vm.restError);
        }
        function listMerge(path, docs) {
            vm.finished = false;
            DocumentListRestService.merge(path, docs).then(vm.restSuccessAndUpdate, vm.restError);
        }
        function listMergeOne(path, doc) {
            return vm.listMerge(path, [ doc ]);
        }


        function updateId3Tag (mp3DocumentId3Tag) {
            vm.finished = false;
            Mp3DocumentId3TagRestService.update(mp3DocumentId3Tag).then(vm.restSuccessAndUpdate, vm.restError);
        }

        function rate(rating) {
            vm.finished = false;
            RatingRestService.update(rating).then(vm.restSuccessAndUpdate, vm.restError);
        }

        function cddbUpdate(cddb) {
            vm.finished = false;
            DownloadRestService.updateCddb(cddb).then(vm.restSuccessAndUpdate, vm.restError);
        }

        function folderUpdate(cddb) {
            vm.finished = false;
            DownloadRestService.updateFolder(cddb).then(vm.restSuccessAndUpdate, vm.restError);
        }

        function zipDownload(zip, docs) {
            vm.finished = false;
            ExportRestService.zipDownload(zip, docs).then(
                function (data) {
                    vm.finished = true;
                    var blob = new Blob([data.data], {
                        type: 'application/zip'
                    });
                    saveAs(blob, zip.name + '.zip');
                },
                vm.restError
            );
        }

        function pdfDownload(pdf, docs) {
            vm.finished = false;
            ExportRestService.pdfDownload(pdf, docs).then(
                function (data) {
                    vm.finished = true;
                    var blob = new Blob([data.data], {
                        type: 'application/pdf'
                    });
                    saveAs(blob, 'out.pdf');
                },
                vm.restError
            );
        }

        function categoryUpdate(renameCategory, facetName) {
            vm.finished = false;
            if (Solr.isHFacet(facetName) === true) {
                CategoryRestService.update(renameCategory).then(
                    function(data) {
                        Solr.rmFilterQuery(facetName, PathService.prependLevel(renameCategory.oldPath));
                        Solr.addFilterQuery(facetName, PathService.prependLevel(renameCategory.newPath));
                        vm.restSuccessAndUpdate(data);

                    },
                    vm.restError
                );
            } else {
                var rename = {
                    oldPath: facetName + PathService.delimiter + renameCategory.oldPath,
                    newPath: facetName + PathService.delimiter + renameCategory.newPath
                };
                CategoryRestService.update(rename).then(
                    function(data) {
                        Solr.rmFilterQuery(facetName, renameCategory.oldPath);
                        Solr.addFilterQuery(facetName, renameCategory.newPath);
                        vm.restSuccessAndUpdate(data);

                    },
                    vm.restError
                );
            }
        }

        function id3TagUpdate(renameCategory, facetName) {
            vm.finished = false;
            var rename = {
              oldPath: facetName + PathService.delimiter + renameCategory.oldPath,
              newPath: facetName + PathService.delimiter + renameCategory.newPath
            };
            Mp3DocumentId3TagRestService.massTag(rename).then(
                function(data) {
                    $rootScope.$emit('apiId3TagUpdate', {facetName: facetName, renameCategory: renameCategory});
                    vm.restSuccess(data);

                },
                vm.restError
            );
        }

        function restSuccessAndUpdate(data) {
            // @deprecated
            vm.restSuccess(data);
            Solr.forceRequest().then(function (response){
                $rootScope.$emit('solrDataUpdate', response.data);
            })


        }

        function restSuccess(data) {
            vm.finished = true;
            $rootScope.$emit('apiRestSuccess');
            FlashMessageService.show(data.data.flashMessages);
        }

        function restError(data) {
            vm.finished = true;
            FlashMessageService.error(data);
        }

    }
})();
