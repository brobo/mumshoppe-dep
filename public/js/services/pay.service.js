angular.module('pay.service', [])
	.factory('PayService', function($http) {
		return {
			deposite: {
				startPayFlow: function(mumId) {
					return $http.get('/mums/api/pay/deposit/' + mumId);
				},
				finalize: function(mumId, payerId) {
					return $http.post('/mums/api/pay/deposit/' + mumId, {
						PayerId: payerId
					});
				}
			}
		};
	});