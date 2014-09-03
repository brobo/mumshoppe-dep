angular.module('bears.service', [])
	.factory('BearsService', function($http) {
		return {
			get: function() {
				return $http.get(getRoute('/api/bear'));
			},
			create: function(bearData) {
				return $http.post(getRoute('/api/bear'), angular.copy(bearData));
			},
			update: function(id, bearData) {
				return $http.put(getRoute('/api/bear/') + id, angular.copy(bearData));
			},
			delete: function(id) {
				return $http.delete(getRoute('/api/bear/') + id);
			},
			fetch: function(id) {
				return $http.get(getRoute('/api/bear/') + id);
			},
			image: {
				upload: function(id, images) {
					var fd = new FormData();
					fd.append("image", images[0]);

					return $http.post(getRoute('/api/bear/' + id + '/image'), fd, {
						withCredentials: true,
						headers: {'Content-Type': undefined},
						transformRequest: angular.identity
					});
				}
			}
		}
	});
