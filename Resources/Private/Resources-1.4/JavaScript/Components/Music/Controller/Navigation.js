/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicNavigationController', MusicNavigationController);

    /* @ngInject */
    function MusicNavigationController ($location, CONFIG, $rootScope) {

        var vm = this;
        vm.items = [
            {name: 'result', active: true, location: 'result'},
            {name: 'filter', active: false, location: 'filter'},
            {name: 'player', active: false, location: 'player'}
        ];
        vm.forward = forward;

        var path = $location.path();
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
