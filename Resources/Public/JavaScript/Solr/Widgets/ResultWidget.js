(function ($) {

	AjaxSolr.ResultWidget = AjaxSolr.AbstractWidget.extend ({
	
		afterRequest: function () {
			var target = $('#' + this.id);
			target.empty();
		   var total = parseInt(this.manager.response.response.numFound);
			target.append($('<p >').text('total: ' + total));
			var ulEl = AchimFritz.App.renderDocs(this.manager.response.response.docs);
		   target.append(ulEl);
			$('#docs').selectableArea();
		}
	
	});
})(jQuery);

