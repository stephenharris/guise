# Admin notices


To create an error message simply create a `Error_Notice_View` instance and
and attach it to a `Notice_Controller` instance. (You may want to store the 
latter in a dependency container or service locator).

```
use stephenharris\guise\Error_Notice_View;
use stephenharris\guise\Notice_Controller;

$notice_controller = new Notice_Controller();
$notice = new Error_Notice_View( '<p>This is an error message</p>' );

$notice_controller->register( 'my-notice-id', $notice );
```

There are also `Info_Notice_View` and `Success_Notice_View` classes available too.


## Dismissible notices

To make your notice dismissible, you can use the `Dismissible_Notice_Decorator`

```
use stephenharris\guise\Success_Notice_View;
use stephenharris\guise\Dismissible_Notice_Decorator;
use stephenharris\guise\Notice_Controller;

$notice_controller = new Notice_Controller();
$notice = new Error_Notice_View( '<p>This is a dismissible success message</p>' );
$dismissible_notice = new Dismissible_Notice_Decorator( $notice );

$notice_controller->register( 'my-notice-id', $dismissible_notice );
```