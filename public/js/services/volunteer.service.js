angular.module('volunteer.service', [])
	.factory('VolunteerService', function($http) {
		return {
			get: function() {
				return $http.get(getRoute('/api/volunteer'));
			},
			update: function(id, volunteer) {
				return $http.put(getRoute('/api/volunteer/') + id, angular.copy(volunteer));
			},
			login: function(email, password) {
				return $http.post(getRoute('/api/volunteer/login'), {Email: email, Password: password});
			},
			register: function(volunteer) {
				return $http.post(getRoute('/api/volunteer'), angular.copy(volunteer));
			},
			verifyEmail: function(email) {
				return $http.post(getRoute('/api/volunteer/verify'), {Email: email});
			}
		}
	});
