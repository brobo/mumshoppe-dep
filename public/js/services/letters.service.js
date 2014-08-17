angular.module('letters.service', [])
	.factory('LettersService', function($http) {
		return {
			get: function() {
				return $http.get('/mums/api/letter');
			},
			create: function(letterData) {
				return $http.post('/mums/api/letter', angular.copy(letterData));
			},
			update: function(id, letterData) {
				return $http.put('/mums/api/letter/' + id, angular.copy(letterData));
			},
			delete: function(id) {
				return $http.delete('/mums/api/letter/' + id);
			},
			fetch: function(id) {
				return $http.get('/mums/api/letter/' + id);
			}
		}
	});
