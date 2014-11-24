var app = angular.module('af-app', ['solstice']);

app.config(function(SolsticeProvider) {
  SolsticeProvider.setEndpoint('http://localhost:8080/solr/documents2/');
});

app.controller('MyController', function($scope, Solstice) {
  Solstice.search({
      q: '*:*',
      sort: 'name desc',
      rows: 10
  })
  .then(function (data){
				console.log(data);
    //$scope.results = data.docs;
    //console.log(data.docs);
  });
});
