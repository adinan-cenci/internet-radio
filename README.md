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
    	"id": "idobihowl", 
        "name": "idobi Howl", 
        "description": "metal, hardcore", 
        "homepage": "http://idobiradio.com/", 
        "playlist": "http://69.46.88.26:80/listen.pls", 
        "currentlyPlaying": "SYLOSIS - Fear the World", 
        "listeners": "2527", 
        "bitRate": "128"
    }, 
    {
    	"id": "knac.com", 
        "name": "KNAC.COM", 
        "description": "metal", 
        "homepage": "http://www.knac.com", 
        "playlist": "http://198.178.123.14:7346/listen.pls?sid=1", 
        "currentlyPlaying": "NAZARETH - HAIR OF THE DOG", 
        "listeners": "268", 
        "bitRate": "128"
    }, 
    {
    	"id": "metalexpressradio", 
        "name": "Metal Express Radio", 
        "description": "heavy metal", 
        "homepage": "http://www.metalexpressradio.com", 
        "playlist": "http://5.135.154.69:11590/listen.pls?sid=1", 
        "currentlyPlaying": "David T. Chastain - Burning Passion", 
        "listeners": "199", 
        "bitRate": "192"
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
| $query | string | | Word(s) to be matched against station names. |
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
As of the moment of this writing internet-radio.com does not provide an API, the information provided by this library is being scraped from the official website and this library may stop working in the day they decide to update their page.

<br><br>

## License

MIT