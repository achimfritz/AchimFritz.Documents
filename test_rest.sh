#!/bin/bash


curl -i -X PUT "http://dev/achimfritz.documents/renamecategory/" -H "Content-Type: application/json" -H "Accept: application/json" -d '{	
	"renameCategory": {
		"oldPath": "locations/deutscheland/astuttgart",
		"newPath": "locations/deutscheland/stuttgart"
	}
}'
exit;

curl -i -X POST "http://dev/achimfritz.documents/pdfexport/" -H "Content-Type: application/json" -H "Accept: application/pdf" -d '{	
	"pdfExport": {
		"documents": [
			"000116c5-99a7-1b98-301c-e2d3c5a06a33"
		]
	}
}'
exit;

curl -i -X POST "http://dev/achimfritz.documents/documentexport/" -H "Content-Type: application/json" -H "Accept: application/zip" -d '{	
	"documentExport": {
		"name": "foo",
		"documents": [
			"000116c5-99a7-1b98-301c-e2d3c5a06a33"
		]
	}
}'
exit;
curl -X GET "http://dev/achimfritz.documents/imageintegrity/index?directory=2014_02_22_boarden_warth_oli" -H "Content-Type: application/json" -H "Accept: application/json"
exit;

curl -X POST "http://dev/achimfritz.documents/documentlistmerge/" -H "Content-Type: application/json" -H "Accept: application/json" -d '{	
	"documentList": {
		"category": {
			"path": "aaafoo/bar"
		},
		"documentListItems": [
			{ "documentListItem": { "document": "3ffb4137-b5fe-87ed-3517-a4d3e264ad5a", "sorting": "2" }}
		]
	}
}'
#{"documentList":{"category":{"path":"ads"},"documentListItems":[{"documentListItem":{"sorting":1,"document":"00bb359b-67ef-d265-74d7-3fca54201345"}}]}}
exit;
curl -X POST "http://dev/achimfritz.documents/documentcollectionmerge/" -H "Content-Type: application/json" -H "Accept: application/json" -d '{	
	"documentCollection": {
		"category": {
			"path": "aaafoo/bar"
		},
		"documents": [
			"0efb714b-f263-5024-80dc-f3e62141be32", 
			"e9f23764-4f9c-c714-e918-52dc49f7c92a"
		]
	}
}'
