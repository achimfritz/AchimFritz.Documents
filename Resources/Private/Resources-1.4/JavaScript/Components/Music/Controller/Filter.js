/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicFilterController', MusicFilterController);

    /* @ngInject */
    function MusicFilterController (ngDialog, $rootScope, CONFIG, Solr) {

        var vm = this;
        var $scope = $rootScope.$new();
        var id3TagFilters = ['artist', 'album', 'genre', 'year'];
        var categoryFilters = ['hPaths'];

        // solr
        vm.filterQueries = {};
        vm.data = {};
        vm.changeFacetSorting = changeFacetSorting;
        vm.changeFacetCount = changeFacetCount;
        vm.addFilterQuery = addFilterQuery;

        // filters
        vm.filters = {};
        vm.toggleFilter = toggleFilter;

        // forms
        vm.isEditableId3Tag = isEditableId3Tag;
        vm.isEditableCategory = isEditableCategory;
        vm.showId3TagForm = showId3TagForm;
        vm.showCategoryForm = showCategoryForm;

        vm.initController = initController;

        vm.initController();

        function initController() {
            getSolrData();

            vm.filters = {
                artist: true,
                album: true,
                genre: true,
                fsProvider: true,
                fsGenre: true,
                artistLetter: false,
                year: false,
                hPaths: false,
                fsArtist: false,
                fsAlbum: false
            };
        }

        /* filter */
        function toggleFilter(name) {
            if (vm.filters[name] === false) {
                vm.filters[name] = true;
            } else {
                vm.filters[name] = false;
            }
        }

        /* forms */

        function isEditableId3Tag(name) {
            return id3TagFilters.indexOf(name) >= 0;
        }

        function isEditableCategory(name) {
            return categoryFilters.indexOf(name) >= 0;
        }

        function showId3TagForm(tagName, tagValue) {
            var data = {
                name: tagName,
                value: tagValue
            };
            $scope.dialog = ngDialog.open({
                "data" : data,
                "template" : CONFIG.templatePath + 'Music/EditId3Tag.html',
                "controller" : 'MusicEditId3TagController',
                "controllerAs" : 'musicEditId3Tag',
                "scope" : $scope
            });
        }

        function showCategoryForm(facetName, facetValue) {
            var data = {
                name: facetName,
                value: facetValue
            };
            $scope.dialog = ngDialog.open({
                "data" : data,
                "template" : CONFIG.templatePath + 'Music/EditCategory.html',
                "controller" : 'MusicEditCategoryController',
                "controllerAs" : 'musicEditCategory',
                "scope" : $scope
            });
        }

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
                vm.data = Solr.getData();
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
