HAProxy is a PHP Class to implement HAProxy integration within non compliant ORMs or vanilla frameworks.

What it does?
-------------
It just chooses the right connection where to send Queries. Master, or readonly. Also know as Read Write Splitting.



EXAMPLE
-------

For Vanilla FW
---------------

use CSI\Drivers\PDO

$proxy = new HAProxy();
$proxy->CreatMaster('mysql:host=MASTER_DB_HOST;dbname=MASTER_DB_NAME','MASTER_DB_USER','MASTER_DB_PASSWORD');
$proxy->CreateReadonly( 'mysql:host=readonly_DB_HOST;dbname=readonly_DB_NAME','readonly_DB_USER','readonly_DB_PASSWORD');


// Framework::setup($proxy);


For PDO override
----------------------------------

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
