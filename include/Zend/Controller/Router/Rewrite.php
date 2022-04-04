<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @package    Zend_Controller
 * @subpackage Router
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @version    $Id: Rewrite.php 12108 2008-10-24 13:02:56Z dasprid $
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/** Zend_Loader */
require_once 'Zend/Loader.php';

/** Zend_Controller_Router_Abstract */
require_once 'Zend/Controller/Router/Abstract.php';

/** Zend_Controller_Router_Route */
require_once 'Zend/Controller/Router/Route.php';

/**
 * Ruby routing based Router.
 *
 * @package    Zend_Controller
 * @subpackage Router
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see        http://manuals.rubyonrails.com/read/chapter/65
 */
class Zend_Controller_Router_Rewrite extends Zend_Controller_Router_Abstract
{

/**
 * Whether or not to use default routes
 * @var boolean
 */
protected $_useDefaultRoutes = true;

/**
 * Array of routes to match against
 * @var array
 */
protected $_routes = array();

/**
 * Currently matched route
 * @var Zend_Controller_Router_Route_Interface
 */
protected $_currentRoute = null;

/**
 * Global parameters given to all routes
 *
 * @var array
 */
protected $_globalParams = array();

/**
 * Add default routes which are used to mimic basic router behaviour
 */
public function addDefaultRoutes()
{
if (!$this->hasRoute('default')) {

$dispatcher = $this->getFrontController()->getDispatcher();
$request = $this->getFrontController()->getRequest();

require_once 'Zend/Controller/Router/Route/Module.php';
$compat = new Zend_Controller_Router_Route_Module(array(), $dispatcher, $request);

$this->_routes = array_merge(array('default' => $compat), $this->_routes);
}
}

/**
 * Add route to the route chain
 *
 * If route implements Zend_Controller_Request_Aware interface it is initialized with a request object
 *
 * @param string $name Name of the route
 * @param Zend_Controller_Router_Route_Interface Route
 */
public function addRoute($name, Zend_Controller_Router_Route_Interface $route)
{
if (method_exists($route, 'setRequest')) {
$route->setRequest($this->getFrontController()->getRequest());
}

$this->_routes[$name] = $route;

return $this;
}

/**
 * Add routes to the route chain
 *
 * @param array $routes Array of routes with names as keys and routes as values
 */
public function addRoutes($routes) {
foreach ($routes as $name => $route) {
$this->addRoute($name, $route);
}
return $this;
}

/**
 * Create routes out of Zend_Config configuration
 *
 * Example INI:
 * routes.archive.route = "archive/:year/*"
 * routes.archive.defaults.controller = archive
 * routes.archive.defaults.action = show
 * routes.archive.defaults.year = 2000
 * routes.archive.reqs.year = "\d+"
 *
 * routes.news.type = "Zend_Controller_Router_Route_Static"
 * routes.news.route = "news"
 * routes.news.defaults.controller = "news"
 * routes.news.defaults.action = "list"
 *
 * And finally after you have created a Zend_Config with above ini:
 * $router = new Zend_Controller_Router_Rewrite();
 * $router->addConfig($config, 'routes');
 *
 * @param Zend_Config $config Configuration object
 * @param string $section Name of the config section containing route's definitions
 * @throws Zend_Controller_Router_Exception
 */
public function addConfig(Zend_Config $config, $section = null)
{
if ($section !== null) {
if ($config->{$section} === null) {
require_once 'Zend/Controller/Router/Exception.php';
throw new Zend_Controller_Router_Exception("No route configuration in section '{$section}'");
}
$config = $config->{$section};
}

foreach ($config as $name => $info) {
$route = $this->_getRouteFromConfig($info);

if (isset($info->chains) && $info->chains instanceof Zend_Config) {
$this->_addChainRoutesFromConfig($name, $route, $info->chains);
} else {
$this->addRoute($name, $route);
}
}

return $this;
}

/**
 * Get a route frm a config instance
 *
 * @param  Zend_Config $info
 * @return Zend_Controller_Router_Route_Interface
 */
protected function _getRouteFromConfig(Zend_Config $info)
{
$class = (isset($info->type)) ? $info->type : 'Zend_Controller_Router_Route';
Zend_Loader::loadClass($class);
 
$route = call_user_func(array($class, 'getInstance'), $info);

return $route;
}

/**
 * Add chain routes from a config route
 *
 * @todo   Add recursive chaining (not required yet, but later when path
 *         route chaining is done)
 *
 * @param  string                                 $name
 * @param  Zend_Controller_Router_Route_Interface $route
 * @param  Zend_Config                            $childRoutesInfo
 * @return void
 */
protected function _addChainRoutesFromConfig($name,
Zend_Controller_Router_Route_Interface $route,
Zend_Config $childRoutesInfo)
{
foreach ($childRoutesInfo as $childRouteName => $childRouteInfo) {
$childRoute = $this->_getRouteFromConfig($childRouteInfo);

$chainRoute = $route->chain($childRoute);
$chainName  = $name . '-' . $childRouteName;

$this->addRoute($chainName, $chainRoute);
}
}

/**
 * Remove a route from the route chain
 *
 * @param string $name Name of the route
 * @throws Zend_Controller_Router_Exception
 */
public function removeRoute($name) {
if (!isset($this->_routes[$name])) {
require_once 'Zend/Controller/Router/Exception.php';
throw new Zend_Controller_Router_Exception("Route $name is not defined");
}
unset($this->_routes[$name]);
return $this;
}

/**
 * Remove all standard default routes
 *
 * @param Zend_Controller_Router_Route_Interface Route
 */
public function removeDefaultRoutes() {
$this->_useDefaultRoutes = false;
return $this;
}

/**
 * Check if named route exists
 *
 * @param string $name Name of the route
 * @return boolean
 */
public function hasRoute($name)
{
return isset($this->_routes[$name]);
}

/**
 * Retrieve a named route
 *
 * @param string $name Name of the route
 * @throws Zend_Controller_Router_Exception
 * @return Zend_Controller_Router_Route_Interface Route object
 */
public function getRoute($name)
{
if (!isset($this->_routes[$name])) {
require_once 'Zend/Controller/Router/Exception.php';
throw new Zend_Controller_Router_Exception("Route $name is not defined");
}
return $this->_routes[$name];
}

/**
 * Retrieve a currently matched route
 *
 * @throws Zend_Controller_Router_Exception
 * @return Zend_Controller_Router_Route_Interface Route object
 */
public function getCurrentRoute()
{
if (!isset($this->_currentRoute)) {
require_once 'Zend/Controller/Router/Exception.php';
throw new Zend_Controller_Router_Exception("Current route is not defined");
}
return $this->getRoute($this->_currentRoute);
}

/**
 * Retrieve a name of currently matched route
 *
 * @throws Zend_Controller_Router_Exception
 * @return Zend_Controller_Router_Route_Interface Route object
 */
public function getCurrentRouteName()
{
if (!isset($this->_currentRoute)) {
require_once 'Zend/Controller/Router/Exception.php';
throw new Zend_Controller_Router_Exception("Current route is not defined");
}
return $this->_currentRoute;
}

/**
 * Retrieve an array of routes added to the route chain
 *
 * @return array All of the defined routes
 */
public function getRoutes()
{
return $this->_routes;
}

/**
 * Find a matching route to the current PATH_INFO and inject
 * returning values to the Request object.
 *
 * @throws Zend_Controller_Router_Exception
 * @return Zend_Controller_Request_Abstract Request object
 */
public function route(Zend_Controller_Request_Abstract $request)
{

if (!$request instanceof Zend_Controller_Request_Http) {
require_once 'Zend/Controller/Router/Exception.php';
throw new Zend_Controller_Router_Exception('Zend_Controller_Router_Rewrite requires a Zend_Controller_Request_Http-based request object');
}

if ($this->_useDefaultRoutes) {
$this->addDefaultRoutes();
}

/** Find the matching route */
foreach (array_reverse($this->_routes) as $name => $route) {

// TODO: Should be an interface method. Hack for 1.0 BC
if (!method_exists($route, 'getVersion') || $route->getVersion() == 1) {
$match = $request->getPathInfo();
} else {
$match = $request;
}

if ($params = $route->match($match)) {
$this->_setRequestParams($request, $params);
$this->_currentRoute = $name;
break;
}
}

return $request;

}

protected function _setRequestParams($request, $params)
{
foreach ($params as $param => $value) {

$request->setParam($param, $value);

if ($param === $request->getModuleKey()) {
$request->setModuleName($value);
}
if ($param === $request->getControllerKey()) {
$request->setControllerName($value);
}
if ($param === $request->getActionKey()) {
$request->setActionName($value);
}

}
}

/**
 * Generates a URL path that can be used in URL creation, redirection, etc.
 *
 * @param  array $userParams Options passed by a user used to override parameters
 * @param  mixed $name The name of a Route to use
 * @param  bool $reset Whether to reset to the route defaults ignoring URL params
 * @param  bool $encode Tells to encode URL parts on output
 * @throws Zend_Controller_Router_Exception
 * @return string Resulting absolute URL path
 */
public function assemble($userParams, $name = null, $reset = false, $encode = true)
{
if ($name == null) {
try {
$name = $this->getCurrentRouteName();
} catch (Zend_Controller_Router_Exception $e) {
$name = 'default';
}
}

$params = array_merge($this->_globalParams, $userParams);

$route = $this->getRoute($name);
$url   = $route->assemble($params, $reset, $encode);

if (!preg_match('|^[a-z]+://|', $url)) {
$url = rtrim($this->getFrontController()->getBaseUrl(), '/') . '/' . $url;
}

return $url;
}

/**
 * Set a global parameter
 *
 * @param  string $name
 * @param  mixed $value
 * @return Zend_Controller_Router_Rewrite
 */
public function setGlobalParam($name, $value)
{
$this->_globalParams[$name] = $value;

return $this;
}
}