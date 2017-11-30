=== Advanced Sidebar Menu ===

Contributors: Mat Lipe
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=paypal%40matlipe%2ecom&lc=US&item_name=Advanced%20Sidebar%20Menu&no_note=0&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest
Tags: menus, sidebar menu, hierarchy, category menu, pages menu
Requires at least: 4.6.0
Tested up to: 4.9.1
Stable tag: 7.0.1

== Description ==

Uses the parent/child relationship of your pages or categories to generate menus based on the current section of your site. Simply assign a page or category to a parent and this will do the rest for you.

Keeps the menu clean and usable. Only related items display so you don't have to worry about keeping a custom menu up to date or displaying links to items that don't belong.


<strong>Check out <a href="https://matlipe.com/product/advanced-sidebar-menu-pro/">Advanced Sidebar Menu Pro</a> for more features including priority support, the ability to customize the look and feel, custom text for your links, excluding of pages, category link ordering, accordions, custom post types, and so much more!</strong>

<blockquote><a href="https://matlipe.com/product/advanced-sidebar-menu-pro/" target="_blank">Pro version 2.3.0</a> just dropped with lots of great new stuff!</blockquote>

	
<h4>Page Widget Options</h4>
* Add a title to the widget
* Include the highest level parent page
* Include the highest level parent page even with no Children
* Order Pages By (date, title, page order)
* Use built in styling (very plain styling, for more advanced styling <a href="https://matlipe.com/product/advanced-sidebar-menu-pro/" target="_blank">Go Pro!</a>)
* Exclude pages
* Always display child Pages
* Number of levels of child pages to display when always display child pages is checked

<h4>Category Widget Options</h4>
* Add a title to the widget
* Include Parent Category
* Include Parent Even with no Children
* Use built in styling (very plain styling, for more advanced styling <a href="https://matlipe.com/product/advanced-sidebar-menu-pro/" target="_blank">Go Pro!</a>)
* Display Categories on Single Posts
* To display each Single Posts Category in a new widget or in same list
* Exclude Categories
* Always display child categories
* Levels of Categories to display when always display child categories is checked

<h4>Pro Features</h4>
* Priority support
* Ability to customize each page link’s text
* Click and drag styling for both the page and category widgets
* Styling options for links including color, background color, size,  and font weight
* Styling options for different levels of links
* Styling options for the current page or category
* Styling options for the parent of the current page or category
* Block styling options including borders and border colors
* Bullet style selection from 7 styles or select none to have no bullets
* Current page parent only for the page widget
* Accordion menu support for pages
* Accordion menu support for categories
* Accordion icon selection from 4 styles of icons
* Accordion icon color selection
* Accordion close all sections
* Include parent in accordion
* Ability to exclude a page from all menus using a simple checkbox
* Link ordering for the category widget
* Number of levels of pages to show when always displayed child pages is not checked.
* Ability to select and display custom post types **NEW**
   
<h4>Developers</h4>
Developer docs may be found <a target="_blank" href="https://matlipe.com/advanced-sidebar-menu/developer-docs/">here</a>.

<h4>Contribute</h4>
Send pull requests via the <a target="_blank" href="https://github.com/lipemat/advanced-sidebar-menu">GitHub Repo</a>


== Installation ==

Use the standard WordPress plugins search and install feature.

Manual Installation

1. Upload the `advanced-sidebar-menu` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Drag the "Advanced Sidebar Pages Menu" widget or the "Advanced Sidebar Categories Menu" widget into a sidebar.


== Screenshots ==

1. Screenshot of the Page widget and options
2. Screenshot of the Categories widget and options


== Frequently Asked Questions ==

Developer docs may be found here:
<a href="https://matlipe.com/advanced-sidebar-menu/developer-docs/" target="_blank">https://matlipe.com/advanced-sidebar-menu/developer-docs/</a>

= What text domain do I use for translation and where is the .pot file? =

The .pot file may be found in the plugins' languages folder. Use the 'advanced-sidebar-menu' text domain.


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

There is a checkbox in the widget options that will display the same structure for the categories the post is in.

= How do you edit the output or built in css? =

Create a folder in your child theme named "advanced-sidebar-menu" copy any of the files from the "views" folder into
the folder you just created. You may edit the files at will to change the output or css.
You must have the option checked to use the built in CSS (in the widget) to be able to edit the css file in this way.


= Does the menu change for each page you are on? =

Yes. Based on whatever page you are on, the menu will change automatically to display the current parents and children.

= How does this work with styling the page? =

This will automatically generate class names for each level for menu system.
You can add classes to your theme's style.css file to style it accordingly. 
You may want to use something like margins to set the levels apart.

== Changelog ==
= 7.0.0 =
* Restructure the codebase to a more modern PSR4 structure
* Improve cache handling
* Improve verbiage in admin
* Implement new actions and filters
* Rebuild templates for improved stability and future changes
* Improve performance
* Kill conflicting backward compatibility with version 5
* Open up more extendability possibilities

= 6.4.0 =
* Code improvements
* Performance improvements via shared child retrieval

= 6.3.0
* Improve category class handling for pro version accordion support

= 6.2.0 =
* Improve sorting of categories

= 6.1.0 =
* Improve page list view
* Add advanced_sidebar_menu_list_pages_args filter

= 6.0.0 =
* Remove legacy template support
* Restructure plugin
* Introduce 'advanced_sidebar_menu_template_part' filter


= 5.1.0 =
* Convert query over to get_posts() to allow for more extendability
* Implement object caching to improve performance for environments using external object caches
* Begin modernizing the naming conventions of methods and improving PHPdocs

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


== Upgrade Notice ==
= 7.0.0 =
The templates have been improved drastically. While the old version 6 templates will work for now they have been deprecated and will one day stop working. If you are using custom templates please being converting them to the new structure.

= 6.0.0 =
If you are using the Pro version of this plugin be sure to update to Pro version 1.4.4 to keep all functionality intact with this version.

= 5.0.0 =
If you used a custom page_list.php template previously you may want to redo it on this version to take advantage of the new structure.

