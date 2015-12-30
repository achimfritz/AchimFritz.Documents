/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicEditCategoryController', MusicEditCategoryController);

    /* @ngInject */
    function MusicEditCategoryController ($scope, PathService, Solr, $rootScope, ngDialog) {

        var vm = this;
        var data = $scope.ngDialogData;
        var path = '';


        vm.renameCategoryFacet = data.facetName;
        vm.editType = data.editType;

        console.log(data);


        if (Solr.isHFacet(data.facetName) === true) {
            path = PathService.slice(data.facetValue, 1);
        } else {
            path = data.facetValue;
        }

        vm.renameCategory = {
            'oldPath': path,
            'newPath': path
        };
        console.log(vm.renameCategory);

        $rootScope.$on('solrDataUpdate', function (event, data) {
            ngDialog.close($scope.dialog.id);
        });

    }
})();
