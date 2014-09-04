
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

angular.module('customer.service', [])
	.factory('CustomerService', function($http) {
		return {
			login: function(email, password) {
				return $http.post(getRoute('/api/customer/login'), {Email: email, Password: password});
			},
			register: function(customer) {
				return $http.post(getRoute('/api/customer'), angular.copy(customer));
			},
			verifyEmail: function(email) {
				return $http.post(getRoute('/api/customer/verify'), {Email: email});
			}
		}
	});
