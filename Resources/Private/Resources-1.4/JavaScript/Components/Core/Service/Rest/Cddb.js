/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.core')
        .service('CddbRestService', CddbRestService);

    function CddbRestService($http) {

        this.cddb = function () {
            var cddb = {
                'path': '',
                'format': 1,
                'url': ''
            };
            return cddb;
        };

        this.update = function (cddb) {
            var url = 'achimfritz.documents/cddb/';
            var data = {
                'cddb': cddb
            };
            return $http({
                method: 'PUT',
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


