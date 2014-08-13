angular.module('accentbows.controller', [])
	.controller('accentbowsController', function($scope, MumtypesService) {
		MumtypesService.grades.get().success(function(data) {
			$scope.grades = data;
		});
	});