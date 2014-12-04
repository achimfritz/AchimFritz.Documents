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
