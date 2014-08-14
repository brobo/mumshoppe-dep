angular.module('accentbows.service', [])
	.factory('AccentBowsService', function($http) {
		return {
			get: function() {
				return $http.get('/mums/api/accentbow');
			},
			create: function(bowData) {
				return $http.post('/mums/api/accentbow', angular.copy(bowData));
			},
			update: function(id, bowData) {
				return $http.put('/mums/api/accentbow/' + id, angular.copy(bowData));
			},
			delete: function(id) {
				return $http.delete('/mums/api/accentbow/' + id);
			},
			fetch: function(id) {
				return $http.get('/mums/api/accentbow/' + id);
			}
		}
	});