/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.directive('autocompleteTags', AutocompleteTags);

				function AutocompleteTags() {

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
																																"facet.field": "paths",
																																"facet.mincount": 1,
																																"f.paths.facet.prefix": '1/tags/' + item,
																																wt: 'json',
																																'json.nl' : 'map'
																												},
																												dataType: "jsonp",
																												jsonp: 'json.wrf',

																												success: function( data ) {
																																response($.map(data.facet_counts.facet_fields.paths, function( item , key) {
																																				var name = key.replace('1/tags/', '');
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
