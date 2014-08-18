HAProxy
=============


##Introduction

This project exposes the following **namespaces** for use in your custom use:
* CSI\Drivers\PDO\HAProxy



## EXAMPLE
-------

For Vanilla FW


    	use CSI\Drivers\PDO

    	$proxy = new HAProxy();
    	$proxy->CreatMaster('mysql:host=MASTER_DB_HOST;dbname=MASTER_DB_NAME','MASTER_DB_USER','MASTER_DB_PASSWORD');
    	$proxy->CreateReadonly( 'mysql:host=readonly_DB_HOST;dbname=readonly_DB_NAME','readonly_DB_USER','readonly_DB_PASSWORD');


    	// Framework::setup($proxy);


For PDO override


    	use CSI\Drivers\PDO\HAProxy as PDO;

or:

    	if ($dsn instanceof PDO || $dsn instanceof HAProxy) {

    	This line happens twice on current RedBean v3.0.1, on lines 29 and 1937 on rb.php file. Repalce both.

or:


    	$SOME_PDO  ...

    	$SOME_PDO = new HAProxy($SOME_PDO);
    	$SOME_PDO->CreateReadonly( 'mysql:host=readonly_DB_HOST;dbname=readonly_DB_NAME','readonly_DB_USER','readonly_DB_PASSWORD');


    	\PDO $SOME_PDO  ...




Limitations
-----------

HAProxy supports the simplest MySQL Replication setup, where you have a Master, and a readonly.



Contributors & Authors
-----------
@t4g
@mauricoder


### About KitCurl
KitCurl is a cURL based proxy service for loading external resources server side (for the times you just forced to).
Why KitCurl over cURL or fopen?
 * Written for server performance and stability.
 * Composer.phar driven
 * Modern PSR-0 coding pattern
 * Access to rationalised cURL configuration, like sane timeouts.
 * Memcached support with transparent failover for easier development installs.
 * Support for multiple memcached servers with failover and load-balancing.
 * Abstracted cache support for rapidly adding support for other caching systems.   
 
## Installation

Installation is done in bash (for now)

1) cd into your Wordpress mu-plugins or plugins directory

    	soap@codex:wp-contents/plugins$
	
2) grab the code
