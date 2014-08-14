angular.module('trinkets.service', [])
	.factory('TrinketsService', function($http) {
		return {
			get: function() {
				return $http.get('/mums/api/trinket');
			},
			create: function(trinketData) {
				return $http.post('/mums/api/trinket', angular.copy(trinketData));
			},
			update: function(id, trinketData) {
				return $http.put('/mums/api/trinket/' + id, angular.copy(trinketData));
			},
			delete: function(id) {
				return $http.delete('/mums/api/trinket/' + id);
			},
			fetch: function(id) {
				return $http.get('/mums/api/trinket/' + id);
			}
		}
	});
