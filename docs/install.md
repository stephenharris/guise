## Installation

Guise is not yet published on Composer, so you need to add the repository manually:

```
{
    ...
    "repositories": [
        ...
        {
            "type": "vcs",
            "url": "https://github.com/stephenharris/guise"
        }
    ],
    
    ...
    
    "require": {
      "stephenharris/guise": "0.*"
    },

}
```

Then run `composer update`.

You will then need to load Composer's auto-loader in your application:

```
require 'vendor/autoload.php';
```
