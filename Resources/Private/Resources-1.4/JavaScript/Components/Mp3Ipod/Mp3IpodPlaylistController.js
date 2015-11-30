/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3ipod')
        .controller('Mp3IpodPlaylistController', Mp3IpodPlaylistController);

    /* @ngInject */
    function Mp3IpodPlaylistController ($location, $routeParams, $rootScope, angularPlayer, $timeout) {

        var vm = this;
        var $scope = $rootScope.$new();
        vm.docs = {};
        vm.filter = '';
        vm.subFilter = '';
        vm.filterValue = '';
        vm.subFilterValue = '';

        vm.playlist = {};
        vm.isPlaying = false;
        vm.currentPlaying = {};

        // used by the view
        vm.back = back;
        vm.toCurrentPlaying = toCurrentPlaying;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
            vm.filter = $routeParams.filter;
            vm.subFilter = $routeParams.subFilter;
            vm.filterValue = $routeParams.filterValue;
            vm.subFilterValue = $routeParams.subFilterValue;

            $timeout(function () {
                vm.playlist = angularPlayer.getPlaylist(); //on load
                if (vm.playlist.length === 0) {
                    vm.back();
                }
            });
            vm.isPlaying = angularPlayer.isPlayingStatus();
            vm.currentPlaying = angularPlayer.currentTrackData();

        }

        function toCurrentPlaying() {
            var oldPath = $location.path();
            var path = oldPath.replace('/playlist/', '/currentPlaying/');
            $scope.$destroy();
            $location.path(path);
        }

        function back() {
            var oldPath = $location.path();
            var path = oldPath.replace('/playlist/', '/result/');
            $scope.$destroy();
            $location.path(path);
        }

        $scope.$on('music:isPlaying', function(event, data) {
            $scope.$apply(function() {
                vm.isPlaying = data;
            });
        });

        $scope.$on('track:id', function(event, data) {
            vm.currentPlaying = angularPlayer.currentTrackData();
        });

        $scope.$on('player:playlist', function(event, data) {
            $timeout(function () {
                if (vm.playlist.length === 0) {
                    vm.back();
                }
            });
            $scope.$apply(function() {
                vm.playlist = data;
            });

        });

    }
})();
