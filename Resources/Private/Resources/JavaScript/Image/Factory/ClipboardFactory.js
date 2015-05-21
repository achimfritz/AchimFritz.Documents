/* global angular */

(function () {
    'use strict';

				angular
				.module('imageApp')
				.factory('ClipboardFactory', ClipboardFactory);

				function ClipboardFactory() {

								var docs = [];
								var solrDocs = [];

								var hasDoc = function(doc) {
												var ret = false;
												angular.forEach(docs, function(itDoc) {
																if (itDoc.identifier === doc.identifier) {
																				ret = true;
																}
												});
												return ret;
								}

								var transferAll = function() {
												if (solrDocs.length > 0) {
																angular.forEach(solrDocs, function(val, key) {
																				if (hasDoc(val) === false) {
																								var newEl = angular.copy(val);
																								newEl.selected = '';
																								docs.push(newEl);
																				}
																});
												}
								};

								var countDocs = function() {
												return docs.length;
								};

								var getDocs = function() {
												return docs;
								};

								var transferSelected = function() {
												angular.forEach(solrDocs, function(val, key) {
																if (val.selected === 'selected') {
																				if (hasDoc(val) === false) {
																								var newEl = angular.copy(val);
																								newEl.selected = '';
																								docs.push(newEl);
																				}
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
												countDocs: function() {
																return countDocs();
												},
												getSolrDocs: function() {
																return solrDocs;
												},
												getDocs: function() {
																return getDocs();
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
												setCurrentPage: function(newCurrentPage) {
																currentPage = newCurrentPage;
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


