angular.module('accessories.service', [])
	.factory('AccessoriesService', function($http) {
		return {
			get: function() {
				return $http.get('/mums/api/accessory');
			},
			create: function(accessoryData) {
				return $http.post('/mums/api/accessory', angular.copy(accessoryData));
			},
			update: function(id, accessoryData) {
				return $http.put('/mums/api/accessory/' + id, angular.copy(accessoryData));
			},
			delete: function(id) {
				return $http.delete('/mums/api/accessory/' + id);
			},
			fetch: function(id) {
				return $http.get('/mums/api/accessory/' + id);
			},
			categories: {
				create: function(categoryData) {
					return $http.post('/mums/api/category', categoryData);
				},
				get: function() {
					return $http.get('/mums/api/category');
				},
				delete: function(id) {
					return $http.delete('/mums/api/category/' + id);
				}
			},
			image: {
				upload: function(id, images) {
					var fd = new FormData();
					fd.append("image", images[0]);

					return $http.post('/mums/api/accessory/' + id + '/image', fd, {
						withCredentials: true,
						headers: {'Content-Type': undefined},
						transformRequest: angular.identity
					});
				}
			}
		}
	});
