angular.module('mum.service', [])
	.factory('MumService', function($http) {
		return {
			create: function(customerId) {
				return $http.post('/mums/api/mum', {
					CustomerId: customerId
				});
			},
			get: function(criteria) {
				return $http.get('/mums/api/mum', {params: criteria});
			},
			update: function(mumId, mumData) {
				return $http.put('/mums/api/mum/' + mumId, mumData);
			},
			fetch: function(mumId) {
				return $http.get('/mums/api/mum/' + mumId);
			},
			delete: function(id) {
				return $http.delete('/mums/api/mum/' + id);
			},
			addBear: function(mumId, bearId) {
				return $http.put('/mums/api/mum/' + mumId + '/bear/' + bearId);
			},
			removeBear: function(mumId, bearId) {
				return $http.delete('/mums/api/mum/' + mumId + '/bear/' + bearId);
			},
			setTrinkets: function(mumId, trinketData) {
				return $http.post('/mums/api/mum/' + mumId + '/trinket', trinketData);
			}
		};
	});