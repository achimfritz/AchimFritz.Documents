/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.service('RestService', RestService);

				function RestService($http) {

								this.merge = function(category, docs) {

												var url = 'achimfritz.documents/documentcollectionmerge/';
												var data = {};
												data {
																'category' => category

												};
												return $http({
																method: 'GET',
																url: url,
																data: data,
																headers: {
																				'Content-Type': 'application/json',
																				'Accept': 'application/json'
																}
												})
								};

				}
}());


