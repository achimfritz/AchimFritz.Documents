/* global angular */

(function () {
    'use strict';
    angular
        .module('achimfritz.core')
        .directive('scrollToTop', ScrollToTop);

    /* @nInject */
    function ScrollToTop() {
        return {
            restrict: 'E',
            template: '<button class="btn btn-default scrollToTop"><span class="glyphicon glyphicon-menu-up" aria-hidden="true"></span></button>',
            link: function (scope, element, attr) {

                $(window).scroll(function(){
                    if ($(this).scrollTop() > 100) {
                        $('.scrollToTop').fadeIn();
                    } else {
                        $('.scrollToTop').fadeOut();
                    }
                });

                $(element).click(function(){
                    $('html, body').animate({scrollTop : 0},800);
                    return false;
                });
            }
        }
    }
}());

