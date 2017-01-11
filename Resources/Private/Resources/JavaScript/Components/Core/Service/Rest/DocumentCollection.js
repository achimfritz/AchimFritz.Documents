/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.core')
        .service('DocumentCollectionRestService', DocumentCollectionRestService);

    function DocumentCollectionRestService($http) {

        var buildRequest = function (path, docs) {
            var documents = [];
            angular.forEach(docs, function (val, key) {
                documents.push(val.identifier);
            });

            var data = {
                'documentCollection': {
                    'category': {
                        'path': path
                    },
                    'documents': documents
                }
            };
            return data;
        };

        this.writeTag = function (path, docs) {
            var url = 'achimfritz.documents/documentcollectionid3tag/';
            var data = buildRequest(path, docs);
            return $http({
                method: 'POST',
                url: url,
                data: data,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
        };

        this.deleteFiles = function (docs) {
            var url = 'achimfritz.documents/documentcollectionremove/';
            var documents = [];
            angular.forEach(docs, function (val, key) {
                documents.push(val.identifier);
            });

            var data = {
                'documentCollection': {
                    'documents': documents
                }
            };
            return $http({
                method: 'DELETE',
                url: url,
                data: data,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
        };

        this.remove = function (path, docs) {
            var url = 'achimfritz.documents/documentcollectionremove/';
            var data = buildRequest(path, docs);
            return $http({
                method: 'POST',
                url: url,
                data: data,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
        };
        this.merge = function (path, docs) {
            var url = 'achimfritz.documents/documentcollectionmerge/';
            var data = buildRequest(path, docs);
            return $http({
                method: 'POST',
                url: url,
                data: data,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
        };

    }
}());


