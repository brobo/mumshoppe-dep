angular.module('volunteer.service', [])
	.factory('VolunteerService', function($http) {
		return {
			get: function() {
				return $http.get('/mums/api/volunteer');
			},
			update: function(id, volunteer) {
				return $http.put('/mums/api/volunteer/' + id, angular.copy(volunteer));
			},
			login: function(email, password) {
				return $http.post('/mums/api/volunteer/login', {Email: email, Password: password});
			},
			register: function(volunteer) {
				return $http.post('/mums/api/volunteer', angular.copy(volunteer));
			},
			verifyEmail: function(email) {
				return $http.post('/mums/api/volunteer/verify', {Email: email});
			}
		}
	});
