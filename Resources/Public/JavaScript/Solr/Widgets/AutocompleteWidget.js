(function ($) {

AjaxSolr.AutocompleteWidget = AjaxSolr.AbstractTextWidget.extend({
	
  afterRequest: function () {
	console.log('afterRequest');
    $(this.target).find('input').unbind().removeData('events').val('');

    var self = this;

    var list = [];
    for (var i = 0; i < this.fields.length; i++) {
      var field = this.fields[i];
      for (var facet in this.manager.response.facet_counts.facet_fields[field]) {
        list.push({
          field: field,
          value: facet,
          label: facet + ' (' + this.manager.response.facet_counts.facet_fields[field][facet] + ') - ' + field
        });
      }
    }
    
    /*
     * http://a1:8080/solr2/core_de/select?facet=on&facet.mincount=1&facet.limit=10&q.alt=*:*&json.nl=map&wt=xml&omitHeader=true&fl=spell&start=0&q=&facet.prefix=app&facet.field=spell&fq={!typo3access}-1,0&fq=%28endtime:[NOW/MINUTE+TO+*]+OR+endtime:%221970-01-01T01:00:00Z%22%29&fq=siteHash:%2247f6b9596f7554913303c08a79705629a960b74a%22
     * http://a1:8080/solr/imagedb2/mp3?q.alt=*:*&rows=50&q=&fl=spell&facet.limit=20&facet=true&facet.field=fsArtist&facet.field=fsAlbum&facet.field=fsArtistLetter&facet.field=Id3Year&facet.field=fsGenre&facet.field=fsProvider&facet.mincount=1&json.nl=map&wt=xml&facet.prefix=Por
     * http://a1:8080/solr/imagedb2/mp3?q.alt=*:*&rows=50&q=&facet.limit=20&facet=true&facet.field=fsArtist&facet.field=fsAlbum&facet.field=fsArtistLetter&facet.field=Id3Year&facet.field=fsGenre&facet.field=fsProvider&facet.mincount=1&json.nl=map&wt=xml&facet.prefix=Por
     */

    this.requestSent = false;
    $(this.target).find('input').autocomplete('destroy').autocomplete({
      source: list,
      select: function(event, ui) {
    	console.log('foo');
        if (ui.item) {
        	
          self.requestSent = true;
          if (self.manager.store.addByValue('fq', ui.item.field + ':' + AjaxSolr.Parameter.escapeValue(ui.item.value))) {
        	  self.manager.doRequest();
            //self.doRequest();
          }
        }
      }
    });

    // This has lower priority so that requestSent is set.
    $(this.target).find('input').bind('keydown', function(e) {
    	console.log('keydown');
      if (self.requestSent === false && e.which == 13) {
        var value = $(this).val();
        if (value && self.set(value)) {
        	console.log('asdf');
        	self.manager.doRequest();
          //self.doRequest();
        }
      }
    });
  }
});

})(jQuery);