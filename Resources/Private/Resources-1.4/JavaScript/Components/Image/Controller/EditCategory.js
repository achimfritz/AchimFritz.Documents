/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageEditCategoryController', ImageEditCategoryController);

    /* @ngInject */
    function ImageEditCategoryController ($scope, CoreApiService, PathService, Solr, $rootScope, ngDialog) {

        var vm = this;
        var $listenerScope = $rootScope.$new();
        var path = $scope.ngDialogData.value;

        vm.name = $scope.ngDialogData.name;
        vm.autoCompleteField = $scope.ngDialogData.name;
        vm.value = path;
        vm.newValue = path;

        vm.update = update;

        if (Solr.isHFacet(vm.name) === true) {
            vm.value = PathService.slice(path, 1);
            vm.newValue = PathService.slice(path, 1);
        }
        if (vm.name === 'hPaths') {
            // we can also generalize by replace the first 'h'
            vm.autocompleteField = 'paths';
        }

        var listener = $listenerScope.$on('core:apiCallSuccess', function(event, data) {
            var newValue = vm.newValue;
            if (Solr.isHFacet(vm.name) === true) {
                newValue = PathService.prependLevel(vm.newValue);
            }
            if (Solr.hasFilterQuery(vm.name, path)) {
                Solr.rmFilterQuery(vm.name, path);
                Solr.addFilterQuery(vm.name, newValue);
            }
            Solr.update();
            ngDialog.close($scope.dialog.id);
            listener();
        });

        function update() {
            var renameCategory = {};
            if (Solr.isHFacet(vm.name) === true) {
                renameCategory = {
                    oldPath: vm.value,
                    newPath: vm.newValue
                };
            } else {
                renameCategory = {
                    oldPath: vm.name + PathService.delimiter + vm.value,
                    newPath: vm.name + PathService.delimiter + vm.newValue
                };
            }
            CoreApiService.categoryUpdate(renameCategory);
        }

    }
})();
