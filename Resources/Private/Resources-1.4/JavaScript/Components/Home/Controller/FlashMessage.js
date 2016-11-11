/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.home')
        .controller('HomeFlashMessageController', HomeFlashMessageController);

    /* @ngInject */
    function HomeFlashMessageController ($rootScope, toaster) {

        var vm = this;
        var $scope = $rootScope.$new();

        vm.view = {
            spinner: false
         };

        function showFlashMessages(flashMessages) {
            angular.forEach(flashMessages, function (flashMessage) {
                var severity = 'error';
                var title = flashMessage.severity;
                if (flashMessage.title !== '') {
                    title = flashMessage.title;
                }
                if (flashMessage.severity === 'OK') {
                    severity = 'success';
                }
                toaster.pop(severity, title, flashMessage.message);
            });
        }

        function showValidationErrors(validationErrors) {
            angular.forEach(validationErrors, function (validationError) {
                angular.forEach(validationError.errors, function (error) {
                    showFlashMessages([{severity: 'error', message: error.message + ' - ' + error.code}]);
                });
            });
        }

        /* listener */
        var apiCallStartListener = $scope.$on('core:apiCallStart', function(ev, data) {
            if (angular.isUndefined(data) || data.noSpinner !== true) {
                vm.view.spinner = true;
            }

        });

        var flashMessageListener = $scope.$on('home:flashMessage', function (ev, flashMessages){
            showFlashMessages(flashMessages);
        });

        var apiCallSuccessListener = $scope.$on('core:apiCallSuccess', function(ev, data) {
            showFlashMessages(data.data.flashMessages);
            showValidationErrors(data.data.validationErrors);
            vm.view.spinner = false;
        });

        var apiCallErrorListener = $scope.$on('core:apiCallError', function(ev, data) {
            if (angular.isDefined(data) && angular.isDefined(data.data) && angular.isDefined(data.data.flashMessages)) {
                showFlashMessages(data.data.flashMessages);
            } else {
                var severity = 'error';
                if (typeof data.data === 'string') {
                    toaster.pop(severity, data.status, data.data);
                } else {
                    toaster.pop(severity, data.status, data.statusText);
                }
            }
            vm.view.spinner = false;
            if (angular.isDefined(data) && angular.isDefined(data.data) && angular.isDefined(data.data.validationErrors)) {
                showValidationErrors(data.data.validationErrors);
            }
        });

        var listener = $scope.$on('$locationChangeSuccess', function(ev, next, current) {
            //apiCallStartListener();
            //apiCallErrorListener();
            //apiCallSuccessListener();
            //listener();
            //console.log('xxx');
        });

    }
})();
