
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

angular.module('mumtypes.service', [])
	.factory('MumtypesService', function($http) {
		return {
			grades: {
				get: function() {
					return $http.get(getRoute('/api/grade'));
				},
				create: function(gradeData) {
					return $http.post(getRoute('/api/grade'), angular.copy(gradeData));
				},
				update: function(id, gradeData) {
					return $http.put(getRoute('/api/grade/') + id, angular.copy(gradeData));
				},
				delete: function(id) {
					return $http.delete(getRoute('/api/grade/') + id);
				},
				fetch: function(id) {
					return $http.get(getRoute('/api/grade/') + id);
				}
			},
			products: {
				get: function() {
					return $http.get(getRoute('/api/product'));
				},
				create: function(productData) {
					return $http.post(getRoute('/api/product'), angular.copy(productData));
				},
				update: function(id, productData) {
					return $http.put(getRoute('/api/product/') + id, angular.copy(productData));
				},
				delete: function(id) {
					return $http.delete(getRoute('/api/product/') + id);
				},
				fetch: function(id) {
					return $http.get(getRoute('/api/product/') + id);
				}
			},
			sizes: {
				get: function() {
					return $http.get(getRoute('/api/size'));
				},
				create: function(sizeData) {
					return $http.post(getRoute('/api/size'), angular.copy(sizeData));
				},
				update: function(id, sizeData) {
					return $http.put(getRoute('/api/size/') + id, angular.copy(sizeData));
				},
				delete: function(id) {
					return $http.delete(getRoute('/api/size/') + id);
				},
				fetch: function(id) {
					return $http.get(getRoute('/api/size/') + id);
				},
				image: {
					upload: function(id, images) {
						var fd = new FormData();
						fd.append("image", images[0]);

						return $http.post(getRoute('/api/size/' + id + '/image'), fd, {
							withCredentials: true,
							headers: { 
								'Content-Type': undefined,
								'Authentication': $cookieStore.get('volunteerToken').jwt
							},
							transformRequest: angular.identity
						});
					}
				}
			},
			backings: {
				get: function() {
					return $http.get(getRoute('/api/backing'));
				},
				create: function(backingData) {
					return $http.post(getRoute('/api/backing'), angular.copy(backingData));
				},
				update: function(id, backingData) {
					return $http.put(getRoute('/api/backing/') + id, angular.copy(backingData));
				},
				delete: function(id) {
					return $http.delete(getRoute('/api/backing/') + id);
				},
				fetch: function(id) {
					return $http.get(getRoute('/api/backing/') + id);
				},
				image: {
					upload: function(id, images) {
						var fd = new FormData();
						fd.append("image", images[0]);

						return $http.post(getRoute('/api/backing/' + id + '/image'), fd, {
							withCredentials: true,
							headers: {
								'Content-Type': undefined,
								'Authentication': $cookieStore.get('volunteerToken').jwt
							},
							transformRequest: angular.identity
						});
					}
				}
			}
		}
	});
