var app = angular.module('af-app', ['solstice']);

app.config(function(SolsticeProvider) {
  SolsticeProvider.setEndpoint('http://localhost:8080/solr/documents2/');
});

app.controller('SearchController', function($scope, Solstice) {
				$scope.reload = function() {
										Solstice.solrSearch()
										.then(function (data){
												//console.log(data.data);
												$scope.results = data.data;
										});
				};
  Solstice.search({
      q: '*:*',
      sort: 'name desc',
      rows: 10
  })
  .then(function (data){
				//console.log(data.data);
				$scope.results = data.data;
  });
});
