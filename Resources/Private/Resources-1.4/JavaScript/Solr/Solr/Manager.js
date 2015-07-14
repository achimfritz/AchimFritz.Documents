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
