/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3ipod')
        .controller('Mp3IpodArtistController', Mp3IpodArtistController);

    /* @ngInject */
    function Mp3IpodArtistController ($location, $routeParams, Mp3IpodSolrService) {

        var vm = this;
        vm.result = {};
        vm.genre = '';

        // used by the view
        vm.forward = forward;
        vm.back = back;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
            vm.genre = $routeParams.genre;
            Mp3IpodSolrService.initialize();
            Mp3IpodSolrService.request(vm.genre, 'all', 'all').then(function (response) {
                vm.result.data = Mp3IpodSolrService.facetsToKeyValue(response.data.facet_counts.facet_fields.artist);
            });
        }

        function back() {
            if (vm.genre !== 'all') {
                $location.path('app/mp3ipod/genre');
            } else {
                $location.path('app/mp3ipod');
            }
        }

        function forward() {
            $location.path('app/mp3ipod/album/' + vm.genre + '/' + vm.result.value);
        }

    }
})();
