/* global angular */

(function () {
    'use strict';

				angular
				.module('imageApp')
				.service('FlashMessageService', FlashMessageService);

				function FlashMessageService(toaster) {

								this.show = function(flashMessages) {
												angular.forEach(flashMessages, function(flashMessage) {
																var severity = 'error';
																var title = flashMessage.severity;
																if (flashMessage.title !== '') {
																				title = flashMessage.title;
																}	
																if (flashMessage.severity === 'OK') {
																				severity = 'success';
																}
																toaster.pop(severity, title, flashMessage.message);
												});
								};

								this.error = function(data) {
												if (data.data.flashMessages !== undefined) {
																this.show(data.data.flashMessages);
												} else {
																var severity = 'error';
																toaster.pop(severity, data.status, data.data);
												}
								};

				}
}());


