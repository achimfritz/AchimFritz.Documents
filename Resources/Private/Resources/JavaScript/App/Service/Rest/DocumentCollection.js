/* global angular */

(function () {
    'use strict';

				angular
				.module('app')
				.service('DocumentCollectionRestService', DocumentCollectionRestService);

				function DocumentCollectionRestService($http) {

								var buildRequest = function(category, docs) {
												var documents = [];
												angular.forEach(docs, function (val, key) {
																documents.push(val.identifier);
												});

												var data = {
																'documentCollection':{
																				'category': {
																								'path': category
																				},
																				'documents': documents
																}
												};
												return data;
								};

								this.remove= function(category, docs) {
												var url = 'achimfritz.documents/documentcollectionremove/';
												var data = buildRequest(category, docs);
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
								this.merge = function(category, docs) {
												var url = 'achimfritz.documents/documentcollectionmerge/';
												var data = buildRequest(category, docs);
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


