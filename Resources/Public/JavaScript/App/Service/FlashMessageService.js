/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
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
												var severity = 'error';
												toaster.pop(severity, data.status, data.data);
								};

				}
}());


