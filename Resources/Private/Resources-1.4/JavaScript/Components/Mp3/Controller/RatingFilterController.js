/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.mp3')
        .controller('RatingFilterController', RatingFilterController);

    /* @ngInject */
    function RatingFilterController (Solr, $rootScope) {

        var vm = this;
        vm.rating = {
            operator: '=',
            filter: '',
            rating: 0
        };

        vm.update = update;

        function update() {
            var fq = '2/rating/' + vm.rating.filter + '/' + vm.rating.rating;
            Solr.addFilterQuery('hPaths', fq);
            Solr.forceRequest().then(function (response) {
                $rootScope.$emit('solrDataUpdate', response.data);
            });
        }


    }
})();
