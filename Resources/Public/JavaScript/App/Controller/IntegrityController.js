/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.controller('IntegrityController', IntegrityController);

				function IntegrityController($scope, IntegrityRestService, FlashMessageService) {

								$scope.view = 'list';
								$scope.finished = false;

								$scope.show = function(directory) {
												$scope.finished = false;
												IntegrityRestService.show(directory).then(function(data) {
																$scope.finished = true;
																$scope.integrity = data.data.integrity;
																$scope.view = 'show';
												});
								};

								$scope.update = function(directory) {
												$scope.finished = false;
												IntegrityRestService.update(directory).then(function(data) {
																FlashMessageService.show(data.data.flashMessages);
																$scope.finished = true;
												});
								};

								$scope.list = function() {
												$scope.finished = false;
												IntegrityRestService.list().then(function(data) {
																$scope.finished = true;
																$scope.integrities = data.data.integrities;
																$scope.view = 'list';
												});
								}

								IntegrityRestService.list().then(function(data) {
												$scope.finished = true;
												$scope.integrities = data.data.integrities;
								});
				}
}());
