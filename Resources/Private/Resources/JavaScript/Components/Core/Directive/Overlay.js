/* global angular */

(function () {
    'use strict';
    angular
        .module('achimfritz.core')
        .directive('overlay', Overlay);

    /* @nInject */
    function Overlay() {
        return {
            restrict: 'E',
            template: '<div data-ng-class="{false:\'overlay\'}[finished]"></div>',
            scope: {
                finished: '='
            }
        };
    }
}());

