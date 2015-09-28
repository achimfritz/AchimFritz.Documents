/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageListController', ImageListController);

    /* @ngInject */
    function ImageListController (FlashMessageService, DocumentListRestService) {

        var vm = this;
        vm.finished = true;
        vm.view = 'list';
        vm.big = false;
        vm.documentLists = [];

        // used by the view
        vm.list = list;
        vm.show = show;
        vm.removeList = removeList;
        vm.onDropComplete = onDropComplete;
        vm.showBig = showBig;
        vm.saveList = saveList;

        // not used by the view
        vm.initController = initController;
        vm.restSuccess = restSuccess;
        vm.restError = restError;

        vm.initController();

        function initController() {
            vm.list();
        }

        function showBig(showBig) {
            vm.big = showBig;
        }

        function onDropComplete (index, obj, evt) {
            var objIndex = vm.documentList.documentListItems.indexOf(obj);
            var oldList = vm.documentList.documentListItems;
            var l = oldList.length;
            var newList = [];
            for (var j = 0; j < l; j++) {
                if (j === index) {
                    newList.push(obj);
                }
                if (j !== objIndex) {
                    newList.push(oldList[j]);
                }
            }
            vm.documentList.documentListItems = newList;
        }

        function saveList() {
            vm.finished = false;
            DocumentListRestService.update(vm.documentList).then(
                vm.restSuccess,
                vm.restError
            );

        }

        function removeList(identifier) {
            vm.finished = false;
            DocumentListRestService.delete(identifier).then(
                function (data) {
                    vm.finished = true;
                    FlashMessageService.show(data.data.flashMessages);
                    vm.list();
                },
                vm.restError
            );
        }

        function list() {
            vm.finished = false;
            DocumentListRestService.list().then(
                function (data) {
                    vm.finished = true;
                    vm.documentLists = data.data.documentLists;
                    vm.view = 'list';
                },
                vm.restError
            );
        }

        function show(identifier) {
            vm.finished = false;
            DocumentListRestService.show(identifier).then(
                function (data) {
                    vm.finished = true;
                    vm.documentList = data.data.documentList;
                    vm.view = 'show';
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
