<style>body { background-color: #333; color: #eee; }</style>
<?php 
use AdinanCenci\InternetRadio\InternetRadio;

//--------------------

require '../vendor/autoload.php';

//--------------------

$radio = new InternetRadio();

echo '<pre>';
print_r(
	$radio->searchStations('power')
);
echo '</pre>';
