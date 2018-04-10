# Pomocné třídy pro Nette Framework

## Nastavení
Pro lokalizaci je třeba nastavit *locale* v **BasePresenter**
```php
protected function startup() {
    parent::startup();

    $locale = 'cs';
    // pokud je nastaven translator
    $locale = $this->translator->getDefaultLocale();
    
    \NAttreid\Utils\Number::setLocale($locale);
    \NAttreid\Utils\Date::setLocale($locale);
```

# Javascript Utils

## fixedPosition
Metoda pro scrollovaní objektu na stránce
```javascript
$('#object').fixedPosition({
    from: '#object',    // objekt, od ktereho se ma scrollovat
    top: 50,            // pocet pixelu od vrchu pri scrollovani
    bottom: 50,         // pocet pixelu od spodu pri scrollovani
    to: '#bottom',      // objekt, ktery omezuje pohyb ze spoda
    width: 450 nebo $('#object')      // sirka nebo jQuery objekt, ze ktereho se sirka bere
});
```

## center
Metoda pro vycentrování na obrazovce (zůstane na aktuální pozici)
```javascript
$('#object').center();
```

## centerFixed
Metoda pro vycentrování na obrazovce (posouvá se při scrollu)
```javascript
$('#object').centerFixed();
```

## clickOut
Metoda spouští callback při kliku mimo daný objekt
```javascript
$('#object').clickOut(function(){

    // vypne tento event, jinak se vola stale
    return true;
});

// vypnuti
$('#object').clickOff();
```

## onScrollTo
Metoda spouští callback při najetí okna k danému elementu
```javascript
$('#object').onScrollTo(function(){

    // vypne tento event, jinak se vola stale
    return true;
});
```

## copyToClipboard
Zkopíruje obsah objeku do schránky
```javascript
$('a').click(function() {
    $('.text').copyToClipboard();
});
```

## format
Formát čísla
```javascript
var i = 4578456;
i.format(); // vrátí 4 578 456,00
i.format(0,'.',''); // vrátí 4578456
```

## removeDiacritic
Odstraní diakritiku
```javascript
var s = 'Čau';
s.removeDiacritic(); // vrátí cau
```

## injectTag
Vloží tag do hledaného řetězce (ignoruje diakritiku při hledání)
```javascript
var s = 'Řecko';
s.injectTag('rec', 'strong'); // vrátí <strong>Řec</strong>ko
```

## onPosition
Umístí objekt podle pozice myši.
```javascript
$('a').click(function(event){
    var x = 30; // posune o 30px napravo 
    var y = -30; // posune o 30px nahoru
    $('#object').onPosition(event,x,y);
});
```

## cachedScript
Nacteni skriptu
```javascript
$.cachedScript("ajax/test.js").done(function (script, textStatus) {
    console.log(textStatus);
});
```

## isOnScreen
Je object na obrazovce
```javascript
var isOnScreen = $('.obj').isOnScreen();
```