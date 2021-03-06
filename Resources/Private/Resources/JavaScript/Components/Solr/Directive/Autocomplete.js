/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.solr')
        .directive('autocomplete', Autocomplete);

    function Autocomplete(Solr) {

        return {

            scope: {
                global: '@',
                field: '@',
                autosubmit: '@'
            },

            link: function (scope, element, attr) {
                var field = 'spell';
                var global= false;
                if (angular.isDefined(scope.field)) {
                    field = scope.field;
                }
                if (angular.isDefined(scope.global) && scope.global === "1") {
                    global = true;
                }

                $(element).autocomplete({
                    source: function (request, response) {
                        var item = request.term.toLowerCase();
                        Solr.getAutocomplete(item, field, global).then(function (data) {
                            var q = data.data.responseHeader.params.q;
                            var results = Solr.facetsToLabelValue(data.data.facet_counts.facet_fields[field], q);
                            response(results);
                        });
                    },
                    select: function( event, ui ) {
                        $(element).val(ui.item.value);
                        if (angular.isDefined(scope.autosubmit)) {
                            Solr.setSearchAndUpdate(ui.item.value);
                        }
                    }
                });
            }

        };
    }
}());
