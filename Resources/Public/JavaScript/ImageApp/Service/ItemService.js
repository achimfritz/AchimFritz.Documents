/* global angular */

(function () {
    'use strict';

				angular
				.module('imageApp')
				.service('ItemService', ItemService);

				function ItemService() {

								var strgPressed = false;
								var shiftPressed = false;

								jQuery(document).keyup(function(e) {
												shiftPressed = false;
												strgPressed = false;
								});
								jQuery(document).keydown(function(e) {
												if (e.keyCode == 16) {
																shiftPressed = true;
												} else if (e.keyCode == 17) {
																strgPressed = true;
												}
								});

								this.getPrev = function(current, items) {
												var prev = {};
												var found = false;
												angular.forEach(items, function(val) {
																if (val.identifier === current.identifier) {
																				found = true;
																}
																if (found === false) {
																				prev = val;
																}
												});
												return prev;																													
								};
												
								this.getNext = function(current, items) {
												var next = {};
												var found = false;
												angular.forEach(items, function(val) {
																if (found === true) {
																				next = val;
																				found = false;
																}
																if (val.identifier === current.identifier) {
																				found = true;
																}
												});
												return next;																													
								};
												
								this.itemClick = function(item, items) {
												if (item.selected === 'selected') {
																item.selected = '';
												} else {
																if (strgPressed === false && shiftPressed === false) {
																				// rm all others
																				angular.forEach(items, function(val, key) {
																								if (item.identifier !== val.identifier) {
																												val.selected = '';
																								}
																				});
																} else if (shiftPressed === true) {
																				// select all from last selected
																				var collect = false;
																				for (var i = ( items.length - 1 ); i >= 0; i--) {
																								var el = items[i];
																								if (el.identifier === item.identifier) {
																												collect = true;
																								}
																								if (collect === true) {
																												if (el.selected === 'selected') {
																																collect = false;
																												}
																												el.selected = 'selected';
																								}
																								
																				}
																}
																// add always me 
																item.selected = 'selected';
												}
								};
				}
}());
