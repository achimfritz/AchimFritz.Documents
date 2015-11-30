/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3ipod')
        .controller('Mp3IpodSubFilterController', Mp3IpodSubFilterController);

    /* @ngInject */
    function Mp3IpodSubFilterController (Solr, $location, $routeParams, SolrConfiguration) {

        var vm = this;
        vm.result = {};
        vm.filter = '';
        vm.subFilter = '';
        vm.filterValue = '';

        // used by the view
        vm.addFilterQuery = addFilterQuery;
        vm.back = back;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
            vm.filter = $routeParams.filter;
            vm.subFilter = $routeParams.subFilter;
            vm.filterValue = $routeParams.filterValue;

            SolrConfiguration.setParam('facet_limit', 9999999);

            SolrConfiguration.setFacets([vm.subFilter, vm.filter]);
            SolrConfiguration.setHFacets({});
            SolrConfiguration.setSetting('servlet', 'mp3');

            Solr.init();

            Solr.resetFilterQueries();
            Solr.addFilterQuery(vm.filter, vm.filterValue);

            Solr.forceRequest().then(function (response) {
                vm.result.data = Solr.facetsToKeyValue(response.data.facet_counts.facet_fields[vm.subFilter]);
            });
        }

        function back() {
            $location.path('app/mp3ipod/filter/' + vm.filter);
        }

        function addFilterQuery() {
            $location.path('app/mp3ipod/result/' + vm.filter + '/' + vm.filterValue + '/' + vm.subFilter + '/' + vm.result.value);
        }

    }
})();
