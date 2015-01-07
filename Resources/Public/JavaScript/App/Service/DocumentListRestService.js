/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.service('DocumentListRestService', DocumentListRestService);

				function DocumentListRestService($http) {

								this.update = function(directory) {
												var url = 'achimfritz.documents/documentlist/';
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
												var url = 'achimfritz.documents/documentlist/';
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
												//TODO
												var url = 'achimfritz.documents/documentlist/?documentList[__identity]=' + directory;
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


