/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3ipod')
        .controller('Mp3IpodFilterController', Mp3IpodFilterController);

    /* @ngInject */
    function Mp3IpodFilterController (Solr, $location, $routeParams, SolrConfiguration) {

        var vm = this;
        vm.result = {};
        vm.filter = '';

        // used by the view
        vm.addFilterQuery = addFilterQuery;
        vm.back = back;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
            vm.filter = $routeParams.filter;
            SolrConfiguration.setParam('facet_limit', 9999999);

            SolrConfiguration.setFacets([vm.filter]);
            SolrConfiguration.setHFacets({});
            SolrConfiguration.setSetting('servlet', 'mp3');

            Solr.init();
            Solr.resetFilterQueries();
            Solr.forceRequest().then(function (response) {
                vm.result.data = Solr.facetsToKeyValue(response.data.facet_counts.facet_fields[vm.filter]);
            });
        }

        function back() {
            $location.path('app/mp3ipod/start');
        }

        function addFilterQuery() {
            if (vm.filter === 'artist') {
                $location.path('app/mp3ipod/subfilter/artist/' + vm.result.value + '/album');
            } else if (vm.filter === 'genre'){
                $location.path('app/mp3ipod/subfilter/genre/' + vm.result.value + '/artist');
            } else if (vm.filter === 'album') {
                $location.path('app/mp3ipod/result/album/' + vm.result.value + '/all/all');
            }

        }

    }
})();
