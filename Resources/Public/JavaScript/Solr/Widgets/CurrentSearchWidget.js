(function ($) {
      AjaxSolr.CurrentSearchWidget = AjaxSolr.AbstractWidget.extend({


		afterRequest: function () {
			var target = $('#' + this.id);
			target.empty();
			var fq = this.manager.store.values('fq');
			var ulEl=$('<ul />');

			if (fq.length) {
				for (var i = 0, l = fq.length; i < l; i++) {
					var s = fq[i];
					var n = s.split(":");
					//var title = n[0];
					var facet = n[0].replace(/"/g, '');
					var value = n[1].replace(/"/g, '');
					var link = $('<a />').attr('href', '#').text(value).bind('click', this.removeFacet(fq[i]));
					var dataFacet = {
						'facet': facet,
						'value': value
					};
					if (dataFacet.facet == 'navigation') {
						// remove path depth
						var hFacet = new hierarchicalFacet(dataFacet.value);
						var path = hFacet.getName();
					} else {
						var path = dataFacet.facet + '/'  + dataFacet.value;
					}
					var span = AchimFritz.App.renderCurrentSearchActions(path);
					var liEl = $('<li />').append(link).append(span);
					ulEl.append(liEl);
				}
			} else {
				var liEl = $('<li />').text('viewing all documents');
				ulEl.append(liEl);

			}
			target.append(ulEl);
			AchimFritz.App.bindAfterRequest();

		},

		removeFacet: function (facet) {
		  var self = this;
		  return function () {
			 if (self.manager.store.removeByValue('fq', facet)) {
				var n = facet.split(":");
				var field = '';
				if (n[0] == 'paths') {
					field = 'paths';
				} else if (n[0] == 'navigation') {
					field = 'navigation';
				}
				if (field) {
					var path = n[1];
					var hFacet = new hierarchicalFacet(path);
					var prefix = hFacet.increaseLevel();
					self.manager.store.removeByValue('f.' + field + '.facet.prefix', prefix);
					var newPrefix = hFacet.removeLast();
					self.manager.store.addByValue('f.' + field + '.facet.prefix', newPrefix);
					if (hFacet.getLevel() > 0) {
						var newFq = field + ':' + hFacet.getPreviousFq();
						self.manager.store.addByValue('fq', newFq);
					}
				}
				self.manager.doRequest(0);
			 }
			 return false;
		  };
		}

   });
})(jQuery);

