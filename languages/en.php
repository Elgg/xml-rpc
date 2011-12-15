<?php
/**
 * English language file for xml-rpc
 */

$english = array(
	'xmlrpc:noinputdata' => "Input data missing",
	
	'NotImplementedException:XMLRPCMethodNotImplemented' => "XML-RPC method call '%s' not implemented.",
	'InvalidParameterException:UnexpectedReturnFormat' => "Call to method '%s' returned an unexpected result.",
	'CallException:NotRPCCall' => "Call does not appear to be a valid XML-RPC call",
);

add_translation('en', $english);
