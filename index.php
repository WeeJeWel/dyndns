<?php

// Dit is de PHP class voor de DirectAdmin.
// http://forum.directadmin.com/showthread.php?t=258
include("HTTPSocket.php");

if( !isset($_REQUEST['sub'] ) ) die('Missing sub');

// Bewaar het IP
$IP = $_SERVER['REMOTE_ADDR'];

$domain = 'weejewel.net';
$sub = $_REQUEST['sub'] . '.dyndns';

// Controleer of het IP
if ($IP == gethostbyname($sub . '.' . $domain)) die('No update needed');

// Maak een instantie aan van het HTTPSocket object.
$sock = new HTTPSocket;

// Stel de hostnaam en de poort in van het DirectAdmin panel.
$sock->connect($domain,2222);

// Stel de gebruikersnaam, het wachtwoord en kies voor de POST of GET methode.
$sock->set_login("USDERNAME","PASSWORD");
$sock->set_method('POST');

// Een update is niet mogelijk dus eerst het oude record verwijderen.
$sock->query("/CMD_API_DNS_CONTROL?domain=$domain&action=select&arecs0=name=$sub.$domain.&value=".gethostbyname($sub . '.' . $domain));

// Maak vervolgens een nieuw record aan in de DNS met het nieuwe IP.
$sock->query("/CMD_API_DNS_CONTROL?domain=$domain&action=add&type=A&name=$sub.$domain.&value=$IP");

echo 'OK';