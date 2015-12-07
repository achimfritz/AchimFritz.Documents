/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3ipod')
        .controller('Mp3IpodSearchController', Mp3IpodSearchController);

    /* @ngInject */
    function Mp3IpodSearchController ($location, $routeParams) {

        var vm = this;
        vm.result = {};
        vm.search = '';

        // used by the view
        vm.back = back;
        vm.update = update;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
            vm.search = $routeParams.search;
            if (vm.search === 'all') {
                vm.search = '';
            }
        }

        function update() {
            var search = 'all';
            if (vm.search !== undefined) {
                if (vm.search !== '') {
                    search = vm.search;
                }
            }
            $location.path('app/mp3ipod/result/all/all/all/all/' + search);
        }


        function back() {
            $location.path('app/mp3ipod');
        }

    }
})();
