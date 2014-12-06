/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.factory('SolrSettingsFactory', SolrSettingsFactory);

				function SolrSettingsFactory() {
								var settings = {
												'rows': 10,
												'q': '*:*',
												'facet.limit': 5,
												'sort': 'fileName asc'
								};

        // Public API
        return {
												getSettings: function() {
																return settings;
												}
        };

				}
}());


