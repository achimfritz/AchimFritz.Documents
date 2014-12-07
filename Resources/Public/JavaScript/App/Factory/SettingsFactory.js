/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.factory('SettingsFactory', SettingsFactory);

				function SettingsFactory() {
								var settings = {
												'rows': 10,
												'q': '*:*',
												'facet_limit': 5,
												'sort': 'fileName asc'
								};

								var getSolrSettings = function() {
												var res = {};
												angular.forEach(settings, function (val, key) {
																var a = key.replace('_', '.');
																res[a] = val;

												});
												return res;
								};

        // Public API
        return {
												getSettings: function() {
																return settings;
												},
												getSolrSettings: function() {
																return getSolrSettings();
												}
        };

				}
}());


