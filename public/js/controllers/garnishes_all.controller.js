angular.module('garnishesAll.controller', [])
	.controller('GarnishesAllController', function($scope, $state, $http, GarnishesService) {
		
		GarnishesService.get().success(function(data) {
			$scope.garnishes = data;
		});

		$scope.addGarnish = function() {
			$state.go('^.add');
		}

	});
