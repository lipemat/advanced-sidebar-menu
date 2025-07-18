import {CONFIG} from '../../../globals/config';
import {BlockSettings, LegacyWidget} from '@wordpress/blocks';
import Edit from './Edit';
import {DisplayOptions} from '../Display';
import {getBlockSupports, transformLegacyWidget, translateBlockAttributes} from '../../helpers';
import {__} from '@wordpress/i18n';
import type {CommonAttr} from '../Preview';
import CategoriesIcon from './CategoriesIcon';

/**
 * Attributes specific to the widget as well as shared
 * widget attributes.
 *
 * @see \Advanced_Sidebar_Menu\Blocks\Block_Abstract::get_all_attributes
 * @see \Advanced_Sidebar_Menu\Blocks\Pages::get_attributes
 */
export type Attr = {
	exclude: string;
	new_widget: 'widget' | 'list';
	single: boolean;
} & DisplayOptions & ProRegistered & CommonAttr;

// Options used by basic when available from PRO.
type ProRegistered = {
	taxonomy?: string;
}

export type setAttributes = ( newValue: {
	[attribute in keyof Attr]?: Attr[attribute]
} ) => void;

/**
 * Attributes used for the example preview.
 * Combines some PRO and basic attributes.
 * The PRO attributes will only be sent if PRO is active.
 */
const EXAMPLE = {
	'display-posts': 'all',
	'display-posts/limit': 2,
	apply_current_page_parent_styles_to_parent: true,
	apply_current_page_styles_to_parent: true,
	block_style: true,
	border: true,
	border_color: '#333',
	bullet_style: 'none',
	child_page_bg_color: '#666',
	child_page_color: '#fff',
	parent_page_bg_color: '#282828',
	parent_page_color: '#0cc4c6',
	parent_page_font_weight: 'normal',
	display_all: true,
	grandchild_page_bg_color: '#989898',
	grandchild_page_color: '#282828',
	grandchild_page_font_weight: 'bold',
	include_childless_parent: true,
	include_parent: true,
	levels: 2,
};


export const block = CONFIG.blocks.categories;

export const name = block.id;

export const settings: BlockSettings<Attr, '', LegacyWidget<Attr & { title: string }>> = {
	title: __( 'Advanced Sidebar - Categories', 'advanced-sidebar-menu' ),
	icon: CategoriesIcon,
	category: 'widgets',
	description: __( 'Creates a menu of all the categories using the parent/child relationship',
		'advanced-sidebar-menu' ),
	keywords: [
		__( 'taxonomy', 'advanced-sidebar-menu' ),
		__( 'term', 'advanced-sidebar-menu' ),
		__( 'category', 'advanced-sidebar-menu' ),
		__( 'menu', 'advanced-sidebar-menu' ),
	],
	example: {
		attributes: EXAMPLE,
	},
	transforms: {
		from: [
			{
				type: 'block',
				blocks: [ 'core/legacy-widget' ],
				isMatch: ( {idBase, instance} ) => {
					if ( null === instance?.raw ) {
						// Can't transform if the raw instance is not shown in REST API.
						return false;
					}
					return 'advanced_sidebar_menu_category' === idBase;
				},
				transform: transformLegacyWidget<Attr>( name ),
			},
		],
	},
	attributes: translateBlockAttributes<Attr>( block.attributes ),
	supports: getBlockSupports(),
	edit: props => (
		<Edit {...props} />
	),
	save: () => null,
	apiVersion: 3,
};
