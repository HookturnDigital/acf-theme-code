=== ACF Theme Code ===
Contributors: aaronrutley, benpearson
Tags: acf,advanced custom fields,php,automation
Requires at least: 4.7
Tested up to: 4.8.0
Stable tag: 1.3.1
License: GPL2+

ACF Theme Code will automatically generate the code needed to implement Advanced Custom Fields in your themes!

== Description ==
ACF Theme Code is an extension for the awesome [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/) plugin.

When you publish, edit or update an ACF Field Group, the code required to implement each field is displayed for you in the Theme Code section at the bottom of the page.

The code is based on the ACF documentation and it's automatically updated to match the fields you have created.
Use the clipboard icon to easily copy these code blocks and paste them into your theme to get you started and save loads of time!

[youtube https://www.youtube.com/watch?v=g9APGNJMy7k]

= Features include: =
* Easily copy the code into your theme
* Great for offline documentation
* Based on the official ACF documentation
* Field names and variables are automatically updated

= Support for the following fields: =
* Text
* Text Area
* Number
* Email
* Password
* WYSIWYG
* File (Object, URL and ID return values)
* Image (Object, URL and ID return values)
* Select (single and multiple values)
* Checkbox
* Radio
* True / False
* User
* Google Map
* Date Picker
* Colour Picker
* Page Link (single and multiple values)
* Post Object (single and multiple values)
* Relationship (Object and ID return values)
* Taxonomy (Checkbox, Multi Select, Radio Buttons and Select field types)

> <strong>Upgrade to ACF Theme Code Pro</strong><br>
> ACF Theme Code Pro generates code for all ACF Pro field types including the Clone, Repeater, Gallery and Flexible Content field.<br>
> Our Pro version also generates code for a range of popular 3rd party fields and we generate code for location rules including Options Pages!
> [Find out more about ACF Theme Code Pro](https://hookturn.io/downloads/acf-theme-code-pro/)

= Requires =
* ACF version 4.4.7 or higher
* ACF Pro version 5.4 or higher

= Thankyou =
Thanks to all of our beta testers including Elliot Condon, Phil Smart, Richard Johnston & James Bundey!

== Installation ==

1. Upload ACF Theme Code to /wp-content/plugins/
2. Activate the Plugin via the plugins menu
3. Create or Update an ACF Field Group
4. Scroll down to see the ACF Theme code

== Frequently Asked Questions ==

= Does this plugin support ACF Pro ?  =

This plugin has basic support for ACF Pro (we support all the fields found in the ACF free version).
Our premium version - [ACF Theme Code Pro](https://hookturn.io/downloads/acf-theme-code-pro/) supports all the fields in ACF Pro, a range of other 3rd Party Fields & locations rules (like options).

= Why is there so many PHP tags in the code that's generated ? =

At the moment it's for developer convenience, so you can easily wrap our code in your own HTML markup.
We're open to suggestions and keen to improve the code generated based on community discussion.

== Screenshots ==
1. When you publish, edit or update an ACF Field Group, the code required to implement each field is displayed for you in the Theme Code section. Use the clipboard icon to easily copy these code blocks and paste them into your theme.

== Changelog ==

= 1.3.1 =
* Fix: Updates to array count functionality for PHP 5.4

= 1.3.0 =
* Core : Support for ACF Pro when bundled in a Theme
* Fix : HTML output by the File field is now valid
* Core : Notice for location rule support (now in ACF Theme Code Pro)

= 1.2.0 =
* Core: Quicklinks feature with anchor links to the relevant theme code block
* Core: Notice updates & various bug fixes

= 1.1.2 =
* Fix: Use the_sub_field method for nested File fields with return format URL

= 1.1.1 =
* Field: Post Object field now works correctly for ACF 4
* Core: Various internal code improvements

= 1.1.0 =
* Fields: All field formatting improved inline with ACF Theme Code Pro

= 1.0.0 =
* Core: First version
