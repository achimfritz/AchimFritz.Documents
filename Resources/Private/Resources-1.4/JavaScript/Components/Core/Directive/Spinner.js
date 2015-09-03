/* global angular */

(function () {
    'use strict';
    angular
        .module('achimfritz.core')
        .directive('spinner', Spinner);

    /* @ngInject */
    function Spinner() {

        var html = '<div id="floatingBarsG"><div class="blockG" id="rotateG_01"></div><div class="blockG" id="rotateG_02"></div><div class="blockG" id="rotateG_03"></div><div class="blockG" id="rotateG_04"></div><div class="blockG" id="rotateG_05"></div><div class="blockG" id="rotateG_06"></div><div class="blockG" id="rotateG_07"></div><div class="blockG" id="rotateG_08"></div></div>';

        return {
            restrict: 'E',
            template: html
        };
    }
}());
