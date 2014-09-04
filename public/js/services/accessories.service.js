
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

angular.module('accessories.service', [])
	.factory('AccessoriesService', function($http) {
		return {
			get: function() {
				return $http.get(getRoute('/api/accessory'));
			},
			create: function(accessoryData) {
				console.log('creating');
				return $http.post(getRoute('/api/accessory'), angular.copy(accessoryData));
			},
			update: function(id, accessoryData) {
				console.log('updating');
				return $http.put(getRoute('/api/accessory/') + id, angular.copy(accessoryData));
			},
			delete: function(id) {
				return $http.delete(getRoute('/api/accessory/') + id);
			},
			fetch: function(id) {
				return $http.get(getRoute('/api/accessory/') + id);
			},
			categories: {
				create: function(categoryData) {
					return $http.post(getRoute('/api/category'), categoryData);
				},
				get: function() {
					return $http.get(getRoute('/api/category'));
				},
				delete: function(id) {
					return $http.delete(getRoute('/api/category/') + id);
				}
			},
			image: {
				upload: function(id, images) {
					var fd = new FormData();
					fd.append("image", images[0]);

					return $http.post(getRoute('/api/accessory/' + id + '/image'), fd, {
						withCredentials: true,
						headers: {'Content-Type': undefined},
						transformRequest: angular.identity
					});
				}
			}
		}
	});
