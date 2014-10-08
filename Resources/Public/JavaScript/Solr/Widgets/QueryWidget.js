(function ($) {
	
	AjaxSolr.QueryWidget = AjaxSolr.AbstractWidget.extend ({
		
		init: function() {
		
			var q = $('#query input[name="query_q"]').prop('value');
				if (q == '') {
					q = '*:*';
					$('#query input[name="query_q"]').prop('value', q);
				}
			var rows = $('#query input[name="query_rows"]').prop('value');
			var facetLimit = $('#query input[name="query_facet_limit"]').prop('value');
			this.manager.store.addByValue('rows', rows);
			this.manager.store.addByValue('q', q);
			this.manager.store.addByValue('facet.limit', facetLimit);

			var self=this;

			// ! this target
			$('#query form').unbind().bind('submit', function (event) {
				event.preventDefault(); 
				var q = $('#query input[name="query_q"]').prop('value');
				if (q == '') {
					q = '*:*';
					$('#query input[name="query_q"]').prop('value', q);
				}
				var rows = $('#query input[name="query_rows"]').prop('value');
				var facetLimit = $('#query input[name="query_facet_limit"]').prop('value');

				self.manager.store.addByValue('rows', rows);
				self.manager.store.addByValue('q', q);
				self.manager.store.addByValue('facet.limit', facetLimit);
				self.manager.doRequest();
			});
		}


	});
})(jQuery);

