
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

angular.module('lostpassword.service', [])
	.factory('LostPasswordService', function($http) {
		return {
			sendRecoveryEmail: function(email) {
				return $http.post(getRoute('/api/recover'), {
					Email: email
				});
			},
			recoverPassword: function(key, password) {
				return $http.post(getRoute('/api/recover/') + key, {
					Password: password
				});
			}
		};
	});
