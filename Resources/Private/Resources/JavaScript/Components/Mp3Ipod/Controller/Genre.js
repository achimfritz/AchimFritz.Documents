/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3ipod')
        .controller('Mp3IpodGenreController', Mp3IpodGenreController);

    /* @ngInject */
    function Mp3IpodGenreController ($location, $routeParams, Mp3IpodSolrService, Solr) {

        var vm = this;
        vm.result = {};

        // used by the view
        vm.forward = forward;
        vm.back = back;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {

            Mp3IpodSolrService.initialize();
            Mp3IpodSolrService.setFilterParams('all', 'all', 'all');
            Solr.forceRequest().then(function (response) {

                vm.result.data = Mp3IpodSolrService.facetsToKeyValue(response.data.facet_counts.facet_fields.genre);
            });
        }

        function back() {
            $location.path('app/mp3ipod');
        }

        function forward() {
            $location.path('app/mp3ipod/artist/' + vm.result.value);
        }

    }
})();
