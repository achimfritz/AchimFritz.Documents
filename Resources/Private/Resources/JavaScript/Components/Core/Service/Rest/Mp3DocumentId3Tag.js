/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.core')
        .service('Mp3DocumentId3TagRestService', Mp3DocumentId3TagRestService);

    function Mp3DocumentId3TagRestService($http) {

        this.update = function (mp3DocumentId3Tag) {
            var url = 'achimfritz.documents/mp3documentid3tag/';
            var data = {
                'mp3DocumentId3Tag': mp3DocumentId3Tag
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

        this.massTag = function (renameCategory) {
            var url = 'achimfritz.documents/mp3massid3tag/';
            var data = {
                'renameCategory': renameCategory
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


