/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.home')
        .controller('HomeNavigationController', HomeNavigationController);

    /* @ngInject */
    function HomeNavigationController ($location, CONFIG) {

        var vm = this;
        vm.items = [
            {name: 'home', active: true, location: 'index'},
            {name: 'music', active: false, location: 'music/result'},
            {name: 'urlbuilder', active: false, location: 'urlbuilder'},
            {name: 'document', active: false, location: 'document'},
            {name: 'mp3', active: false, location: 'mp3'},
            {name: 'mp3list', active: false, location: 'mp3list'},
            {name: 'mp3ipod', active: false, location: 'mp3ipod'},
            {name: 'image', active: false, location: 'image'},
            {name: 'imagelist', active: false, location: 'imagelist'}
        ];
        vm.forward = forward;

        var path = $location.path();
        var newLocation = path.replace(CONFIG.baseUrl + '/', '');
        setActive(newLocation);

        function setActive(newLocation) {
             angular.forEach(vm.items, function(item) {
                if (item.location === newLocation) {
                    item.active = true;
                } else {
                    item.active = false;
                }
            });
        }

        function forward(newLocation) {
            setActive(newLocation);
            $location.path('app/' + newLocation);
        }

    }
})();
