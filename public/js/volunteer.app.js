
	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

var app = angular.module('volunteer', [
	'ajoslin.promise-tracker',
	'cn.offCanvas',
	'ncy-angular-breadcrumb',
	'ngCookies',
	'ui.bootstrap',
	'ui.router',
	'accentbows.controller',
	'alerts.controller',
	'bears.controller',
	'configure.controller',
	'confirm.controller',
	'home.volunteer.controller',
	'letters.controller',
	'mums.volunteer.controller',
	'mumtypes.controller',
	'accessoriesAdd.controller',
	'accessoriesAll.controller',
	'accessoriesEdit.controller',
	'accentbows.service',
	'alerts.service',
	'bears.service',
	'confirm.service',
	'letters.service',
	'mum.service',
	'mumtypes.service',
	'pay.service',
	'accessories.service',
	'volunteer.service']);

app.config(function($stateProvider, $urlRouterProvider, $httpProvider) {

	$urlRouterProvider.otherwise('/home');

	$stateProvider
		.state('home', {
			url: '/home',
			templateUrl: 'public/views/volunteer/home/index.html',
			controller: 'homeController'
		})
		.state('home.logout', {
			url: '/logout',
			onEnter: function($cookieStore, $rootScope, $state) {
				$cookieStore.remove('volunteerToken');
				$rootScope.updateHeader();
				$state.go('home');
			}
		})
		.state('configure', {
			url: '/configure',
			templateUrl: 'public/views/volunteer/configure/index.html',
			controller: 'configureController'
		})
		.state('configure.accentbows', {
			url: '/accentbows',
			templateUrl: 'public/views/volunteer/accentbows/index.html',
			controller: 'accentbowsController'
		})
		.state('configure.bears', {
			url: '/bears',
			templateUrl: 'public/views/volunteer/bears/index.html',
			controller: 'bearsController'
		})
		.state('configure.letters', {
			url: '/letters',
			templateUrl: 'public/views/volunteer/letters/index.html',
			controller: 'lettersController'
		})
		.state('configure.volunteers', {
			url: '/volunteers',
			templateUrl: 'public/views/volunteer/configure/volunteers.html',
			controller: 'configureVolunteerController'
		})
		.state('configure.yearly', {
			url: '/yearly',
			templateUrl: 'public/views/volunteer/configure/yearly.html',
			controller: 'configureYearlyController'
		})
		.state('mums', {
			url: '/mums',
			templateUrl: 'public/views/volunteer/mums/index.html',
			controller: 'mumsController',
			abstract: true
		})
		.state('mums.all', {
			url: '',
			templateUrl: 'public/views/volunteer/mums/all.html',
			controller: 'mumsAllController'
		})
		.state('mums.view', {
			url: '/view/:mumId',
			templateUrl: 'public/views/volunteer/mums/view.html',
			controller: 'mumsViewController'
		})
		.state('configure.mumtypes', {
			url: '/mumtypes',
			templateUrl: 'public/views/volunteer/mumtypes/index.html',
			controller: 'mumtypesController',
			abstract: true
		})
		.state('configure.mumtypes.grade', {
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
			}
		})
		.state('configure.mumtypes.product', {
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
			}
		})
		.state('configure.mumtypes.size', {
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
			}
		})
		.state('configure.mumtypes.backing', {
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
			}
		})

		.state('configure.accessories', {
			url: '/accessories',
			template: '<ui-view />',
			abstract: true
		})
		.state('configure.accessories.all', {
			url: '',
			templateUrl: 'public/views/volunteer/accessories/all.html',
			controller: 'accessoriesAllController'
		})
		.state('configure.accessories.add', {
			url: '/add',
			templateUrl: 'public/views/volunteer/accessories/edit.html',
			controller: 'accessoriesAddController'
		})
		.state('configure.accessories.edit', {
			url: '/edit/:accessoryId',
			templateUrl: 'public/views/volunteer/accessories/edit.html',
			controller: 'accessoriesEditController'
		});

    $httpProvider.defaults.headers.post = {'Content-Type': 'application/x-www-form-urlencoded'};
    $httpProvider.defaults.headers.put = {'Content-Type': 'application/x-www-form-urlencoded'};

	//PHP does not play nice with this feature. It's no big deal.
	//$locationProvider.html5Mode(true);

});

app.run(['$cookieStore', '$injector', function($cookieStore, $injector) {
	$injector.get("$http").defaults.transformRequest.unshift(function(data, headersGetter) {

		var token = $cookieStore.get('volunteerToken');
		if (token) {
			headersGetter()['Authentication'] = token.jwt;
		}

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
    });

}]);

app.controller('headerController', function($rootScope, $cookieStore) {
	$rootScope.updateHeader = function() {
		$rootScope.volunteer = $cookieStore.get('volunteerToken');
	};
	$rootScope.updateHeader();
});

//This filter is used to convert MySQL datetimes into AngularJS a readable ISO format.
app.filter('dateToISO', function() {
  return function(badTime) {
  	if (!badTime) return "";
  	if (badTime.date) {
  		return badTime.date.replace(/(.+) (.+)/, "$1T$2Z");
  	} else {
  		return badTime.replace(/(.+) (.+)/, "$1T$2Z");
  	}
  };
});
