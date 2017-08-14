/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.solr')
        .directive('solrDate', SolrDate);

    function SolrDate() {
        return {
            restrict: 'E',
            link: function (scope, element, attr) {
                var val = new Date(attr.datestring);
                element.replaceWith(val);
            }
        };
    }
}());
