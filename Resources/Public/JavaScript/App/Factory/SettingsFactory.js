/* global angular */

(function () {
    'use strict';

				angular
				.module('documentApp')
				.factory('SettingsFactory', SettingsFactory);

				function SettingsFactory() {
								var settings = {
												'rows': 100,
												'q': '*:*',
												'facet_limit': 20,
												'sort': 'mDateTime desc',
												'start': 0,
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
												setSetting: function(name, value) {
																settings[name] = value;
												},
												getSolrSettings: function() {
																return getSolrSettings();
												}
        };

				}
}());


