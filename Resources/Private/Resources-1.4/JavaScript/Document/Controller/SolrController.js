/* global angular */

(function () {
    'use strict';

    angular
        .module('documentApp')
        .controller('SolrController', SolrController);

    function SolrController($scope, Solr) {

        $scope.url = '';

        $scope.update = function (url) {
            $scope.url = url;
        };
    }
}());
