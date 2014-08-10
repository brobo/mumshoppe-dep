angular.module('garnishesAll.controller', [])
	.controller('GarnishesAllController', function($scope, $http, GarnishesService) {
		
		GarnishesService.get().success(function(data) {
			$scope.garnishes = data;
		})

	});