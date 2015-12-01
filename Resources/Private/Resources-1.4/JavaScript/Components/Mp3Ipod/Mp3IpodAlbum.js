/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3ipod')
        .controller('Mp3IpodAlbumController', Mp3IpodAlbumController);

    /* @ngInject */
    function Mp3IpodAlbumController ($location, $routeParams, Mp3IpodSolrService) {

        var vm = this;
        vm.result = {};
        vm.artist = '';
        vm.genre = '';

        // used by the view
        vm.forward = forward;
        vm.back = back;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
            vm.genre = $routeParams.genre;
            vm.artist = $routeParams.artist;
            Mp3IpodSolrService.initialize();
            Mp3IpodSolrService.request(vm.genre, vm.artist, 'all').then(function (response) {
                vm.result.data = Mp3IpodSolrService.facetsToKeyValue(response.data.facet_counts.facet_fields.album);
            });
        }

        function back() {
            if (vm.artist !== 'all') {
                $location.path('app/mp3ipod/artist/' + vm.genre);
            } else if (vm.genre !== 'all') {
                $location.path('app/mp3ipod/genre');
            } else {
                $location.path('app/mp3ipod');
            }
        }

        function forward() {
            $location.path('app/mp3ipod/result/' + vm.genre + '/' +vm.artist + '/' + vm.result.value);
        }

    }
})();
