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
                if (Solr.isHFacet(attr.facet)) {
                    element.replaceWith(PathService.last(attr.path));
                } else {
                    element.replaceWith(attr.path);
                }
            }
        };
    }
}());
