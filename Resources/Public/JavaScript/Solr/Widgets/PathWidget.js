(function ($) {

      AjaxSolr.PathWidget = AjaxSolr.AbstractFacetWidget.extend({	  

    	  /**
    	   * @param {String} value The value.
    	   * @returns {Function} Sends a request to Solr if it successfully adds a
    	   *   filter query with the given value.
    	   */
    	  pathClickHandler: function (facet) {
    	    var self = this;
    	    return function () {
				 //var val = self.increaseLevel(value);
				 //self.manager.store.remove('fq');
				 var result = self.manager.response.facet_counts.facet_fields[self.field];
				 //console.log(self.manager.response.facet_counts.facet_fields);
				 if (result != undefined) {
				 //console.log(result);
						 self.manager.store.removeByValue('fq', /' + this.field + '/);
						 self.manager.store.addByValue('fq', self.field + ':' + facet.getPath());
						 self.manager.store.addByValue('f.' + self.field + '.facet.prefix', facet.increaseLevel());
						 self.manager.doRequest(0);
					
				}
				 return false;
    	    }
    	  },

		  getLevel: function() {
				var facetPrefix = this.manager.store.values('f.' + this.field + '.facet.prefix');
				var facetPrefix = facetPrefix[0];
				var arr = facetPrefix.split('/');
				var level = arr.shift();
				return level;

		  },

		  increaseLevel: function (path) {
			var arr = path.split('/');
			var level = arr.shift();
			level++;
			return level + '/' + arr.join('/');
		  },

		  replacePath: function (path) {
			var arr = path.split('/');
			var last = arr.pop();
			return last;
		  },
    	  
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
            objectedItems.sort(function (a, b) {
               return a.facet < b.facet ? -1 : 1;
            });
            
            var ul = $('<ul />'); 
            for (var i=0, l = objectedItems.length; i < l; i++) {

               var item = objectedItems[i];
					var facet = new hierarchicalFacet(item.facet);
               var name = $('<span />').addClass('facetName').html(facet.getLast());
               var count = $('<span />').addClass('facetCount').html(' (' + item.count + ')');
               var link = $('<a />').attr('href', '#').append(name).append(count).click(this.pathClickHandler(facet));
               ul.append($('<li />').append(link));
            }
            $(this.target).append(ul);
         }
      });
})(jQuery);

