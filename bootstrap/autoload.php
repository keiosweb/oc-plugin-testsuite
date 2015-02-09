<?php

/*#########################################
 * Load plugin host OctoberCMS autoloader #
 *****************************************/

require __DIR__ . '/../../../../../../../bootstrap/autoload.php';

/*####################################
 * Load plugin's composer autoloader #
 ************************************/

require __DIR__ . '/../../../../vendor/autoload.php';

/*######################################
 * Load October Plugin Test Case Class #
 **************************************/

require __DIR__ . '/../OctoberPluginTestCase.php';
