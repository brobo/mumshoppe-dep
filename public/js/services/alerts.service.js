
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

angular.module('alerts.service', [])
	.factory('AlertsService', ['$rootScope', function($rootScope) {
		$rootScope.alerts = [];
		var alertsService = {
			add: function(type, message) {
				return $rootScope.alerts.splice(0,0, {
					type: type,
					msg: message,
					close: function() {
						return alertsService.closeAlert(this);
					}
				});
			},
			closeAlert: function(alert) {
				var index = $rootScope.alerts.indexOf(alert);
				return $rootScope.alerts.splice(index, 1);
			},
			closeAlertIndex: function(index) {
				return $rootScope.alerts.splice(index, 1);
			},
			clear: function() {
				$rootScope.alerts = [];
			}
		};
		return alertsService;
	}]);
