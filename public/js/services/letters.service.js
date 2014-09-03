angular.module('letters.service', [])
	.factory('LettersService', function($http) {
		return {
			get: function() {
				return $http.get(getRoute('/api/letter'));
			},
			create: function(letterData) {
				return $http.post(getRoute('/api/letter'), angular.copy(letterData));
			},
			update: function(id, letterData) {
				return $http.put(getRoute('/api/letter/') + id, angular.copy(letterData));
			},
			delete: function(id) {
				return $http.delete(getRoute('/api/letter/') + id);
			},
			fetch: function(id) {
				return $http.get(getRoute('/api/letter/') + id);
			}
		}
	});
