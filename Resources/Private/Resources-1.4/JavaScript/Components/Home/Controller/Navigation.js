/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.home')
        .controller('HomeNavigationController', HomeNavigationController);

    /* @ngInject */
    function HomeNavigationController ($location, CONFIG, $rootScope) {

        var vm = this;
        var $scope = $rootScope.$new();
        vm.current = '';
        vm.items = [
            {name: 'home', location: 'index'},
            {name: 'music', location: 'music/result'},
            {name: 'mp3Ipod', location: 'mp3ipod'},
            {name: 'urlbuilder', location: 'urlbuilder'},
            {name: 'document', location: 'document'},
            {name: 'image', location: 'image/result'}
        ];
        vm.forward = forward;

        vm.initController = initController;

        vm.initController();

        function initController() {
            var path = $location.path();
            vm.current = path.replace(CONFIG.baseUrl + '/', '');
        }

        function forward(newLocation) {
            vm.current = newLocation;
            $location.path('app/' + newLocation);
        }
    }
})();
