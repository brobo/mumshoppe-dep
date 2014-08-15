var app = angular.module('mumshoppe', [
	'ui.bootstrap',
	'ui.router',
	'alerts.controller',
	'home.controller',
	'login.controller',
	'register.controller',
	'alerts.service',
	'customer.service']);

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
		.state('home.register', {
			url: '/register',
			templateUrl: 'public/views/mumshoppe/home/register.html',
			controller: 'registerController'
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