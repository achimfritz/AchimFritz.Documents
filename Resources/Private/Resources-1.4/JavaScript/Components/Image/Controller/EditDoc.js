/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageEditDocController', ImageEditDocController);

    /* @ngInject */
    function ImageEditDocController ($scope, CoreApiService) {

        var vm = this;

        vm.doc = $scope.ngDialogData;
        vm.category = '';

        vm.categoryMergeOne = categoryMergeOne;

        function categoryMergeOne() {
            CoreApiService.listMergeOne(vm.category, vm.doc);
        }

    }
})();
