/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicNavigationController', MusicNavigationController);

    /* @ngInject */
    function MusicNavigationController ($location, CONFIG, $rootScope, $timeout, Solr, Mp3PlayerService, angularPlayer, $filter) {

        var vm = this;
        var $scope = $rootScope.$new();

        /* navigation */
        vm.items = [
            {name: 'home', active: false, location: 'index'},
            {name: 'result', active: true, location: 'music/result'},
            {name: 'playlists', active: false, location: 'music/list'},
            {name: 'filter', active: false, location: 'music/filter'},
            {name: 'player', active: false, location: 'music/player'}
        ];
        vm.forward = forward;

        /* solr */
        vm.search = '';
        vm.params = {};
        vm.filterQueries = {};
        vm.update = update;
        vm.clearSearch = clearSearch;
        vm.rmFilterQuery = rmFilterQuery;

        /* player */
        vm.song = {};
        vm.playlist = {};
        vm.currentPosition = 0;

        vm.initController = initController;

        vm.initController();

        function initController() {
            getSolrData();
            Mp3PlayerService.initialize();

            $timeout(function () {
                vm.song = angularPlayer.currentTrackData();
                vm.playlist = angularPlayer.getPlaylist();
            });

            var path = $location.path();
            if (path === CONFIG.baseUrl + '/music' || path === CONFIG.baseUrl + '/music/') {
                $timeout(function () {
                    forward('music/result');
                });
            }
            var newLocation = path.replace(CONFIG.baseUrl + '/', '');
            setActive(newLocation);
        }

        /* navigation */
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

        /* solr */

        function rmFilterQuery(name, value) {
            Solr.rmFilterQueryAndUpdate(name, value);
        }

        function clearSearch() {
            Solr.clearSearchAndUpdate();
            vm.search = '';
        }

        function update() {
            Solr.update();
        }

        function getSolrData() {
            var data = Solr.getData();
            if (angular.isDefined(data.response) === true) {
                vm.filterQueries = Solr.getFilterQueries();
                vm.params = Solr.getParams();
            }
        }

        var listener = $scope.$on('solrDataUpdate', function(event, data) {
            getSolrData();
        });

        var musicListener = $scope.$on('currentTrack:position', function(event, data) {
            $scope.$apply(function() {
                vm.currentPosition = $filter('humanTime')(data);
            });
        });

        var locationListener = $scope.$on('music:locationChanged', function (event, data) {
            //console.log('music locationChanged');
            setActive(data);
        });

        var musicTrackListener = $scope.$on('track:id', function(event, data) {
            vm.song = angularPlayer.currentTrackData();
        });

        var musicPlaylistListener = $scope.$on('player:playlist', function(event, playlist){
            $scope.$apply(function() {
                vm.playlist = playlist;
            });
        });

        /*
        var s = $scope.$on('$locationChangeStart', function(ev, next, current) {
            console.log('locationChangeSucess');
        });
        */

        var killerListener = $scope.$on('$locationChangeSuccess', function(ev, next, current) {
            //console.log('locationChangeStart');
            listener();
            //musicListener();
            //musicPlaylistListener();
            //musicTrackListener();
            //locationListener();
            killerListener();
        });

    }
})();
