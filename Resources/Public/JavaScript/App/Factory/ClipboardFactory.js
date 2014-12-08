/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.factory('ClipboardFactory', ClipboardFactory);

				function ClipboardFactory(SolrFactory) {
								var docs = [];
								var solrDocs = [];

								var transferAll = function() {
												if (solrDocs.length > 0) {
																angular.forEach(solrDocs, function(val, key) {
																				var newEl = angular.copy(val);
																				newEl.selected = '';
																				docs.push(newEl);
																});
																return docs;
												} else {
																SolrFactory.getData().then(function(data) {
																				angular.forEach(data.data.response.docs, function(val, key) {
																								var newEl = angular.copy(val);
																								newEl.selected = '';
																								docs.push(newEl);
																				});
																				return docs;
																});
												}
								};

								var transferSelected = function() {
												angular.forEach(solrDocs, function(val, key) {
																if (val.selected === 'selected') {
																				var newEl = angular.copy(val);
																				newEl.selected = '';
																				docs.push(newEl);
																}
												});
												return docs;
								};

								var deleteSelected = function() {
												var newDocs = [];
												angular.forEach(docs, function(val, key) {
																if (val.selected !== 'selected') {
																				newDocs.push(val);
																}
												});
												docs = newDocs;
												return docs;
								};

        // Public API
        return {
												getDocs: function() {
																return docs;
												},
												setSolrDocs: function(newSolrDocs) {
																solrDocs = newSolrDocs;
												},
												transferAll: function() {
																transferAll();
												},
												transferSelected: function() {
																transferSelected();
												},
												empty: function() {
																docs = [];
												},
												deleteSelected: function() {
																deleteSelected();
												}

        };

				}
}());


