# Columns

*Currently only post type and taxonomy terms are supported*

WordPress uses alot of tables in its admin interfaces (e.g. list posts, pages,
categories, users etc). Guise lets you easily add a column to these tables by 
simply implementing the `Column_View` interface and registering it with the 
appropriate controller.

The set up is the same for post types, taxonomies and users. The only difference
are the names.


## Adding a column to a post type's admin table


First define your column view which must implement the `Post_Type_Column_View` interface 
(defining the `label()` and the `render()` methods:

```
use StephenHarris\Guise\Columns\Post_Type_Column_View;

class Rating_Column_View implements Post_Type_Column_View {

    /**
     * Renders the column's header
     */
    public function label() {
        return 'My column label';
    }
    
    /**
     * Renders the content of the column for the passed row object
     */
    public function render( WP_Post $post ) {
        
        $rating = get_post_meta( $post->ID, '_rating', true );
        
        if ( ! $rating ) {
            return 'Unrated';
        }
        
        return sprintf( '%f out of 10 starts', $rating );
    }

}
```

Then add add your columns to an instance of `Post_Type_Column_Controller` (you 
may want to store an instance of this in a dependency injection container or a
service locator).


```
use StephenHarris\Guise\Columns\Post_Type_Column_Controller;

$post_type_columns = new Post_Type_Column_Controller();
$review_column = new Rating_Column_View();

$post_type_columns->register( $review_column, 'post' );
$post_type_columns->register( $review_column, 'page' );
$post_type_columns->register( $review_column, 'custom-post-type' );

```

### Adding the column at a particular location

You can also specify the index you want the column to appear in:

```
//Adds to the second index (i.e. it appears as the third column)
$controller->register( $column_view, 'foobar', 2 );
```

Please note that later columns can shift it out of place.


### Making your column sortable

To add a sortable column your column view class must also implement the 
`Sortable_Column_View` interface.

```
use StephenHarris\Guise\Columns\Post_Type_Column_View;
use StephenHarris\Guise\Columns\Sortable_Column_View;

Rating_Column_View implements Post_Type_Column_View, Sortable_Column_View {

    /* ... as above ... */
    
    function sort_by() {
        //Return the value of the orderby query parameter
        return 'rating';
    }
}
```

*You will have to intercept the `pre_get_posts` query to interpret the 'rating' ordering*


## Adding a column to a taxonomy's admin table

The procedure for adding a column to a taxonomy admin table is almost identical. The only differences are:

 - Your column view must implement `Taxonomy_Column_View` (passes a `WP_Term` object to the `render()` method), rather than `Post_Type_Column_View`
 - Use the `Taxonomy_Column_Controller` rather than `Post_Type_Column_Controller`.
 - Pass a taxonomy slug as the second argument of `Taxonomy_Column_Controller::register()` in stead of a post type slug.
 
 
## Feature wish list

Other admin tables that are on the 'wish list': 

 - Support the following tables:
   - Users  (and multisite)
   - Themes (and multisite)
   - Plugins (and multisite)
   - Sites
 - Allow views to specify the class attribute fo the column
 
Please contribute!



