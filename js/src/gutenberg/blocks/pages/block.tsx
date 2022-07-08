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
	display_all: string;
	exclude: string;
	include_childless_parent: boolean;
	include_parent: boolean;
	levels: number;
	order_by: string;
	title: string;
} & PreviewOptions;

export type setAttributes = ( newValue: {
	[attribute in keyof Attr]?: Attr[attribute]
} ) => void;

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
