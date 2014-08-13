var app = angular.module('volunteer', [
	'ajoslin.promise-tracker',
	'ncy-angular-breadcrumb',
	'ui.bootstrap',
	'ui.router',
	'accentbows.controller',
	'alerts.controller',
	'confirm.controller',
	'mumtypes.controller',
	'trinketsAdd.controller',
	'trinketsAll.controller',
	'trinketsEdit.controller',
	'alerts.service',
	'confirm.service',
	'mumtypes.service',
	'trinkets.service']);

app.config(function($stateProvider, $urlRouterProvider, $httpProvider) {

	$urlRouterProvider.otherwise('/trinkets');

	$stateProvider
		.state('accentbows', {
			url: '/accentbows',
			templateUrl: 'public/views/volunteer/accentbows/index.html',
			controller: 'accentbowsController'
		})
		.state('mumtypes', {
			url: '/mumtypes',
			templateUrl: 'public/views/volunteer/mumtypes/index.html',
			controller: 'mumtypesController',
			abstract: true
		})
		.state('mumtypes.grade', {
			url: '',
			templateUrl: 'public/views/volunteer/mumtypes/grade.html',
			controller: 'mumtypesItemsController',
			resolve: {
				itemDetails: function(MumtypesService) {
					return {
						formController: 'mumtypesEditGradeController',
						service: MumtypesService.grades,
						fetch: []
					};
				}
			},
			data: {
				ncyBreadcrumbLabel: 'Home'
			}
		})
		.state('mumtypes.product', {
			url: '/:gradeId',
			templateUrl: 'public/views/volunteer/mumtypes/product.html',
			controller: 'mumtypesItemsController',
			resolve: {
				itemDetails: function($stateParams, MumtypesService) {
					return {
						formController: 'mumtypesEditProductController',
						service: MumtypesService.products,
						fetch: [
							function($scope) {
								return MumtypesService.grades.fetch($stateParams.gradeId)
									.success(function(data) {
										$scope.grade = data;
									});
							}
						]
					};
				}
			},
			data: {
				ncyBreadcrumbParent: 'mumtypes.grade',
				ncyBreadcrumbLabel: '{{grade.Name}}'
			}
		})
		.state('mumtypes.size', {
			url: '/:gradeId/:productId',
			templateUrl: 'public/views/volunteer/mumtypes/size.html',
			controller: 'mumtypesItemsController',
			resolve: {
				itemDetails: function($stateParams, MumtypesService) {
					return {
						formController: 'mumtypesEditSizeController',
						service: MumtypesService.sizes,
						fetch: [
							function($scope) {
								return MumtypesService.grades.fetch($stateParams.gradeId)
									.success(function(data) {
										$scope.grade = data;
									});
							},
							function($scope) {
								return MumtypesService.products.fetch($stateParams.productId)
									.success(function(data) {
										$scope.product = data;
									});
							}
						]
					};
				}
			},
			data: {
				ncyBreadcrumbParent: 'mumtypes.product',
				ncyBreadcrumbLabel: '{{product.Name}}'
			}
		})
		.state('mumtypes.backing', {
			url: '/:gradeId/:productId/:sizeId',
			templateUrl: 'public/views/volunteer/mumtypes/backing.html',
			controller: 'mumtypesItemsController',
			resolve: {
				itemDetails: function($stateParams, MumtypesService) {
					return {
						formController: 'mumtypesEditBackingController',
						service: MumtypesService.backings,
						fetch: [
							function($scope) {
								return MumtypesService.grades.fetch($stateParams.gradeId)
									.success(function(data) {
										$scope.grade = data;
									});
							},
							function($scope) {
								return MumtypesService.products.fetch($stateParams.productId)
									.success(function(data) {
										$scope.product = data;
									});
							},
							function($scope) {
								return MumtypesService.sizes.fetch($stateParams.sizeId)
									.success(function(data) {
										$scope.size = data;
									});
							}
						]
					}
				}
			},
			data: {
				ncyBreadcrumbParent: 'mumtypes.size',
				ncyBreadcrumbLabel: '{{size.Name}}'
			}
		})

		.state('trinkets', {
			url: '/trinkets',
			template: '<ui-view />',
			abstract: true
		})
		.state('trinkets.all', {
			url: '',
			templateUrl: 'public/views/volunteer/trinkets/all.html',
			controller: 'trinketsAllController'
		})
		.state('trinkets.add', {
			url: '/add',
			templateUrl: 'public/views/volunteer/trinkets/edit.html',
			controller: 'trinketsAddController'
		})
		.state('trinkets.edit', {
			url: '/edit/:trinketId',
			templateUrl: 'public/views/volunteer/trinkets/edit.html',
			controller: 'trinketsEditController'
		});

    $httpProvider.defaults.transformRequest = function(data) {
        if (data === undefined) {
            return data;
        }

        // If this is not an object, defer to native stringification.
	    if ( ! angular.isObject( data ) ) { 
	        return( ( data == null ) ? "" : data.toString() ); 
	    }

	    var buffer = [];

	    // Serialize each key in the object.
	    for ( var name in data ) { 
	        if ( ! data.hasOwnProperty( name ) ) { 
	            continue; 
	        }

	        var value = data[ name ];

	        buffer.push(
	            encodeURIComponent( name ) + "=" + encodeURIComponent( ( value == null ) ? "" : value )
	        ); 
	    }

	    // Serialize the buffer and clean it up for transportation.
	    var source = buffer.join( "&" ).replace( /%20/g, "+" ); 
	    return( source );
    }

    $httpProvider.defaults.headers.post = {'Content-Type': 'application/x-www-form-urlencoded'};
    $httpProvider.defaults.headers.put = {'Content-Type': 'application/x-www-form-urlencoded'};

	//PHP does not play nice with this feature. It's no big deal.
	//$locationProvider.html5Mode(true);

});
