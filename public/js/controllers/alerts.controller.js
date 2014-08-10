angular.module('alerts.controller', [])
	.controller('AlertsController', function($scope, AlertsService) {
		$scope.closeAlert = AlertsService.closeAlert;
	});
