#!/bin/bash
curl -i -X POST "http://dev/achimfritz.documents/job/" -H "Content-Type: application/json" -H "Accept: application/json" -d '{	
	"job": {
		"command": "ls; sleep 10; ls -l",
		"log": "",
		"status": "1"
	}
}'
