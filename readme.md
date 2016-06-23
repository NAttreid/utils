# Pomocné třídy pro Nette Framework

## Hasher
Pro použití je třeba zaregistrovat
```neon
services:
    - NAttreid\Utils\Hasher(%salt%)
```

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
