(function ($) {
	AjaxSolr.LetterFacetWidget = AjaxSolr.AbstractFacetWidget.extend({

		firstRequest : true,
		multivalue : false,

		afterRequest: function () {
			var objectedItems = [];
	
			for (var facet in this.manager.response.facet_counts.facet_fields[this.field]) {
				var count = parseInt(this.manager.response.facet_counts.facet_fields[this.field][facet]);
				objectedItems.push({ facet: facet, count: count });
			}
			objectedItems.sort(function (a, b) {
				return a.facet < b.facet ? -1 : 1;
			});
			
			//$(this.target).empty();
			
			if (this.firstRequest) {
				var myUl = $('#'+this.field);
				for (var i=0, l = objectedItems.length; i < l; i++) {
					var item = objectedItems[i];
					var tag = $('<li class="tagcloud_item"/>').html('<a href="#">'+item.facet + '</a>').addClass('ui-state-default').click(this.clickHandler(item.facet));
					myUl.append(tag);
				}
			}
			this.firstRequest = false;
		},
	});
})(jQuery);

