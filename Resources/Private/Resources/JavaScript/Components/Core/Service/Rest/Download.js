/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.core')
        .service('DownloadRestService', DownloadRestService);

    function DownloadRestService($http) {

        this.updateFolder = function (cddb) {
            var folder = {
                path: cddb.path,
                url: cddb.url
            };
            var url = 'achimfritz.documents/mp3folder/';
            var data = {
                'folder': folder
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

        this.updateCddb = function (cddb) {
            var url = 'achimfritz.documents/mp3cddb/';
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


