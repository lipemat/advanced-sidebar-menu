import type {EntriesConfig} from '@lipemat/js-boilerplate/config/entries.config';

/**
 * @see `webpack.dist` for where .min entries are set.
 */
const entries: EntriesConfig = {
	'advanced-sidebar-menu-admin': [ 'widget-admin.ts' ],
	'advanced-sidebar-menu-block-editor': [ 'block-editor.ts' ],
};

module.exports = entries;
