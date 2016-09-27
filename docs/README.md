# Guise

Guise is a lightweight WordPress framework for modifying the UI, it handles the behind-the-scenes 
interactions with WordPress so that you can focus on writing OOP code.

**This is early on in development, contributions are welcome - see the wish list below!**


## What is Guise, why should I use it?

WordPress is procedurally written. This can make interacting with it
in an object-oriented manner clunky. It is also an event-based CMS with
various parts of the UI modifiable by various different hooks; remembering
which hooks to use and setting up callbacks for them is finickity and often
involves repeating the procedure multiple times for your different objects.

Guise is a framework which focuses on making interacting with the WordPress
UI in an object-oriented ways easier by acting as a thin layer between WordPress
and your code. It works to an interface, so the *implementation* of the
user interface is entirely down to your code, but Guise handles the
behind-the-scenes interactions with WordPress.

Guise also provides a number of concreate classes (such as `Notice_View` 
implementations) which cater for the majority of use-cases.

## Installation

See [Installation](install.html).

## Health warning

This framework is intended for composer-managed WordPress sites. You shouldn't 
include it in a plug-in for public distribution, as this will likely break
sites which have more than one plug-in requiring it.

If you want to include it in your plug-in, I'd advise you to change the
namespaces, but beaware that this framework also makes uses of other third-party
libraries.


## Requirements

- PHP 5.4+
- Composer to install
   
   
## License

Guise is open source and released under MIT or GPLv2+ license at your preference.


## Questions? Bugs? Feature requests?

Please open an [issue](https://github.com/stephenharris/guise/issues)!


## Wishlist
- Metaboxes
- More setting views
- Support for columsn for all types (Users, Comments, etc)