/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageModalController', ImageModalController);

    /* @ngInject */
    function ImageModalController ($rootScope, FlashMessageService, ListService, $scope, hotkeys, DocumentCollectionRestService) {

        var vm = this;
        vm.doc = $scope.ngDialogData;

        // used by the view
        vm.itemNext = itemNext;
        vm.itemPrev = itemPrev;
        vm.newTag = '';

        // not used by the view
        vm.initController = initController;
        vm.restSuccess = restSuccess;
        vm.restError = restError;
        vm.addTag = addTag;

        vm.initController();

        function initController() {
            hotkeys.bindTo($scope).add({
                combo: 'n',
                description: 'next',
                callback: function () {
                    vm.itemNext();
                }
            }).add({
                combo: 'b',
                callback: function () {
                    vm.itemPrev();
                }
            });
        }

        function addTag() {
            DocumentCollectionRestService.merge('tags/' + vm.newTag, [vm.doc]).then(vm.restSuccess, vm.restError);
        }

        function itemNext() {
            vm.doc = ListService.getNext(vm.doc);
        }

        function itemPrev() {
            vm.doc = ListService.getPrev(vm.doc);
        }


        function restSuccess(data) {
            //vm.finished = true;
            FlashMessageService.show(data.data.flashMessages);
            Solr.forceRequest().then(function (response) {
                $rootScope.$emit('solrDataUpdate', response.data);
            })
        }

        function restError(data) {
            //vm.finished = true;
            FlashMessageService.error(data);
        }

    }
})();
