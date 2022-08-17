import {PreviewOptions} from '../Preview';
import {CONFIG} from '../../../globals/config';
import {BlockSettings, LegacyWidget} from '@wordpress/blocks';
import Edit from './Edit';
import {useBlockProps} from '@wordpress/block-editor';
import {DisplayOptions} from '../Display';
import {transformLegacyWidget} from '../../helpers';
import {__} from '@wordpress/i18n';

/**
 * Attributes specific to the widget as well as shared
 * widget attributes.
 *
 * @see \Advanced_Sidebar_Menu\Blocks\Block_Abstract::get_all_attributes
 * @see \Advanced_Sidebar_Menu\Blocks\Pages::get_attributes
 */
export type Attr = {
	exclude: string;
	order_by: string;
	single: boolean;
	title: string;
	new_widget: 'widget' | 'list';
} & DisplayOptions & ProRegistered & PreviewOptions;

// Options used by basic when available from PRO.
type ProRegistered = {
	taxonomy: string;
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
	levels: '2',
};


export const block = CONFIG.blocks.categories;

export const name = block.id;

export const settings: BlockSettings<Attr, '', LegacyWidget<Attr & { title: string }>> = {
	title: __( 'Advanced Sidebar - Categories', 'advanced-sidebar-menu' ),
	icon: 'welcome-widgets-menus',
	category: 'widgets',
	example: {
		attributes: EXAMPLE as any,
	},
	transforms: {
		from: [
			{
				type: 'block',
				blocks: [ 'core/legacy-widget' ],
				isMatch: ( {idBase, instance} ) => {
					if ( ! instance?.raw ) {
						// Can't transform if raw instance is not shown in REST API.
						return false;
					}
					return 'advanced_sidebar_menu_category' === idBase;
				},
				transform: transformLegacyWidget<Attr>( name ),
			},
		],
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
