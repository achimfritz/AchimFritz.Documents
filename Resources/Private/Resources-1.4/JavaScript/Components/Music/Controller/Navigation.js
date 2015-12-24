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
            {name: 'result', active: true, location: 'result'},
            {name: 'playlists', active: false, location: 'list'},
            {name: 'filter', active: false, location: 'filter'},
            {name: 'player', active: false, location: 'player'}
        ];
        vm.forward = forward;

        var path = $location.path();
        if (path === CONFIG.baseUrl + '/music' || path === CONFIG.baseUrl + '/music/') {
            $timeout(function () {
                forward('result');
            });
        }
        var newLocation = path.replace(CONFIG.baseUrl + '/music/', '');
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
            $location.path('app/music/' + newLocation);
        }

        $rootScope.$on('locationChanged', function (event, data) {
            setActive(data);
        });

    }
})();
