/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicFilterController', MusicFilterController);

    /* @ngInject */
    function MusicFilterController (ngDialog, $rootScope, CONFIG) {

        var vm = this;
        var $scope = $rootScope.$new();

        vm.showRenameCategoryForm = showRenameCategoryForm;

        function showRenameCategoryForm(facetName, facetValue, editType) {

            var data = {
                facetName: facetName,
                facetValue: facetValue,
                editType: editType
            };

            $scope.dialog = ngDialog.open({
                "data" : data,
                "template" : CONFIG.templatePath + 'Music/EditCategory.html',
                "controller" : 'MusicEditCategoryController',
                "controllerAs" : 'musicEditCategory',
                "scope" : $scope
            });
        }

    }
})();
