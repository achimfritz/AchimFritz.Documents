(function ($) {

      AjaxSolr.FacetWidget = AjaxSolr.AbstractFacetWidget.extend({	  
    	  
         afterRequest: function () {
    	  
    	  $(this.target).empty();
    	  
          
    	  	// empty ?
            if (this.manager.response.facet_counts.facet_fields[this.field] === undefined) {
            	$(this.target).append($('<ul />').append($('<li />').html('no_items_found for facet')));
               return;
            }
            
            var countFoo = 0;
            for (k in this.manager.response.facet_counts.facet_fields[this.field]) {
            	countFoo++;
            }
            if (countFoo == 0) {
            	$(this.target).append($('<ul />').append($('<li />').html('no_items_found for facet')));
                return;
            }
            
            // loop
            var maxCount = 0;
            var objectedItems = [];            
            for (var facet in this.manager.response.facet_counts.facet_fields[this.field]) {
               var count = parseInt(this.manager.response.facet_counts.facet_fields[this.field][facet]);
               if (count > maxCount) {
                  maxCount = count;
               }
               objectedItems.push({ facet: facet, count: count });
            }
            var ul = $('<ul />');
				if (!$(this.target).hasClass('facet-list')) {
					ul.addClass('inline'); 
				}
				var select = $('<select />');
				if (objectedItems.length > 1) {
					var option = $('<option />').text('--');
					select.append(option);
				}
            for (var i=0, l = objectedItems.length; i < l; i++) {
               var item = objectedItems[i];
               var name = $('<span />').addClass('facetName').html(item.facet);
               var count = $('<span />').addClass('facetCount').html(' (' + item.count + ')');
               var link = $('<a />').attr('href', '#').append(name).append(count).click(this.clickHandler(item.facet));
					var option = $('<option />').text(item.facet + ' (' + item.count + ')').click(this.clickHandler(item.facet));
					select.append(option);
               ul.append($('<li />').append(link));
            }
				if ($(this.target).hasClass('facet-select')) {
					$(this.target).append(select);
				} else {
					$(this.target).append(ul);
				}
         }
      });
})(jQuery);

