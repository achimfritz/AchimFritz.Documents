/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageNavigationController', ImageNavigationController);

    /* @ngInject */
    function ImageNavigationController ($location, CONFIG, $rootScope, $timeout, Solr) {

        var vm = this;
        var $scope = $rootScope.$new();

        /* navigation */
        vm.current = {location: ''};
        vm.items = [
            {name: 'home', location: 'index'},
            {name: 'result', location: 'image/result'},
            {name: 'filter', location: 'image/filter'}
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
            vm.current.location = path.replace(CONFIG.baseUrl + '/', '');
        }

        function forward(newLocation) {
            vm.current.location = newLocation;
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


        /* listener */

        var listener = $scope.$on('solrDataUpdate', function(event, data) {
            getSolrData();
        });


        var killerListener = $scope.$on('$locationChangeStart', function(ev, next, current) {
            var path = next.split('/app/')
            vm.current.location = path[1];
            listener();
            killerListener();
            // TODO cannot kill listener ???


        });

    }
})();
