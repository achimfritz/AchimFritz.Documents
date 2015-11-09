/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.filter')
        .controller('FilterController', FilterController);

    /* @ngInject */
    function FilterController (FilterConfiguration, $rootScope, PathService) {

        var vm = this;
        vm.filters = {};
        vm.configuration = {};
        vm.renameCategory = false;
        vm.renameCategoryFacet = '';
        vm.editType = '';

        // used by the view
        vm.toggleFilter = toggleFilter;
        vm.showRenameCategoryForm = showRenameCategoryForm;
        vm.isEditable = isEditable;

        vm.initController = initController;

        vm.initController();

        function initController() {
            vm.filters = FilterConfiguration.getFilters();
            vm.configuration = FilterConfiguration.getConfiguration();
        }

        function isEditable(editType, key) {
            if (angular.isDefined(vm.configuration[editType])) {
                var config = vm.configuration[editType];
                if (config.indexOf(key) >= 0) {
                    return true;
                }
            }
            return false;
        }

        function toggleFilter(name) {
            if (vm.filters[name] === false) {
                vm.filters[name] = true;
            } else {
                vm.filters[name] = false;
            }
        }

        function showRenameCategoryForm(facetName, facetValue, editType) {
            vm.renameCategoryFacet = facetName;
            vm.editType = editType;
            var path = '';
            if (PathService.depth(facetValue) === 1) {
                path = facetValue;
            } else {
                path = PathService.slice(facetValue, 1);
            }
            vm.renameCategory = {
                'oldPath': path,
                'newPath': path
            };
        }

        $rootScope.$on('solrDataUpdate', function (event, data) {
            vm.renameCategory = null;
            vm.renameCategoryFacet = '';
        })

    }
})();
