/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3ipod')
        .controller('Mp3IpodCurrentPlayingController', Mp3IpodCurrentPlayingController);

    /* @ngInject */
    function Mp3IpodCurrentPlayingController ($location, $routeParams, $rootScope, $timeout, angularPlayer, $filter) {

        var vm = this;
        var $scope = $rootScope.$new();
        vm.song = {};
        vm.currentPostion = '';

        vm.filter = '';
        vm.subFilter = '';
        vm.filterValue = '';
        vm.subFilterValue = '';
        vm.toPlaylist = toPlaylist;


        // used by the view
        vm.back = back;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
            vm.filter = $routeParams.filter;
            vm.subFilter = $routeParams.subFilter;
            vm.filterValue = $routeParams.filterValue;
            vm.subFilterValue = $routeParams.subFilterValue;
            $timeout(function () {
                vm.song = angularPlayer.currentTrackData();

                if (angular.isDefined(vm.song) === false) {
                    vm.back();
                }
            });
        }

        function toPlaylist() {
            var oldPath = $location.path();
            var path = oldPath.replace('/currentPlaying/', '/playlist/');
            $scope.$destroy();
            $location.path(path);
        }

        function back() {
            var oldPath = $location.path();
            var path = oldPath.replace('/currentPlaying/', '/result/');
            $scope.$destroy();
            $location.path(path);
        }

        $scope.$on('track:id', function(event, data) {
            vm.song = angularPlayer.currentTrackData();
        });

        $scope.$on('currentTrack:position', function(event, data) {
            $scope.$apply(function() {
                vm.currentPostion = $filter('humanTime')(data);
            });
        });

        $scope.$on('player:playlist', function(event, playlist){
            $timeout(function () {
                if (playlist.length === 0) {
                    vm.back();
                }
            });
        });

    }
})();
