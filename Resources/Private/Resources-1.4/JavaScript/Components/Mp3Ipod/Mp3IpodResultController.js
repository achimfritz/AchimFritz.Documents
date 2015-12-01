/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3ipod')
        .controller('Mp3IpodResultController', Mp3IpodResultController);

    /* @ngInject */
    function Mp3IpodResultController ($location, $routeParams, Mp3IpodSolrService, angularPlayer, $timeout, Mp3PlayerService, $rootScope) {

        var vm = this;
        var $scope = $rootScope.$new();
        vm.docs = {};
        vm.genre = '';
        vm.artist = '';
        vm.album = '';

        // used by the view
        vm.back = back;
        vm.playAll = playAll;
        vm.toPlaylist = toPlaylist;
        vm.toCurrentPlaying = toCurrentPlaying;
        vm.addAll = addAll;
        vm.addOne = addOne;
        vm.playOne = playOne;

        vm.playlist = {};
        vm.isPlaying = false;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
            vm.genre = $routeParams.genre;
            vm.artist = $routeParams.artist;
            vm.album = $routeParams.album;
            Mp3PlayerService.initialize();
            Mp3IpodSolrService.initialize();
            Mp3IpodSolrService.request(vm.genre, vm.artist, vm.album).then(function (response) {
                vm.docs = response.data.response.docs;
            });

            vm.playlist = angularPlayer.getPlaylist(); //on load
            vm.isPlaying = angularPlayer.isPlayingStatus();
        }

        function back() {
            $scope.$destroy();
            $location.path('app/mp3ipod/album/' + vm.genre + '/' + vm.artist);
        }

        function playAll() {
            Mp3PlayerService.playAll(vm.docs);
            vm.toCurrentPlaying();
        }

        function addAll() {
            Mp3PlayerService.addAll(vm.docs);
        }

        function addOne(doc) {
            Mp3PlayerService.addOne(doc);
        }

        function playOne(doc) {
            Mp3PlayerService.playOne(doc);
            vm.toCurrentPlaying();
        }

        function toCurrentPlaying() {
            var oldPath = $location.path();
            var path = oldPath.replace('/result/', '/currentPlaying/');
            $scope.$destroy();
            $timeout(function () {
                $location.path(path);
            });
        }

        function toPlaylist() {
            var oldPath = $location.path();
            var path = oldPath.replace('/result/', '/playlist/');
            $scope.$destroy();
            $location.path(path);
        }

        $scope.$on('music:isPlaying', function(event, data) {
            $scope.$apply(function() {
                vm.isPlaying = data;
            });
        });

        $scope.$on('player:playlist', function(event, data) {
            $scope.$apply(function() {
                vm.playlist = data;
            });
        });


    }
})();
