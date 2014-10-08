var AchimFritz;

(function ($) {

	if (AchimFritz == undefined) {
		AchimFritz = {};
	};

   AchimFritz.App = {

		categoryPathUri: '/achimfritz.documents/categorypath/index',
		categoryUri: '/achimfritz.documents/category/index',
		documentUri: '/achimfritz.documents/document/index',

		restRequest: function (uri, data, method) {
			AchimFritz.App.cleanFlashMessage();
			$.ajax({
				type: method,
				url: uri,
				dataType: "json",
				data: data,
				success: function(data){
					if (data.messages) {
						for (var i = 0; i < data.messages.length; i++) {
							AchimFritz.App.flashMessage(data.messages[i]);
						}
						if (AchimFritz.App.isSolr()) {
							Manager.doRequest();	
						}
					}
					if (data.href) {
						var msg = '<a href="' + data.href + '">' + data.href + '</a>';
						var message = {severity: "Info", message: msg, title: 'See', code: ''};
						AchimFritz.App.flashMessage(message);
					}
				},
				error: function(data) {
					AchimFritz.App.errorMsg('Fatal');
				}
			});
		},

		restUpdate: function(uri, data) {
			AchimFritz.App.restRequest(uri, data, 'PUT');
		},
		restDelete: function(uri, data) {
			AchimFritz.App.restRequest(uri, data, 'DELETE');
		},
		restCreate: function(uri, data) {
			AchimFritz.App.restRequest(uri, data, 'POST');
		},

		errorMsg : function(msg) {
			var message = {severity: "Error", message: msg, code: 'unknown code', title: ''};
			AchimFritz.App.flashMessage(message);
		},

		cleanFlashMessage: function() {
			$('#flashMessages').html('');
		},

		flashMessage: function(message) {
			var severity = 'success';
			if (message.severity == 'Error') {
				severity = 'error';
			}
			var html = '<div class="alert alert-' + severity + '">';
			html += '<button type="button" class="close" data-dismiss="alert">&times;</button>'
			html += '<strong>' + message.severity + ' ' + message.title + '</strong>';
			html += message.message + ' ' + message.code + '</div>';
			$('#flashMessages').append(html);
		},

		showClipboard: function() {
			$('.app-showClipboard').unbind('click').bind('click', function (e) {
				var path = $(this).data('path');
				$('input.transferCategory').val(path);
				$('h6.transferCategory').text(path);
				$('.tab-clipboard').tab('show');
			});
		},

		bindAfterRequest: function() {
			AchimFritz.App.showEditCategoryPaths();
			AchimFritz.App.showDeleteCategoryPath();
			AchimFritz.App.showClipboard();
		},

		showDeleteCategoryPath: function() {
			$('.app-deleteCategoryPath').hide();
			$('.app-showDeleteCategoryPath').unbind('click').bind('click', function (e) {
				var path = $(this).data('path');
				$('h6.transferCategory').text(path);
				$('.app-deleteCategoryPath .transferCategory').val(path);
				e.preventDefault();
				$('.app-deleteCategoryPath .app-cancel').bind('click', function (e) {
					e.preventDefault();
					$('.app-deleteCategoryPath').hide();
				});
				$('.app-deleteCategoryPath').show();
				$('.app-deleteCategoryPath').unbind().bind('submit', function (e){
					e.preventDefault();
					data = {
						"path": $(this).find('.path').val()
					};
					uri = $(this).attr('action');
					AchimFritz.App.restDelete(uri, data);
				});
			});
		},

		showEditCategoryPaths: function() {
			$('.app-editCategoryPaths').hide();
			$('.app-showEditCategoryPaths').unbind('click').bind('click', function (e) {
				var path = $(this).data('path');
				$('h6.transferCategory').text(path);
				$('.app-editCategoryPaths .transferCategory').val(path);
				$('.app-editCategoryPaths .app-cancel').bind('click', function (e) {
					e.preventDefault();
					$('.app-editCategoryPaths').hide();
				});
				$('.app-editCategoryPaths').show();
				$('.app-editCategoryPaths').unbind().bind('submit', function (e){
					e.preventDefault();
					data = {
						"paths": {
							"old": $(this).find('.old').val(),
							"new": $(this).find('.new').val()
						}
					};
					uri = $(this).attr('action');
					AchimFritz.App.restUpdate(uri, data);
				});
			});
		},

		settings: function() {
			return $('#settings').data('settings');
		},

		documentReady: function() {
			$('#floatingBarsG').hide();
			jQuery.ajaxSetup({
			  beforeSend: function() {
				  $('#floatingBarsG').show();
			  },
			  complete: function(){
				  $('#floatingBarsG').hide();
			  }
			});
			$('.documentList').selectableArea();
			//$('#docs').selectableArea();
			AchimFritz.App.selecteable($('body'));

			AchimFritz.App.categoryDiff();
			AchimFritz.App.categoryCreate();

			AchimFritz.App.showEditCategoryPaths();
			AchimFritz.App.showDeleteCategoryPath();
		},

		selecteable: function(container) {
			$('.emptySelectedElements', container).unbind('click').bind('click', function(e) {
				e.preventDefault();
				$('.transferedElements li.selected', container).each(function(index, el) {
					$(el).remove();
				});
			});
			$('.removeAll', container).unbind('click').bind('click', function(e) {
				e.preventDefault();
				$('.transferedElements li', container).each(function(index, el) {
					$(el).remove();
				});
			});
			$('.selectAll', container).unbind('click').bind('click', function(e) {
				e.preventDefault();
				$('.documentList li', container).addClass('selected');
			});
			$('.transferSelectedElements', container).unbind('click').bind('click', function(e) {
				e.preventDefault();
				var docs = [];
				$('li.selected', container).each(function(index, el) {
					var doc = $(el).data('doc');
					docs.push(doc);
				});
				AchimFritz.App.addDocsToClipboard(docs);
			});
		},

		addElementToClipboard: function(element) {
			var doc = $(element).data('doc');
			var docs = [];
			docs.push(doc);
			AchimFritz.App.addDocsToClipboard(docs);
			$('.tab-clipboard').tab('show');
			$('.addElementToClipboard').hide();
		},


		addDocsToClipboard: function(docs, container) {
			if (container == undefined) {
				container = $('body');
			}
				var ul = AchimFritz.App.renderDocs(docs);
				$('.transferedElements', container).append(ul);
				$('.transferedElements', container).selectableArea();
		},

		categoryCreate: function() {
			$('.createCategory').unbind().bind('submit', function (e) {
				e.preventDefault();
				docs = AchimFritz.App.getDocumentsFromClipboard();
				var path = $('.path', $(this)).val();
				if (docs.length > 0 && path.length) {
					var data = {
						category : {
							path: path,
							documents: docs
						}
					};
					uri = $(this).attr('action');
					AchimFritz.App.restCreate(uri, data);

				} else {
					AchimFritz.App.errorMsg('please select elements and insert path');
				}
			});
		},

		categoryDiff: function() {
			$('.categoryDiff').unbind().bind('submit', function (e){
				e.preventDefault();
				docs = AchimFritz.App.getDocumentsFromClipboard();
				var path = $('.path', $(this)).val();
				if (docs.length > 0 && path.length) {
					var data = {
						category : {
							path: path,
							documents: docs
						}
					};
					uri = $(this).attr('action');
					AchimFritz.App.restUpdate(uri, data);

				} else {
					AchimFritz.App.errorMsg('please select elements and insert path');
				}
			});
		},

		getDocumentsFromClipboard: function() {
				var docs = [];
				$('.transferedElements li').each(function(index, el) {
					doc = $(el).data('doc');
					docs.push(doc.identifier);
				});
				docs = jQuery.unique(docs);
				return docs;
		},



		isSolr: function() {
			var docs = $('#docs');
			if (docs.length > 0) {
				return true;
			}
			return false;
		},

		renderDocs : function(docs) {
			var ulEl = $('<ul />');
			for (var i = 0, l = docs.length; i < l; i++) {
				var doc = docs[i];
				var liEl = $('<li />').addClass('selectable').attr({'data-identifier': doc.identifier}).html(doc.name);
				liEl.attr({'data-doc': JSON.stringify(doc)});
				ulEl.append(liEl);
			}
			return ulEl;
		},

		renderCurrentSearchActions: function(path) {
			var detailAction = $('<i />').append($('<a />').attr('href', 'javascript:;').append($('<span />').addClass('icon-arrow-right').attr({'data-path': path}).addClass('app-showClipboard')));
			var editAction = $('<i />').append($('<a />').attr('href', 'javascript:;').append($('<span />').addClass('icon-pencil').attr({'data-path': path}).addClass('app-showEditCategoryPaths')));
			var deleteAction = $('<i />').append($('<a />').attr('href', 'javascript:;').append($('<span />').addClass('icon-remove').attr({'data-path': path}).addClass('app-showDeleteCategoryPath')));
			var span = $('<span />').append(detailAction).append(editAction).append(deleteAction);
			return span;

		}
   };

	$(function () {
		AchimFritz.App.documentReady();
	});




})(jQuery);
