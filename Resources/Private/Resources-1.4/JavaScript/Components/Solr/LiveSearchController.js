/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.solr')
        .controller('LiveSearchController', LiveSearchController);

    function LiveSearchController(Solr, $q, $rootScope) {

        var vm = this;

        vm.selectCallback = selectCallback;
        vm.searchCallback = searchCallback;

        function selectCallback(params) {
            Solr.setParam('q', params.item.key);
            Solr.forceRequest().then(function (response) {
                $rootScope.$emit('solrDataUpdate', response.data);
            });
            return params.item.key;
        }

        function searchCallback(params) {
            var defer = $q.defer();
            Solr.getAutocomplete(params.query, params.facet, true).then(function (data) {
                var results = Solr.facetsToKeyValue(data.data.facet_counts.facet_fields[params.facet]);
                defer.resolve(results);
            });
            return defer.promise;
        }

    }
}());
