# Internet Radio
A library to retrieve radio stations from the internet-radio.com catalog.

<br><br>

## Stations by genre
Use the `::getStationsByGenre($genre, $pageOffset, $pageLimit)` method to retrieve stations labeled with a specific musical genre, returns an array with 20 items.

| Parameter | Type | Default | Description |
|---|---|---|---|
| $genre | string | | The name of a musical genre, see `::getGenres()` for possible values. |
| $pageOffset | int | 0 | From which page to start. |
| $pageLimit | int | 1 | How many pages to read. |

```php
use AdinanCenci\InternetRadio\InternetRadio;

$internetRadio = new InternetRadio();
$stations = $internetRadio->getStationsByGenre('metal');
```

It will return an array as such:

```
[
    {
        "name": "Radio Apintie Suriname - Powered by Bombelman.com", 
        "description": "top 40", 
        "homepage": "https://www.apintie.sr", 
        "playlist": "http://198.178.123.17:8150/listen.pls?sid=1", 
        "currentlyPlaying": "Jezus Da Boen Masra. - The Jaffo Gate Quartet", 
        "listeners": "1123", 
        "bitRate": "96"
    }, 
    {
        "name": "Kalika FM :: Powered by SoftNEP.com", 
        "description": "pali", 
        "homepage": "http://www.kalikanews.com", 
        "playlist": "http://198.178.123.20:7740/listen.pls?sid=1", 
        "listeners": "472", 
        "bitRate": "40"
    }, 
    {
        "name": "GHRadio1Real Music Power", 
        "description": "gh music", 
        "playlist": "http://149.56.234.136:9946/listen.pls", 
        "listeners": "358", 
        "bitRate": "128"
    }
    ...
    ...
    ...
```

<br><br>

## Search stations

Use the `::searchStations($query, $pageOffset, $pageLimit)` method to search stations based on their names, returns an array with 20 items.

| Parameter | Type | Default | Description |
|---|---|---|---|
| $query | string | | Word(s) to be matched against station titles. |
| $pageOffset | int | 0 | From which page to start. |
| $pageLimit | int | 1 | How many pages to read. |

<br><br>

## Genres

The `::getGenres()` returns an array of musical genres used to label radio stations. Use this values to search stations with the `::getStationsByGenre()` method.

```php
$genres = $internetRadio->getGenres();
/*
'50s', 
'60s', 
'Acid House', 
'Bass', 
'Jazz', 
'Metal',
...
...
...
*/
```

<br><br>

## Notes
As of the moment of this writing, internet-radio.com does not provide an API, the information this library provides is scraped from their official page and this library may stop working in the day they decide to update the website.



## License

MIT