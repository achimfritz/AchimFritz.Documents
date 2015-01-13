/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.service('ExportRestService', ExportRestService);

				function ExportRestService($http) {

								this.export = function(name, useThumb, useFullPath, docs) {
												var url = 'achimfritz.documents/export/';
												var documents = [];
												angular.forEach(docs, function (val, key) {
																documents.push(val.identifier);
												});

												var data = {
																'documentExport':{
																				'name': name,
																				'useThumb': useThumb,
																				'useFullPath': useFullPath,
																				'documents': documents
																}
												};

												return $http({
																method: 'POST',
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


