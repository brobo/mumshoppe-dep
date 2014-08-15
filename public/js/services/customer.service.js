angular.module('customer.service', [])
	.factory('CustomerService', function($http) {
		return {
			login: function(email, password) {
				return $http.post('/mums/api/customer/login', {Email: email, Password: password});
			},
			register: function(customer) {
				return $http.post('/mums/api/customer', angular.copy(customer));
			},
			verifyEmail: function(email) {
				return $http.post('/mums/api/customer/verify', {Email: email});
			}
		}
	});