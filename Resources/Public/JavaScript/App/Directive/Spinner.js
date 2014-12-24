/* global angular */

(function () {
    'use strict';
    angular
        .module('documentApp')
        .directive('spinner', spinner);

    /* @ngInject */
    function spinner() {

        return {
            restrict: 'E',
												templateUrl: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/App/Partials/Spinner.html?xx'
        };
    }
}());
