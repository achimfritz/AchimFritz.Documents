/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ClipboardController', ClipboardController);

    /* @ngInject */
    function ClipboardController ($rootScope, FlashMessageService, ExportRestService, DocumentListRestService, DocumentCollectionRestService) {

        var vm = this;
        vm.docs = [];
        vm.form = 'category';
        vm.category = '';
        vm.zip = {};
        vm.pdf = {};
        vm.finished = true;


        // used by the view
        vm.showForm = showForm;
        vm.pdfDownload = pdfDownload;
        vm.zipDownload = zipDownload;
        vm.listMerge = listMerge;
        vm.listRemove = listRemove;
        vm.categoryRemove = categoryRemove;
        vm.categoryMerge = categoryMerge;

        // not used by the view
        vm.initController = initController;
        vm.restSuccess = restSuccess;
        vm.restError = restError;

        vm.initController();

        function initController() {
            vm.pdf = ExportRestService.pdf();
            vm.zip = ExportRestService.zip();
        }

        function categoryMerge() {
            vm.finished = false;
            DocumentCollectionRestService.merge(vm.category, vm.docs).then(vm.restSuccess, vm.restError);
        }

        function categoryRemove() {
            vm.finished = false;
            DocumentCollectionRestService.remove(vm.category, vm.docs).then(vm.restSuccess, vm.restError);
        }

        function listRemove() {
            vm.finished = false;
            DocumentListRestService.remove(vm.category, vm.docs).then(vm.restSuccess, vm.restError);
        }

        function listMerge() {
            vm.finished = false;
            DocumentListRestService.merge(vm.category, vm.docs).then(vm.restSuccess, vm.restError);
        }

        function zipDownload() {
            vm.finished = false;
            ExportRestService.zipDownload(vm.zip, vm.docs).then(
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

        function pdfDownload() {
            vm.finished = false;
            ExportRestService.pdfDownload(vm.pdf, vm.docs).then(
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

        function showForm(form) {
            vm.form = form;
        }

        function restSuccess(data) {
            vm.finished = true;
            FlashMessageService.show(data.data.flashMessages);
        }

        function restError(data) {
            vm.finished = true;
            FlashMessageService.error(data);
        }

        $rootScope.$on('docsUpdate', function (event, docs) {
            vm.docs = [];
            angular.forEach(docs, function (val, key) {
                if (val.selected === 'selected') {
                   vm.docs.push(val);
                }
            });
            if (vm.docs.length > 0) {
                $rootScope.$emit('openWidget', 'clipboard');
            }
        })

    }
})();
