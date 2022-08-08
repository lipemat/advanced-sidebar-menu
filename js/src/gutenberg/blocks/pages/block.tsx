import {useBlockProps} from '@wordpress/block-editor';
import {BlockSettings, LegacyWidget} from '@wordpress/blocks';
import {CONFIG, I18N} from '../../../globals/config';
import Edit from './Edit';
import {PreviewOptions} from '../Preview';
import {DisplayOptions} from '../Display';
import {transformLegacyWidget} from '../../helpers';

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
} & DisplayOptions & ProRegistered & PreviewOptions;

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
const EXAMPLE = {
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

export const settings: BlockSettings<Attr, '', LegacyWidget<Attr & { title: string }>> = {
	title: I18N.pages.title,
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
					return 'advanced_sidebar_menu' === idBase;
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
