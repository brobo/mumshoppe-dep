
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

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
				return $http.post(getRoute('/api/volunteer/verify'), {Email: email})
			},
			rights: function(id, rights) {
				return $http.post(getRoute('/api/volunteer/' + id + '/rights'), {Rights: rights});
			}
		}
	});
