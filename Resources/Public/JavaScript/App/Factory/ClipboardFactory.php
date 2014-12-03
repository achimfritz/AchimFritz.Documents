/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.factory('DocumentFactory', DocumentFactory);

				function DocumentFactory() {
								var docs = [];
								var storage = {};

								storage.getDocs = function() {
												return docs;
								};

								storage.setDocs = function (setDocs) {
												docs = setDocs;
								};

								storage.addDoc = function (doc) {
												var newDoc = angular.copy(doc);
												newDoc.selected = '';
												docs.push(newDoc);
								};
								storage.rmDoc = function (doc) {
												for (var i = 0; i < docs.length; i++) {
																var current = docs[i];
																if (current.identifier === doc.identifier) {
																				docs.splice(i,1);
																}
												}
								};

        // Public API
        return {
												getDocs: function() {
																return storage.getDocs();
												},
												setDocs: function(docs) {
																storage.setDocs(docs);
												},
												addDoc: function(doc) {
																storage.addDoc(doc);
												},
												rmDoc: function(doc) {
																storage.rmDoc(doc);
												}
        };

				}
}());


