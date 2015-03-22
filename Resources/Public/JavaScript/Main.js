/* global angular */

(function () {
    'use strict';
				angular.module('solrApp', []);
}());

/* global angular */

(function () {
    'use strict';

				angular
				.module('solrApp')
				.controller('SearchController', SearchController);

				function SearchController($scope, Solr) {

								$scope.settings = Solr.getSettings();
								$scope.facets = Solr.getFacets();
								$scope.filterQueries = Solr.getFilterQueries();
								$scope.search = '';

								$scope.rmFilterQuery = function (name, value) {
												Solr.rmFilterQuery(name, value);
												update();
								};

								$scope.addFilterQuery = function(name, value) {
												Solr.addFilterQuery(name, value);
												update();
								};

								$scope.update = function(search) {
												update(search);
								};

								update();

								function update(search) {
												if (search !== undefined) {
																if (search !== '') {
																				Solr.setSetting('q', search);
																} else {
																				Solr.setSetting('q', '*:*');
																}
												}
												Solr.getData().then(function(data) {
																$scope.data = data.data;
												});
								};

				}
				SearchController.$inject = ['$scope', 'Solr'];
}());

/* global angular */

(function () {
    'use strict';

				angular
				.module('solrApp')
				.directive('autocomplete', Autocomplete);

				function Autocomplete(Solr) {

								return {

												scope: {
												},

												link: function(scope, element, attr) {
																$(element).autocomplete({
																				source: function( request, response ) {
																								var item = request.term;
																								Solr.getAutocomplete(item, 'spell').then(function(data) {
																												var q = data.data.responseHeader.params.q;
																												response($.map(data.data.facet_counts.facet_fields.spell, function( val , key) {
																																var name = key;
																																var label = name + ' (' + val + ')';
																																var value = name;
																																if (q !== '*:*') {
																																				label = q + ' ' + label;
																																				value = q + ' ' + value;
																																}
																																return {
																																			label: label,
																																			value: value
																																};
																												}));
																								});
																				}
																});
												}

								};
				}
				Autocomplete.$inject = ['Solr'];
}());

/* global angular */

(function () {
    'use strict';

				angular
				.module('solrApp')
				.directive('filterQuery', FilterQuery);

				function FilterQuery(Solr, PathService) {
								return {
												restrict: 'E',
												link: function(scope, element, attr) {
																if (Solr.isHFacet(attr.facet)) {
																				var hFacet = Solr.getHFacet(attr.facet);
																				var depth = PathService.depth(hFacet);
																				element.replaceWith(PathService.slice(attr.path, depth));
																} else {
																				element.replaceWith(attr.path);
																}
												}
								};
				}
				FilterQuery.$inject = ['Solr', 'PathService'];
}());

/* global angular */

(function () {
    'use strict';

				angular
				.module('solrApp')
				.directive('navPath', NavPath);

				function NavPath(Solr, PathService) {
								return {
												restrict: 'E',
												link: function(scope, element, attr) {
																if (Solr.isHFacet(attr.facet)) {
																				element.replaceWith(PathService.last(attr.path));
																} else {
																				element.replaceWith(attr.path);
																}
												}
								};
				}
				NavPath.$inject = ['Solr', 'PathService'];
}());

/* global angular */

(function () {
    'use strict';

				angular
				.module('solrApp')
				.provider('Solr', Solr);

				function Solr() {

								var solrSettings = {
												'solrUrl': 'http://localhost:8080/solr4/documents/',
												'servlet': 'select',
												'debug': true
								};
								var settings = {
												'rows': 10,
												'q': '*:*',
												'facet_limit': 5,
												'sort': 'mDateTime desc',
												'start': 0,
												'facet': true,
												'json.nl': 'map',
												'facet_mincount': 1

								};
								var facets = ['hCategories', 'hPaths'];
								var hFacets = {
												'hPaths': '0',
												'hCategories': '1/categories'
								};

								var facetPrefixes = {};
								var filterQueries = {};
								var manager = new AjaxSolr.Manager(solrSettings);

								this.setFacets = function (newFacets) {
												facets = newFacets;
								};
								this.setHFacets = function (newHFacets) {
												hFacets = newHFacets;
								};
								this.setSolrSetting = function(name, value) {
												solrSettings[name] = value;
								};
								this.setSetting = function(name, value) {
												settings[name] = value;
								};

								var getSolrSettings = function() {
												var res = {};
												angular.forEach(settings, function (val, key) {
																var a = key.replace('_', '.');
																res[a] = val;

												});
												return res;
								};

								var getAutocomplete = function(search, searchField, $q, $http) {
												var defer = $q.defer();
												buildSolrValues();
												manager.store.addByValue('rows', 0);
												var words = search.split(' ');
												var lastWord = words.pop();
												var searchWord = words.join(' ');
												if (searchWord !== '') {
																manager.store.addByValue('q', searchWord);
												} else {
																manager.store.addByValue('q', '*:*');
												}
												manager.store.addByValue('f.' + searchField + '.facet.prefix', lastWord);
												manager.store.addByValue('facet.field', searchField);
												var url = manager.buildUrl();
												manager.store.removeByValue('f.' + searchField + '.facet.prefix', lastWord);
												manager.store.removeByValue('facet.field', searchField);
												$http.jsonp(url).then(function(data) {
																defer.resolve(data);
												});
												return defer.promise;
								};

								var getData = function($q, $http) {
												var defer = $q.defer();
												buildSolrValues();

												var url = manager.buildUrl();
												$http.jsonp(url).then(function(data) {
																defer.resolve(data);
												});
												return defer.promise;
								};

								var isHFacet = function(name) {
												if (hFacets[name] !== undefined) {
																return true;
												} else {
																return false;
												}
								};

								var buildSolrValues = function() {

												// settings
												var solrSettings = getSolrSettings();
												angular.forEach(solrSettings, function(val, key) {
																manager.store.addByValue(key, val);
												});

												// remove all fq
												manager.store.remove('fq');

												// fq
												angular.forEach(filterQueries, function(values, key) {
																angular.forEach(values, function(value) {
																				manager.store.addByValue('fq', key + ':' + value);
																});
												});

												// facet.prefix
												angular.forEach(facetPrefixes, function(val, key) {
																manager.store.addByValue('f.' + key + '.facet.prefix', val);
												});
								};


								var init = function() {
												manager.init();
												angular.forEach(facets, function(val) {
																manager.store.addByValue('facet.field', val);
												});
												angular.forEach(hFacets, function(val, key) {
																manager.store.addByValue('f.' + key + '.facet.prefix', val);
												});
												buildSolrValues();
								};


        // Public API
								this.$get = ['$http', '$q', 'PathService', function($http, $q, PathService) {
												init();
												return {
																getSettings: function() {
																				return settings;
																},
																getSetting: function(name) {
																				return settings[name];
																},
																setSetting: function(name, value) {
																				settings[name] = value;
																},
																getSolrSettings: function() {
																				return getSolrSettings();
																},
																getAutocomplete: function(search, searchField) {
																				return getAutocomplete(search, searchField, $q, $http);
																},
																getData: function() {
																				return getData($q, $http);
																},
																getFilterQueries: function() {
																				return filterQueries;
																},
																isHFacet: function(name) {
																				return isHFacet(name);
																},
																getHFacet: function(name) {
																				return hFacets[name];
																},
																addFilterQuery: function(name, value) {
																				if (filterQueries[name] === undefined) {
																								filterQueries[name] = [];
																				}
																				if (isHFacet(name) === true) {
																								facetPrefixes[name] = PathService.increase(value);
																								filterQueries[name] = [];
																				}
																				filterQueries[name].push(value);
																},
																rmFilterQuery: function(name, value) {
																				if (isHFacet(name) === true) {
																								filterQueries[name] = [];
																								var fq = PathService.decreaseFq(value);
																								if (fq !== '' && PathService.decreaseLevel(hFacets[name]) !== fq) {
																												filterQueries[name].push(fq);
																								}
																								facetPrefixes[name] = PathService.decrease(value);
																				} else {
																								var index = filterQueries[name].indexOf(value);
																								filterQueries[name].splice(index, 1);
																				}
																},
																getFacets: function() {
																				return facets;
																}
												}
								}];

				}
}());



/* global angular */

(function () {
    'use strict';

				angular
				.module('solrApp')
				.service('PathService', PathService);

				function PathService() {

								this.delimiter = '/';

								// 0/foo -> 2
								this.depth = function(path) {
												var splitPath = path.split(this.delimiter);
												return splitPath.length;
								};

								// 0/foo/bar, 1  -> foo/bar
								// 0/foo/bar, 2  -> bar
								this.slice = function(path, depth) {
												var splitPath = path.split(this.delimiter);
												var sliced = splitPath.slice(depth);
												return sliced.join(this.delimiter);
								};

								// 0/foo -> 1/foo
								this.increase = function(path) {
												var splitPath = path.split(this.delimiter);
												var level = splitPath.shift();
												level ++;
												return level + this.delimiter + splitPath.join(this.delimiter);
								};

								// 0/foo -> 0
								this.decrease = function(path) {
												var splitPath = path.split(this.delimiter);
												var last = splitPath.pop();
												return splitPath.join(this.delimiter);
								};

								// 1/foo -> 0/foo
								this.decreaseLevel = function(path) {
												var splitPath = path.split(this.delimiter);
												var level = splitPath.shift();
												level --;
												return level + this.delimiter + splitPath.join(this.delimiter);
								};

								// 0/foo -> foo
								this.last = function(path) {
												var splitPath = path.split(this.delimiter);
												var last = splitPath.pop();
												return last;
								};

								// 2/foo/bar -> 1/foo
								// 0/foo -> ''
								this.decreaseFq = function(path) {
												var splitPath = path.split(this.delimiter);
												var last = splitPath.pop();
												var level = parseInt(splitPath.shift());
												if (level === 0) {
																return '';
												}
												level--;
												return level + this.delimiter + splitPath.join(this.delimiter);
								};

				}
}());




AjaxSolr.Manager = AjaxSolr.AbstractManager.extend({

				debug: false,

				buildUrl: function () {
								var url = this.solrUrl + this.servlet + '?' + this.store.string() + '&wt=json&json.wrf=JSON_CALLBACK';
								if (this.debug === true) {
												console.log(url);
								}
								return url;
				}

});

/* global angular */

(function () {
    'use strict';
				angular.module('imageApp', ['solrApp', 'angularUtils.directives.dirPagination', 'ngDialog', 'ngAnimate', 'toaster', 'ngDraggable'])
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
				}
				toasterConfiguration.$inject = ['toasterConfig'];;

				/* @ngInject */
				function paginationConfiguration(paginationTemplateProvider) {
								paginationTemplateProvider.setPath('/_Resources/Static/Packages/AchimFritz.Documents/Resources/Public/bower_components/angular-utils-pagination/dirPagination.tpl.html');
				}
				paginationConfiguration.$inject = ['paginationTemplateProvider'];;

				function solrConfiguration(SolrProvider) {
								SolrProvider.setFacets(['hCategories', 'hPaths', 'hLocations']);
								SolrProvider.setHFacets({
												'hPaths': '0',
												'hCategories': '1/categories',
												'hLocations': '1/locations'
								});
								SolrProvider.setSetting('rows', 10);
				}
				solrConfiguration.$inject = ['SolrProvider'];;

}());

/* global angular */

(function () {
    'use strict';

				angular
				.module('imageApp')
				.controller('IndexController', IndexController);

				function IndexController($scope, Solr) {

								$scope.settings = Solr.getSettings();
								$scope.facets = Solr.getFacets();
								$scope.filterQueries = Solr.getFilterQueries();
								$scope.search = '';

								$scope.rmFilterQuery = function (name, value) {
												Solr.rmFilterQuery(name, value);
												update();
								};

								$scope.addFilterQuery = function(name, value) {
												Solr.addFilterQuery(name, value);
												update();
								};

								$scope.update = function(search) {
												update(search);
								};

								update();

								function update(search) {
												if (search !== undefined) {
																if (search !== '') {
																				Solr.setSetting('q', search);
																} else {
																				Solr.setSetting('q', '*:*');
																}
												}
												Solr.getData().then(function(data) {
																$scope.data = data.data;
												});
								};

				}
				IndexController.$inject = ['$scope', 'Solr'];
}());

/* global angular */

(function () {
    'use strict';

				angular
				.module('imageApp')
				.directive('isoContainer', IsoContainer);

				function IsoContainer($timeout, ngDialog, ItemService, RestService, FlashMessageService) {

								return {

												scope: {
																items: '=isoContainer',
																total: '@',
																currentPage: '@',
																itemsPerPage: '@'
												},

												templateUrl: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/ImageApp/Partials/Docs.html',

												

											link: function(scope, element, attr) {
																scope.mode = 'view';
																scope.finished = true;
																scope.current = {};

																jQuery(document).keydown(function(e) {
																				if (e.keyCode == 39) {
																								// next
																								if (scope.current.identifier) {
																												scope.next();
																								}
																				} else if (e.keyCode == 37) {
																								// prev
																								if (scope.current.identifier) {
																												scope.prev();
																								}
																				} else if (e.keyCode == 27) {
																								// close
																								ngDialog.close();
																				}
																});
																				
																var options = {
																				itemSelector: '.iso-item',
																				layoutMode: 'fitRows'
																};
																element.isotope(options);

																scope.$watch('items', function(newVal, oldVal){
																			$timeout(function(){
																								element.isotope('reloadItems').isotope(options);
																			}, 500);
																},true);

																scope.addTag = function() {
																				var tag = jQuery('#addTag').val();
																				var docs = [];
																				docs.push(scope.current);
																				scope.finished = false;
																				RestService.merge('tags/' + tag, docs).then(function(data) {
																								scope.finished = true;
																								FlashMessageService.show(data.data.flashMessages);
																				});
																};

																scope.nextPage = function(pageNumber) {
																				scope.$parent.pageChanged(pageNumber);
																};

																scope.prev = function() {
																				var current = ItemService.getPrev(scope.current, scope.items);
																				if (current.identifier) {
																								scope.current = current;
																								ngDialog.close();
																								ngDialog.open({
																												template: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/App/Partials/Dialog.html',
																												scope: scope
																								});
																				}
																};

																scope.next = function() {
																				var current = ItemService.getNext(scope.current, scope.items);
																				if (current.identifier) {
																								scope.current = current;
																								ngDialog.close();
																								ngDialog.open({
																												template: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/App/Partials/Dialog.html',
																												scope: scope
																								});
																				}
																};


																scope.itemClick = function(item) {
																				var items = scope.items;
																				if (scope.mode === 'select') {
																								ItemService.itemClick(item, scope.items);
																				} else { // mode = view
																								scope.current = item;
																								ngDialog.close();
																								ngDialog.open({
																												template: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/App/Partials/Dialog.html',
																												scope: scope
																								});
																				}
																};
												},

								};

				}
				IsoContainer.$inject = ['$timeout', 'ngDialog', 'ItemService', 'RestService', 'FlashMessageService'];
}());

/* global angular */

(function () {
    'use strict';
    angular
        .module('imageApp')
        .directive('overlay', Overlay);

    /* @nInject */
    function Overlay() {
        return {
            restrict: 'E',
            template: '<div data-ng-class="{false:\'overlay\'}[finished]"></div>',
            scope: {
                finished: '='
            }
        };
    }
}());


/* global angular */

(function () {
    'use strict';
    angular
        .module('imageApp')
        .directive('spinner', Spinner);

    /* @ngInject */
    function Spinner() {

        return {
            restrict: 'E',
												templateUrl: '/_Resources/Static/Packages/AchimFritz.Documents/JavaScript/ImageApp/Partials/Spinner.html?xx'
        };
    }
}());

/* global angular */

(function () {
    'use strict';

				angular
				.module('imageApp')
				.service('FlashMessageService', FlashMessageService);

				function FlashMessageService(toaster) {

								this.show = function(flashMessages) {
												angular.forEach(flashMessages, function(flashMessage) {
																var severity = 'error';
																var title = flashMessage.severity;
																if (flashMessage.title !== '') {
																				title = flashMessage.title;
																}	
																if (flashMessage.severity === 'OK') {
																				severity = 'success';
																}
																toaster.pop(severity, title, flashMessage.message);
												});
								};

								this.error = function(data) {
												if (data.data.flashMessages !== undefined) {
																this.show(data.data.flashMessages);
												} else {
																var severity = 'error';
																toaster.pop(severity, data.status, data.data);
												}
								};

				}
				FlashMessageService.$inject = ['toaster'];
}());



/* global angular */

(function () {
    'use strict';

				angular
				.module('imageApp')
				.service('ItemService', ItemService);

				function ItemService() {

								var strgPressed = false;
								var shiftPressed = false;

								jQuery(document).keyup(function(e) {
												shiftPressed = false;
												strgPressed = false;
								});
								jQuery(document).keydown(function(e) {
												if (e.keyCode == 16) {
																shiftPressed = true;
												} else if (e.keyCode == 17) {
																strgPressed = true;
												}
								});

								this.getPrev = function(current, items) {
												var prev = {};
												var found = false;
												angular.forEach(items, function(val) {
																if (val.identifier === current.identifier) {
																				found = true;
																}
																if (found === false) {
																				prev = val;
																}
												});
												return prev;																													
								};
												
								this.getNext = function(current, items) {
												var next = {};
												var found = false;
												angular.forEach(items, function(val) {
																if (found === true) {
																				next = val;
																				found = false;
																}
																if (val.identifier === current.identifier) {
																				found = true;
																}
												});
												return next;																													
								};
												
								this.itemClick = function(item, items) {
												if (item.selected === 'selected') {
																item.selected = '';
												} else {
																if (strgPressed === false && shiftPressed === false) {
																				// rm all others
																				angular.forEach(items, function(val, key) {
																								if (item.identifier !== val.identifier) {
																												val.selected = '';
																								}
																				});
																} else if (shiftPressed === true) {
																				// select all from last selected
																				var collect = false;
																				for (var i = ( items.length - 1 ); i >= 0; i--) {
																								var el = items[i];
																								if (el.identifier === item.identifier) {
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
																item.selected = 'selected';
												}
								};
				}
}());

/* global angular */

(function () {
    'use strict';

				angular
				.module('imageApp')
				.service('RestService', RestService);

				function RestService($http) {

								var buildRequest = function(category, docs) {
												var documents = [];
												angular.forEach(docs, function (val, key) {
																documents.push(val.identifier);
												});

												var data = {
																'documentCollection':{
																				'category': {
																								'path': category
																				},
																				'documents': documents
																}
												};
												return data;
								};

								this.remove= function(category, docs) {
												var url = 'achimfritz.documents/documentcollectionremove/';
												var data = buildRequest(category, docs);
												return $http({
																method: 'POST',
																url: url,
																data: data,
																headers: {
																				'Content-Type': 'application/json',
																				'Accept': 'application/json'
																}
												})
								};
								this.merge = function(category, docs) {
												var url = 'achimfritz.documents/documentcollectionmerge/';
												var data = buildRequest(category, docs);
												return $http({
																method: 'POST',
																url: url,
																data: data,
																headers: {
																				'Content-Type': 'application/json',
																				'Accept': 'application/json'
																}
												})
								};

				}
				RestService.$inject = ['$http'];
}());


