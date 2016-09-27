# Guise

Guise is a WordPress framework for modifying the UI, it handles the behind-the-scenes 
interactions with WordPress so that you can focus on writing OOP code.

**This is early on in development, contributions are welcome - see the wish list below!**

## Documentation

The documentation for using Guise can be found here:  https://stephenharris.github.io/guise/

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


## Example

## Adding a column to a post type

To add a column to the 'foobar' post type, first define your column view 
it must implement the `Post_Type_Column_View` interface.

```
use StephenHarris\Guise\Columns\Post_Type_Column_View;

My_Foo_Bar_Column_View implements Post_Type_Column_View {

    function label() {
        return 'My column header';
    }
    
    function render( WP_Post $post ) { 
        return sprintf( 'This is the cell for post %d', $post->ID );
    }
}
```

Then register your column:

```
$column_view = new My_Foo_Bar_Column_View();
$controller new Post_Type_Column_Controller()

$controller->register( $column_view, 'foo-bar' );
```

You can also specify the index you want the column to appear in:

```
//Adds to the second index (i.e. it appears as the third column)
$controller->register( $column_view, 'foobar', 2 );
```

Please note that later columns can shift it out of place.

## Adding a sortable to a post type

To add a sortable column your column view class must implement the 
`Post_Type_Column_View` and `Sortable_Column_View` interfaces.

```
use StephenHarris\Guise\Columns\Post_Type_Column_View;
use StephenHarris\Guise\Columns\Sortable_Column_View;

My_Sortable_Foo_Bar_Column_View implements Post_Type_Column_View, Sortable_Column_View {

    function label() {
        return 'A sortable column';
    }
    
    function render( WP_Post $post ) { 
        return sprintf( 'This is the cell for post %d', $post->ID );
    }
    
    function sort_by() {
        //Return the value of the orderby query parameter
        return 'query-variable';
    }
}
```

## Bug or feature request?

Please open an [issue](https://github.com/stephenharris/guise/issues)!

## Wishlist
- Metaboxes
- Settings