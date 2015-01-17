/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.service('ExportRestService', ExportRestService);

				function ExportRestService($http) {

								this.zip = function() {
												var zip = {
																'name': 'download',
																'useThumb': false,
																'useFullPath': false,
																'docs': []
												};
												return zip;
								};


								this.pdf = function() {
												var pdf = {
																'columns': 3,
																'size': 4,
																'dpi': 72,
																'docs': []
												};
												return pdf;
								};

								this.zipDownload = function(name, useThumb, useFullPath, docs) {
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
																				'Accept': 'application/zip'
																}
												})
								};
				}
}());


