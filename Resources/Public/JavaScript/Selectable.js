(function($){

	$.fn.extend({

		//pass the options variable to the function
		selectableArea: function(options) {
			var shiftPressed = false;
			var strgPressed = false;
			var self = $(this);
			
			$(document).keyup(function(e) {
				shiftPressed = false;
				strgPressed = false;
			});
			$(document).keydown(function(e) {
				if (e.keyCode == 16) {
					shiftPressed = true;
				}
				if (e.keyCode == 17) {
					strgPressed = true;
				}
			});
			
			var items = $('li.selectable', self).unbind('click');
			
			
			items.bind('click', function() {
				if (!strgPressed && !shiftPressed) {
		            $(this).nextAll().removeClass('selected');
		            $(this).prevAll().removeClass('selected');
		         }
		         if (shiftPressed) {
		            //$(this).prevUntil('.selected', '.selecteable').addClass('selected');
		            $(this).prevUntil('.selected').addClass('selected');
		         }
		         if ($(this).hasClass('selected')) {
		            $(this).removeClass('selected');
		         } else {
		            $(this).addClass('selected');
		         }
			});
		}
	});

})(jQuery);
