/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.core')
        .service('DocumentListRestService', DocumentListRestService);

    function DocumentListRestService($http, RestConfiguration) {

        this.url = function() {
            return RestConfiguration.getSetting('restBaseUrl') + '/' + RestConfiguration.getSetting('documentListResource') + '/';
        };

        this.mergeUrl = function() {
            return RestConfiguration.getSetting('restBaseUrl') + '/' + RestConfiguration.getSetting('documentListMergeResource') + '/';
        };
        
        this.removeUrl = function() {
            return RestConfiguration.getSetting('restBaseUrl') + '/' + RestConfiguration.getSetting('documentListRemoveResource') + '/';
        };

        var buildRequest = function(path, docs) {
            var sorting = 1;
            var documentListItems = [];
            var documentListItem = {};
            angular.forEach(docs, function (val, key) {
                documentListItem = {
                    'sorting': sorting,
                    'document': val.identifier
                };
                documentListItems.push(documentListItem);
                sorting++;
            });
            var data = {
                'documentList': {
                    'category': {
                        'path': path
                    },
                    'documentListItems': documentListItems
                }
            };
            return data;
        };

        this.list = function () {
            var url =  this.url();
            return $http({
                method: 'GET',
                url: url,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
        };

        this.show = function (identifier) {
            var url =  this.url() + '?documentList[__identity]=' + identifier;
            return $http({
                method: 'GET',
                url: url,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
        };

        this.delete = function (identifier) {
            var url =  this.url() + '?documentList[__identity]=' + identifier;
            return $http({
                method: 'DELETE',
                url: url,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
        };

        this.remove= function (path, docs) {
            var url =  this.removeUrl();
            var data = buildRequest(path, docs);
            return $http({
                method: 'Post',
                url: url,
                data: data,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
        };

        this.merge= function (path, docs) {
            var url =  this.mergeUrl();
            var data = buildRequest(path, docs);
            return $http({
                method: 'Post',
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

