/* global angular */

(function () {
    'use strict';

				angular
				.module('solr')
				.directive('autocomplete', Autocomplete);

				function Autocomplete(Solr) {

								return {

												scope: {
																global: '=global'
												},

												link: function(scope, element, attr) {
																$(element).autocomplete({
																				source: function( request, response ) {
																								var item = request.term;
																								Solr.getAutocomplete(item, 'spell', scope.global).then(function(data) {
																												var q = data.data.responseHeader.params.q;
																												response($.map(data.data.facet_counts.facet_fields.spell, function( val , key) {
																																var name = key;
																																var label = name + ' (' + val + ')';
																																var value = name;
																																if (q !== '*:*') {
																																				label = q + ' ' + label;
																																				value = q + ' ' + value;
																																}
																																return {
																																			label: label,
																																			value: value
																																};
																												}));
																								});
																				}
																});
												}

								};
				}
}());
