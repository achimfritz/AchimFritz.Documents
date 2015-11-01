/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.core')
        .controller('ApiController', ApiController);

    /* @ngInject */
    function ApiController(FlashMessageService, ExportRestService, DocumentListRestService, DocumentCollectionRestService, CategoryRestService, $rootScope, PathService, Solr) {

        var vm = this;

        vm.finished = true;

        // used by the view
        vm.pdfDownload = pdfDownload;
        vm.zipDownload = zipDownload;
        vm.listMerge = listMerge;
        vm.listRemove = listRemove;
        vm.categoryRemove = categoryRemove;
        vm.categoryMerge = categoryMerge;
        vm.categoryUpdate = categoryUpdate;

        // not used by the view
        vm.initController = initController;
        vm.restSuccess = restSuccess;
        vm.restError = restError;

        vm.initController();

        function initController() {

        }

        function categoryMerge(category, docs) {
            vm.finished = false;
            DocumentCollectionRestService.merge(category, docs).then(vm.restSuccess, vm.restError);
        }

        function categoryRemove(category, docs) {
            vm.finished = false;
            DocumentCollectionRestService.remove(category, docs).then(vm.restSuccess, vm.restError);
        }

        function listRemove(category, docs) {
            vm.finished = false;
            DocumentListRestService.remove(category, docs).then(vm.restSuccess, vm.restError);
        }

        function listMerge(category, docs) {
            vm.finished = false;
            DocumentListRestService.merge(category, docs).then(vm.restSuccess, vm.restError);
        }

        function zipDownload(zip, docs) {
            console.log('dsfds');
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
            CategoryRestService.update(renameCategory).then(
                function(data) {
                    vm.restSuccess(data);
                    Solr.rmFilterQuery(facetName, PathService.prependLevel(renameCategory.oldPath));
                    Solr.addFilterQuery(facetName, PathService.prependLevel(renameCategory.newPath));
                    Solr.forceRequest().then(function (response){
                        $rootScope.$emit('solrDataUpdate', response.data);
                    })
                },
                vm.restError
            );
        }

        function restSuccess(data) {
            vm.finished = true;
            FlashMessageService.show(data.data.flashMessages);
        }

        function restError(data) {
            vm.finished = true;
            FlashMessageService.error(data);
        }

    }
})();
