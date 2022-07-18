import {useBlockProps} from '@wordpress/block-editor';
import {BlockSettings} from '@wordpress/blocks';
import {CONFIG, I18N} from '../../../globals/config';
import Edit from './Edit';
import {PreviewOptions} from '../Preview';

/**
 * Attributes specific to the widget as well as shared
 * widget attributes.
 *
 * @see \Advanced_Sidebar_Menu\Blocks\Block_Abstract::get_all_attributes
 * @see \Advanced_Sidebar_Menu\Blocks\Pages::get_attributes
 */
export type Attr = {
	display_all: boolean;
	exclude: string;
	include_childless_parent: boolean;
	include_parent: boolean;
	levels: string;
	order_by: string;
	title: string;
} & ProRegistered & PreviewOptions;

// Options used by basic when available from PRO.
type ProRegistered = {
	post_type: string;
}

export type setAttributes = ( newValue: {
	[attribute in keyof Attr]?: Attr[attribute]
} ) => void;

/**
 * Attributes used for the example preview.
 * Combines some PRO and basic attributes.
 * The PRO attributes will only be sent if PRO is active.
 */
const example = {
	include_parent: true,
	include_childless_parent: true,
	display_all: true,
	levels: '2',
	apply_current_page_styles_to_parent: true,
	apply_current_page_parent_styles_to_parent: true,
	block_style: true,
	border: true,
	border_color: '#333',
	bullet_style: 'none',
	parent_page_color: '#fff',
	parent_page_bg_color: '#666',
	child_page_color: '#fff',
	child_page_bg_color: '#666',
	grandchild_page_color: '#282828',
	grandchild_page_bg_color: '#989898',
	grandchild_page_font_weight: 'bold',
	current_page_color: '#0cc4c6',
	current_page_bg_color: '#282828',
	current_page_font_weight: 'normal',
	current_page_parent_bg_color: '#333',
};

export const block = CONFIG.blocks.pages;

export const name = block.id;

export const settings: BlockSettings<Attr> = {


	// @todo - Translate all strings here
	title: I18N.widgetPages,
	description: 'Creates a menu of all the pages using the child/parent relationship',
	icon: 'welcome-widgets-menus',
	category: 'widgets',
	// @todo - Translated and english keywords so both will be searchable.
	keywords: [
		'Advanced Sidebar',
		'menu',
		'sidebar',
		'pages',
	],
	example: {
		attributes: example as any,
	},
	// `attributes` are registered server side because we use ServerSideRender.
	// `supports` are registered server side for easy overrides.
	edit: props => (
		<Edit {...props} />
	),
	save: () => {
		const blockProps = useBlockProps.save();
		return <div {...blockProps}>%s</div>;
	},
	apiVersion: 2,
};
