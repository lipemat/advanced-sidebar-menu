=== Advanced Sidebar Menu ===

Contributors: Mat Lipe
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=paypal%40matlipe%2ecom&lc=US&item_name=Advanced%20Sidebar%20Menu&no_note=0&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest
Tags: menus, sidebar menu, hierarchy, category menu, pages menu
Requires at least: 4.6.0
Tested up to: 4.9.2
Requires PHP: 5.2.4
Stable tag: 7.1.1

== Description ==

Uses the parent/child relationship of your pages or categories to generate menus based on the current section of your site. Assign a page or category to a parent and this will do the rest for you.

Keeps the menu clean and usable. Only related items display so you don't have to worry about keeping a custom menu up to date or displaying links to items that don't belong. 

<strong>Check out <a href="https://matlipe.com/product/advanced-sidebar-menu-pro/">Advanced Sidebar Menu Pro</a> for more features including priority support, the ability to customize the look and feel, custom link text, excluding of pages, category ordering, accordions, custom post types, custom taxonomies, and so much more!</strong>

<blockquote><a href="https://matlipe.com/product/advanced-sidebar-menu-pro/" target="_blank">Pro version 3.0.0</a> just dropped with lots of great new stuff!</blockquote>

<h4>Features</h4>
* Page and Category widgets
* Option to display or not display the highest level parent page or category
* Option to display the menu when there is only the highest level parent
* Ability to order pages by (date, title, page order)
* Exclude pages or categories by entering a comma separated list of ids
* Option to always display child pages or categories
* Option to select the levels of pages or categories to display when always display child is used
* Option to display or not display categories on single posts
* Ability to display each single post's category in a new widget or in same list

<h4>Page Widget Options</h4>
* Add a title to the widget
* Display highest level parent page
* Display menu when there is only the parent page
* Order pages by (date, title, page order)
* Use built in styling (very plain styling, for more advanced styling <a href="https://matlipe.com/product/advanced-sidebar-menu-pro/" target="_blank">Go Pro!</a>)
* Exclude pages
* Always display child Pages
* Number of levels of child pages to display when always display child pages is checked

<h4>Category Widget Options</h4>
* Add a title to the widget
* Display highest level parent category
* Display menu when there is only the parent category
* Use built in styling (very plain styling, for more advanced styling <a href="https://matlipe.com/product/advanced-sidebar-menu-pro/" target="_blank">Go Pro!</a>)
* Display categories on single posts
* Display each single post's category in a new widget or in same list
* Exclude categories
* Always display child categories
* Levels of Categories to display when always display child categories is checked

<h4>Pro Features</h4>
* Priority support
* Ability to customize each page link’s text
* Option to display the current page's parents and grandparents only
* Number of levels of pages to show when always displayed child pages is not checked
* Click and drag styling for both the page and category widgets
* Styling options for links including color, background color, size, and font weight
* Styling options for different levels of links
* Styling options for the current page or category
* Styling options for the parent of the current page or category
* Block styling options including borders and border colors
* Bullet style selection from 7 styles or select none to have no bullets
* Accordion menu support for pages
* Accordion menu support for categories
* Accordion icon selection from 4 styles of icons
* Accordion icon color selection
* Accordion option to keep all sections closed until clicked
* Accordion option to include highest level parent in accordion
* Ability to exclude a page from all menus using a simple checkbox
* Link ordering for the category widget
* Ability to select and display custom post types **NEW**
* Ability to select and display custom taxonomies **NEW**

<h4>Currently ships with the following languages</h4>
* English (US)
* German (de_DE)
   
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

1. Page widget options
2. Category widget options
3. Example of a page menu using the 2017 theme and default styles
3. Example of a category menu ordered by title using the 2017 theme and default styles


== Frequently Asked Questions ==

Developer docs may be found here:
<a href="https://matlipe.com/advanced-sidebar-menu/developer-docs/" target="_blank">https://matlipe.com/advanced-sidebar-menu/developer-docs/</a>


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

To style your menu without using any code <a href="https://matlipe.com/product/advanced-sidebar-menu-pro/" target="_blank">Go Pro!</a>

= How do you get the categories to display on single post pages? =

There is a checkbox in the widget options that will display the same structure for the categories the post is in.

= How do you edit the output or built in css? =

Create a folder in your child theme named "advanced-sidebar-menu" copy any of the files from the "views" folder into
the folder you just created. You may edit the files to change the output or css. You must have the option checked to use the built in CSS (in the widget) to be able to edit the css file in this way.


= Does the menu change for each page you are on? =

Yes. Based on whatever page, post, or category you are on, the menu will change automatically to display the current parents and children.


== Changelog ==
= 7.1.0 =
* Support Pro Version 3.0.0
* Add German translations
* Begin converting code formatting to strict WordPress standards

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

