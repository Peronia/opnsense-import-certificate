<?php

/**
 * Import SSL certificates from a pre-determined place on the filesystem.
 * Once imported, set them for use in the GUI
 * Based on: https://github.com/pluspol-interactive/opnsense-import-certificate
 */

if (empty($argc)) {
	echo "Only accessible from the CLI.\r\n";
	die(1);
}

if ($argc != 3) {
	echo "Usage: php " . $argv[0] . " /path/to/certificate.crt /path/to/private/key.key\r\n";
	die(1);
}

require_once "config.inc";
require_once "certs.inc";

$certificate = trim(file_get_contents($argv[1]));
$key = trim(file_get_contents($argv[2]));

$cert = array();
$cert['refid'] = uniqid();
$cert['descr'] = "Certificate added to opnsense through " . $argv[0] . " on " . date("d.m.Y");

// Create certificate
cert_import($cert, $certificate, $key);

// Set up the existing certificate store
// Copied from system_certmanager.php

// If no array present, create one
if (!is_array($config['cert'])) {
	$config['cert'] = array();
}
$a_cert =& $config['cert'];

// Overwrite the last certificate with the new one
$a_cert[sizeof($a_cert) - 1] = $cert;

// Write out the updated configuration
write_config();

// Assuming that all worked, we now need to set the new certificate for use in the GUI
$config['system']['webgui']['ssl-certref'] = $cert['refid'];

write_config();

// Restart GUI
configd_run('webgui restart 2', true);
