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
            {name: 'urlbuilder', location: 'urlbuilder'},
            {name: 'document', location: 'document'},
            {name: 'mp3', location: 'mp3'},
            {name: 'mp3list', location: 'mp3list'},
            {name: 'mp3ipod', location: 'mp3ipod'},
            {name: 'image', location: 'image'},
            {name: 'imagelist', location: 'imagelist'}
        ];
        vm.forward = forward;

        vm.initController = initController;

        vm.initController();

        function initController() {
            var path = $location.path();
            vm.current = path.replace(CONFIG.baseUrl + '/', '');
        }

        function forward(newLocation) {
            $location.path('app/' + newLocation);
        }

        function resolveRelativePath(path) {
            var res = path.split('/');
            res.shift();
            res.shift();
            res.shift();
            res.shift();
            return res.join('/');
        }


        /* listener */

        var listener = $scope.$on('$locationChangeSuccess', function(ev, next, current) {
            vm.current = resolveRelativePath(next);
            listener();
        });

    }
})();
