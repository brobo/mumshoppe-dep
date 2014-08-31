angular.module('bears.service', [])
	.factory('BearsService', function($http) {
		return {
			get: function() {
				return $http.get('/mums/api/bear');
			},
			create: function(bearData) {
				return $http.post('/mums/api/bear', angular.copy(bearData));
			},
			update: function(id, bearData) {
				return $http.put('/mums/api/bear/' + id, angular.copy(bearData));
			},
			delete: function(id) {
				return $http.delete('/mums/api/bear/' + id);
			},
			fetch: function(id) {
				return $http.get('/mums/api/bear/' + id);
			},
			image: {
				upload: function(id, images) {
					var fd = new FormData();
					fd.append("image", images[0]);

					return $http.post('/mums/api/bear/' + id + '/image', fd, {
						withCredentials: true,
						headers: {'Content-Type': undefined},
						transformRequest: angular.identity
					});
				}
			}
		}
	});
