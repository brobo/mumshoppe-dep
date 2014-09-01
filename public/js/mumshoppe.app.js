var app = angular.module('mumshoppe', [
	'ajoslin.promise-tracker',
	'ui.bootstrap',
	'ui.router',
	'ngCookies',
	'ncy-angular-breadcrumb',
	'alerts.controller',
	'confirm.controller',
	'home.controller',
	'mums.mumshoppe.controller',
	'pay.controller',
	'create.controller',
	'accentbows.service',
	'alerts.service',
	'bears.service',
	'confirm.service',
	'customer.service',
	'letters.service',
	'lostpassword.service',
	'mum.service',
	'mumtypes.service',
	'pay.service',
	'accessories.service']);

app.config(function($stateProvider, $urlRouterProvider, $httpProvider) {

	$urlRouterProvider.otherwise('/home');

	$stateProvider
		.state('home', {
			url: '/home',
			template: '<div ui-view></div>',
			abstract: true
		})
		.state('home.index', {
			url: '',
			templateUrl: 'public/views/mumshoppe/home/home.html',
			controller: 'homeController'
		})
		.state('home.logout', {
			url: '/logout',
			onEnter: function($cookieStore, $rootScope, $state) {
				$cookieStore.remove('customerToken');
				$rootScope.updateHeader();
				$state.go('home.index');
			}
		})
		.state('home.lostpassword', {
			url: '/lostpassword',
			templateUrl: 'public/views/mumshoppe/home/lostpassword.html',
			controller: 'lostPasswordController'
		})
		.state('home.recoverpassword', {
			url: '/recoverpassword/:key',
			templateUrl: 'public/views/mumshoppe/home/recoverpassword.html',
			controller: 'recoverPasswordController'
		})
		.state('mums', {
			url: '/mums',
			templateUrl: 'public/views/mumshoppe/mums/index.html',
			abstract: true
		})
		.state('mums.all', {
			url: '',
			templateUrl: 'public/views/mumshoppe/mums/all.html',
			controller: 'mumsController'
		})
		.state('mums.view', {
			url: '/view/:mumId',
			templateUrl: 'public/views/mumshoppe/mums/view.html',
			controller: 'mumsViewController'
		})
		.state('pay', {
			url: '/pay/:mumId',
			template: '<div ui-view></div>',
			abstract: true
		})
		.state('pay.start', {
			url: '',
			templateUrl: 'public/views/mumshoppe/pay/index.html',
			controller: 'payIndexController'
		})
		.state('pay.finalize', {
			url: '/finalize',
			templateUrl: 'public/views/mumshoppe/pay/finalize.html',
			controller: 'payFinalizeController'
		})
		.state('pay.thankyou', {
			url: '/thankyou',
			templateUrl: 'public/views/mumshoppe/pay/thankyou.html'
		})
		.state('create', {
			url: '/create/:mumId',
			templateUrl: 'public/views/mumshoppe/create/index.html',
			controller: 'createController',
			abstract: true
		})
		.state('create.start', {
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
		.state('create.accessories', {
			templateUrl: 'public/views/mumshoppe/create/accessories.html',
			url: '/accessories',
			controller: 'createAccessoriesController'
		})
		.state('create.review', {
			templateUrl: 'public/views/mumshoppe/create/review.html',
			url: '/review',
			controller: 'createReviewController'
		})
		.state('create.deposit', {
			templateUrl: 'public/views/mumshoppe/create/deposit.html',
			url: '/deposit',
			controller: 'createDepositController'
		})
		.state('create.finalize', {
			templateUrl: 'public/views/mumshoppe/create/finalize.html',
			url: '/finalize',
			controller: 'createFinalizeController'
		})
		.state('create.thankyou', {
			templateUrl: 'public/views/mumshoppe/create/thankyou.html',
			url: '/thankyou',
			controller: 'createThankYouController'
		});

		$httpProvider.defaults.headers.post = {'Content-Type': 'application/x-www-form-urlencoded'};
	    $httpProvider.defaults.headers.put = {'Content-Type': 'application/x-www-form-urlencoded'};

		//PHP does not play nice with this feature. It's no big deal.
		//$locationProvider.html5Mode(true);

	});

app.run(['$cookieStore', '$injector', function($cookieStore, $injector) {
	$injector.get("$http").defaults.transformRequest.unshift(function(data, headersGetter) {

		var token = $cookieStore.get('customerToken');
		if (token) {
			headersGetter()['Authorization'] = token.jwt;
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

app.controller('headerController', function($scope, $rootScope, $cookieStore) {
	$rootScope.updateHeader = function() {
		$rootScope.customer = $cookieStore.get('customerToken');
	}
	$rootScope.updateHeader();
})

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
