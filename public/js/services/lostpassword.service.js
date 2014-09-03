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
