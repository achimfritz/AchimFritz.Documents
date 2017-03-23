/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicNavigationController', MusicNavigationController);

    /* @ngInject */
    function MusicNavigationController ($location, CONFIG, $rootScope, $timeout, Solr, CoreApplicationScopeFactory) {

        var vm = this;
        var $scope = $rootScope.$new();

        /* navigation */
        vm.current = {location: ''};
        vm.items = [
            {name: 'home', location: 'index'},
            {name: 'result', location: 'music/result'},
            {name: 'playlists', location: 'music/list'},
            {name: 'filter', location: 'music/filter'},
            {name: 'player', location: 'music/player'},
            {name: 'docList', location: 'music/docList'}
        ];
        vm.forward = forward;

        /* solr */
        vm.search = '';
        vm.params = {};
        vm.filterQueries = {};
        vm.solr = Solr;
        vm.update = update;
        vm.clearSearch = clearSearch;

        vm.initController = initController;

        vm.initController();

        function initController() {
            getSolrData();

            var path = $location.path();
            if (path === CONFIG.baseUrl + '/music' || path === CONFIG.baseUrl + '/music/') {
                $timeout(function () {
                    forward('music/result');
                });
            }
            vm.current.location = path.replace(CONFIG.baseUrl + '/', '');
        }

        function forward(newLocation) {
            vm.current.location = newLocation;
            $location.path('app/' + newLocation);
        }

        /* solr */

        function clearSearch() {
            Solr.clearSearchAndUpdate();
            vm.search = '';
        }

        function update() {
            Solr.setSearchAndUpdate(vm.search);
        }

        function getSolrData() {
            var data = Solr.getData();
            if (angular.isDefined(data.response) === true) {
                vm.filterQueries = Solr.getFilterQueries();
                vm.params = Solr.getParams();
            }
        }


        /* listener */

        var listener = $scope.$on('solrDataUpdate', function(event, data) {
            getSolrData();
        });


        var killerListener = $scope.$on('$locationChangeStart', function(ev, next, current) {
            var path = next.split('/app/')
            vm.current.location = path[1];
            listener();
            killerListener();
        });


    }
})();
