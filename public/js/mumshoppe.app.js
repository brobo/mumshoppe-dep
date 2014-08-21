var app = angular.module('mumshoppe', [
	'ajoslin.promise-tracker',
	'ui.bootstrap',
	'ui.router',
	'ngCookies',
	'ncy-angular-breadcrumb',
	'alerts.controller',
	'confirm.controller',
	'home.controller',
	'login.controller',
	'mums.controller',
	'create.controller',
	'register.controller',
	'accentbows.service',
	'alerts.service',
	'bears.service',
	'confirm.service',
	'customer.service',
	'letters.service',
	'mum.service',
	'mumtypes.service',
	'trinkets.service']);

app.config(function($stateProvider, $urlRouterProvider, $httpProvider) {

	$stateProvider
		.state('home', {
			url: '',
			templateUrl: 'public/views/mumshoppe/home/home.html',
			controller: 'homeController'
		})
		.state('home.login', {
			url: '/login',
			templateUrl: 'public/views/mumshoppe/home/login.html',
			controller: 'loginController'
		})
		.state('home.logout', {
			url: '/logout',
			controller: 'logoutController'
		})
		.state('home.register', {
			url: '/register',
			templateUrl: 'public/views/mumshoppe/home/register.html',
			controller: 'registerController'
		})
		.state('mums', {
			url: '/mums',
			templateUrl: 'public/views/mumshoppe/mums/index.html',
			controller: 'mumsController'
		})
		.state('create', {
			url: '/create/:mumId',
			templateUrl: 'public/views/mumshoppe/create/index.html',
			controller: 'createController',
			abstract: true
		})
		.state('create.getStarted', {
			url: '',
			controller: function($state) {
				$state.go('^.base.product');
			}
		})
		.state('create.base', {
			url: '/base',
			template: '<div ui-view></div>',
			controller: 'createProductController',
			abstract: true			
		})
		.state('create.base.product', {
			templateUrl: 'public/views/mumshoppe/create/product.html',
			//url: '/product',
			url: '',
		})
		.state('create.base.grade', {
			templateUrl: 'public/views/mumshoppe/create/grade.html',
			//url: '/grade',
		})
		.state('create.base.size', {
			templateUrl: 'public/views/mumshoppe/create/size.html',
			//url: '/size',
		})
		.state('create.base.backing', {
			templateUrl: 'public/views/mumshoppe/create/backing.html',
			//url: '/backing',
		})
		.state('create.accentbow', {
			templateUrl: 'public/views/mumshoppe/create/accentbow.html',
			url: '/accentbow',
			controller: 'createAccentBowController'
		})
		.state('create.nameribbons', {
			templateUrl: 'public/views/mumshoppe/create/nameribbons.html',
			url: '/nameribbons',
			controller: 'createNameRibbonController'
		})
		.state('create.bears', {
			templateUrl: 'public/views/mumshoppe/create/bears.html',
			url: '/bears',
			controller: 'createBearsController'
		})
		.state('create.trinkets', {
			templateUrl: 'public/views/mumshoppe/create/trinkets.html',
			url: '/trinkets',
			controller: 'createTrinketsController'
		})
		.state('create.review', {
			templateUrl: 'public/views/mumshoppe/create/review.html',
			url: '/review',
			controller: 'createReview'
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