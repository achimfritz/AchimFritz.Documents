/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.core')
        .service('RatingRestService', RatingRestService);

    function RatingRestService($http) {

        this.delete = function (rating) {
            var url = 'achimfritz.documents/rating/';
            return $http({
                method: 'DELETE',
                data: {'rating': rating},
                url: url,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
        };

        this.update = function (rating) {
            var url = 'achimfritz.documents/rating/';
            return $http({
                method: 'PUT',
                data: {'rating': rating},
                url: url,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
        };
    }
}());


