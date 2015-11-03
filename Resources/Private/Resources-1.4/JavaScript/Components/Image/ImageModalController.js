/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageModalController', ImageModalController);

    /* @ngInject */
    function ImageModalController (ListService, $scope, hotkeys) {

        var vm = this;
        vm.doc = $scope.ngDialogData;

        // used by the view
        vm.itemNext = itemNext;
        vm.itemPrev = itemPrev;
        vm.path = '';

        // not used by the view
        vm.initController = initController;

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

        function itemNext() {
            vm.doc = ListService.getNext(vm.doc);
        }

        function itemPrev() {
            vm.doc = ListService.getPrev(vm.doc);
        }

    }
})();
