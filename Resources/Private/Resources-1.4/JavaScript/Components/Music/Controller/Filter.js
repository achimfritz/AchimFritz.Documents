/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.music')
        .controller('MusicFilterController', MusicFilterController);

    /* @ngInject */
    function MusicFilterController (ngDialog, $rootScope, CONFIG, Solr, FilterConfiguration) {

        var vm = this;
        var $scope = $rootScope.$new();

        // solr
        vm.filterQueries = {};
        vm.data = {};
        vm.changeFacetSorting = changeFacetSorting;
        vm.changeFacetCount = changeFacetCount;
        vm.addFilterQuery = addFilterQuery;

        // filters
        vm.filters = {};
        vm.configuration = {};
        vm.renameCategory = false;
        vm.renameCategoryFacet = '';
        vm.editType = '';
        vm.toggleFilter = toggleFilter;
        vm.showRenameCategoryForm = showRenameCategoryForm;
        vm.isEditable = isEditable;

        vm.initController = initController;

        vm.initController();

        function initController() {
            getSolrData();
            vm.filters = FilterConfiguration.getFilters();
            vm.configuration = FilterConfiguration.getConfiguration();
        }

        /* filter */

        function isEditable(editType, key) {
            if (angular.isDefined(vm.configuration[editType])) {
                var config = vm.configuration[editType];
                if (config.indexOf(key) >= 0) {
                    return true;
                }
            }
            return false;
        }

        function toggleFilter(name) {
            if (vm.filters[name] === false) {
                vm.filters[name] = true;
            } else {
                vm.filters[name] = false;
            }
        }


        function showRenameCategoryForm(facetName, facetValue, editType) {

            var data = {
                facetName: facetName,
                facetValue: facetValue,
                editType: editType
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

        $rootScope.$on('apiId3TagUpdate', function (event, data) {
            ngDialog.close($scope.dialog.id);
            if (Solr.hasFilterQuery(data.facetName) === true) {
                Solr.rmFilterQuery(data.facetName, data.renameCategory.oldPath);
                Solr.addFilterQuery(data.facetName, data.renameCategory.newPath);
            }
            Solr.forceRequest().then(function (response) {
                Solr.setData(response.data);
            });
        });

    }
})();
