<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

// *** BACKEND ============================================================================

// LOGIN
$route['dang-nhap'] = 'login/index';
// HOME
$route['home'] = 'home/index';
// Export for order
$route['warehousemap/get-order-detail'] = 'warehousemap/get_order_detail';
$route['warehousemap/export-for-order'] = 'warehousemap/export_for_order';
/**
 * Warehouse map
 */
// Export transaction
$route['warehousemap/get-order-detail'] = 'warehousemap/get_order_detail';
$route['warehousemap/export-for-order'] = 'warehousemap/export_for_order';
// Get Size & Color
$route['warehousemap/get-size-color'] = 'warehousemap/get_size_color';
// Import
$route['warehousemap/action-import'] = 'warehousemap/action_import';
// Transfer
$route['warehousemap/warehouse-to'] = 'warehousemap/warehouse_to';
$route['warehousemap/action-transfer'] = 'warehousemap/action_transfer';
// Inventory
$route['warehousemap/(:num)'] = 'warehousemap/index/$1';
$route['warehousemap'] = 'warehousemap/index';
/**
 * Warehouse
 */
// Import
$route['warehouse/action-import'] = 'warehouse/action_import';
// Transfer
$route['warehouse/warehouse-to'] = 'warehouse/warehouse_to';
$route['warehouse/action-transfer'] = 'warehouse/action_transfer';
// Inventory
$route['warehouse/(:num)'] = 'warehouse/index/$1';
$route['warehouse'] = 'warehouse/index';

// Error
$route['(:any)'] = 'myerror';
/* Defaul page: error, home */
$route['default_controller'] = 'warehouse/history';
$route['404_override'] = 'myerror';
$route['translate_uri_dashes'] = FALSE;
