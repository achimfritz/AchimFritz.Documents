
AjaxSolr.Manager = AjaxSolr.AbstractManager.extend({
	
	  executeRequest: function (servlet) {
	    var self = this;
	    if (this.proxyUrl) {
	      jQuery.post(this.proxyUrl, { query: this.store.string() }, function (data) { self.handleResponse(data); }, 'json');
	    }
	    else {
	    	var url = this.solrUrl + servlet + '?' + this.store.string() + '&wt=xml';
	    	if (this.debug == true ) {
	    		if (typeof window.console != 'undefined') {
	    			console.log(url);
	    		}
	    	}
	      jQuery.getJSON(this.solrUrl + servlet + '?' + this.store.string() + '&wt=json&json.wrf=?', {}, function (data) { self.handleResponse(data); });
	      
	    }
	  }
		

});
