=== ACF Theme Code ===
Contributors: aaronrutley, ben-pearson
Tags: acf,advanced custom fields,php,automation
Requires at least: 4.5.3
Tested up to: 4.6
Stable tag: 1.0.0
License: GPL2+

ACF Theme Code will automatically generate the code needed to implement Advanced Custom Fields in your themes!

== Description ==
ACF Theme Code is an extension for the awesome [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/) plugin.

We've created this plugin to save developers time when it comes to implementing Advanced Custom Fields in their WordPress themes.

= Existing / common ACF workflow: =

1. Create Field Group
2. Search ACF documentation for how to implement a particular field type and it's settings (return values etc)
3. Copy example code from ACF documentation and paste it into your theme or plugin
4. Go through and customise all the field names in the example code

= New / improved workflow with ACF Theme Code: =

1. Create Field Group
2. Use clipboard icon to copy and paste a field's already customised code into your theme

No more constantly referring to the ACF documentation to see how to implement a particular field. No more customising field names in code examples.

= Features include: =
* Easily copy / paste the field code into your theme
* Great for offline documentation
* Code generated is based on examples from the official ACF documentation

> <strong>Upgrade to ACF Theme Code Pro</strong><br>
> ALL ACF Pro field types are supported, including the popular Repeater, Flexible Content and Gallery field types.
> 10+ ACF Add-on field types are also supported, including Font Awesome and Image Crop.
> [Find out more about ACF Theme Code Pro](https://hookturn.io/downloads/acf-theme-code-pro/)

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

= Requires =
* ACF version 4.4.7 or higher
* ACF Pro version 5.3.9 or higher

== Installation ==

1. Upload ACF Theme Code to /wp-content/plugins/
2. Activate the Plugin via the plugins menu
3. Create or Update an ACF Field Group
4. Scroll down to see the ACF Theme code

== Frequently Asked Questions ==

= Does this plugin support ACF Pro ?  =

This plugin has basic support for ACF Pro (we support all the fields found in the ACF free version).
Our premium version - [ACF Theme Code Pro](https://hookturn.io/downloads/acf-theme-code-pro/) supports all the fields in ACF Pro and a range of other 3rd Party Fields.

= Why is there so many PHP tags in the code that's generated ? =

At the moment it's for developer convenience, so you can easily wrap our code in your own HTML markup.
We're open to suggestions and keen to improve the code generated based on community discussion.

== Screenshots ==
1. When you publish, edit or update an ACF Field Group, the code required to implement each field is displayed for you in the Template Code section below. Use the clipboard icon to easily copy these code blocks and paste them into your theme.

== Changelog ==

= 1.1.0 =
* Various field formatting improvements inline with ACF Theme Code Pro

= 1.0.0 =
* First version
