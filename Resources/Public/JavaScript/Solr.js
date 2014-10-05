         var Manager;
         (function ($) {
         
         
            $(function () {
					if (AchimFritz.App.isSolr()) {
						// TODO global
						var settings = AchimFritz.App.settings();
			
						Manager = new AjaxSolr.Manager({
							solrUrl: 'http://' + settings.Solr.hostname + ':' + settings.Solr.port + '/' + settings.Solr.path + '/',
							servlet: settings.Solr.servlet,
							debug: true
						});

						var params = {
								'facet': true,
								'facet.field': ['navigation', 'paths'],
								'facet.mincount': 1,
								'json.nl': 'map',
								'f.paths.facet.prefix': '0',
								'f.navigation.facet.prefix': '0',
						};

						var widget = new AjaxSolr.PathWidget({
							id: 'paths',
							target: '#paths',
							field: 'paths',
						});
						Manager.addWidget(widget);
						var widget = new AjaxSolr.PathWidget({
							id: 'navigation',
							target: '#navigation',
							field: 'navigation',
						});
						Manager.addWidget(widget);
						Manager.addWidget(new AjaxSolr.ResultWidget({'id': 'docs', 'target': '#docs'}));
						Manager.addWidget(new AjaxSolr.CurrentSearchWidget({'id': 'currentSearch', 'target': '#currentSearch'}));
						Manager.addWidget(new AjaxSolr.PagerWidget({'id': 'pager', 'target': '#pager'}));
						Manager.addWidget(new AjaxSolr.QueryWidget({'id': 'query', 'target': '#query'}));
						Manager.init();
						for (var name in params) {
							Manager.store.addByValue(name, params[name]);
						}
						Manager.doRequest();
					}

            });
   
         })(jQuery);

