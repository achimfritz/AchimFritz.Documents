/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3')
        .controller('Mp3IpadController', Mp3IpadController);

    /* @ngInject */
    function Mp3IpadController (FlashMessageService, Solr, $rootScope) {

        var vm = this;
        vm.facets = {
            artist: {},
            album: {},
            genre: {}
        };

        // used by the view
        vm.addFacet = addFacet;
        vm.addFilterQuery = addFilterQuery;

        // not used by the view
        vm.initController = initController;
        vm.restSuccess = restSuccess;
        vm.restError = restError;
        vm.disableAllFacets = disableAllFacets;

        vm.initController();

        function initController() {
            vm.disableAllFacets();
        }

        function addFacet(name) {
            vm.disableAllFacets();
            vm.facets[name]['enabled'] = true;
        }

        function restSuccess(data) {
            vm.finished = true;
            FlashMessageService.show(data.data.flashMessages);
        }

        function restError(data) {
            vm.finished = true;
            FlashMessageService.error(data);
        }

        function disableAllFacets() {
            vm.facets.artist.enabled = false;
            vm.facets.artist.selected = false;
            vm.facets.album.enabled = false;
            vm.facets.genre.enabled = false;
            vm.facets.genre.selected = false;
        }

        function addFilterQuery(name) {
            Solr.addFilterQuery(name, vm.facets.artist.value);
            Solr.forceRequest().then(function (response) {
                $rootScope.$emit('solrDataUpdate', response.data);
            });
        }

        $rootScope.$on('solrDataUpdate', function (event, data) {
            //console.log(data.facet_counts.facet_fields.artist);
            var artists = []
            angular.forEach(data.facet_counts.facet_fields.artist, function(key, val) {
                artists.push({label: val + ' (' + key + ')', value: val});
            });

            //vm.facets.artist.data = artists;
            //console.log(artists);
            vm.facets.artist.data = artists;
            //vm.facets.artist.data = data.facet_counts.facet_fields.artist;
            vm.facets.album.data = data.facet_counts.facet_fields.album;
            vm.facets.genre.data = data.facet_counts.facet_fields.genre;
            var filterQueries = Solr.getFilterQueries();
            if (angular.isDefined(filterQueries['artist']) && filterQueries.artist.length > 0) {
                vm.facets.artist.selected = true;
            } else {
                vm.facets.artist.selected = false;
            }
            if (angular.isDefined(filterQueries['genre']) && filterQueries.genre.length > 0) {
                vm.facets.genre.selected = true;
            } else {
                vm.facets.genre.selected = false;
            }
        });

    }
})();
