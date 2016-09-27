# Settings

*There are a limited number of setting views available - please contribute more!*

Guise allows you to register WordPress settings and sections to an existing settings page.
This can be core WordPress settings page, or one added by yourself or a third-party plugin.

To register a setting you need:

 1. A unique identifier for the setting (this equates to teh setting name passed in `get_option()`
 2. A section identifer to add the setting to
 3. A `Settings\View` instance, to render the setting field
 4. An optional `Respect\Validation\Validatable` (defaults to `null`: no validation) to allow add validation constraints. (Guise uses [Validation](https://github.com/Respect/Validation) version 0.9).
 
 
## Registering a section
 
Let's start with registering a section. First we need page to add the 
section to. You can create a new instance
 
```
use StephenHarris\Guise\Settings\Page;
 
$page = new Page( 'discussion' ); //Discussion settings page
```
 
(though you may wish to store that in Dependency Injection Container or
service locator). Alternatively you can use a static factory method which
will return the same page instance for the same ID (except instances created 
via `new Page`).
 
```
use StephenHarris\Guise\Settings\Page;

$page = Page::get_by_id('discussion'); //Discussion settings page
```
 
Next we need to create our `Section` view. You can create your own class,
which implements the `StephenHarris\Guise\Settings\Views\Section` interface
or you can extend the `StephenHarris\Guise\Settings\Views\SimpleSection` abstract
class.

```
use StephenHarris\Guise\Settings\Views\SimpleSection;

$section_view = new SimpleSection( 'My section title', 'My section description' );
$page->register_section( 'my-section-id', $section_view );
```
 
## Adding a setting to our section

To register a setting first create an instance implementing the `StephenHarris\Guise\Settings\Views\Setting`
interface. There are some default classes you can use such as `Input`, `Checkbox`, `MultipleCheckbox`
and `Select` - please contribute more!
 
The responsibilities of the settings view are simple: 

 - It must render a label
 - And interpret the stored database value to which it belongs (and render 
  the setting field accordingly)

You register the setting by passing to `StephenHarris\Guise\Settings\Page::register_setting()`
(see *Register a section* section on obaining `Page` instance):
 
 - The ID of the setting you are adding
 - The ID of the section to add the setting to
 - The setting view (`StephenHarris\Guise\Settings\Views\Setting`)
 - An optional `Respect\Validation\Validatable` instance (see *Validating the settings*
   below).
    
```
use StephenHarris\Guise\Settings\Views\Input;
use StephenHarris\Guise\Settings\Views\Checkbox;
 
$text_field = new Input( 'my-text-field', 'My text field' );
$page->register_setting( 'my-text-field', 'my-section-id', $text_field );
 
$checkbox = new Checkbox( 'my-checkbox', 'My checkbox', 'checked-value' );
$page->register_setting( 'my-checkbox', 'my-section-id', $checkbox );
```
 
## Validating the settings (optional)
 
Guise allows you to use the [Validation](https://github.com/Respect/Validation)
library to add constraints. 
 
These validations are passed as fourth argument to `StephenHarris\Guise\Settings\Page::register_setting()`.
If the user-entered value fails these validation checks, the setting is not updated and an error message is displayed.
 
 
```
use StephenHarris\Guise\Settings\Page;
use StephenHarris\Guise\Settings\Views\Input;
use Respect\Validation\Rules\Email;

$page = Page::get_by_id('discussion'); //Discussion settings page
$text_field = new Input( 'my-text-field', 'My text field' ); //Our setting view
$validation = new Email(); //Add validation for checking an e-mail was provdied
 
$page->register_setting( 'my-text-field', 'my-section-id', $text_field, $validation );
```
  
 
## Available setting views

1. `Input` (text field)
2. `Checkbox` (single checkbox)
3. `MultipleCheckbox` (list of one or more checkboxes)
4. `Select` (dropdown field)

**Need more? Please contribute!**
