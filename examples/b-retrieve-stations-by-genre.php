<style>body { background-color: #333; color: #eee; font-size: 16px }</style>
<?php 
use AdinanCenci\InternetRadio\InternetRadio;

//--------------------

require '../vendor/autoload.php';

//--------------------

$radio    = new InternetRadio();
$genre    = 'jazz';
$offset   = 0;
$limit    = 20;
$sortBy   = 'listeners';

try {
    $stations = $radio->getStationsByGenre($genre, $offset, $limit, $sortBy);
} catch (\Exception $e) {
    die($e->getMessage());
}

echo '<pre>';
print_r($stations);
echo '</pre>';
