/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.document')
        .service('ListService', ListService);


    function ListService($rootScope, Solr) {

        var self = this;
        self.data = {};

        self.initService = function() {
            Solr.request().then(function (response){
                self.data = response.data;
            })
        };

        self.getPrev = function (current) {
            var items = self.data.response.docs;
            var prev = {};
            var found = false;
            angular.forEach(items, function (val) {
                if (val.identifier === current.identifier) {
                    found = true;
                }
                if (found === false) {
                    prev = val;
                }
            });
            return prev;
        };

        self.getNext = function (current) {
            var items = self.data.response.docs;
            var next = {};
            var found = false;
            angular.forEach(items, function (val) {
                if (found === true) {
                    next = val;
                    found = false;
                }
                if (val.identifier === current.identifier) {
                    found = true;
                }
            });
            return next;
        };


        self.initService();

        $rootScope.$on('solrDataUpdate', function (event, data) {
            self.data = data;
        })


    }


}());


