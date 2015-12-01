/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3ipod')
        .controller('Mp3IpodListController', Mp3IpodListController);

    /* @ngInject */
    function Mp3IpodListController ($location, $routeParams, Mp3IpodSolrService) {

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
