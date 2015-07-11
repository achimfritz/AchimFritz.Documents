/* global angular */

(function () {
    'use strict';
    angular.module('documentApp', ['solr', 'angularUtils.directives.dirPagination']).config(solrConfiguration);

    /* @ngInject */
    function solrConfiguration(SolrProvider) {
        var solrSettingsDiv = jQuery('#solrSettings');
        if (solrSettingsDiv.length) {
            var solrSettings = solrSettingsDiv.data('solrsettings');
            SolrProvider.setSolrSetting('servlet', solrSettings.servlet);
            SolrProvider.setSolrSetting('solrUrl', 'http://' + solrSettings.hostname + ':' + solrSettings.port + '/' + solrSettings.path + '/');
        }
    }
}());
