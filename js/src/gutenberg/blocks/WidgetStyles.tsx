import {Dropdown, ToolbarButton} from '@wordpress/components';
import {I18N} from '../../globals/config';
import {BlockEditProps} from '@wordpress/blocks';
import Section from './widget-styles/Section';

import styles from './widget-styles.pcss';

export type StyleOptions = {
	apply_current_page_styles_to_parent: boolean;
	apply_current_page_parent_styles_to_parent: boolean;
	block_style: boolean;
	border: boolean;
	border_color: boolean;
	border_width: boolean;
	bullet_style: boolean;
}

export type Props = Pick<BlockEditProps<StyleOptions>, 'attributes' | 'setAttributes'>;

const WidgetStyles = ( {attributes, setAttributes}: Props ) => {
	return (
		<Dropdown
			renderToggle={( {isOpen, onToggle} ) => <ToolbarButton
				className={styles.toolbarButton}
				title={I18N.menuStyles}
				onClick={onToggle}
				icon={'admin-appearance'}
				isActive={isOpen}
			/>}
			renderContent={() => I18N.styles.sections.map( section => <Section
				key={section.prefix}
				attributes={attributes}
				setAttributes={setAttributes}
				section={section} /> )}
		/>
	);
};

export default WidgetStyles;
