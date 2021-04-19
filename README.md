# How to use

## Use Memeberships class in PHP header file
```
use Ramphor\Memberships\Memberships;
```

## Create memberships instance
```
$membershipParentMenu = 'parent-menu-slug';
$memberships = Memberships::createInstance(
    'wordland',
    $membershipParentMenu
);
```

Notes: _If you want to enable profile fields for memberships plan please call below method_

```
$memberships->enableProfileFields();
```


## Execute memberships features

You can execute memberships directly by the way `$memberships->run();` or use WordPress hook same below code

```
add_action(
    'after_setup_theme',
    array( $memberships, 'run' )
);
```

Notes: _Please use hooks before hook `init` such as: `plugins_loaded`, `after_setup_theme`, etc_
