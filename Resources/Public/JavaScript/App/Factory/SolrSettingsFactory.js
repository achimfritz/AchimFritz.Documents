/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.factory('SolrSettingsFactory', SolrSettingsFactory);

				function SolrSettingsFactory(SolrFactory) {
								var settings = {
												'rows': 10,
												'q': '*:*',
												'facet.limit': 5,
												'sort': 'fileName asc',
												'facet.field': [
																'mainDirectoryName',
																'navigation'
												]
												
								};

        // Public API
        return {
												getSettings: function() {
																return settings;
												}
        };

				}
}());


