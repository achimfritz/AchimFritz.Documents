/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.home')
        .controller('HomeDefaultController', HomeDefaultController);

    /* @ngInject */
    function HomeDefaultController ($location) {

        var vm = this;

        vm.forward = forward;

        function forward(newLocation) {
            $location.path('app/' + newLocation);
        }

    }
})();
