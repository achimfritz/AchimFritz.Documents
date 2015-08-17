/* global angular */

(function () {
    'use strict';

    angular
        .module('app')
        .service('DocumentListRestService', DocumentListRestService);

    function DocumentListRestService($http, AppConfiguration) {

        this.url = function() {
            return AppConfiguration.getSetting('restBaseUrl') + '/' + AppConfiguration.getSetting('documentListResource') + '/';
            console.log(AppConfiguration.getSettings());
        }

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
            var url =  this.url() + '?documentList[__identitiy]=' + identifier;
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
            var url =  this.url() + '?documentList[__identitiy]=' + identifier;
            return $http({
                method: 'DELETE',
                url: url,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
        };
    }
}());


