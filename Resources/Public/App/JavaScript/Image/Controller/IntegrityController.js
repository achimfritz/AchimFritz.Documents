/* global angular */

(function () {
    'use strict';

				angular
				.module('imageApp')
				.controller('IntegrityController', IntegrityController);

				function IntegrityController($scope, IntegrityRestService, FlashMessageService, Solr, toaster, JobRestService) {

								var currentDirectory = '';

								$scope.view = 'list';
								$scope.finished = false;
								$scope.showAll = false;

								$scope.setShowAll = function(showAll) {
												$scope.showAll = showAll;
								};

								function jobWatch(identifier) {
												setTimeout(getJob(identifier), 2000);
								};

								function getJob(identifier) {
												JobRestService.show(identifier).then(
																function (data) {
																				var job = data.data.job;
																				$scope.job = job;
																				if (job.status < 3) {
																								jobWatch(identifier);
																				} else if (job.status === 3) {
																								toaster.pop('success', 'Job', 'images initialized');
																								$scope.show(currentDirectory);
																								// success
																				} else if (job.status > 3) {
																								toaster.pop('error', 'Job', 'image initialized failed');
																								// failed
																				}
																},
																function (data) {
								            $scope.finished = true;
																				FlashMessageService.error(data);
																}
												);
								};

								$scope.initialize = function(name, isExif) {
												$scope.finished = false;
												var command = 'cd /data/home/af/dev && ./flow achimfritz.surf:surf:imageTwo --name ' + name + ' --isExif ' + isExif;
												currentDirectory = name;
												var job = {
																'command': command
												};
												JobRestService.create(job).then(
																function (data) {
																				$scope.finished = true;
																				FlashMessageService.show(data.data.flashMessages);
																				console.log(data.data.job);
																				getJob(data.data.job.__identity);
																},
																function (data) {
								            $scope.finished = true;
																				FlashMessageService.error(data);
																}
												);

								}

								//getJob('9f27fdb1-11c3-aaec-adb7-a60df985b3b9');


								$scope.show = function(directory) {
												$scope.finished = false;
												IntegrityRestService.show(directory).then(
																function(data) {
																				$scope.finished = true;
																				$scope.integrity = data.data.integrity;
																				$scope.view = 'show';
																},
																function (data) {
								            $scope.finished = true;
																				FlashMessageService.error(data);
																}
												);
								};

								$scope.solr = function(directory) {
												Solr.addFilterQuery('mainDirectoryName', directory);
												toaster.pop('success', 'Solr', 'TOOO add mainDirectoryName to FilterQueries');
								};

								$scope.update = function(directory) {
												$scope.finished = false;
												IntegrityRestService.update(directory).then(
																function(data) {
								            $scope.finished = true;
																				FlashMessageService.show(data.data.flashMessages);
																				$scope.show(directory);
																},
																function (data) {
								            $scope.finished = true;
																				FlashMessageService.error(data);
																}
												);
								};

								function list() {
												$scope.finished = false;
												IntegrityRestService.list().then(
																function(data) {
																				$scope.finished = true;
																				$scope.integrities = data.data.integrities;
																				$scope.view = 'list';
																},
																function (data) {
								            $scope.finished = true;
																				FlashMessageService.error(data);
																}
												);
								};

								$scope.list = function() {
												list();
								};

								list();
				}
}());
