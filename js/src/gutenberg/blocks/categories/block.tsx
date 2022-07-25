import {PreviewOptions} from '../Preview';
import {CONFIG, I18N} from '../../../globals/config';
import {BlockSettings, LegacyWidget} from '@wordpress/blocks';
import Edit from './Edit';
import {useBlockProps} from '@wordpress/block-editor';
import {EXAMPLE, transformLegacyWidget} from '../pages/block';
import {DisplayOptions} from '../Display';

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
	taxonomy: string;
}

export type setAttributes = ( newValue: {
	[attribute in keyof Attr]?: Attr[attribute]
} ) => void;

export const block = CONFIG.blocks.categories;

export const name = block.id;


export const settings: BlockSettings<Attr, '', LegacyWidget<Attr & { title: string }>> = {
	title: I18N.categories.title,
	description: I18N.categories.description,
	icon: 'welcome-widgets-menus',
	category: 'widgets',
	keywords: I18N.categories.keywords,
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
				transform: transformLegacyWidget,
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
