/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicPlayerController', MusicPlayerController);

    /* @ngInject */
    function MusicPlayerController ($timeout, angularPlayer, $location, $rootScope, CONFIG, $filter, ngDialog, MusicPlayerService) {

        var vm = this;
        var $scope = $rootScope.$new();

        vm.song = {};
        vm.currentPostion = 0;
        vm.currentDuration = 0;

        vm.playlist = {};

        vm.editPlaylist = editPlaylist;
        vm.editDoc = editDoc;
        vm.onDropComplete = onDropComplete;

        vm.initController = initController;

        vm.initController();

        function initController() {
            MusicPlayerService.initialize();
            getPlayerData();
        }

        /* player */

        function getPlayerData() {
            $timeout(function () {
                vm.song = angularPlayer.currentTrackData();
                vm.playlist = angularPlayer.getPlaylist();
                if (angular.isDefined(vm.playlist) === false || vm.playlist.length === 0) {
                    toResult();
                }
            });
        }

        function toResult () {
            $timeout(function () {
                $location.path(CONFIG.baseUrl + '/music/result');
            });
        }

        function onDropComplete(index, obj, evt) {
            var objIndex = vm.playlist.indexOf(obj);
            var oldList = vm.playlist;
            var soundIds = [];
            var l = oldList.length;
            var newList = [];
            for (var j = 0; j < l; j++) {
                if (j === index) {
                    newList.push(obj);
                    soundIds.push(obj.id);
                }
                if (j !== objIndex) {
                    newList.push(oldList[j]);
                    soundIds.push(oldList[j].id);
                }
            }
            soundManager.soundIDs = soundIds;
            vm.playlist = newList;
        }

        function editPlaylist() {
            $scope.dialog = ngDialog.open({
                "data" : vm.playlist,
                "template" : CONFIG.templatePath + 'Music/EditPlaylist.html',
                "controller" : 'MusicEditPlaylistController',
                "controllerAs" : 'musicEditPlaylist',
                "scope" : $scope
            });
        }

        function editDoc(doc) {
            $scope.dialog = ngDialog.open({
                "data" : doc,
                "template" : CONFIG.templatePath + 'Music/EditDoc.html',
                "controller" : 'MusicEditDocController',
                "controllerAs" : 'musicEditDoc',
                "scope" : $scope
            });
        }

        var positionListener = $scope.$on('currentTrack:position', function(event, data) {
            $scope.$apply(function() {
                vm.currentPostion = $filter('humanTime')(data);
            });
        });

        var durationListener = $scope.$on('currentTrack:duration', function(event, data) {
            $scope.$apply(function() {
                vm.currentDuration = $filter('humanTime')(data);
            });
        })

        var playlistListener = $scope.$on('player:playlist', function(event, playlist){
            $timeout(function () {
                if (playlist.length === 0) {
                    toResult();
                }
            });
            $scope.$apply(function() {
                vm.playlist = playlist;
            });
        });


        var listener = $scope.$on('track:id', function(event, data) {
            vm.song = angularPlayer.currentTrackData();
        });

        var killerListener = $scope.$on('$locationChangeSuccess', function(ev, next, current) {
            listener();
            playlistListener();
            positionListener();
            killerListener();
        });


    }
})();
