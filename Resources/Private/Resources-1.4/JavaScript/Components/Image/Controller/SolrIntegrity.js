/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.image')
        .controller('ImageSolrIntegrityController', ImageSolrIntegrityController);

    /* @ngInject */
    function ImageSolrIntegrityController ($scope, Solr) {

        var vm = this;

        vm.setSearch = setSearch;

        function setSearch(search) {
            Solr.setSearchAndUpdate(search);
            $scope.closeThisDialog();
        }
    }
})();
