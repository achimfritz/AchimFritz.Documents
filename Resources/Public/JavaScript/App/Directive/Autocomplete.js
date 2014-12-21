/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.directive('autocomplete', Autocomplete);

				function Autocomplete() {

								return {

												scope: {
												},

												//templateUrl: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/App/Partials/Docs.html?xx',

												link: function(scope, element, attr) {
																				$(element).autocomplete({
																								source: function( request, response ) {
																												var item = request.term;
																												var arr = item.split('/');
																												var level = arr.length;
																												level--;
																												item = level + '/' + arr.join('/');
																												//var settings = AchimFritz.App.settings();
																												$.ajax({
																																//url: 'http://' + settings.Solr.hostname + ':' + settings.Solr.port + '/' + settings.Solr.path + '/' + settings.Solr.servlet,
																																url: 'http://localhost:8080/solr/documents2/select',
																																data: {
																																				q: "*:*",
																																				facet: true,
																																				"facet.field": "paths",
																																				"facet.mincount": 1,
																																				"f.paths.facet.prefix": item,
																																				wt: 'json',
																																				'json.nl' : 'map'
																																},
																																dataType: "jsonp",
																																jsonp: 'json.wrf',

																																success: function( data ) {
																																				response($.map(data.facet_counts.facet_fields.paths, function( item , key) {
																																								var arr = key.split('/');
																																								var level = arr.shift();
																																								var name = arr.join('/');
																																								return {
																																												label: name + ' (' + item + ')',
																																												value: name,
																																								};
																																				}));
																																}
																												});
																								},
																								select: function( event, ui ) {
																												var selected = ui.item.value;
																												var that = $(this);
																												setTimeout(function() {
																																that.autocomplete("search", selected + '/');
																												}, 0.2);
																								},
																								minLength: 1
																				});




												},

								};
				}
}());
