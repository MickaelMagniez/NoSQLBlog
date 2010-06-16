<?php
/**
 * Config
 *
 * @author Michael Pearson <pandra-support@phpgrease.net>
 * @copyright 2010 phpgrease.net
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @version 0.2
 * @package pandra
 */
//error_reporting(E_ALL);
$GLOBALS['THRIFT_ROOT'] = dirname(__FILE__).'/thrift-php/';

require_once $GLOBALS['THRIFT_ROOT'].'/packages/cassandra/Cassandra.php';
require_once $GLOBALS['THRIFT_ROOT'].'/transport/TSocket.php';
require_once $GLOBALS['THRIFT_ROOT'].'/protocol/TBinaryProtocol.php';
require_once $GLOBALS['THRIFT_ROOT'].'/transport/TFramedTransport.php';
require_once $GLOBALS['THRIFT_ROOT'].'/transport/TBufferedTransport.php';

// Config xml path for Cassandra
define('CASSANDRA_CONF_PATH', __CONF_PATH . '/storage/cassandra/storage-conf.xml');

define('MODEL_OUT_DIR', dirname(__FILE__).'/models/');
define('SCHEMA_PATH', dirname(__FILE__).'/schemas/');
define('THRIFT_PORT_DEFAULT', 9160);
define('DEFAULT_ROW_LIMIT', 10);
define('PERSIST_CONNECTIONS', FALSE); // TSocket Persistence

function _pandraAutoLoad($className) {

    // seperate classes and interfaces for clarity
    $fExt = array('.class.php', '.interface.php');

    // strip prefix
    $className = preg_replace('/^pandra/i', '', $className);

    // class path relative to config
    $classPath = dirname(__FILE__)."/lib/";

    if (preg_match('/^(Query|Clause)/', $className)) {
        $classPath .= 'query/';
    } elseif (preg_match('/^Log/', $className)) {
        $classPath .= 'logging/';
    }

    foreach ($fExt as $ext) {
        $classFile = $classPath.$className.$ext;
        if (file_exists($classFile)) {
            require_once($classFile);
            break;
        // Check if it's an external class we might know about
        } else if (file_exists($classPath.'/ext/'.$className.$ext)) {
            require_once($classPath.'/ext/'.$className.$ext);
            break;
        }
    }
}
spl_autoload_register('_pandraAutoLoad');
define('PANDRA_64', PHP_INT_SIZE == 8);
?>
