/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicEditCategoryController', MusicEditCategoryController);

    /* @ngInject */
    function MusicEditCategoryController ($scope, PathService) {

        var vm = this;
        var data = $scope.ngDialogData;
        var path = '';


        vm.renameCategoryFacet = data.facetName;
        vm.editType = data.editType;

        console.log(data);

        if (PathService.depth(data.facetValue) === 1) {
            path = data.facetValue;
        } else {
            path = PathService.slice(data.facetValue, 1);
        }

        vm.renameCategory = {
            'oldPath': path,
            'newPath': path
        };



    }
})();
