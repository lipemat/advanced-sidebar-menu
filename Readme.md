# Advanced Sidebar Menu - WordPress Plugin

This Readme is for development.

Full plugin information is available in [readme.txt](readme.txt).

## Deploying to WordPress.org

Using the [action provided by 10Up](https://github.com/10up/action-wordpress-plugin-deploy).

New tags are automatically deployed to wordpress.org via SVN.

### Updating Readme or Assets between versions

Using the [action provided by 10Up](https://github.com/10up/action-wordpress-plugin-asset-update).

Changes to `readme.txt` or `.wordpress-org` on the `master` branch are automatically deployed to the matching tag on wordpress.org when
the `deploy` branch is pushed.

**If other changes have been made to the `master` branch, nothing will be deployed.**

Plugin assets like screenshots are kept in the `.wordpress-org` directory.

### Configuration

* Ignore files from SVN via `.distignore`.
* Assets are updated within `.wordpress-org`.
* SVN credentials are stored as [GitHub secrets](https://github.com/lipemat/advanced-sidebar-menu/settings/secrets)

## Translation Process

### Poedit

Out of the box, Poedit does not support TypeScript extensions. TypeScript support may be added using the following:

1. File -> Preferences
2. Tab -> Extractors
3. `+`
4. Language -> "TypeScript"
5. List of extensions -> "*.tsx;*.ts"
6. Command -> "xgettext -L JavaScript --add-comments=translators: --force-po -o %o %C %K %F"
7. An item in keyword list -> "-k%k"
8. An item in input files list -> "%f"
9. Source code charset -> "--from-code=%c"

### PHP

PHP translations are using the standard [i18n process](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/).
1. Open `advanced-sidebar-menu.pot` file:
2. Click "Update from code".
3. Click "Save".
4. Open various `.po` files:
5. Click "Update from code".
6. Translate any missing strings.
7. Click "Save".

### JS

JS files are translated using the [block editor i18n process](https://developer.wordpress.org/block-editor/how-to-guides/internationalization/).

This plugin contains a custom PHP CLI command exists in the `dev/translate-cli` directory for generating JSON files. Like WP CLI `i18n make-json` command with a few differences to  
support a Webpack/TypeScript structure.

1. Looks for .jsx, .ts, and .tsx as well as the .js.
2. Combines all matching translations to a single file instead of split
   by source file.
3. Use the `js/dist/admin.js` as the source file for all.
4. May be run outside WP using PHP.

The JSON files are automatically generated during deployment via GitHub Actions scripts.

**Manually Generate Translation JSON Files**
1. Run `composer install`.
2. Run `php command.php`
