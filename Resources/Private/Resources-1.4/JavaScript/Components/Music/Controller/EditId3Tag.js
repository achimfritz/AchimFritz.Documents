/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicEditId3TagController', MusicEditId3TagController);

    /* @ngInject */
    function MusicEditId3TagController ($scope, CoreApiService, Solr, ngDialog, $rootScope, PathService) {

        var vm = this;
        var $listenerScope = $rootScope.$new();

        vm.name = $scope.ngDialogData.name;
        vm.value = $scope.ngDialogData.value;
        vm.newValue = $scope.ngDialogData.value;

        vm.update = update;

        var listener = $listenerScope.$on('core:apiCallSuccess', function(event, data) {
            if (Solr.hasFilterQuery(vm.name)) {
                Solr.rmFilterQuery(vm.name, vm.value);
                Solr.addFilterQuery(vm.name, vm.newValue);
            }
            Solr.update();
            ngDialog.close($scope.dialog.id);
            listener();
        });

        function update() {
            var renameCategory = {
                oldPath: vm.name + PathService.delimiter + vm.value,
                newPath: vm.name + PathService.delimiter + vm.newValue
            };
            CoreApiService.id3TagUpdate(renameCategory);
        }
    }
})();
