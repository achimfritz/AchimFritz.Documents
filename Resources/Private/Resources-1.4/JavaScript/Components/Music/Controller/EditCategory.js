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

        if (vm.editType === 'id3Tag') {
            path = PathService.slice(data.facetValue, 1);
        } else {
            path = data.facetValue;
        }

        vm.renameCategory = {
            'oldPath': path,
            'newPath': path
        };



    }
})();
