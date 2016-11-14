/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageFilterController', ImageFilterController);

    /* @ngInject */
    function ImageFilterController(ngDialog, $rootScope, CONFIG, Solr, FacetFactory, CoreApplicationScopeFactory) {

        var vm = this;
        var $scope = $rootScope.$new();
        var categoryFilters = ['hPaths', 'hLocations', 'hCategories'];

        vm.solr = Solr;
        vm.facetFactory = FacetFactory;
        vm.facets = [];

        vm.showCategoryForm = showCategoryForm;
        vm.isEditableCategory = isEditableCategory;

        getSolrData();

        function isEditableCategory(name) {
            return categoryFilters.indexOf(name) >= 0;
        }

        function showCategoryForm(facetName, facetValue) {
            var data = {
                name: facetName,
                value: facetValue
            };

            $scope.dialog = ngDialog.open({
                data: data,
                template: CONFIG.templatePath + 'Image/EditCategory.html',
                controller: 'ImageEditCategoryController',
                controllerAs: 'imageEditCategory',
                scope: $scope
            });
        }

        function getSolrData() {
            var data = Solr.getData();
            if (angular.isDefined(data.response) === true) {
                vm.facets = FacetFactory.updateFacets(data);
            }
        }

        CoreApplicationScopeFactory.registerListener('ImageFilterController', 'solrDataUpdate', function (event, data) {
            getSolrData();
        });

    }
})();
