<?php
$host = '186.192.240.6';
$community = 'public';
$object_id = '1.3.6.1.2.1.2.2.1.2';
$sysdesc = snmpwalk($host, $community, $object_id);
print_r($sysdesc);

# Identificadores
# system
# hrStorage

?>