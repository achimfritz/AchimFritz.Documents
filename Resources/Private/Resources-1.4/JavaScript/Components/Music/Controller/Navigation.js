/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicNavigationController', MusicNavigationController);

    /* @ngInject */
    function MusicNavigationController ($location, CONFIG, $rootScope, $timeout, Solr) {

        var vm = this;
        var $scope = $rootScope.$new();

        /* navigation */
        vm.current = '';
        vm.items = [
            {name: 'home', location: 'index'},
            {name: 'result', location: 'music/result'},
            {name: 'playlists', location: 'music/list'},
            {name: 'filter', location: 'music/filter'},
            {name: 'player', location: 'music/player'}
        ];
        vm.forward = forward;

        /* solr */
        vm.search = '';
        vm.params = {};
        vm.filterQueries = {};
        vm.update = update;
        vm.clearSearch = clearSearch;
        vm.rmFilterQuery = rmFilterQuery;

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
            vm.current = path.replace(CONFIG.baseUrl + '/', '');
        }

        function forward(newLocation) {
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
            Solr.setSearchAndUpdate(vm.search);
        }

        function getSolrData() {
            var data = Solr.getData();
            if (angular.isDefined(data.response) === true) {
                vm.filterQueries = Solr.getFilterQueries();
                vm.params = Solr.getParams();
            }
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

        var listener = $scope.$on('solrDataUpdate', function(event, data) {
            getSolrData();
        });

        var killerListener = $scope.$on('$locationChangeSuccess', function(ev, next, current) {
            var name = resolveRelativePath(next);
            vm.current = name;
            killerListener();
        });

    }
})();
