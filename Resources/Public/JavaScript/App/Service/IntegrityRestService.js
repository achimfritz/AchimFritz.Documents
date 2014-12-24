/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.service('IntegrityRestService', IntegrityRestService);

				function IntegrityRestService($http) {

								this.update = function(directory) {
												var url = 'achimfritz.documents/imageintegrity/';
												var data = {
															'directory': directory	
												};
												return $http({
																method: 'PUT',
																url: url,
																data: data,
																headers: {
																				'Content-Type': 'application/json',
																				'Accept': 'application/json'
																}
												})
								};
								this.list = function() {
												var url = 'achimfritz.documents/imageintegrity/';
												return $http({
																method: 'GET',
																url: url,
																headers: {
																				'Content-Type': 'application/json',
																				'Accept': 'application/json'
																}
												})
								};
								this.show = function(directory) {
												var url = 'achimfritz.documents/imageintegrity/?directory=' + directory;
												return $http({
																method: 'GET',
																url: url,
																headers: {
																				'Content-Type': 'application/json',
																				'Accept': 'application/json'
																}
												})
								};
				}
}());


