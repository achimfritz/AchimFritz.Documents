/* global angular */

(function () {
    'use strict';
				angular.module('imageApp', ['solr', 'angularUtils.directives.dirPagination', 'ngDialog', 'ngAnimate', 'toaster', 'ngDraggable'])
				.config(paginationConfiguration)
				.config(toasterConfiguration)
				.config(solrConfiguration);

				/* @ngInject */
				function toasterConfiguration(toasterConfig) {
								var customConfig = {
												'position-class': 'toast-bottom-right',
												'time-out': 5000,
												'close-button': true
								};
								angular.extend(toasterConfig, customConfig);
				};

				/* @ngInject */
				function paginationConfiguration(paginationTemplateProvider) {
								paginationTemplateProvider.setPath('/_Resources/Static/Packages/AchimFritz.Documents/bower_components/angular-utils-pagination/dirPagination.tpl.html');
				};

				function solrConfiguration(SolrProvider) {
								SolrProvider.setFacets(['hCategories', 'hPaths', 'hLocations']);
								SolrProvider.setHFacets({
												'hPaths': '0',
												'hCategories': '1/categories',
												'hLocations': '1/locations'
								});
								SolrProvider.setSetting('rows', 10);
				};

}());
