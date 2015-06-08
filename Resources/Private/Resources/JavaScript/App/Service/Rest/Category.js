/* global angular */

(function () {
    'use strict';

				angular
				.module('app')
				.service('CategoryRestService', CategoryRestService);

				function CategoryRestService($http) {

								this.update = function(renameCategory) {
												var url = 'achimfritz.documents/renamecategory/';
												return $http({
																method: 'PUT',
																url: url,
																data: {'renameCategory': renameCategory},
																headers: {
																				'Content-Type': 'application/json',
																				'Accept': 'application/json'
																}
												})
								};
				}
}());


