angular.module('mumtypes.service', [])
	.factory('MumtypesService', function($http) {
		return {
			grades: {
				get: function() {
					return $http.get('/mums/api/grade');
				},
				create: function(gradeData) {
					return $http.post('/mums/api/grade', gradeData);
				},
				update: function(id, gradeData) {
					return $http.put('/mums/api/grade/' + id, gradeData);
				},
				delete: function(id) {
					return $http.delete('/mums/api/grade/' + id);
				},
				fetch: function(id) {
					return $http.get('/mums/api/grade/' + id);
				}
			},
			products: {
				get: function() {
					return $http.get('/mums/api/product');
				},
				create: function(productData) {
					return $http.post('/mums/api/product', productData);
				},
				update: function(id, productData) {
					return $http.put('/mums/api/product/' + id, productData);
				},
				delete: function(id) {
					return $http.delete('/mums/api/product/' + id);
				},
				fetch: function(id) {
					return $http.get('/mums/api/product/' + id);
				}
			},
			sizes: {
				get: function() {
					return $http.get('/mums/api/size');
				},
				create: function(sizeData) {
					return $http.post('/mums/api/size', sizeData);
				},
				update: function(id, sizeData) {
					return $http.put('/mums/api/size/' + id, sizeData);
				},
				delete: function(id) {
					return $http.delete('/mums/api/size/' + id);
				},
				fetch: function(id) {
					return $http.get('/mums/api/size/' + id);
				}
			},
			backings: {
				get: function() {
					return $http.get('/mums/api/backing');
				},
				create: function(backingData) {
					return $http.post('/mums/api/backing', backingData);
				},
				update: function(id, backingData) {
					return $http.put('/mums/api/backing/' + id, backingData);
				},
				delete: function(id) {
					return $http.delete('/mums/api/backing/' + id);
				},
				fetch: function(id) {
					return $http.get('/mums/api/backing/' + id);
				}
			}
		}
	});
