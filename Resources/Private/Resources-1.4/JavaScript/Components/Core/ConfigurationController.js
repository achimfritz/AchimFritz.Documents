/* global angular */
(function () {
    'use strict';
    angular
        .module('achimfritz.core')
        .controller('ConfigurationController', ConfigurationController);

    /* @ngInject */
    function ConfigurationController (SolrConfiguration, AppConfiguration) {

        var vm = this;

        // not used by the view
        vm.initController = initController;

        vm.initController();

        function initController() {
            var frontendConfigurationDiv = jQuery('#frontendConfiguration');
            if (frontendConfigurationDiv.length) {
                var frontendConfiguration = frontendConfigurationDiv.data('frontendconfiguration');
                var solrSettings = frontendConfiguration.solrSettings;
                SolrConfiguration.setSetting('solrUrl', 'http://' + solrSettings.hostname + ':' + solrSettings.port + '/' + solrSettings.path + '/');
                AppConfiguration.setApplicationRoot(frontendConfiguration.applicationRoot);
            }
        }

    }
})();
