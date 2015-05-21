/* global angular */

(function () {
    'use strict';

				angular
				.module('imageApp')
				.service('JobRestService', JobRestService);

				function JobRestService($http) {

								this.list = function() {
												var url = 'achimfritz.documents/job/';
												return $http({
																method: 'GET',
																url: url,
																headers: {
																				'Content-Type': 'application/json',
																				'Accept': 'application/json'
																}
												})
								};
								this.show = function(identifier) {
												var url = 'achimfritz.documents/job/?job[__identity]=' + identifier;
												return $http({
																method: 'GET',
																url: url,
																headers: {
																				'Content-Type': 'application/json',
																				'Accept': 'application/json'
																}
												})
								};
								this.create = function(job) {
												var url = 'achimfritz.documents/job/';
												return $http({
																method: 'POST',
																data: {'job': job},
																url: url,
																headers: {
																				'Content-Type': 'application/json',
																				'Accept': 'application/json'
																}
												})
								};
				}
}());


