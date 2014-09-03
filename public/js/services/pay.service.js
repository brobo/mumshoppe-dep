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
	