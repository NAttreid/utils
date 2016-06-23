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


## Hasher
Pro použití je třeba zaregistrovat
```neon
services:
    - NAttreid\Utils\Hasher(%salt%)
```