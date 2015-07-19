/* global angular */

(function () {
    'use strict';

    angular
        .module('imageApp')
        .controller('SolrIntegrityController', SolrIntegrityController);

    function SolrIntegrityController($scope, Solr) {

        $scope.settings = Solr.getSettings();
        $scope.facets = Solr.getFacets();
        $scope.filterQueries = Solr.getFilterQueries();
        $scope.query = $scope.settings.q;

        $scope.rmFilterQuery = function (name, value) {
            Solr.rmFilterQuery(name, value);
            update();
        };

        $scope.addFilterQuery = function (name, value) {
            Solr.addFilterQuery(name, value);
            update();
        };

        $scope.integrity = function (query) {
            $scope.category = '';
            $scope.query = query;
            Solr.setSetting('q', query);
            update();
        };

        update();

        function update() {
            Solr.getData().then(function (data) {
                $scope.data = data.data;
            });
        };

    }
}());
