angular.module('configure.controller', [])
	.controller('configureController', function($scope, $state) {
		$scope.active = null;
		$scope.tabs = [
			{heading: "Accent Bows",	route:"configure.accentbows"},
			{heading: "Bears",			route:"configure.bears"},
			{heading: "Trinkets",		route:"configure.trinkets.all"},
			{heading: "Letters",		route:"configure.letters"},
			{heading: "Mum Types",		route:"configure.mumtypes.grade"}
		];

		$scope.isActive = function(tab) {
			return $state.includes(tab.route);
		}
	});