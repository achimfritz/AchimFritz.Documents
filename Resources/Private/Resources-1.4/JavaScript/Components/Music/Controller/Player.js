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

        vm.playlist = {};

        vm.editPlaylist = editPlaylist;

        function toResult () {
            $timeout(function () {
                $location.path(CONFIG.baseUrl + '/music/result');
                $rootScope.$emit('locationChanged', 'result');
            });
        }

        $timeout(function () {
            vm.song = angularPlayer.currentTrackData();
            vm.playlist = angularPlayer.getPlaylist();
            if (angular.isDefined(vm.playlist) === false || vm.playlist.length === 0) {
                toResult();
            }
        });

        function editPlaylist() {
            $scope.dialog = ngDialog.open({
                "data" : vm.playlist,
                "template" : CONFIG.templatePath + 'Music/EditPlaylist.html',
                "controller" : 'MusicEditPlaylistController',
                "controllerAs" : 'musicEditPlaylist',
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

    }
})();
