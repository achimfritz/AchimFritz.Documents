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

        vm.spinner = false;

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

        /* listener */

        var apiCallStartListener = $scope.$on('core:apiCallStart', function() {
            vm.spinner = true;
        });

        var apiCallSuccessListener = $scope.$on('core:apiCallSuccess', function(ev, data) {
            showFlashMessages(data.data.flashMessages);
            vm.spinner = false;
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
            vm.spinner = false;
        });

        var listener = $scope.$on('$locationChangeSuccess', function(ev, next, current) {
            apiCallStartListener();
            apiCallErrorListener();
            apiCallSuccessListener();
            listener();
        });

    }
})();
