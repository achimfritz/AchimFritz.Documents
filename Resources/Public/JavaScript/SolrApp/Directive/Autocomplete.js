/* global angular */

(function () {
    'use strict';

				angular
				.module('solrApp')
				.directive('autocomplete', Autocomplete);

				function Autocomplete(SolrFactory) {

								return {

												scope: {
												},

												link: function(scope, element, attr) {
																$(element).autocomplete({
																				source: function( request, response ) {
																								var item = request.term;
																								SolrFactory.getAutocomplete(item, 'spell').then(function(data) {
																												console.log(data.data);
																																response($.map(data.data.facet_counts.facet_fields.spell, function( item , key) {
																																				var name = key;
																																				return {
																																							label: name + ' (' + item + ')',
																																							value: name,
																																				};
																																}));
																								});
																				}
																});
												},

								};
				}
}());
