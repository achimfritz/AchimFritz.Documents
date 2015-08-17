#curl -X GET "http://dev/achimfritz.documents/documentlist/" -H "Content-Type: application/json" -H "Accept: application/json"

#curl -X POST "http://dev/achimfritz.documents/documentlistmerge/" -H "Content-Type: application/json" -H "Accept: application/json" -d '{	
#	"documentList":{"category":{"path":"ads"},"documentListItems":[{"documentListItem":{"sorting":1,"document":"00bb359b-67ef-d265-74d7-3fca54201345"}}]}
#}'

curl -X POST "http://dev/achimfritz.documents/documentlistmerge/" -H "Content-Type: application/json" -H "Accept: application/json" -d '{	
	"documentList": {
		"category": {
			"path": "aaafoo/bar"
		},
		"documentListItems": [
			{ "document": "00bb359b-67ef-d265-74d7-3fca54201345", "sorting": "2" }
		]
	}
}'
echo ""

