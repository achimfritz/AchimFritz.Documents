/* global angular */

(function () {
    'use strict';

    angular
        .module('mp2App')
        .controller('SearchController', SearchController);

    function SearchController($scope, Solr, RatingRestService) {

        $scope.songs = [];

        $scope.settings = Solr.getSettings();
        $scope.facets = Solr.getFacets();
        $scope.filterQueries = Solr.getFilterQueries();
        $scope.search = '';
        $scope.finished = true;

        var currentFacetField = null;

        $scope.changeFacetCount = function (facetName, diff) {
            var solrKey = 'f_' + facetName + '_facet_limit';
            var settings = Solr.getSettings();
            var newVal = settings['facet_limit'] + diff;
            if (angular.isDefined(settings[solrKey])) {
                newVal = settings[solrKey] + diff;
            }
            Solr.setSetting(solrKey, newVal);
            update($scope.search);
        };


        $scope.rate = function (name, rate, value) {
            var rating = {
                'name': name,
                'value': value,
                'rate': rate
            };
            RatingRestService.update(rating).then(
                function (data) {
                    $scope.finished = true;
                    //FlashMessageService.show(data.data.flashMessages);
                },
                function (data) {
                    $scope.finished = true;
                    //FlashMessageService.error(data);
                }
            );
        };


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
            if (search !== undefined) {
                if (search !== '') {
                    Solr.setSetting('q', search);
                } else {
                    Solr.setSetting('q', '*:*');
                }
            }
            Solr.getData().then(function (data) {
                $scope.songs = [];
                angular.forEach(data.data.response.docs, function (doc) {
                    var song = {
                        id: doc.identifier,
                        title: doc.title,
                        artist: doc.artist,
                        doc: doc,
                        url: '/' + doc.webPath
                    };
                    $scope.songs.push(song);
                });
                $scope.data = data.data;
            });
        };

    }
}());
