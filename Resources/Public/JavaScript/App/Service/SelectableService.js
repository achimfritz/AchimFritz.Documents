/* global angular,jQuery */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.service('SelectableService', SelectableService);

				function SelectableService() {
												var shiftPressed = false;
												var strgPressed = false;
												var $container = jQuery('#items');

												jQuery(document).keyup(function(e) {
																shiftPressed = false;
																strgPressed = false;
												});
												jQuery(document).keydown(function(e) {
																if (e.keyCode == 16) {
																	shiftPressed = true;
																}
																if (e.keyCode == 17) {
																	strgPressed = true;
																}
												});

								this.init = function() {
												//var items = jQuery('div.iso-item', $container).unbind('click');
												var items = jQuery('div.iso-item', $container).unbind('click');
												console.log(items.length);
							
												items.bind('click', function() {
																if (!strgPressed && !shiftPressed) {
																				jQuery(this).nextAll().removeClass('selected');
																				jQuery(this).prevAll().removeClass('selected');
																}
																if (shiftPressed) {
																				jQuery(this).prevUntil('.selected').addClass('selected');
																}
																if (jQuery(this).hasClass('selected')) {
																				jQuery(this).removeClass('selected');
																} else {
																				jQuery(this).addClass('selected');
																}
												});
								};

								this.getSelected = function() {
												var docs = [];
												jQuery('div.selected', $container).each(function(i, el) {
																var doc = jQuery(el).data('doc');
																docs.push(doc);
																console.log(doc);
												});
												return docs;
								};
				}
}());
