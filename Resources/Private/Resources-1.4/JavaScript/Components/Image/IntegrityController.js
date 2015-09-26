/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('IntegrityController', IntegrityController);

    /* @ngInject */
    function IntegrityController (IntegrityRestService, FlashMessageService, $rootScope) {

        var vm = this;

        vm.view = 'list';
        vm.finished = true;

        vm.list = list;
        vm.show = show;

        // not used by the view
        vm.initController = initController;
        vm.restError = restError;

        vm.initController();

        function initController() {
            vm.list();
        }

        function list() {
            vm.finished = false;
            IntegrityRestService.list().then(
                function (data) {
                    vm.finished = true;
                    vm.integrities = data.data.integrities;
                    vm.view = 'list';
                },
                vm.restError
            );
        }

        function show (directory) {
            vm.finished = false;
            IntegrityRestService.show(directory).then(
                function (data) {
                    vm.finished = true;
                    vm.integrity = data.data.integrity;
                    vm.view = 'show';
                },
                vm.restError
            );
        }

        function restError(data) {
            vm.finished = true;
            FlashMessageService.error(data);
        }

        $rootScope.$on('jobFinished', function (event, directory) {
            vm.show(directory);
        });

    }
})();
