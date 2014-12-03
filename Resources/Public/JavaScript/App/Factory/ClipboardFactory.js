/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.factory('ClipboardFactory', ClipboardFactory);

				function ClipboardFactory(SolrFactory) {
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

								storage.transferAll = function() {
												SolrFactory.getData().then(function(data) {
																angular.forEach(data.data.response.docs, function(val, key) {
																				var newEl = angular.copy(val);
																				newEl.selected = '';
																				docs.push(newEl);
																});
																return docs;
												});
								};
								storage.transferSelected = function() {
												SolrFactory.getData().then(function(data) {
																angular.forEach(data.data.response.docs, function(val, key) {
																				if (val.selected === 'selected') {
																								var newEl = angular.copy(val);
																								newEl.selected = '';
																								docs.push(newEl);
																				}
																});
																return docs;
												});
								};

								storage.deleteSelected = function() {
												var newDocs = [];
												angular.forEach(docs, function(val, key) {
																if (val.selected !== 'selected') {
																				newDocs.push(val);
																}
												});
												docs = newDocs;
												return docs;
								};

								storage.empty = function() {
												docs = [];
												return docs;
								};

        // Public API
        return {
												getDocs: function() {
																return storage.getDocs();
												},
												transferAll: function() {
																storage.transferAll();
																//return storage.transferAll();
												},
												transferSelected: function() {
																storage.transferSelected();
												},
												empty: function() {
																storage.empty();
												},
												deleteSelected: function() {
																storage.deleteSelected();
												}

        };

				}
}());


