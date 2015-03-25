/* global angular */

(function () {
    'use strict';
    angular
        .module('imageApp')
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

