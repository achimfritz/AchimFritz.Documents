/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3ipod')
        .controller('Mp3IpodStartController', Mp3IpodStartController);

    /* @ngInject */
    function Mp3IpodStartController ($location) {

        var vm = this;

        // used by the view
        vm.addFilter = addFilter;


        function addFilter(name) {
            $location.path('app/mp3ipod/filter/' + name);
            //$location.path('app/mp3ipod/filter);
        }
    }
})();
