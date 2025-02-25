<?php

// Observium Includes
require_once $config['install_dir'].'/includes/dbFacile.php';
require_once $config['install_dir'].'/includes/mergecnf.inc.php';

// Connect to database
if ($config['db']['extension'] == 'mysqli') {
    $database_link = mysqli_connect('p:'.$config['db_host'], $config['db_user'], $config['db_pass']);
}
else {
    $database_link = mysql_pconnect($config['db_host'], $config['db_user'], $config['db_pass']);
}

if (!$database_link) {
    echo '<h2>MySQL Error</h2>';
    if ($config['db']['extension'] == 'mysqli') {
        echo mysqli_error($database_link);
    }
    else {
        echo mysql_error();
    }
die;
}

if ($config['db']['extension'] == 'mysqli') {
    $database_db = mysqli_select_db($database_link, $config['db_name']);
}
else {
    $database_db = mysql_select_db($config['db_name'], $database_link);
}

if ($config['memcached']['enable'] === true) {
    if (class_exists('Memcached')) {
        $config['memcached']['ttl']      = 60;
        $config['memcached']['resource'] = new Memcached();
        $config['memcached']['resource']->addServer($config['memcached']['host'], $config['memcached']['port']);
    }
    else {
        echo "WARNING: You have enabled memcached but have not installed the PHP bindings. Disabling memcached support.\n";
        echo "Try 'apt-get install php5-memcached' or 'pecl install memcached'. You will need the php5-dev and libmemcached-dev packages to use pecl.\n\n";
        $config['memcached']['enable'] = 0;
    }
}

$clone = $config;
foreach (dbFetchRows('select config_name,config_value from config') as $obj) {
    $clone = array_replace_recursive($clone, mergecnf($obj));
}

$config = array_replace_recursive($clone, $config);

//
// NO CHANGES TO THIS FILE, IT IS NOT USER-EDITABLE   #
//
// YES, THAT MEANS YOU                   #
//
umask(0002);

$config['os']['default']['over'][0]['graph'] = 'device_processor';
$config['os']['default']['over'][0]['text']  = 'Processor Usage';
$config['os']['default']['over'][1]['graph'] = 'device_mempool';
$config['os']['default']['over'][1]['text']  = 'Memory Usage';

$os_group = 'unix';
$config['os_group'][$os_group]['type']              = 'server';
$config['os_group'][$os_group]['processor_stacked'] = 1;
$config['os_group'][$os_group]['over'][0]['graph']  = 'device_processor';
$config['os_group'][$os_group]['over'][0]['text']   = 'Processor Usage';
$config['os_group'][$os_group]['over'][1]['graph']  = 'device_ucd_memory';
$config['os_group'][$os_group]['over'][1]['text']   = 'Memory Usage';

$os = 'generic';
$config['os'][$os]['text'] = 'Generic Device';

// Linux-based routers/switches
$os = 'vyatta';
$config['os'][$os]['text']             = 'Vyatta';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'Processor Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_ucd_memory';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'vyos';
$config['os'][$os]['text']             = 'VyOS';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'Processor Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_ucd_memory';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'edgeos';
$config['os'][$os]['text']             = 'EdgeOS';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'Processor Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_ucd_memory';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

// Ubiquiti
$os = 'unifi';
$config['os'][$os]['text']             = 'Ubiquiti UniFi';
$config['os'][$os]['type']             = 'wireless';
$config['os'][$os]['icon']             = 'ubiquiti';
$config['os'][$os]['nobulk']           = 1;
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'Processor Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'airos';
$config['os'][$os]['text']             = 'Ubiquiti AirOS';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'ubiquiti';
$config['os'][$os]['nobulk']           = 1;
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';

$os = 'airos-af';
$config['os'][$os]['text']             = 'Ubiquiti AirFiber';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'ubiquiti';
$config['os'][$os]['nobulk']           = 1;
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';


// Linux-based OSes here please.
$os = 'linux';
$config['os'][$os]['type']             = 'server';
$config['os'][$os]['group']            = 'unix';
$config['os'][$os]['text']             = 'Linux';
$config['os'][$os]['ifXmcbc']          = 1;
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['over'][0]['graph'] = 'device_processor';
$config['os'][$os]['over'][0]['text']  = 'Processor Usage';
$config['os'][$os]['over'][1]['graph'] = 'device_ucd_memory';
$config['os'][$os]['over'][1]['text']  = 'Memory Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_storage';
$config['os'][$os]['over'][2]['text']  = 'Storage Usage';

$os = 'qnap';
$config['os'][$os]['type']    = 'storage';
$config['os'][$os]['group']   = 'unix';
$config['os'][$os]['text']    = 'QNAP TurboNAS';
$config['os'][$os]['ifXmcbc'] = 1;

$os = 'netapp';
$config['os'][$os]['type']             = 'storage';
$config['os'][$os]['text']             = 'NetApp';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';

$os = 'endian';
$config['os'][$os]['text']             = 'Endian';
$config['os'][$os]['type']             = 'firewall';
$config['os'][$os]['group']            = 'unix';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'Processor Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'ciscosmblinux';
$config['os'][$os]['type']             = 'wireless';
$config['os'][$os]['group']            = 'unix';
$config['os'][$os]['text']             = 'Cisco SMB Linux';
$config['os'][$os]['icon']             = 'cisco';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'Processor Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'powercode';
$onfig['os'][$os]['type']              = 'server';
$config['os'][$os]['group']            = 'unix';
$config['os'][$os]['text']             = 'Powercode BMU';
$config['os'][$os]['icon']             = 'powercode';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'Processor Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'procera';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['group']            = 'unix';
$config['os'][$os]['text']             = 'Procera Networks';
$config['os'][$os]['icon']             = 'procera';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'Processor Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

// Other Unix-based OSes here please.
$os = 'freebsd';
$config['os'][$os]['type']  = 'server';
$config['os'][$os]['group'] = 'unix';
$config['os'][$os]['text']  = 'FreeBSD';

$os = 'pfsense';
$config['os'][$os]['type']  = 'firewall';
$config['os'][$os]['group'] = 'unix';
$config['os'][$os]['text']  = 'pfSense';

$os = 'openbsd';
$config['os'][$os]['type']  = 'server';
$config['os'][$os]['group'] = 'unix';
$config['os'][$os]['text']  = 'OpenBSD';

$os = 'netbsd';
$config['os'][$os]['type']  = 'server';
$config['os'][$os]['group'] = 'unix';
$config['os'][$os]['text']  = 'NetBSD';

$os = 'dragonfly';
$config['os'][$os]['type']  = 'server';
$config['os'][$os]['group'] = 'unix';
$config['os'][$os]['text']  = 'DragonflyBSD';

$os = 'netware';
$config['os'][$os]['type'] = 'server';
$config['os'][$os]['text'] = 'Novell Netware';
$config['os'][$os]['icon'] = 'novell';

$os = 'monowall';
$config['os'][$os]['group']            = 'unix';
$config['os'][$os]['text']             = 'm0n0wall';
$config['os'][$os]['type']             = 'firewall';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';

$os = 'solaris';
$config['os'][$os]['group'] = 'unix';
$config['os'][$os]['text']  = 'Sun Solaris';
$config['os'][$os]['type']  = 'server';

$os = 'opensolaris';
$config['os'][$os]['type']  = 'server';
$config['os'][$os]['group'] = 'unix';
$config['os'][$os]['text']  = 'Sun OpenSolaris';

$os = 'openindiana';
$config['os'][$os]['type']  = 'server';
$config['os'][$os]['group'] = 'unix';
$config['os'][$os]['text']  = 'OpenIndiana';

// Alcatel
$os = 'aos';
$config['os'][$os]['group']            = 'aos';
$config['os'][$os]['text']             = 'Alcatel-Lucent OS';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['ifXmcbc']          = 1;
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['icon']             = 'alcatellucent';

$os = 'timos';
$config['os'][$os]['group']            = 'timos';
$config['os'][$os]['text']             = 'Alcatel-Lucent TimOS';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['ifXmcbc']          = 1;
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['icon']             = 'alcatellucent';

// Barracuda
$os = 'barracudaloadbalancer';
$config['os'][$os]['text']             = 'Barracuda Load Balancer';
$config['os'][$os]['type']             = 'loadbalancer';
$config['os'][$os]['icon']             = 'barracuda';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';

// Cisco OSes
$os = 'ios';
$config['os'][$os]['group']            = 'cisco';
$config['os'][$os]['text']             = 'Cisco IOS';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['ifXmcbc']          = 1;
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';
$config['os'][$os]['icon']             = 'cisco';

$os = 'acsw';
// $config['os'][$os]['group']            = "cisco";
$config['os'][$os]['text']             = 'Cisco ACE';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['type']             = 'loadbalancer';
$config['os'][$os]['icon']             = 'cisco';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'cat1900';
$config['os'][$os]['group']            = 'cat1900';
$config['os'][$os]['text']             = 'Cisco Catalyst 1900';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'cisco-old';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'iosxe';
$config['os'][$os]['group']            = 'cisco';
$config['os'][$os]['text']             = 'Cisco IOS-XE';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['ifXmcbc']          = 1;
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';
$config['os'][$os]['icon']             = 'cisco';

$os = 'iosxr';
$config['os'][$os]['group']            = 'cisco';
$config['os'][$os]['text']             = 'Cisco IOS-XR';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['ifXmcbc']          = 1;
$config['os'][$os]['icon']             = 'cisco';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'asa';
$config['os'][$os]['group']            = 'cisco';
$config['os'][$os]['text']             = 'Cisco ASA';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['type']             = 'firewall';
$config['os'][$os]['icon']             = 'cisco';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';

$os = 'pixos';
$config['os'][$os]['group']            = 'cisco';
$config['os'][$os]['text']             = 'Cisco PIX-OS';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['type']             = 'firewall';
$config['os'][$os]['icon']             = 'cisco';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'nxos';
$config['os'][$os]['group']            = 'cisco';
$config['os'][$os]['text']             = 'Cisco NX-OS';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'cisco';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'sanos';
$config['os'][$os]['group']            = 'cisco';
$config['os'][$os]['text']             = 'Cisco SAN-OS';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'cisco';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'catos';
$config['os'][$os]['group']            = 'cisco';
$config['os'][$os]['text']             = 'Cisco CatOS';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'cisco-old';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'ciscowlc';
$config['os'][$os]['text']             = 'Cisco WLC';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';
$config['os'][$os]['over'][3]['graph'] = 'device_ciscowlc_numaps';
$config['os'][$os]['over'][3]['text']  = 'Number of APs';
$config['os'][$os]['over'][4]['graph'] = 'device_ciscowlc_numclients';
$config['os'][$os]['over'][4]['text']  = 'Number of Clients';
$config['os'][$os]['icon']             = 'cisco';

// Brocade NOS
$os = 'nos';
$config['os'][$os]['text']             = 'Brocade NOS';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['descr_to_alias']   = 1;
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';
$config['os'][$os]['icon']             = 'brocade';

// Cisco Small Business
$os = 'ciscosb';
$config['os'][$os]['group']            = 'cisco';
$config['os'][$os]['text']             = 'Cisco Small Business';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'linksys';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';

// Huawei
$os = 'vrp';
$config['os'][$os]['group'] = 'vrp';
$config['os'][$os]['text']  = 'Huawei VRP';
$config['os'][$os]['type']  = 'network';
$config['os'][$os]['icon']  = 'huawei';

// ZTE
$os = 'zxr10';
$config['os'][$os]['group'] = 'zxr10';
$config['os'][$os]['text']  = 'ZTE ZXR10';
$config['os'][$os]['type']  = 'network';
$config['os'][$os]['icon']  = 'zte';

// Ruckus Wireless
$os = 'ruckuswireless';
$config['os'][$os]['text']             = 'Ruckus Wireless';
$config['os'][$os]['type']             = 'wireless';
$config['os'][$os]['icon']             = 'ruckus';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';

// Siklu Wireless
$os = 'siklu';
$config['os'][$os]['text']             = 'Siklu Wireless';
$config['os'][$os]['type']             = 'wireless';
$config['os'][$os]['icon']             = 'siklu';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';

// Saf Wireless
$os = 'saf';
$config['os'][$os]['text']             = 'SAF Wireless';
$config['os'][$os]['type']             = 'wireless';
$config['os'][$os]['icon']             = 'saf';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';

// Supermicro Switch
$os = 'supermicro-switch';
$config['os'][$os]['group']  = 'supermicro';
$config['os'][$os]['text']   = 'Supermicro Switch';
$config['os'][$os]['type']   = 'network';
$config['os'][$os]['icon']   = 'supermicro';
$config['os'][$os]['ifname'] = 1;

// Netgear ProSafe switches
$os = 'netgear';
$config['os'][$os]['text']             = 'Netgear ProSafe';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['bad_if'][]         = 'cpu';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';

$os = 'netonix';
$config['os'][$os]['text']             = 'Netonix';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'netonix';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

// Juniper
$os = 'junos';
$config['os'][$os]['text']             = 'Juniper JunOS';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'junose';
$config['os'][$os]['text']             = 'Juniper JunOSe';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'junos';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'jwos';
$config['os'][$os]['text'] = 'Juniper JWOS';
$config['os'][$os]['type'] = 'network';
$config['os'][$os]['icon'] = 'junos';

$os = 'screenos';
$config['os'][$os]['text']             = 'Juniper ScreenOS';
$config['os'][$os]['type']             = 'firewall';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

// Pulse Secure OS definition
$os = 'pulse';
$config['os'][$os]['text']             = 'Pulse Secure';
$config['os'][$os]['type']             = 'firewall';
$config['os'][$os]['icon']             = 'junos';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'fortigate';
$config['os'][$os]['text']             = 'Fortinet Fortigate';
$config['os'][$os]['type']             = 'firewall';
$config['os'][$os]['icon']             = 'fortinet';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_fortigate_cpu';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'routeros';
$config['os'][$os]['text']             = 'Mikrotik RouterOS';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['nobulk']           = 1;
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'ironware';
$config['os'][$os]['text']             = 'Brocade IronWare';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'brocade';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'fabos';
$config['os'][$os]['text']             = 'Brocade FabricOS';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'brocade';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';

$os = 'extremeware';
$config['os'][$os]['text']             = 'Extremeware';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['icon']             = 'extreme';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'packetshaper';
$config['os'][$os]['text'] = 'Blue Coat Packetshaper';
$config['os'][$os]['type'] = 'network';

$os = 'xos';
$config['os'][$os]['text']             = 'Extreme XOS';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['group']            = 'extremeware';
$config['os'][$os]['icon']             = 'extreme';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'ftos';
$config['os'][$os]['text']             = 'Force10 FTOS';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'force10';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'dnos';
$config['os'][$os]['text']             = 'Dell Networking OS';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'dell';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'avaya-ers';
$config['os'][$os]['text']             = 'ERS Firmware';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'avaya';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';

$os = 'avaya-ipo';
$config['os'][$os]['text']             = 'IP Office Firmware';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'avaya';


$os = 'arista_eos';
$config['os'][$os]['text']             = 'Arista EOS';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'arista';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'mellanox';
$config['os'][$os]['text']              = 'Mellanox';
$config['os'][$os]['type']              = 'network';
$config['os'][$os]['over'][0]['graph']  = 'device_bits';
$config['os'][$os]['over'][0]['text']   = 'Device Traffic';
$config['os'][$os]['over'][1]['graph']  = 'device_processor';
$config['os'][$os]['over'][1]['text']   = 'CPU Usage';
$config['os'][$os]['over'][2]['graph']  = 'device_mempool';
$config['os'][$os]['over'][2]['text']   = 'Memory Usage';

$os = 'netscaler';
$config['os'][$os]['text']             = 'Citrix Netscaler';
$config['os'][$os]['type']             = 'loadbalancer';
$config['os'][$os]['icon']             = 'citrix';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';

$os = 'f5';
$config['os'][$os]['text']             = 'F5 Big IP';
$config['os'][$os]['type']             = 'loadbalancer';
$config['os'][$os]['icon']             = 'f5';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_ucd_memory';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'proxim';
$config['os'][$os]['text']             = 'Proxim';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'proxim';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';

$os = 'powerconnect';
$config['os'][$os]['text']             = 'Dell PowerConnect';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'dell';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';

$os = 'radlan';
$config['os'][$os]['text']             = 'Radlan';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
// $config['os'][$os]['over'][2]['graph']  = "device_mempool";
// $config['os'][$os]['over'][2]['text']   = "Memory Usage";
$os = 'powervault';
$config['os'][$os]['text'] = 'Dell PowerVault';
$config['os'][$os]['icon'] = 'dell';

$os = 'equallogic';
$config['os'][$os]['text']             = 'Dell EqualLogic';
$config['os'][$os]['icon']             = 'dell';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';

$os = 'drac';
$config['os'][$os]['text'] = 'Dell DRAC';
$config['os'][$os]['icon'] = 'dell';
$config['os'][$os]['type'] = 'server';

$os = 'bcm963';
$config['os'][$os]['text']             = 'Broadcom BCM963xx';
$config['os'][$os]['icon']             = 'broadcom';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';

$os = 'netopia';
$config['os'][$os]['text'] = 'Motorola Netopia';
$config['os'][$os]['type'] = 'network';

$os = 'tranzeo';
$config['os'][$os]['text']             = 'Tranzeo';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';

$os = 'dlink';
$config['os'][$os]['text']   = 'D-Link Switch';
$config['os'][$os]['type']   = 'network';
$config['os'][$os]['icon']   = 'dlink';
$config['os'][$os]['ifname'] = 1;

$os = 'dlinkap';
$config['os'][$os]['text'] = 'D-Link Access Point';
$config['os'][$os]['type'] = 'wireless';
$config['os'][$os]['icon'] = 'dlink';

// TP-Link
$os = 'tplink';
$config['os'][$os]['text']             = 'TP-Link Switch';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'tplink';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';

$os = 'axiscam';
$config['os'][$os]['text'] = 'AXIS Network Camera';
$config['os'][$os]['icon'] = 'axis';

$os = 'axisdocserver';
$config['os'][$os]['text'] = 'AXIS Network Document Server';
$config['os'][$os]['icon'] = 'axis';

$os = 'gamatronicups';
$config['os'][$os]['text'] = 'Gamatronic UPS Stack';
$config['os'][$os]['type'] = 'power';

$os = 'powerware';
$config['os'][$os]['text']             = 'Powerware UPS';
$config['os'][$os]['type']             = 'power';
$config['os'][$os]['icon']             = 'eaton';
$config['os'][$os]['over'][0]['graph'] = 'device_voltage';
$config['os'][$os]['over'][0]['text']  = 'Voltage';
$config['os'][$os]['over'][1]['graph'] = 'device_current';
$config['os'][$os]['over'][1]['text']  = 'Current';
$config['os'][$os]['over'][2]['graph'] = 'device_frequency';
$config['os'][$os]['over'][2]['text']  = 'Frequencies';

$os = 'deltaups';
$config['os'][$os]['text'] = 'Delta UPS';
$config['os'][$os]['type'] = 'power';
$config['os'][$os]['icon'] = 'delta';

$os = 'liebert';
$config['os'][$os]['text'] = 'Liebert';
$config['os'][$os]['type'] = 'power';
$config['os'][$os]['icon'] = 'liebert';

$os = 'engenius';
$config['os'][$os]['type']             = 'wireless';
$config['os'][$os]['text']             = 'EnGenius Access Point';
$config['os'][$os]['icon']             = 'engenius';
$config['os'][$os]['over'][0]['graph'] = 'device_ucd_cpu';
$config['os'][$os]['over'][0]['text']  = 'Processor Usage';
$config['os'][$os]['over'][1]['graph'] = 'device_ucd_memory';
$config['os'][$os]['over'][1]['text']  = 'Memory Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_bits';
$config['os'][$os]['over'][2]['text']  = 'Device Traffic';

$os = 'airport';
$config['os'][$os]['type'] = 'wireless';
$config['os'][$os]['text'] = 'Apple AirPort';
$config['os'][$os]['icon'] = 'apple';

$os = 'windows';
$config['os'][$os]['type']              = 'server';
$config['os'][$os]['text']              = 'Microsoft Windows';
$config['os'][$os]['ifname']            = 1;
$config['os'][$os]['processor_stacked'] = 1;

$os = 'bnt';
$config['os'][$os]['text'] = 'Blade Network Technologies';
$config['os'][$os]['type'] = 'network';
$config['os'][$os]['icon'] = 'bnt';

$os = 'ibmnos';
$config['os'][$os]['text']             = 'IBM Networking Operating System';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'ibmnos';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';

$os = 'ibmtl';
$config['os'][$os]['text']             = 'IBM Tape Library';
$config['os'][$os]['type']             = 'storage';
$config['os'][$os]['icon']             = 'generic';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';

$os = 'comware';
$config['os'][$os]['text']             = 'HP Comware';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'hp';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';
// $config['os'][$os]['over'][1]['graph']  = "device_processor";
// $config['os'][$os]['over'][1]['text']   = "CPU Usage";
// $config['os'][$os]['over'][2]['graph']  = "device_mempool";
// $config['os'][$os]['over'][2]['text']   = "Memory Usage";
$os = 'procurve';
$config['os'][$os]['text']             = 'HP ProCurve';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'hp';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

$os = 'speedtouch';
$config['os'][$os]['text']             = 'Thomson Speedtouch';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';

$os = 'sonicwall';
$config['os'][$os]['text']             = 'SonicWALL';
$config['os'][$os]['type']             = 'firewall';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory';

$os = 'zywall';
$config['os'][$os]['text']             = 'ZyXEL ZyWALL';
$config['os'][$os]['type']             = 'firewall';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';
$config['os'][$os]['icon']             = 'zyxel';

$os = 'prestige';
$config['os'][$os]['text'] = 'ZyXEL Prestige';
$config['os'][$os]['type'] = 'network';
$config['os'][$os]['icon'] = 'zyxel';

$os = 'zyxeles';
$config['os'][$os]['text'] = 'ZyXEL Ethernet Switch';
$config['os'][$os]['type'] = 'network';
$config['os'][$os]['icon'] = 'zyxel';

$os = 'zyxelnwa';
$config['os'][$os]['text'] = 'ZyXEL NWA';
$config['os'][$os]['type'] = 'network';
$config['os'][$os]['icon'] = 'zyxel';

$os = 'ies';
$config['os'][$os]['text'] = 'ZyXEL DSLAM';
$config['os'][$os]['type'] = 'network';
$config['os'][$os]['icon'] = 'zyxel';

$os = 'allied';
$config['os'][$os]['text']             = 'AlliedWare';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';

$os = 'mgeups';
$config['os'][$os]['text']             = 'MGE UPS';
$config['os'][$os]['group']            = 'ups';
$config['os'][$os]['type']             = 'power';
$config['os'][$os]['icon']             = 'mge';
$config['os'][$os]['over'][0]['graph'] = 'device_current';
$config['os'][$os]['over'][0]['text']  = 'Current';

$os = 'mgepdu';
$config['os'][$os]['text'] = 'MGE PDU';
$config['os'][$os]['type'] = 'power';
$config['os'][$os]['icon'] = 'mge';

$os = 'apc';
$config['os'][$os]['text']             = 'APC Management Module';
$config['os'][$os]['type']             = 'power';
$config['os'][$os]['over'][0]['graph'] = 'device_current';
$config['os'][$os]['over'][0]['text']  = 'Current';

$os = 'webpower';
$config['os'][$os]['text']             = 'WebPower';
$config['os'][$os]['type']             = 'power';
$config['os'][$os]['over'][0]['graph'] = 'device_current';
$config['os'][$os]['over'][0]['text']  = 'Current';

$os = 'netbotz';
$config['os'][$os]['text']             = 'Netbotz Environment sensor';
$config['os'][$os]['type']             = 'environment';
$config['os'][$os]['over'][0]['graph'] = 'device_temperature';
$config['os'][$os]['over'][0]['text']  = 'Temperature';
$config['os'][$os]['over'][1]['graph'] = 'device_humidity';
$config['os'][$os]['over'][1]['text']  = 'Humidity';

$os = 'pcoweb';
$config['os'][$os]['text']             = 'Carel pCOWeb';
$config['os'][$os]['type']             = 'environment';
$config['os'][$os]['over'][0]['graph'] = 'device_temperature';
$config['os'][$os]['over'][0]['text']  = 'Temperature';
$config['os'][$os]['over'][1]['graph'] = 'device_humidity';
$config['os'][$os]['over'][1]['text']  = 'Humidity';
$config['os'][$os]['icon']             = 'carel';
$config['os'][$os]['icons'][]          = 'uniflair';

$os = 'netvision';
$config['os'][$os]['text']             = 'Socomec Net Vision';
$config['os'][$os]['type']             = 'power';
$config['os'][$os]['over'][0]['graph'] = 'device_current';
$config['os'][$os]['over'][0]['text']  = 'Current';

$os = 'areca';
$config['os'][$os]['text']             = 'Areca RAID Subsystem';
$config['os'][$os]['over'][0]['graph'] = '';
$config['os'][$os]['over'][0]['text']  = '';

$os = 'netmanplus';
$config['os'][$os]['text']             = 'NetMan Plus';
$config['os'][$os]['group']            = 'ups';
$config['os'][$os]['nobulk']           = 1;
$config['os'][$os]['type']             = 'power';
$config['os'][$os]['over'][0]['graph'] = 'device_current';
$config['os'][$os]['over'][0]['text']  = 'Current';

$os = 'akcp';
$config['os'][$os]['text']             = 'AKCP SensorProbe';
$config['os'][$os]['type']             = 'environment';
$config['os'][$os]['over'][0]['graph'] = 'device_temperature';
$config['os'][$os]['over'][0]['text']  = 'temperature';

$os = 'minkelsrms';
$config['os'][$os]['text']             = 'Minkels RMS';
$config['os'][$os]['type']             = 'environment';
$config['os'][$os]['over'][0]['graph'] = 'device_temperature';
$config['os'][$os]['over'][0]['text']  = 'temperature';

$os = 'ipoman';
$config['os'][$os]['text']             = 'Ingrasys iPoMan';
$config['os'][$os]['type']             = 'power';
$config['os'][$os]['icon']             = 'ingrasys';
$config['os'][$os]['over'][0]['graph'] = 'device_current';
$config['os'][$os]['over'][0]['text']  = 'Current';
$config['os'][$os]['over'][1]['graph'] = 'device_power';
$config['os'][$os]['over'][1]['text']  = 'Power';

$os = 'wxgoos';
$config['os'][$os]['text']             = 'ITWatchDogs Goose';
$config['os'][$os]['type']             = 'environment';
$config['os'][$os]['over'][0]['graph'] = 'device_temperature';
$config['os'][$os]['over'][0]['text']  = 'temperature';

$os = 'papouch-tme';
$config['os'][$os]['text']             = 'Papouch TME';
$config['os'][$os]['type']             = 'environment';
$config['os'][$os]['over'][0]['graph'] = 'device_temperature';
$config['os'][$os]['over'][0]['text']  = 'temperature';

$os = 'cometsystem-p85xx';
$config['os'][$os]['text']             = 'Comet System P85xx';
$config['os'][$os]['type']             = 'environment';
$config['os'][$os]['icon']             = 'comet';
$config['os'][$os]['over'][0]['graph'] = 'device_temperature';
$config['os'][$os]['over'][0]['text']  = 'temperature';

$os = 'dell-laser';
$config['os'][$os]['group']            = 'printer';
$config['os'][$os]['text']             = 'Dell Laser';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['type']             = 'printer';
$config['os'][$os]['icon']             = 'dell';
$config['os'][$os]['over'][0]['graph'] = 'device_toner';
$config['os'][$os]['over'][0]['text']  = 'Toner';

$os = 'ricoh';
$config['os'][$os]['group']            = 'printer';
$config['os'][$os]['text']             = 'Ricoh Printer';
$config['os'][$os]['type']             = 'printer';
$config['os'][$os]['icon']             = 'ricoh';
$config['os'][$os]['over'][0]['graph'] = 'device_toner';
$config['os'][$os]['over'][0]['text']  = 'Toner';

$os = 'nrg';
$config['os'][$os]['group']            = 'printer';
$config['os'][$os]['text']             = 'NRG Printer';
$config['os'][$os]['type']             = 'printer';
$config['os'][$os]['icon']             = 'nrg';
$config['os'][$os]['over'][0]['graph'] = 'device_toner';
$config['os'][$os]['over'][0]['text']  = 'Toner';

$os = 'epson';
$config['os'][$os]['group']            = 'printer';
$config['os'][$os]['text']             = 'Epson Printer';
$config['os'][$os]['type']             = 'printer';
$config['os'][$os]['icon']             = 'epson';
$config['os'][$os]['over'][0]['graph'] = 'device_toner';
$config['os'][$os]['over'][0]['text']  = 'Toner';

$os = 'xerox';
$config['os'][$os]['group']            = 'printer';
$config['os'][$os]['text']             = 'Xerox Printer';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['type']             = 'printer';
$config['os'][$os]['over'][0]['graph'] = 'device_toner';
$config['os'][$os]['over'][0]['text']  = 'Toner';

$os = 'jetdirect';
$config['os'][$os]['group']            = 'printer';
$config['os'][$os]['text']             = 'HP Print server';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['type']             = 'printer';
$config['os'][$os]['icon']             = 'hp';
$config['os'][$os]['over'][0]['graph'] = 'device_toner';
$config['os'][$os]['over'][0]['text']  = 'Toner';

$os = 'richoh';
$config['os'][$os]['group']            = 'printer';
$config['os'][$os]['text']             = 'Ricoh Printer';
$config['os'][$os]['type']             = 'printer';
$config['os'][$os]['over'][0]['graph'] = 'device_toner';
$config['os'][$os]['over'][0]['text']  = 'Toner';

$os = 'okilan';
$config['os'][$os]['group']       = 'printer';
$config['os'][$os]['text']        = 'OKI Printer';
$config['os'][$os]['overgraph'][] = 'device_toner';
$config['os'][$os]['overtext']    = 'Toner';
$config['os'][$os]['type']        = 'printer';
$config['os'][$os]['icon']        = 'oki';

$os = 'brother';
$config['os'][$os]['group']            = 'printer';
$config['os'][$os]['text']             = 'Brother Printer';
$config['os'][$os]['type']             = 'printer';
$config['os'][$os]['over'][0]['graph'] = 'device_toner';
$config['os'][$os]['over'][0]['text']  = 'Toner';

$os = 'konica';
$config['os'][$os]['group']            = 'printer';
$config['os'][$os]['text']             = 'Konica-Minolta Printer';
$config['os'][$os]['type']             = 'printer';
$config['os'][$os]['over'][0]['graph'] = 'device_toner';
$config['os'][$os]['over'][0]['text']  = 'Toner';

$os = 'kyocera';
$config['os'][$os]['group']            = 'printer';
$config['os'][$os]['text']             = 'Kyocera Mita Printer';
$config['os'][$os]['over'][0]['graph'] = 'device_toner';
$config['os'][$os]['over'][0]['text']  = 'Toner';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['type']             = 'printer';

$os = '3com';
$config['os'][$os]['text']             = '3Com';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';
$config['os'][$os]['type']             = 'network';

$os = 'sentry3';
$config['os'][$os]['text']             = 'ServerTech Sentry3';
$config['os'][$os]['type']             = 'power';
$config['os'][$os]['over'][0]['graph'] = 'device_current';
$config['os'][$os]['over'][0]['text']  = 'Current';
$config['os'][$os]['icon']             = 'servertech';

$os = 'raritan';
$config['os'][$os]['text']             = 'Raritan PDU';
$config['os'][$os]['type']             = 'power';
$config['os'][$os]['over'][0]['graph'] = 'device_current';
$config['os'][$os]['over'][0]['text']  = 'Current';
$config['os'][$os]['icon']             = 'raritan';

$os = 'vmware';
$config['os'][$os]['type']             = 'server';
$config['os'][$os]['text']             = 'VMware';
$config['os'][$os]['ifXmcbc']          = 1;
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';

$os = 'mrvld';
$config['os'][$os]['group'] = 'mrv';
$config['os'][$os]['text']  = 'MRV LambdaDriver';
$config['os'][$os]['type']  = 'network';
$config['os'][$os]['icon']  = 'mrv';

$os = 'poweralert';
$config['os'][$os]['text']             = 'Tripp Lite PowerAlert';
$config['os'][$os]['type']             = 'power';
$config['os'][$os]['over'][0]['graph'] = 'device_current';
$config['os'][$os]['over'][0]['text']  = 'Current';
$config['os'][$os]['icon']             = 'tripplite';

$os = 'avocent';
$config['os'][$os]['text'] = 'Avocent';
$config['os'][$os]['type'] = 'network';
$config['os'][$os]['icon'] = 'avocent';

$os = 'symbol';
$config['os'][$os]['type'] = 'network';
$config['os'][$os]['text'] = 'Symbol AP';
$config['os'][$os]['icon'] = 'symbol';

$os = 'firebox';
$config['os'][$os]['text']             = 'Watchguard Firebox';
$config['os'][$os]['type']             = 'firewall';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['icon']             = 'watchguard';

$os = 'fireware';
$config['os'][$os]['text']             = 'Watchguard Fireware';
$config['os'][$os]['type']             = 'firewall';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['icon']             = 'watchguard';

$os = 'panos';
$config['os'][$os]['text'] = 'PanOS';
$config['os'][$os]['type'] = 'firewall';
$config['os'][$os]['icon'] = 'panos';

$os = 'arubaos';
$config['os'][$os]['text']             = 'ArubaOS';
$config['os'][$os]['type']             = 'wireless';
$config['os'][$os]['icon']             = 'arubaos';
$config['os'][$os]['over'][0]['graph'] = 'device_arubacontroller_numaps';
$config['os'][$os]['over'][0]['text']  = 'Number of APs';
$config['os'][$os]['over'][1]['graph'] = 'device_arubacontroller_numclients';
$config['os'][$os]['over'][1]['text']  = 'Number of Clients';

$os = 'dsm';
$config['os'][$os]['text']  = 'Synology DSM';
$config['os'][$os]['group'] = 'unix';
$config['os'][$os]['type']  = 'storage';
$config['os'][$os]['icon']  = 'synology';

$os = 'hikvision';
$config['os'][$os]['text'] = 'Hikvision';
$config['os'][$os]['type'] = 'network';
$config['os'][$os]['icon'] = 'hikvision';

// Canopy / Cambium support
$os = 'canopy';
$config['os'][$os]['text'] = 'Cambium';
$config['os'][$os]['type'] = 'wireless';
$config['os'][$os]['icon'] = 'cambium';

$os = 'datacom';
$config['os'][$os]['text'] = 'Datacom';
$config['os'][$os]['type'] = 'network';
$config['os'][$os]['icon'] = 'datacom';

// UBNT EdgeSwitch 750W
$os = 'edgeswitch';
$config['os'][$os]['text']             = 'EdgeSwitch';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'ubiquiti';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['ifname']           = 1;

// Fiberhome
$os = 'fiberhome';
$config['os'][$os]['text'] = 'Fiberhome';
$config['os'][$os]['type'] = 'network';
$config['os'][$os]['icon'] = 'fiberhome';

// PBN, Pacific Broadband Networks
$os = 'pbn';
$config['os'][$os]['text']             = 'PBN';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['ifXmcbc']          = 1;
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['icon']             = 'pbn';

// Enterasys
$os = 'enterasys';
$config['os'][$os]['text']             = 'Enterasys';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';
$config['os'][$os]['icon']             = 'enterasys';

// Multimatic UPS (Generex CS121 SNMP Adapter)
$os = 'multimatic';
$config['os'][$os]['text'] = 'Multimatic UPS';
$config['os'][$os]['type'] = 'power';
$config['os'][$os]['icon'] = 'multimatic';

// Huawei UPS
$os = 'huaweiups';
$config['os'][$os]['text']             = 'Huawei UPS';
$config['os'][$os]['group']            = 'ups';
$config['os'][$os]['type']             = 'power';
$config['os'][$os]['icon']             = 'huawei';
$config['os'][$os]['over'][0]['graph'] = 'device_current';
$config['os'][$os]['over'][0]['text']  = 'Current';

foreach ($config['os'] as $this_os => $blah) {
    if (isset($config['os'][$this_os]['group'])) {
        $this_os_group = $config['os'][$this_os]['group'];
        if (isset($config['os_group'][$this_os_group])) {
            foreach ($config['os_group'][$this_os_group] as $property => $value) {
                if (!isset($config['os'][$this_os][$property])) {
                    $config['os'][$this_os][$property] = $value;
                }
            }
        }
    }
}

// Meraki Devices
$os = 'merakimx';
$config['os'][$os]['text']             = 'Meraki MX Appliance';
$config['os'][$os]['type']             = 'firewall';
$config['os'][$os]['icon']             = 'meraki';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';

$os = 'merakimr';
$config['os'][$os]['text']             = 'Meraki AP';
$config['os'][$os]['type']             = 'wireless';
$config['os'][$os]['icon']             = 'meraki';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';

$os = 'merakims';
$config['os'][$os]['text']             = 'Meraki Switch';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'meraki';
$config['os'][$os]['ifname']           = 1;
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Device Traffic';

// Aerohive
$os = 'aerohive';
$config['os'][$os]['text']             = 'Aerohive HiveOS';
$config['os'][$os]['type']             = 'wireless';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';

// Perle
$os = 'perle';
$config['os'][$os]['text']             = 'Perle';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'perle';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';

// MACOSX
$os = 'macosx';
$config['os'][$os]['text']             = 'Apple OS X';
$config['os'][$os]['type']             = 'server';
$config['os'][$os]['icon']             = 'generic';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';

// HP MSM 
$os = 'hpmsm';
$config['os'][$os]['text']             = 'HP MSM';
$config['os'][$os]['type']             = 'wireless';
$config['os'][$os]['icon']             = 'hp';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';

// Riverbed
$os = 'riverbed';
$config['os'][$os]['text']             = 'Riverbed';
$config['os'][$os]['type']             = 'network';
$config['os'][$os]['icon']             = 'riverbed';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';

// Appliances
$os = 'fortios';
$config['os'][$os]['text']             = 'FortiOS';
$config['os'][$os]['type']             = 'appliance';
$config['os'][$os]['icon']             = 'fortios';
$config['os'][$os]['over'][0]['graph'] = 'device_bits';
$config['os'][$os]['over'][0]['text']  = 'Traffic';
$config['os'][$os]['over'][1]['graph'] = 'device_processor';
$config['os'][$os]['over'][1]['text']  = 'CPU Usage';
$config['os'][$os]['over'][2]['graph'] = 'device_mempool';
$config['os'][$os]['over'][2]['text']  = 'Memory Usage';

// Graph Types
require_once $config['install_dir'].'/includes/load_db_graph_types.inc.php';


// Device - Wireless - AirMAX
$config['graph_types']['device']['ubnt_airmax_WlStatStaCount']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airmax_WlStatStaCount']['order'] = '0';
$config['graph_types']['device']['ubnt_airmax_WlStatStaCount']['descr'] = 'Wireless Clients';

$config['graph_types']['device']['ubnt_airmax_RadioDistance']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airmax_RadioDistance']['order'] = '1';
$config['graph_types']['device']['ubnt_airmax_RadioDistance']['descr'] = 'Radio Distance';

$config['graph_types']['device']['ubnt_airmax_RadioFreq']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airmax_RadioFreq']['order'] = '2';
$config['graph_types']['device']['ubnt_airmax_RadioFreq']['descr'] = 'Radio Frequency';

$config['graph_types']['device']['ubnt_airmax_RadioTxPower']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airmax_RadioTxPower']['order'] = '3';
$config['graph_types']['device']['ubnt_airmax_RadioTxPower']['descr'] = 'Radio Tx Power';

$config['graph_types']['device']['ubnt_airmax_RadioRssi_0']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airmax_RadioRssi_0']['order'] = '4';
$config['graph_types']['device']['ubnt_airmax_RadioRssi_0']['descr'] = 'Radio Rssi Chain 0';

$config['graph_types']['device']['ubnt_airmax_RadioRssi_1']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airmax_RadioRssi_1']['order'] = '5';
$config['graph_types']['device']['ubnt_airmax_RadioRssi_1']['descr'] = 'Radio Rssi Chain 1';

$config['graph_types']['device']['ubnt_airmax_WlStatSignal']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airmax_WlStatSignal']['order'] = '6';
$config['graph_types']['device']['ubnt_airmax_WlStatSignal']['descr'] = 'Radio Signal';

$config['graph_types']['device']['ubnt_airmax_WlStatRssi']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airmax_WlStatRssi']['order'] = '7';
$config['graph_types']['device']['ubnt_airmax_WlStatRssi']['descr'] = 'Radio Overall RSSI';

$config['graph_types']['device']['ubnt_airmax_WlStatCcq']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airmax_WlStatCcq']['order'] = '8';
$config['graph_types']['device']['ubnt_airmax_WlStatCcq']['descr'] = 'Radio CCQ';

$config['graph_types']['device']['ubnt_airmax_WlStatNoiseFloor']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airmax_WlStatNoiseFloor']['order'] = '10';
$config['graph_types']['device']['ubnt_airmax_WlStatNoiseFloor']['descr'] = 'Radio Noise Floor';

$config['graph_types']['device']['ubnt_airmax_WlStatTxRate']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airmax_WlStatTxRate']['order'] = '11';
$config['graph_types']['device']['ubnt_airmax_WlStatTxRate']['descr'] = 'Radio Tx Rate';

$config['graph_types']['device']['ubnt_airmax_WlStatRxRate']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airmax_WlStatRxRate']['order'] = '12';
$config['graph_types']['device']['ubnt_airmax_WlStatRxRate']['descr'] = 'Radio Rx Rate';

$config['graph_types']['device']['ubnt_airmax_AirMaxQuality']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airmax_AirMaxQuality']['order'] = '13';
$config['graph_types']['device']['ubnt_airmax_AirMaxQuality']['descr'] = 'AirMax Quality';

$config['graph_types']['device']['ubnt_airmax_AirMaxCapacity']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airmax_AirMaxCapacity']['order'] = '14';
$config['graph_types']['device']['ubnt_airmax_AirMaxCapacity']['descr'] = 'AirMax Capacity';

// Device  - AirFIBER
$config['graph_types']['device']['ubnt_airfiber_RadioFreqs']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airfiber_RadioFreqs']['order'] = '0';
$config['graph_types']['device']['ubnt_airfiber_RadioFreqs']['descr'] = 'Radio Frequencies';

$config['graph_types']['device']['ubnt_airfiber_TxPower']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airfiber_TxPower']['order'] = '0';
$config['graph_types']['device']['ubnt_airfiber_TxPower']['descr'] = 'Radio Tx Power';

$config['graph_types']['device']['ubnt_airfiber_LinkDist']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airfiber_LinkDist']['order'] = '1';
$config['graph_types']['device']['ubnt_airfiber_LinkDist']['descr'] = 'Link Distance';

$config['graph_types']['device']['ubnt_airfiber_Capacity']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airfiber_Capacity']['order'] = '2';
$config['graph_types']['device']['ubnt_airfiber_Capacity']['descr'] = 'Link Capacity';

$config['graph_types']['device']['ubnt_airfiber_RadioTemp']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airfiber_RadioTemp']['order'] = '3';
$config['graph_types']['device']['ubnt_airfiber_RadioTemp']['descr'] = 'Radio Temperatures';

$config['graph_types']['device']['ubnt_airfiber_RFTotOctetsTx']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airfiber_RFTotOctetsTx']['order'] = '4';
$config['graph_types']['device']['ubnt_airfiber_RFTotOctetsTx']['descr'] = 'RF Total Octets Tx';

$config['graph_types']['device']['ubnt_airfiber_RFTotPktsTx']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airfiber_RFTotPktsTx']['order'] = '5';
$config['graph_types']['device']['ubnt_airfiber_RFTotPktsTx']['descr'] = 'RF Total Packets Tx';

$config['graph_types']['device']['ubnt_airfiber_RFTotOctetsRx']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airfiber_RFTotOctetsRx']['order'] = '6';
$config['graph_types']['device']['ubnt_airfiber_RFTotOctetsRx']['descr'] = 'RF Total Octets Rx';

$config['graph_types']['device']['ubnt_airfiber_RFTotPktsRx']['section'] = 'wireless';
$config['graph_types']['device']['ubnt_airfiber_RFTotPktsRx']['order'] = '7';
$config['graph_types']['device']['ubnt_airfiber_RFTotPktsRx']['descr'] = 'RF Total Packets Rx';

// Siklu support
$config['graph_types']['device']['siklu_rfAverageRssi']['section'] = 'wireless';
$config['graph_types']['device']['siklu_rfAverageRssi']['order'] = '0';
$config['graph_types']['device']['siklu_rfAverageRssi']['descr'] = 'Radio Average RSSI';

$config['graph_types']['device']['siklu_rfAverageCinr']['section'] = 'wireless';
$config['graph_types']['device']['siklu_rfAverageCinr']['order'] = '1';
$config['graph_types']['device']['siklu_rfAverageCinr']['descr'] = 'Radio Average CINR';

$config['graph_types']['device']['siklu_rfOperationalFrequency']['section'] = 'wireless';
$config['graph_types']['device']['siklu_rfOperationalFrequency']['order'] = '2';
$config['graph_types']['device']['siklu_rfOperationalFrequency']['descr'] = 'Operational Frequency';

$config['graph_types']['device']['siklu_rfinterfacePkts']['section'] = 'wireless';
$config['graph_types']['device']['siklu_rfinterfacePkts']['order'] = '3';
$config['graph_types']['device']['siklu_rfinterfacePkts']['descr'] = 'Packets';

$config['graph_types']['device']['siklu_rfinterfaceOtherPkts']['section'] = 'wireless';
$config['graph_types']['device']['siklu_rfinterfaceOtherPkts']['order'] = '4';
$config['graph_types']['device']['siklu_rfinterfaceOtherPkts']['descr'] = 'Other Packets';

$config['graph_types']['device']['siklu_rfinterfaceOctets']['section'] = 'wireless';
$config['graph_types']['device']['siklu_rfinterfaceOctets']['order'] = '5';
$config['graph_types']['device']['siklu_rfinterfaceOctets']['descr'] = 'Traffic';

$config['graph_types']['device']['siklu_rfinterfaceOtherOctets']['section'] = 'wireless';
$config['graph_types']['device']['siklu_rfinterfaceOtherOctets']['order'] = '6';
$config['graph_types']['device']['siklu_rfinterfaceOtherOctets']['descr'] = 'Other Octets';

$config['graph_types']['device']['wifi_clients']['section'] = 'wireless';
$config['graph_types']['device']['wifi_clients']['order']   = '0';
$config['graph_types']['device']['wifi_clients']['descr']   = 'Wireless Clients';

$config['graph_types']['device']['agent']['section'] = 'poller';
$config['graph_types']['device']['agent']['order']   = '0';
$config['graph_types']['device']['agent']['descr']   = 'Agent Execution Time';

$config['graph_types']['device']['cipsec_flow_bits']['section']    = 'firewall';
$config['graph_types']['device']['cipsec_flow_bits']['order']      = '0';
$config['graph_types']['device']['cipsec_flow_bits']['descr']      = 'IPSec Tunnel Traffic Volume';
$config['graph_types']['device']['cipsec_flow_pkts']['section']    = 'firewall';
$config['graph_types']['device']['cipsec_flow_pkts']['order']      = '0';
$config['graph_types']['device']['cipsec_flow_pkts']['descr']      = 'IPSec Tunnel Traffic Packets';
$config['graph_types']['device']['cipsec_flow_stats']['section']   = 'firewall';
$config['graph_types']['device']['cipsec_flow_stats']['order']     = '0';
$config['graph_types']['device']['cipsec_flow_stats']['descr']     = 'IPSec Tunnel Statistics';
$config['graph_types']['device']['cipsec_flow_tunnels']['section'] = 'firewall';
$config['graph_types']['device']['cipsec_flow_tunnels']['order']   = '0';
$config['graph_types']['device']['cipsec_flow_tunnels']['descr']   = 'IPSec Active Tunnels';
$config['graph_types']['device']['cras_sessions']['section']       = 'firewall';
$config['graph_types']['device']['cras_sessions']['order']         = '0';
$config['graph_types']['device']['cras_sessions']['descr']         = 'Remote Access Sessions';
$config['graph_types']['device']['fortigate_sessions']['section']  = 'firewall';
$config['graph_types']['device']['fortigate_sessions']['order']    = '0';
$config['graph_types']['device']['fortigate_sessions']['descr']    = 'Active Sessions';
$config['graph_types']['device']['fortigate_cpu']['section']       = 'system';
$config['graph_types']['device']['fortigate_cpu']['order']         = '0';
$config['graph_types']['device']['fortigate_cpu']['descr']         = 'CPU';
$config['graph_types']['device']['screenos_sessions']['section']   = 'firewall';
$config['graph_types']['device']['screenos_sessions']['order']     = '0';
$config['graph_types']['device']['screenos_sessions']['descr']     = 'Active Sessions';
$config['graph_types']['device']['panos_sessions']['section']      = 'firewall';
$config['graph_types']['device']['panos_sessions']['order']        = '0';
$config['graph_types']['device']['panos_sessions']['descr']        = 'Active Sessions';

//Pulse Secure Graphs
$config['graph_types']['device']['pulse_users']['section']         = 'firewall';
$config['graph_types']['device']['pulse_users']['order']           = '0';
$config['graph_types']['device']['pulse_users']['descr']           = 'Active Users';
$config['graph_types']['device']['pulse_sessions']['section']      = 'firewall';
$config['graph_types']['device']['pulse_sessions']['order']        = '0';
$config['graph_types']['device']['pulse_sessions']['descr']        = 'Active Sessions';

$config['graph_types']['device']['bits']['section']               = 'netstats';
$config['graph_types']['device']['bits']['order']                 = '0';
$config['graph_types']['device']['bits']['descr']                 = 'Total Traffic';
$config['graph_types']['device']['ipsystemstats_ipv4']['section'] = 'netstats';
$config['graph_types']['device']['ipsystemstats_ipv4']['order']   = '0';
$config['graph_types']['device']['ipsystemstats_ipv4']['descr']   = 'IPv4 Packet Statistics';
$config['graph_types']['device']['ipsystemstats_ipv4_frag']['section'] = 'netstats';
$config['graph_types']['device']['ipsystemstats_ipv4_frag']['order']   = '0';
$config['graph_types']['device']['ipsystemstats_ipv4_frag']['descr']   = 'IPv4 Fragmentation Statistics';
$config['graph_types']['device']['ipsystemstats_ipv6']['section']      = 'netstats';
$config['graph_types']['device']['ipsystemstats_ipv6']['order']        = '0';
$config['graph_types']['device']['ipsystemstats_ipv6']['descr']        = 'IPv6 Packet Statistics';
$config['graph_types']['device']['ipsystemstats_ipv6_frag']['section'] = 'netstats';
$config['graph_types']['device']['ipsystemstats_ipv6_frag']['order']   = '0';
$config['graph_types']['device']['ipsystemstats_ipv6_frag']['descr']   = 'IPv6 Fragmentation Statistics';
$config['graph_types']['device']['netstat_icmp_info']['section']       = 'netstats';
$config['graph_types']['device']['netstat_icmp_info']['order']         = '0';
$config['graph_types']['device']['netstat_icmp_info']['descr']         = 'ICMP Informational Statistics';
$config['graph_types']['device']['netstat_icmp']['section']            = 'netstats';
$config['graph_types']['device']['netstat_icmp']['order']              = '0';
$config['graph_types']['device']['netstat_icmp']['descr']              = 'ICMP Statistics';
$config['graph_types']['device']['netstat_ip']['section']              = 'netstats';
$config['graph_types']['device']['netstat_ip']['order']                = '0';
$config['graph_types']['device']['netstat_ip']['descr']                = 'IP Statistics';
$config['graph_types']['device']['netstat_ip_frag']['section']         = 'netstats';
$config['graph_types']['device']['netstat_ip_frag']['order']           = '0';
$config['graph_types']['device']['netstat_ip_frag']['descr']           = 'IP Fragmentation Statistics';
$config['graph_types']['device']['netstat_snmp']['section']            = 'netstats';
$config['graph_types']['device']['netstat_snmp']['order']              = '0';
$config['graph_types']['device']['netstat_snmp']['descr']              = 'SNMP Statistics';
$config['graph_types']['device']['netstat_snmp_pkt']['section']        = 'netstats';
$config['graph_types']['device']['netstat_snmp_pkt']['order']          = '0';
$config['graph_types']['device']['netstat_snmp_pkt']['descr']          = 'SNMP Packet Type Statistics';

$config['graph_types']['device']['netstat_ip_forward']['section'] = 'netstats';
$config['graph_types']['device']['netstat_ip_forward']['order']   = '0';
$config['graph_types']['device']['netstat_ip_forward']['descr']   = 'IP Forwarding Statistics';

$config['graph_types']['device']['netstat_tcp']['section'] = 'netstats';
$config['graph_types']['device']['netstat_tcp']['order']   = '0';
$config['graph_types']['device']['netstat_tcp']['descr']   = 'TCP Statistics';
$config['graph_types']['device']['netstat_udp']['section'] = 'netstats';
$config['graph_types']['device']['netstat_udp']['order']   = '0';
$config['graph_types']['device']['netstat_udp']['descr']   = 'UDP Statistics';

$config['graph_types']['device']['fdb_count']['section']      = 'system';
$config['graph_types']['device']['fdb_count']['order']        = '0';
$config['graph_types']['device']['fdb_count']['descr']        = 'MAC Addresses Learnt';
$config['graph_types']['device']['hr_processes']['section']   = 'system';
$config['graph_types']['device']['hr_processes']['order']     = '0';
$config['graph_types']['device']['hr_processes']['descr']     = 'Running Processes';
$config['graph_types']['device']['hr_users']['section']       = 'system';
$config['graph_types']['device']['hr_users']['order']         = '0';
$config['graph_types']['device']['hr_users']['descr']         = 'Users Logged In';
$config['graph_types']['device']['mempool']['section']        = 'system';
$config['graph_types']['device']['mempool']['order']          = '0';
$config['graph_types']['device']['mempool']['descr']          = 'Memory Pool Usage';
$config['graph_types']['device']['processor']['section']      = 'system';
$config['graph_types']['device']['processor']['order']        = '0';
$config['graph_types']['device']['processor']['descr']        = 'Processor Usage';
$config['graph_types']['device']['storage']['section']        = 'system';
$config['graph_types']['device']['storage']['order']          = '0';
$config['graph_types']['device']['storage']['descr']          = 'Filesystem Usage';
$config['graph_types']['device']['temperature']['section']    = 'system';
$config['graph_types']['device']['temperature']['order']      = '0';
$config['graph_types']['device']['temperature']['descr']      = 'temperature';
$config['graph_types']['device']['charge']['section']         = 'system';
$config['graph_types']['device']['charge']['order']           = '0';
$config['graph_types']['device']['charge']['descr']           = 'Battery Charge';
$config['graph_types']['device']['ucd_cpu']['section']        = 'system';
$config['graph_types']['device']['ucd_cpu']['order']          = '0';
$config['graph_types']['device']['ucd_cpu']['descr']          = 'Detailed Processor Usage';
$config['graph_types']['device']['ucd_load']['section']       = 'system';
$config['graph_types']['device']['ucd_load']['order']         = '0';
$config['graph_types']['device']['ucd_load']['descr']         = 'Load Averages';
$config['graph_types']['device']['ucd_memory']['section']     = 'system';
$config['graph_types']['device']['ucd_memory']['order']       = '0';
$config['graph_types']['device']['ucd_memory']['descr']       = 'Detailed Memory Usage';
$config['graph_types']['device']['ucd_swap_io']['section']    = 'system';
$config['graph_types']['device']['ucd_swap_io']['order']      = '0';
$config['graph_types']['device']['ucd_swap_io']['descr']      = 'Swap I/O Activity';
$config['graph_types']['device']['ucd_io']['section']         = 'system';
$config['graph_types']['device']['ucd_io']['order']           = '0';
$config['graph_types']['device']['ucd_io']['descr']           = 'System I/O Activity';
$config['graph_types']['device']['ucd_contexts']['section']   = 'system';
$config['graph_types']['device']['ucd_contexts']['order']     = '0';
$config['graph_types']['device']['ucd_contexts']['descr']     = 'Context Switches';
$config['graph_types']['device']['ucd_interrupts']['section'] = 'system';
$config['graph_types']['device']['ucd_interrupts']['order']   = '0';
$config['graph_types']['device']['ucd_interrupts']['descr']   = 'Interrupts';
$config['graph_types']['device']['uptime']['section']         = 'system';
$config['graph_types']['device']['uptime']['order']           = '0';
$config['graph_types']['device']['uptime']['descr']           = 'System Uptime';
$config['graph_types']['device']['poller_perf']['section']    = 'poller';
$config['graph_types']['device']['poller_perf']['order']      = '0';
$config['graph_types']['device']['poller_perf']['descr']      = 'Poller Time';
$config['graph_types']['device']['ping_perf']['section']      = 'poller';
$config['graph_types']['device']['ping_perf']['order']        = '0';
$config['graph_types']['device']['ping_perf']['descr']        = 'Ping Response';

$config['graph_types']['device']['vpdn_sessions_l2tp']['section'] = 'vpdn';
$config['graph_types']['device']['vpdn_sessions_l2tp']['order']   = '0';
$config['graph_types']['device']['vpdn_sessions_l2tp']['descr']   = 'VPDN L2TP Sessions';

$config['graph_types']['device']['vpdn_tunnels_l2tp']['section'] = 'vpdn';
$config['graph_types']['device']['vpdn_tunnels_l2tp']['order']   = '0';
$config['graph_types']['device']['vpdn_tunnels_l2tp']['descr']   = 'VPDN L2TP Tunnels';

$config['graph_types']['device']['netscaler_tcp_conn']['section'] = 'load balancer';
$config['graph_types']['device']['netscaler_tcp_conn']['order']   = '0';
$config['graph_types']['device']['netscaler_tcp_conn']['descr']   = 'TCP Connections';

$config['graph_types']['device']['netscaler_tcp_bits']['section'] = 'load balancer';
$config['graph_types']['device']['netscaler_tcp_bits']['order']   = '0';
$config['graph_types']['device']['netscaler_tcp_bits']['descr']   = 'TCP Traffic';

$config['graph_types']['device']['netscaler_tcp_pkts']['section'] = 'load balancer';
$config['graph_types']['device']['netscaler_tcp_pkts']['order']   = '0';
$config['graph_types']['device']['netscaler_tcp_pkts']['descr']   = 'TCP Packets';

$config['graph_types']['device']['asa_conns']['section'] = 'firewall';
$config['graph_types']['device']['asa_conns']['order']   = '0';
$config['graph_types']['device']['asa_conns']['descr']   = 'Current connections';

$config['graph_types']['device']['cisco-iospri']['section']  = 'voice';
$config['graph_types']['device']['cisco-iospri']['order']    = '0';
$config['graph_types']['device']['cisco-iospri']['descr']    = 'PRI Utilisation';

$config['graph_types']['device']['cisco-iosdsp']['section']  = 'voice';
$config['graph_types']['device']['cisco-iosdsp']['order']    = '0';
$config['graph_types']['device']['cisco-iosdsp']['descr']    = 'DSP Utilisation';

$config['graph_types']['device']['cisco-iosmtp']['section']  = 'voice';
$config['graph_types']['device']['cisco-iosmtp']['order']    = '0';
$config['graph_types']['device']['cisco-iosmtp']['descr']    = 'Hardware MTP Utilisation';

$config['graph_types']['device']['cisco-iosxcode']['section']  = 'voice';
$config['graph_types']['device']['cisco-iosxcode']['order']    = '0';
$config['graph_types']['device']['cisco-iosxcode']['descr']    = 'Transcoder Utilisation';

$config['graph_descr']['device_smokeping_in_all'] = 'This is an aggregate graph of the incoming smokeping tests to this host. The line corresponds to the average RTT. The shaded area around each line denotes the standard deviation.';
$config['graph_descr']['device_processor']        = 'This is an aggregate graph of all processors in the system.';

// Device Types
$i = 0;
$config['device_types'][$i]['text'] = 'Servers';
$config['device_types'][$i]['type'] = 'server';
$config['device_types'][$i]['icon'] = 'server.png';

$i++;
$config['device_types'][$i]['text'] = 'Network';
$config['device_types'][$i]['type'] = 'network';
$config['device_types'][$i]['icon'] = 'network.png';

$i++;
$config['device_types'][$i]['text'] = 'Wireless';
$config['device_types'][$i]['type'] = 'wireless';
$config['device_types'][$i]['icon'] = 'wireless.png';

$i++;
$config['device_types'][$i]['text'] = 'Firewalls';
$config['device_types'][$i]['type'] = 'firewall';
$config['device_types'][$i]['icon'] = 'firewall.png';

$i++;
$config['device_types'][$i]['text'] = 'Power';
$config['device_types'][$i]['type'] = 'power';
$config['device_types'][$i]['icon'] = 'power.png';

$i++;
$config['device_types'][$i]['text'] = 'Environment';
$config['device_types'][$i]['type'] = 'environment';
$config['device_types'][$i]['icon'] = 'environment.png';

$i++;
$config['device_types'][$i]['text'] = 'Load Balancers';
$config['device_types'][$i]['type'] = 'loadbalancer';
$config['device_types'][$i]['icon'] = 'loadbalancer.png';

$i++;
$config['device_types'][$i]['text'] = 'Storage';
$config['device_types'][$i]['type'] = 'storage';
$config['device_types'][$i]['icon'] = 'storage.png';

if (isset($config['enable_printers']) && $config['enable_printers']) {
    $i++;
    $config['device_types'][$i]['text'] = 'Printers';
    $config['device_types'][$i]['type'] = 'printer';
    $config['device_types'][$i]['icon'] = 'printer.png';
}

$i++;
$config['device_types'][$i]['text'] = 'Appliance';
$config['device_types'][$i]['type'] = 'appliance';
$config['device_types'][$i]['icon'] = 'appliance.png';

//
// No changes below this line #
//
$config['version']              = '2015.master';
$config['project_name_version'] = $config['project_name'].' '.$config['version'];

if (isset($config['rrdgraph_def_text'])) {
    $config['rrdgraph_def_text'] = str_replace('  ', ' ', $config['rrdgraph_def_text']);
    $config['rrd_opts_array']    = explode(' ', trim($config['rrdgraph_def_text']));
}

if (!isset($config['log_file'])) {
    $config['log_file'] = $config['log_dir'].'/'.$config['project_id'].'.log';
}

if (isset($config['cdp_autocreate'])) {
    $config['dp_autocreate'] = $config['cdp_autocreate'];
}

if (!isset($config['mibdir'])) {
    $config['mibdir'] = $config['install_dir'].'/mibs';
}

$config['mib_dir'] = $config['mibdir'];

// If we're on SSL, let's properly detect it
if (isset($_SERVER['HTTPS'])) {
    $config['base_url'] = preg_replace('/^http:/', 'https:', $config['base_url']);
}

// Set some times needed by loads of scripts (it's dynamic, so we do it here!)
$config['time']['now']      = time();
$config['time']['now']     -= ($config['time']['now'] % 300);
$config['time']['fourhour'] = ($config['time']['now'] - 14400);
// time() - (4 * 60 * 60);
$config['time']['sixhour'] = ($config['time']['now'] - 21600);
// time() - (6 * 60 * 60);
$config['time']['twelvehour'] = ($config['time']['now'] - 43200);
// time() - (12 * 60 * 60);
$config['time']['day'] = ($config['time']['now'] - 86400);
// time() - (24 * 60 * 60);
$config['time']['twoday'] = ($config['time']['now'] - 172800);
// time() - (2 * 24 * 60 * 60);
$config['time']['week'] = ($config['time']['now'] - 604800);
// time() - (7 * 24 * 60 * 60);
$config['time']['twoweek'] = ($config['time']['now'] - 1209600);
// time() - (2 * 7 * 24 * 60 * 60);
$config['time']['month'] = ($config['time']['now'] - 2678400);
// time() - (31 * 24 * 60 * 60);
$config['time']['twomonth'] = ($config['time']['now'] - 5356800);
// time() - (2 * 31 * 24 * 60 * 60);
$config['time']['threemonth'] = ($config['time']['now'] - 8035200);
// time() - (3 * 31 * 24 * 60 * 60);
$config['time']['sixmonth'] = ($config['time']['now'] - 16070400);
// time() - (6 * 31 * 24 * 60 * 60);
$config['time']['year'] = ($config['time']['now'] - 31536000);
// time() - (365 * 24 * 60 * 60);
$config['time']['twoyear'] = ($config['time']['now'] - 63072000);
// time() - (2 * 365 * 24 * 60 * 60);
// IPMI sensor type mappings
$config['ipmi_unit']['Volts']     = 'voltage';
$config['ipmi_unit']['degrees C'] = 'temperature';
$config['ipmi_unit']['RPM']       = 'fanspeed';
$config['ipmi_unit']['Watts']     = 'power';
$config['ipmi_unit']['discrete']  = '';

// INCLUDE THE VMWARE DEFINITION FILE.
require_once 'vmware_guestid.inc.php';

// Define some variables if they aren't set by user definition in config.php
if (!isset($config['html_dir'])) {
    $config['html_dir'] = $config['install_dir'].'/html';
}

if (!isset($config['rrd_dir'])) {
    $config['rrd_dir'] = $config['install_dir'].'/rrd';
}

if (!isset($config['log_dir'])) {
    $config['log_dir'] = $config['install_dir'].'/logs';
}

if (!isset($config['log_file'])) {
    $config['log_dir'].'/'.$config['project_id'].'.log';
}

if (!isset($config['plugin_dir'])) {
    $config['plugin_dir'] = $config['html_dir'].'/plugins';
}

if (!isset($config['title_image'])) {
    $config['title_image'] = 'images/librenms_logo_'.$config['site_style'].'.png';
}
