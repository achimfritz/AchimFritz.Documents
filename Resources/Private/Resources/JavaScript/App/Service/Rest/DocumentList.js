/* global angular */

(function () {
    'use strict';

    angular
        .module('app')
        .service('DocumentListRestService', DocumentListRestService);

    function DocumentListRestService($http) {

        this.list = function () {
            var url = 'achimfritz.documents/imagedocumentlist/';
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
            var url = 'achimfritz.documents/imagedocumentlist/?documentList[__identity]=' + identifier;
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
            var url = 'achimfritz.documents/imagedocumentlist/?documentList[__identity]=' + identifier;
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


