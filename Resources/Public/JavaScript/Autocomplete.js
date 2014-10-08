var AchimFritz;
(function ($) {

	if (AchimFritz == undefined) {
		AchimFritz = {};
	}

   AchimFritz.Autocomplete = {
		
		tagSearch: function() {
			$("#tagSearchInput").autocomplete({
				source: function( request, response ) { 
					var item = request.term;
					var settings = AchimFritz.App.settings();
					$.ajax({
						url: 'http://' + settings.Solr.hostname + ':' + settings.Solr.port + '/' + settings.Solr.path + '/' + settings.Solr.servlet,
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
				/*
						select: function( event, ui ) {
							var selected = ui.item.value;
							$(this).val(selected);
							$(this).parent('form').submit();
						}
						*/
			});
		},

		search: function() {
			$(".searchField").autocomplete({
				source: function( request, response ) { 
					var item = request.term;
					var settings = AchimFritz.App.settings();
					$.ajax({
						url: 'http://' + settings.Solr.hostname + ':' + settings.Solr.port + '/' + settings.Solr.path + '/' + settings.Solr.servlet,
						data: {
							q: '*:*',
							facet: true,
							"facet.field": "search",
							"facet.mincount": 1,
							"f.search.facet.prefix": item,
							wt: 'json',
							'json.nl' : 'map'
						}, 
						dataType: "jsonp",
						jsonp: 'json.wrf',

						success: function( data ) {
							response($.map(data.facet_counts.facet_fields.search, function( item , key) {
								return {
									label: key + ' (' + item + ')',
									value: key,
								}; 
							}));
						}
					});
				},
						select: function( event, ui ) {
							var selected = ui.item.value;
							$(this).val(selected);
							$(this).parent('form').submit();
						}
			});
		},

		pathAutosearch: function() {
			$(".pathAutosearch").autocomplete({
				source: function( request, response ) { 
					var item = request.term;
					var arr = item.split('/');
					var level = arr.length;
					level--;
					item = level + '/' + arr.join('/');
					var settings = AchimFritz.App.settings();
					$.ajax({
						url: 'http://' + settings.Solr.hostname + ':' + settings.Solr.port + '/' + settings.Solr.path + '/' + settings.Solr.servlet,
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

	$(function () {
		AchimFritz.Autocomplete.pathAutosearch();
		AchimFritz.Autocomplete.search();
		AchimFritz.Autocomplete.tagSearch();
	});

})(jQuery);
