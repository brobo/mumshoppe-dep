
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

angular.module('alerts.controller', [])
	.controller('AlertsController', function($scope, AlertsService) {
		$scope.closeAlert = AlertsService.closeAlert;
	});
