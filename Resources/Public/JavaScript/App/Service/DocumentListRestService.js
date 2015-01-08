/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.service('DocumentListRestService', DocumentListRestService);

				function DocumentListRestService($http) {

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
								this.show = function(identifier) {
												var url = 'achimfritz.documents/documentlist/?documentList[__identity]=' + identifier;
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


