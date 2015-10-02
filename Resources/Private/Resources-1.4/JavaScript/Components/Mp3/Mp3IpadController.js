/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3')
        .controller('Mp3IpadController', Mp3IpadController);

    /* @ngInject */
    function Mp3IpadController (Solr, $rootScope) {

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
        vm.disableAllFacets = disableAllFacets;

        vm.disableAllFacets();


        function addFacet(name) {
            vm.disableAllFacets();
            vm.facets[name]['enabled'] = true;
        }

        function disableAllFacets() {
            vm.facets.artist.enabled = false;
            vm.facets.artist.selected = false;
            vm.facets.album.enabled = false;
            vm.facets.genre.enabled = false;
            vm.facets.genre.selected = false;
        }

        function addFilterQuery(name) {
            Solr.addFilterQuery(name, vm.facets[name]['value']);
            Solr.forceRequest().then(function (response) {
                $rootScope.$emit('solrDataUpdate', response.data);
            });
        }

        $rootScope.$on('solrDataUpdate', function (event, data) {
            vm.facets.artist.data = Solr.facetsToKeyValue(data.facet_counts.facet_fields.artist);
            vm.facets.album.data = Solr.facetsToKeyValue(data.facet_counts.facet_fields.album);
            vm.facets.genre.data = Solr.facetsToKeyValue(data.facet_counts.facet_fields.genre);
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
