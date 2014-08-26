angular.module('configure.controller', [])
	.controller('configureController', function($scope, $state) {
		$scope.active = null;
		$scope.tabs = [
			{heading: "Bears",		route:"configure.bears"},
			{heading: "Trinkets",	route:"configure.trinkets.all"},
			{heading: "Letters",	route:"configure.letters"},
			{heading: "Mum Types",	route:"configure.mumtypes.grade"}

		];

		$scope.go = function(tab) {
			$state.go(tab.route);
			$scope.active = tab;

		};

		$scope.active = function(route) {
			return $state.includes(route);
		}
	});