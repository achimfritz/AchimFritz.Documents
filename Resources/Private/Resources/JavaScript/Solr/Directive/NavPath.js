/* global angular */

(function () {
    'use strict';

    angular
        .module('solr')
        .directive('navPath', NavPath);

    function NavPath(Solr, PathService) {
        return {
            restrict: 'E',
            link: function (scope, element, attr) {
                var val = attr.path;
                if (Solr.isHFacet(attr.facet)) {
                    val = PathService.last(attr.path);
                }
                if (attr.length) {
                    element.replaceWith(val.substr(0, attr.length));
                } else {
                    element.replaceWith(val);
                }
            }
        };
    }
}());
