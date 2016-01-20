/* global angular */

(function () {
    'use strict';

    angular
        .module('achimfritz.solr')
        .service('HPathService', HPathService);

    function HPathService(PathService) {

        var self = this;

        var data =  {};


        self.getData = getData;
        self.setData = setData;
        self.setDataByIndex = setDataByIndex;
        self.initData = initData;
        self.update = update;

        initData();

        function initData() {
            data =  {
                0: {},
                1: {},
                2: {},
                3: {}
            };
        }

        function update(path, results) {
            if (PathService.depth(path) === 1) {
                setDataByIndex(0, results);
                setDataByIndex(1, {});
                setDataByIndex(2, {});

            } else if (PathService.depth(path) === 2) {
                setDataByIndex(1, results);
                setDataByIndex(2, {});
            } else if (PathService.depth(path) === 3) {
                setDataByIndex(2, results);
            } else if (PathService.depth(path) > 3) {

            } else {
                setDataByIndex(1, {});
                setDataByIndex(2, {});
            }
        }

        function setDataByIndex(index, value) {
            data[index] = value;
        }

        function getData() {
            return data;
        }

        function setData(newData) {
            data = newData;
            //$rootScope.$broadcast('solrDataUpdate', data);
        }

    }
}());
