/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3ipod')
        .controller('Mp3IpodResultController', Mp3IpodResultController);

    /* @ngInject */
    function Mp3IpodResultController (Solr, $location, $routeParams, SolrConfiguration, angularPlayer, $timeout, Mp3PlayerService, $rootScope) {

        var vm = this;
        var $scope = $rootScope.$new();
        vm.docs = {};
        vm.filter = '';
        vm.subFilter = '';
        vm.filterValue = '';
        vm.subFilterValue = '';

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
            vm.filter = $routeParams.filter;
            vm.subFilter = $routeParams.subFilter;
            vm.filterValue = $routeParams.filterValue;
            vm.subFilterValue = $routeParams.subFilterValue;
            SolrConfiguration.setParam('facet_limit', 0);

            SolrConfiguration.setFacets([]);
            SolrConfiguration.setHFacets({});
            SolrConfiguration.setSetting('servlet', 'mp3');
            Solr.init();

            Solr.resetFilterQueries();
            Solr.setParam('rows', 2);
            Solr.addFilterQuery(vm.filter, vm.filterValue);
            if (vm.subFilter !== 'all') {
                Solr.addFilterQuery(vm.subFilter, vm.subFilterValue);
            }

            Mp3PlayerService.initialize();

            Solr.forceRequest().then(function (response) {
                vm.docs = response.data.response.docs;
            });
            vm.playlist = angularPlayer.getPlaylist(); //on load
            vm.isPlaying = angularPlayer.isPlayingStatus();
        }

        function back() {
            $scope.$destroy();
            if (vm.filter === 'album') {
                $location.path('app/mp3ipod/filter/album');
            } else {
                $location.path('app/mp3ipod/subfilter/' + vm.filter + '/' + vm.filterValue + '/' + vm.subFilter);
            }
        }

        function playAll() {
            Mp3PlayerService.playAll(vm.docs);
            vm.toCurrentPlaying();
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

    }
})();
