(function (ng) {
  'use strict';

  var solr = ng.module('solstice', []);

  /* Solr search service */
  solr.provider('Solstice', function () {
    var defEndpoint = '';
				var manager = null;
    return {
      setEndpoint: function (url) {
								manager = new AjaxSolr.Manager({
												solrUrl: url,
												servlet: 'select',
												debug: true
								});
								manager.init();
								console.log('setEndpoint');
        defEndpoint = url;
      },
      $get: function ($http) {
        function Solstice(endpoint) {
												this.solrSearch = function() {
																manager.store.addByValue('q', '*:*');
																		var t1 =	manager.buildUrl();
																		//console.log(t1);
																return $http.jsonp(t1);
												},
          this.search = function(options) {
												console.log('search');
            var url = endpoint + '/select/';
            var defaults = {
              wt: 'json',
              'json.wrf': 'JSON_CALLBACK'
            };
            ng.extend(defaults, options);
            return $http.jsonp(url, {
              params: defaults
            });
          };
          this.withEndpoint = function (url) {
            return new Solstice(url);
          };
        }
        return new Solstice(defEndpoint);
      }
    };
  });
})(window.angular);
