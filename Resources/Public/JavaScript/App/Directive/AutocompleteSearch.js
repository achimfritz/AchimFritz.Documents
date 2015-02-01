/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.directive('autocompleteSearch', AutocompleteSearch);

				function AutocompleteSearch() {

								return {

												scope: {
												},

												link: function(scope, element, attr) {
																$(element).autocomplete({
																				source: function( request, response ) {
																								var item = request.term;
																								$.ajax({
																												url: 'http://localhost:8080/solr/documents2/select',
																												data: {
																																q: "*:*",
																																facet: true,
																																"facet.field": "search",
																																"facet.mincount": 1,
																																"facet.maxcount": 2,
																																"f.search.facet.prefix": item,
																																wt: 'json',
																																'json.nl' : 'map'
																												},
																												dataType: "jsonp",
																												jsonp: 'json.wrf',

																												success: function( data ) {
																																response($.map(data.facet_counts.facet_fields.search, function( item , key) {
																																				var name = key;
																																				return {
																																								label: name + ' (' + item + ')',
																																								value: name,
																																				};
																																}));
																												}
																								});
																				}
																});




												},

								};
				}
}());
