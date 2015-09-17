/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.image')
        .directive('tagAutocomplete', TagAutocomplete);

    function TagAutocomplete(Solr) {

        return {

            scope: {},

            link: function (scope, element, attr) {
                $(element).autocomplete({
                    source: function (request, response) {
                        var item = request.term;
                        Solr.getAutocomplete('tags/' + item, 'paths', true).then(function (data) {
                            var q = data.data.responseHeader.params.q;
                            response($.map(data.data.facet_counts.facet_fields.paths, function (val, key) {
                                var name = key.replace('tags/', '');
                                return {
                                    label: name + ' (' + val + ')',
                                    value: name
                                };
                            }));
                        });
                    }
                });
            }

        };
    }
}());
