<?php

use Gettext\Extractors\Po as PoExtractor;
use Gettext\Generators\Jed;
use Gettext\Translations;

require __DIR__ . '/vendor/autoload.php';

const RELATIVE_PATH = 'js/dist/advanced-sidebar-menu-block-editor.js';
const EXTENSIONS = [ '.ts', '.js', 'jsx', 'tsx' ];

class LocalGenerator extends Jed {
	/**
	 * Wrap the translations with `local_data` so WP can read
	 * them. Otherwise, same as default Jed.
	 *
	 * @see WP_CLI\I18n\JedGenerator
	 */
	public static function toString( Translations $translations, array $options = [] ) {
		$message = parent::toString( $translations );
		$object = json_decode( $message, true );
		return json_encode( [
			'locale_data' => $object,
		] );
	}
}

/**
 * Extract the JSON from the PO files and generate
 * JSON files including just the translations used in JS files.
 *
 * @param string $source_file - PO file.
 *
 * @return void
 */
function make_json( $source_file ) {
	$all_translations = new Translations();
	PoExtractor::fromFile( $source_file, $all_translations );

	$base_file_name = basename( $source_file, '.po' ) . '-' . md5( RELATIVE_PATH );

	$js_translations = new Translations();
	$js_translations->setHeader( 'Language', $all_translations->getLanguage() );
	$js_translations->setHeader( 'PO-Revision-Date', $all_translations->getHeader( 'PO-Revision-Date' ) );

	$plural_forms = $all_translations->getPluralForms();
	if ( $plural_forms ) {
		[ $count, $rule ] = $plural_forms;
		$js_translations->setPluralForms( $count, $rule );
	}

	foreach ( $all_translations as $translation ) {
		foreach ( $translation->getReferences() as $reference ) {
			if ( in_array( substr( $reference[0], - 3 ), EXTENSIONS, true ) ) {
				$js_translations[] = $translation;
				break;
			}
		}
	}

	$destination_file = "../../languages/{$base_file_name}.json";
	$success = LocalGenerator::toFile( $js_translations, $destination_file );

	if ( ! $success ) {
		echo sprintf( 'Could not create %s file.' . "\n", basename( $destination_file ) );
		exit( 1 );
	}

	echo sprintf( '%s has been created.' . "\n", basename( $destination_file ) );
}

$count = 0;
foreach ( new IteratorIterator( new DirectoryIterator( '../../languages' ) ) as $file ) {
	if ( $file->isFile() && $file->isReadable() && 'po' === $file->getExtension() ) {
		make_json( $file->getRealPath() );
		$count ++;
	}
}

if ( $count < 3 ) {
	echo 'Unable to create translation files' . "\n";
	exit( 1 );
}

exit( 0 );
