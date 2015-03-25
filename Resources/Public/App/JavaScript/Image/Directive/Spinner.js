/* global angular */

(function () {
    'use strict';
    angular
        .module('imageApp')
        .directive('spinner', Spinner);

    /* @ngInject */
    function Spinner() {

        return {
            restrict: 'E',
												templateUrl: '/_Resources/Static/Packages/AchimFritz.Documents/App/JavaScript/Image/Partials/Spinner.html'
        };
    }
}());
