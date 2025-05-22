<?php
//phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

namespace Advanced_Sidebar_Menu\Blocks\Attributes;

use Advanced_Sidebar_Menu\Menus\Category as Menu;
use Advanced_Sidebar_Menu\Menus\Menu_Abstract;
use Advanced_Sidebar_Menu\Widget\Category as Widget;

/**
 * A fluent interface for the Category block attributes.
 *
 * @author OnPoint Plugins
 * @since  9.7.0
 *
 * @phpstan-import-type BOOL_CHECKED from Utils
 * @phpstan-import-type CATEGORY_SETTINGS from Menu
 * @phpstan-import-type COMMON_ATTR from Common
 *
 * @implements Attributes<CATEGORY_SETTINGS>
 */
class CategoryAttr implements Attributes {
	/**
	 * @use Common<CATEGORY_SETTINGS>
	 */
	use Common;

	/**
	 * Always display child categories.
	 *
	 * @var bool
	 */
	public bool $display_all;

	/**
	 * category IDs to exclude in a comma-separated list.
	 *
	 * @var string
	 */
	public string $exclude;

	/**
	 * Indicates if the highest level parent should be included in the list
	 * if it has no children.
	 *
	 * @var bool
	 */
	public bool $include_childless_parent;

	/**
	 * Indicates if the highest level parent should be included in the list.
	 *
	 * @var bool
	 */
	public bool $include_parent;

	/**
	 * The number of levels to display.
	 *
	 * @var int|numeric-string
	 */
	public $levels;

	/**
	 * How to display each single post's category.
	 *
	 * @phpstan-var Menu::EACH_*
	 * @var 'list'|'widget'
	 */
	public string $new_widget;

	/**
	 * Display on single posts?
	 *
	 * @var bool
	 */
	public bool $single;

	/**
	 * Used by the PRO version.
	 *
	 * @var string
	 */
	public string $taxonomy;

	/**
	 * @todo Remove when the minimum PRO version is 9.9.0.
	 * @var array<string, mixed>
	 */
	private array $instance; //phpcs:ignore


	/**
	 * Constructor.
	 *
	 * @param array<string, mixed> $instance - The instance to parse.
	 */
	protected function __construct( array $instance ) {
		$utils = Utils::instance();

		$this->display_all = $utils->get_checked_bool( Menu::DISPLAY_ALL, $instance );
		$this->exclude = $instance[ Menu::EXCLUDE ] ?? '';
		$this->include_childless_parent = $utils->get_checked_bool( Menu::INCLUDE_CHILDLESS_PARENT, $instance );
		$this->include_parent = $utils->get_checked_bool( Menu::INCLUDE_PARENT, $instance );
		$this->levels = $instance[ Menu::LEVELS ] ?? 1;
		$this->new_widget = $instance[ Widget::POST_CATEGORY_LAYOUT ] ?? Menu::EACH_WIDGET;
		$this->single = $utils->get_checked_bool( Menu::DISPLAY_ON_SINGLE, $instance );
		$this->taxonomy = $instance['taxonomy'] ?? 'category';

		$this->instance = $instance;
	}


	/**
	 * Get the finished arguments in widget format.
	 *
	 * @phpstan-return \Union<\Required<CATEGORY_SETTINGS>, COMMON_ATTR>
	 */
	public function get_args(): array {
		// @todo Remove when the minimum PRO version is 9.9.0.
		if ( \defined( 'ADVANCED_SIDEBAR_MENU_PRO_VERSION' ) && ! \class_exists( ProCategoryAttr::class ) ) {
			return Utils::instance()->convert_all_checkboxes( $this->instance );
		}

		$utils = Utils::instance();

		return [
			'clientId'                     => $this->clientId,
			'sidebarId'                    => $this->sidebarId,
			'style'                        => $this->style,
			Menu_Abstract::TITLE           => $this->title,
			'isServerSideRenderRequest'    => $this->isServerSideRenderRequest,
			Menu::DISPLAY_ALL              => $utils->get_bool_checked( $this->display_all ),
			Menu::EXCLUDE                  => $this->exclude,
			Menu::INCLUDE_CHILDLESS_PARENT => $utils->get_bool_checked( $this->include_childless_parent ),
			Menu::INCLUDE_PARENT           => $utils->get_bool_checked( $this->include_parent ),
			Menu::LEVELS                   => $this->levels,
			Menu::POST_CATEGORY_LAYOUT     => $this->new_widget,
			Menu::DISPLAY_ON_SINGLE        => $utils->get_bool_checked( $this->single ),

			// Used in the PRO version.
			'display-posts'                => '',
			'taxonomy'                     => $this->taxonomy,
		];
	}


	/**
	 * Get an instance of the CategoryAttr class.
	 *
	 * @param array<string, mixed> $instance - The instance to parse.
	 *
	 * @return CategoryAttr
	 */
	public static function factory( array $instance ): CategoryAttr {
		$class = apply_filters( 'advanced-sidebar-menu/blocks/attributes/category-attr/class', __CLASS__, $instance );
		if ( ! \is_a( $class, __CLASS__, true ) ) {
			$class = __CLASS__;
		}
		/* @var CategoryAttr $category - Category instance. */
		$category = new $class( $instance );
		$category->set_common( $instance );
		return $category;
	}
}
