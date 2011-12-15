<?php
/**
 * xml-rpc plugin
 *
 * @license http://opensource.org/licenses/gpl-2.0.php GPL 2
 */

elgg_register_event_handler('init', 'system', 'xmlrpc_init');

/**
 * Initialize the xml-rpc plugin
 */
function xmlrpc_init() {
	$base = elgg_get_plugins_path() . 'xml-rpc/lib';
	elgg_register_library('xml-rpc', "$base/xml-rpc.php");
	elgg_load_library('xml-rpc');

	elgg_register_page_handler('mt', 'xmlrpc_page_handler');
	elgg_register_page_handler('xml-rpc.php', 'xmlrpc_page_handler');

	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'xmlrpc_public_pages');
}

/**
 * Handle requests to the xml-rpc endpoint
 *
 * @param array $page
 * @return bool
 */
function xmlrpc_page_handler($page) {

	// Register the error handler
	error_reporting(E_ALL);
	set_error_handler('_php_xmlrpc_error_handler');

	// Register a default exception handler
	set_exception_handler('_php_xmlrpc_exception_handler');

	// Set some defaults
	$result = null;
	set_input('view', 'xml'); // Set default view regardless

	// Get the post data
	$input = get_post_data();

	if ($input) {
		// 	Parse structures from xml
		$call = new XMLRPCCall($input);

		// Process call
		$result = trigger_xmlrpc_handler($call);
	} else {
		throw new CallException(elgg_echo('xmlrpc:noinputdata'));
	}

	if (!($result instanceof XMLRPCResponse)) {
		throw new APIException(elgg_echo('APIException:ApiResultUnknown'));
	}

	// Output result
	echo elgg_view_page('XML-RPC', elgg_view('xml-rpc/output', array('result' => $result)));

	return true;
}

/**
 * Add the xml-rpc endpoints to the list of public pages
 *
 * @param string $hook  The hook name
 * @param string $type  The hook type
 * @param array  $pages Array of public pages
 * @return array 
 */
function xmlrpc_public_pages($hook, $type, $pages) {
	$pages[] = 'xml-rpc\.php';
	$pages[] = 'mt/mt-xmlrpc\.cgi';
	return $pages;
}
