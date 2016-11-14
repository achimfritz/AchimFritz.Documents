/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.home')
        .controller('HomeFlashMessageController', HomeFlashMessageController);

    /* @ngInject */
    function HomeFlashMessageController (toaster, CoreApplicationScopeFactory) {

        var vm = this;

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
        CoreApplicationScopeFactory.registerListener('HomeFlashMessageController', 'core:apiCallStart', function(ev, data) {
            if (angular.isUndefined(data) || data.noSpinner !== true) {
                vm.view.spinner = true;
            }
        });

        CoreApplicationScopeFactory.registerListener('HomeFlashMessageController', 'home:flashMessage', function (ev, flashMessages){
            showFlashMessages(flashMessages);
        });

        CoreApplicationScopeFactory.registerListener('HomeFlashMessageController', 'core:apiCallSuccess', function(ev, data) {
            showFlashMessages(data.data.flashMessages);
            showValidationErrors(data.data.validationErrors);
            vm.view.spinner = false;
        });

        CoreApplicationScopeFactory.registerListener('HomeFlashMessageController', 'core:apiCallError', function(ev, data) {
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

    }
})();
