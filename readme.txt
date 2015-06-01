=== Advanced Sidebar Menu ===

Contributors: Mat Lipe
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=paypal%40lipeimagination%2einfo&lc=US&item_name=Advanced%20Sidebar%20Menu&no_note=0&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest
Tags: menus, sidebar menu, heirchy, category menu, pages menu
Requires at least: 3.8.0
Tested up to: 4.2.2
Stable tag: 5.0.7

Creates a widget for both page and categories that will display the current page/category and all child pages or categories.

== Description ==

Creates a widget for both page and categories that will display the current page/category and an child pages or categories.
Keeps the menu clean and usable.

<h3>Want More Options and Better Support?</h3>
	
<strong><big><a href="http://matlipe.com/product/advanced-sidebar-menu-pro/">Go Pro!</a></big></strong>


Includes Page options:
<ol>
   <li>Add a title to the widget</li>
   <li>Include the highest level parent page</li>
   <li>Include the highest level parent page even with no Children</li>
   <li>Order Pages By (date, title, page order)</li>
   <li>Use built in styling (very plain styling currently but plans for more advanced in near future versions)</li>
   <li>Exclude pages</li>
   <li>Legacy Mode - For those who like the way it was pre version 4.0</li>
   <li>Always display child Pages</li>
   <li>Number of levels of child pages to display</li>
</ol>  
Includes Category Options:
<ol>
   <li>Add a title to the widget</li>
   <li>Include Parent Category</li>
   <li>Include Parent Even with no Children</li>
  <li>Use Built in Styling</li>
  <li>Display Categories on Single Posts</li>
   <li>To display each Single Posts Category in a new widget or in same list</li>
   <li>Exclude Categories</li>
  <li>Always display child categories</li>
   <li>Levels of Categories to display</li>
</ol>    

Templates may be overridden for customization of outputs and/or css.

Many built in filters for altering the way the widgets function.
   
Developer Docs may be found here
<a href="http://matlipe.com/advanced-sidebar-menu/developer-docs/">http://matlipe.com/advanced-sidebar-menu/developer-docs/</a>

To contribute send pull requests:
<a href="https://github.com/lipemat/advanced-sidebar-menu">GitHub Repo</a>


== Installation ==

Use the standard WordPress plugins search and install feature.

Manual Installation

1. Upload the `advanced-sidbebar-menu` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Drag the "Advanced Sidebar Pages Menu" widget Or the "Advanced Sidebar Categories Menu" widget into a sidebar.


== Screenshots ==

1. Screenshot of the Page widget and options
2. Screenshot of the Categories widget and options


== Frequently Asked Questions ==

Developer docs may be found here:
<a href="http://matlipe.com/advanced-sidebar-menu/developer-docs/">http://matlipe.com/advanced-sidebar-menu/developer-docs/</a>

= What text domain do I use for translation and where is the .pot file? =

The .pot file may be found in the plugins' languages folder. Use the 'advanced-sidebar-menu' text domain.


= Version 4.0+ is not displaying the same as previous version of the plugin. How do I bring this back? =

You will find a checkbox in the widget options for Legacy Mode. 

= How do I change the styling of the current page? =

You may add css to your theme's style.css to change the way the menu looks

For Instance This would remove the dot and  change the color
<code>
.advanced-sidebar-menu li.current_page_item a {
    color: black;
}

.advanced-sidebar-menu li.current_page_item {
    list-style-type:  none !important;
}
</code>


= How do you get the categories to display on single post pages? =

There is a checkbox in the widget options as of version 3.2 that will display the same structure for the categories the post is in.

= How do you edit the output or built in css? =

Create a folder in your child theme named "advanced-sidebar-menu" copy any of the files from the "views" folder into
the folder you just created. You may edit the files at will to change the output or css?
You must have the option checked to use the built in CSS (in the widget) to be able to edit the css file in this way.
The Others will work always.


= Does the menu change for each page you are on? =

Yes. Based on whatever parents and children pages you are on, the menu will change automatically.

= How does this work with styling the page? =

As of version 1.1 this will automatically generate class names for each level for menu system. 
You can add classes to your theme's style.css file to style it accordingly. 
You may want to use something like margins to set the levels apart.


= What if I want functionality which is not built in? =

I do offer preminum services for buiding custom add-ons for additional functionality. Otherwise you are welcome to alter the code in any way or build your own add-ons. keep in mind, if you edit the code directly, it will break on update. The best way to customize is using the built in filters or template overides. If you have a cool add-on you have developed, let me know, I may want to publish your work.


== Changelog ==
= 5.0.0 =
* Greatly improved performance
* Improved code structure 

= 4.7.0 =
* Added Internationalization (I18n) support

= 4.6.0 =
* Added support for Pro Version

= 4.5.0 =
* Improved filter structure to allow for add-ons to work more effectively


= 4.4.0 =
* Added a has_children class to page links with hidden children

= 4.3.0 =
* Added many filters into the category widget for things like taxonomies, parent category, display on override, order by, and much more. There is no UI support for any of this yet, but developers may now tap into this. 

= 4.2.0 =
* Added Order By Selection in Page Widget

= 4.0.0 =
* Added support for an unlimited number of page levels
* Change structure slightly for future enhancements
* Added Legacy Mode for backwards compatibility


= 3.4.0 =
* Added filter support for custom post types

= 3.3.1 =
* Added unlimited number of levels displayed once on the grandchild level of pages

= 3.3.0 =
* Added the ability to have a widget title
* Redesigned the entire structure to prepare for future changes


= 3.2.5 =
* Bugfixes

= 3.2.3 = 
* Fix a bug that caused multiple category list to display of more than one category the single post was in shared the same parent

= 3.2.1 =
* Fix a possible bug that may display a * Notice * error if there is nothing to display and the  error reporting is set to strict when using the categories widget.

= 3.2.0 =
* Added ability to have categories show on single post pages
* Improved the code structure


= 3.0.2 =
*Bugfixes


= 3.0 =
* Added a categories menu widget with the same functionality as the pages widget
* Added the ability to edit "views" files through your child theme to edit output and css
* Cleanedup the output


== Upgrade Notice ==

= 5.0.0 =
If you used a custom page_list.php template previously you may want to redo it on this version to take advantage of the new structure.

= 3.3.0 =
If you customized the output previously you may want to redo it on this version to take advantage of the new structure.
IF you are are using the page_list.php view you will most likely get an error message to remove a couple lines.
These lines are no longer needed for the structure in this version.

= 3.2.3 = 
This will add the ability to display the categories on single post pages.
If you are using the category_list.php view you will most likely get an error message to remove a couple lines.
These lines are no longer needed for the structure in this new version.

= 3.0 =
This Version will add a widget for displaying categories as well, 
better functionality, a cleaner output, and the ability to customize the output/css
through your child theme.

= 2.0 =
This Version will give you better control over the menu and styling ability.
Added new options and more stable code.

= 1.2 =
This Version will allow you to order the pages in the menu using the page order section of the editor.

= 1.1 =
This version will allow simliar css styling.

