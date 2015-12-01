/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3ipod')
        .controller('Mp3IpodSearchController', Mp3IpodSearchController);

    /* @ngInject */
    function Mp3IpodSearchController ($location, $routeParams, Mp3IpodSolrService) {

        var vm = this;
        vm.result = {};

        // used by the view
        vm.back = back;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {

        }

        function back() {
            $location.path('app/mp3ipod');
        }

    }
})();
