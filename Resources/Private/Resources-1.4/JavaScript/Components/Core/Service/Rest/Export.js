/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.core')
        .service('ExportRestService', ExportRestService);

    function ExportRestService($http) {

        this.pdfDownload = function (pdf, docs) {
            var url = 'achimfritz.documents/pdfexport/';
            var documents = [];
            angular.forEach(docs, function (val, key) {
                documents.push(val.identifier);
            });

            var data = {
                'pdfExport': {
                    'columns': pdf.columns,
                    'size': pdf.size,
                    'dpi': pdf.dip,
                    'documents': documents
                }
            };

            return $http({
                method: 'POST',
                url: url,
                data: data,
                responseType: 'arraybuffer',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/pdf'
                }
            })
        };

        this.zipDownload = function (zip, docs) {
            var url = 'achimfritz.documents/documentexport/';
            var documents = [];
            angular.forEach(docs, function (val, key) {
                documents.push(val.identifier);
            });

            var data = {
                'documentExport': {
                    'name': zip.name,
                    'useThumb': zip.useThumb,
                    'useFullPath': zip.useFullPath,
                    'documents': documents
                }
            };

            return $http({
                method: 'POST',
                url: url,
                data: data,
                responseType: 'arraybuffer',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/zip'
                }
            })
        };
    }
}());


