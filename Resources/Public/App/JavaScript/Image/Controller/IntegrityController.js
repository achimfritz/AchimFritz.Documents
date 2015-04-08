/* global angular */

(function () {
    'use strict';

				angular
				.module('imageApp')
				.controller('IntegrityController', IntegrityController);

				function IntegrityController($scope, IntegrityRestService, FlashMessageService, Solr, toaster) {


								$scope.view = 'list';
								$scope.finished = false;
								$scope.showAll = false;

								$scope.setShowAll = function(showAll) {
												$scope.showAll = showAll;
								};


								$scope.show = function(directory) {
												$scope.finished = false;
												IntegrityRestService.show(directory).then(function(data) {
																$scope.finished = true;
																$scope.integrity = data.data.integrity;
																$scope.view = 'show';
												});
								};

								$scope.solr = function(directory) {
												Solr.addFilterQuery('mainDirectoryName', directory);
												toaster.pop('success', 'Solr', 'TOOO add mainDirectoryName to FilterQueries');
								};

								$scope.update = function(directory) {
												$scope.finished = false;
												IntegrityRestService.update(directory).then(function(data) {
																FlashMessageService.show(data.data.flashMessages);
																$scope.show(directory);
												});
								};

								function list() {
												$scope.finished = false;
												IntegrityRestService.list().then(function(data) {
																$scope.finished = true;
																$scope.integrities = data.data.integrities;
																$scope.view = 'list';
												});
								};

								$scope.list = function() {
												list();
								};

								list();
				}
}());
