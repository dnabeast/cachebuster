Simple cachebuster for Laravel Blade
========


Installing
==========

Add the dependency to your project:

```bash
composer require DNABeast/cachebuster:dev-master
```

After updating composer, add the ServiceProvider to the providers array in config/app.php

### Laravel 5.2:

```
DNABeast\CacheBuster\CacheBusterServiceProvider::class,
```

## USAGE
In a blade file:
@cachebuster('css/style.css')

If in a production environment it checks whether the file is older or a different size and if it is creates a new build file. Good for busting long caches set by your server.