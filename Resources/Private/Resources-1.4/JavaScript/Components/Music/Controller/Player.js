/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicPlayerController', MusicPlayerController);

    /* @ngInject */
    function MusicPlayerController ($timeout, angularPlayer, $location, $rootScope, CONFIG, $filter, ngDialog) {

        var vm = this;
        var $scope = $rootScope.$new();

        vm.song = {};
        vm.currentPostion = 0;

        vm.docs = {};
        vm.playlist = {};
        vm.isPlaying = false;

        vm.showInfoDoc = showInfoDoc;

        function toResult () {
            $timeout(function () {
                $location.path(CONFIG.baseUrl + '/music/result');
                $rootScope.$emit('locationChanged', 'result');
            });
        }


        $timeout(function () {
            vm.song = angularPlayer.currentTrackData();
            vm.playlist = angularPlayer.getPlaylist();
            vm.isPlaying = angularPlayer.isPlayingStatus();

            if (angular.isDefined(vm.song) === false) {
                toResult();
            }
        });

        function showInfoDoc(doc) {
            $scope.dialog = ngDialog.open({
                "data" : doc,
                "template" : CONFIG.templatePath + 'Music/InfoDoc.html',
                "controller" : 'MusicInfoDocController',
                "controllerAs" : 'musicInfoDoc',
                "scope" : $scope
            });
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
                    toResult();
                }
            });
            $scope.$apply(function() {
                vm.playlist = playlist;
            });
        });

        $scope.$on('music:isPlaying', function(event, data) {
            $scope.$apply(function() {
                vm.isPlaying = data;
            });
        });


    }
})();
