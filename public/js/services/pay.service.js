
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

angular.module('pay.service', [])
	.factory('PayService', function($http) {
		return {
			deposite: {
				startPayFlow: function(mumId) {
					return $http.get(getRoute('/api/pay/deposit/') + mumId);
				},
				finalize: function(mumId, payerId) {
					return $http.post(getRoute('/api/pay/deposit/') + mumId, {
						PayerId: payerId
					});
				}
			},
			full: {
				startPayFlow: function(mumId) {
					return $http.get(getRoute('/api/pay/full/') + mumId);
				},
				finalize: function(mumId, payerId) {
					return $http.post(getRoute('/api/pay/full/') + mumId, {
						PayerId: payerId
					});
				},
				markPaid: function(mumId) {
					return $http.get(getRoute('/api/pay/mark/') + mumId);
				}
			}
		};
	});
	