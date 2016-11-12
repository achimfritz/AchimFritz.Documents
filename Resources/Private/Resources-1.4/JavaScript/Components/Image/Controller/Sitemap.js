/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageSitemapController', ImageSitemapController);

    /* @ngInject */
    function ImageSitemapController (Solr, $rootScope, ngDialog, CONFIG) {

        var vm = this;
        var $scope = $rootScope.$new();

        vm.paths = [];

        vm.showCategoryForm = showCategoryForm;

        initController();

        function initController() {
            var params = {
                rows: '0',
                q: '*:*',
                facet: true,
                facet_sort: 'index',
                facet_field: 'paths',
                facet_mincount: 1,
                facet_limit: 5000
            };
            Solr.fetchByParams(params).then(function(response){
                console.log(response.data.facet_counts.facet_fields.paths);
                vm.paths = response.data.facet_counts.facet_fields.paths;
            });
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

    }
})();
