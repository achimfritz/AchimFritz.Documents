/* global angular */

(function () {
    'use strict';



    angular
        .module('achimfritz.solr')
        .factory('ResultFactory', ResultFactory);

    function ResultFactory() {

        var result = {
            numFound:  0,
            docs: [],
            clipboard: []
        };

        var updateClipboard = function () {
            result.clipboard = [];
            angular.forEach(result.docs, function (doc) {
                if (doc.selected === 'selected') {
                    result.clipboard.push(doc);
                }
            });
        };

        return {
            updateResult: function(data) {
                result.numFound = data.response.numFound;
                result.docs = [];
                angular.forEach(data.response.docs, function (doc) {
                    doc.selected = '';
                    result.docs.push(doc);
                });
                result.clipboard = [];
                return result;
            },
            selectAll: function() {
                angular.forEach(result.docs, function (doc) {
                    doc.selected = 'selected';
                });
                updateClipboard();
                return result;
            },
            itemClick: function(doc, strgPressed, shiftPressed) {
                if (doc.selected === 'selected') {
                    doc.selected = '';
                } else {
                    if (strgPressed === false && shiftPressed === false) {
                        // rm all others
                        angular.forEach(result.docs, function (val, key) {
                            if (doc.identifier !== val.identifier) {
                                val.selected = '';
                            }
                        });
                    } else if (shiftPressed === true) {
                        // select all from last selected
                        var collect = false;
                        for (var i = ( result.docs.length - 1 ); i >= 0; i--) {
                            var el = result.docs[i];
                            if (el.identifier === doc.identifier) {
                                collect = true;
                            }
                            if (collect === true) {
                                if (el.selected === 'selected') {
                                    collect = false;
                                }
                                el.selected = 'selected';
                            }
                        }
                    }
                    // add always me
                    doc.selected = 'selected';
                }
                updateClipboard();
                return result;
            },
            getPrev: function (current) {
                var items = result.docs;
                var prev = false;
                var found = false;
                angular.forEach(items, function (val) {
                    if (val.identifier === current.identifier) {
                        found = true;
                    }
                    if (found === false) {
                        prev = val;
                    }
                });
                return prev;
            },

            getNext: function (current) {
                var items = result.docs;
                var next = false;
                var found = false;
                angular.forEach(items, function (val) {
                    if (found === true) {
                        next = val;
                        found = false;
                    }
                    if (val.identifier === current.identifier) {
                        found = true;
                    }
                });
                return next;
            },
            getResult: function() {
                return result;
            }
        };



    }

}());
