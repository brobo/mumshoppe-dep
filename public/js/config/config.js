/**
  * Sets the prefix for all routes on the server - use for development purposes.
  * An empty route prefix is the recommended route for production builds.
**/
var ROUTE_PREFIX = "/mums";

// CONFIG RELATED FUNCTIONS FOLLOW

function getRoute(route) {
	return ROUTE_PREFIX + route;
}