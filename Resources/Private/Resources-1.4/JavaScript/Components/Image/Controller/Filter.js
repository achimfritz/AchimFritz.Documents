/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageFilterController', ImageFilterController);

    /* @ngInject */
    function ImageFilterController (ngDialog, $rootScope, CONFIG, Solr, FacetFactory) {

        var vm = this;
        var $scope = $rootScope.$new();
        var categoryFilters = ['hPaths'];

        // solr
        vm.filterQueries = {};
        vm.data = {};
        vm.facets = [];
        vm.changeFacetSorting = changeFacetSorting;
        vm.changeFacetCount = changeFacetCount;
        vm.addFilterQuery = addFilterQuery;

        // filters
        vm.toggleFilter = toggleFilter;

        // forms
        //vm.showCategoryForm = showCategoryForm;

        getSolrData();

        /* filter */
        function toggleFilter(name) {
            vm.facets = FacetFactory.toggleFacet(name);
        }

        /* forms */


        /* solr */

        function changeFacetCount(facetName, diff) {
            Solr.changeFacetCountAndUpdate(facetName, diff);
        }

        function changeFacetSorting(facetName, sorting) {
            Solr.changeFacetSortingAndUpdate(facetName, sorting);
        }

        function addFilterQuery(name, value) {
            Solr.addFilterQueryAndUpdate(name, value);
        }

        function getSolrData() {
            var data = Solr.getData();
            if (angular.isDefined(data.response) === true) {
                vm.filterQueries = Solr.getFilterQueries();
                vm.data = data;
                vm.facets = FacetFactory.updateFacets(data);
            }
        }

        /* listener */

        var listener = $scope.$on('solrDataUpdate', function(event, data) {
            getSolrData();
        });

        var killerListener = $scope.$on('$locationChangeStart', function(ev, next, current) {
            listener();
            killerListener();
        });

    }
})();
