# Guise

Guise is a WordPress framework for modifying the UI, it handles the behind-the-scenes 
interactions with WordPress so that you can focus on writing OOP code.

**This is early on in development, contributions are wellcome - see the wish list below!**


## Example

To add a column to the 'foobar' post type, first define your column view 
it must implement the `Post_Type_Column_View` interface.

```
use stephenharris\guise\Post_Type_Column_View;

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
$controller->register( $column_view, 'foo-bar', 2 );
```

Please note that later columns can shift it out of place.


## Bug or feature request?

Please open an [issue](https://github.com/stephenharris/guise/issues)!


## Wishlist
- Providing sortable columns
- Metaboxes
- Settings