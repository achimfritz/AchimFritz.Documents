/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicNavigationController', MusicNavigationController);

    /* @ngInject */
    function MusicNavigationController ($location, CONFIG, $rootScope, $timeout) {

        var vm = this;
        vm.items = [
            {name: 'home', active: false, location: 'index'},
            {name: 'result', active: true, location: 'music/result'},
            {name: 'playlists', active: false, location: 'music/list'},
            {name: 'filter', active: false, location: 'music/filter'},
            {name: 'player', active: false, location: 'music/player'}
        ];
        vm.forward = forward;

        var path = $location.path();
        if (path === CONFIG.baseUrl + '/music' || path === CONFIG.baseUrl + '/music/') {
            $timeout(function () {
                forward('music/result');
            });
        }
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

        $rootScope.$on('music:locationChanged', function (event, data) {
            setActive(data);
        });

    }
})();
