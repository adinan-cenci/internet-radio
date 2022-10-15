<style>body { background-color: #333; color: #eee; font-size: 16px }</style>
<?php 
use AdinanCenci\InternetRadio\InternetRadio;

//--------------------

require '../vendor/autoload.php';

//--------------------

$radio = new InternetRadio();


try {
    $genres = $radio->getGenres();
} catch (\Exception $e) {
    die($e->getMessage());
}

echo '<pre>';
print_r($radio->getGenres());
echo '</pre>';
