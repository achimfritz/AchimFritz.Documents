/* global angular */

(function () {
    'use strict';

    angular
        .module('imageApp')
        .controller('FilterController', FilterController);

    function FilterController($scope, Solr, PathService, CategoryRestService, FlashMessageService) {

        $scope.settings = Solr.getSettings();
        $scope.facets = Solr.getFacets();
        $scope.filterQueries = Solr.getFilterQueries();
        $scope.search = '';
        $scope.finished = true;
        $scope.renameCategory = null;
        var currentFacetField = null;

        $scope.rmFilterQuery = function (name, value) {
            Solr.rmFilterQuery(name, value);
            update();
        };

        $scope.addFilterQuery = function (name, value) {
            Solr.addFilterQuery(name, value);
            update();
        };

        $scope.update = function (search) {
            update(search);
        };

        update();

        function update(search) {
            $scope.renameCategory = null;
            if (search !== undefined) {
                if (search !== '') {
                    Solr.setSetting('q', search);
                } else {
                    Solr.setSetting('q', '*:*');
                }
            }
            Solr.getData().then(function (data) {
                $scope.data = data.data;
            });
        };

        $scope.editCategory = function (facetName, facetValue) {
            currentFacetField = facetName;
            var path = ''
            if (PathService.depth(facetValue) === 1) {
                path = facetName + PathService.delimiter + facetValue;
            } else {
                path = PathService.slice(facetValue, 1);
            }
            $scope.renameCategory = {
                'oldPath': path,
                'newPath': path
            }
        };

        $scope.updateCategory = function (renameCategory) {
            $scope.finished = false;
            CategoryRestService.update(renameCategory).then(function (data) {
                $scope.finished = true;
                FlashMessageService.show(data.data.flashMessages);
                Solr.rmFilterQuery(currentFacetField, PathService.prependLevel(renameCategory.oldPath));
                Solr.addFilterQuery(currentFacetField, PathService.prependLevel(renameCategory.newPath));
                $scope.renameCategory = null;
                update();
            }, function (data) {
                $scope.finished = true;
                FlashMessageService.error(data);
            });
        };

    }
}());
