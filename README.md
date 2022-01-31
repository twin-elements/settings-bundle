#Installation

in bundles.php 
```
TwinElements\SettingsBundle\TwinElementsSettingsBundle::class => ['all' => true],
```

in routes.yaml
```
settings_admin:
    resource: "@TwinElementsSettingsBundle/Controller/"
    prefix: /admin
    type: annotation
    requirements:
    _locale: '%app_locales%'
    defaults:
    _locale: '%locale%'
    _admin_locale: '%admin_locale%'
    options: { i18n: false }
```
