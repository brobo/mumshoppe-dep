angular.module('home.controller', [])
	.controller('homeController', function($scope, $rootScope, $state) {

		$rootScope.$state = $state;

		$scope.openLogIn = function() {
			$state.go('.login');
		}

	});