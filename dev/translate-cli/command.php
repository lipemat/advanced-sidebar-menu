<?php

use Gettext\Extractors\Po as PoExtractor;
use Gettext\Generators\Jed;
use Gettext\Translations;

require __DIR__ . '/vendor/autoload.php';

const SOURCE = 'js/dist/admin.js';
const EXTENSIONS = [ '.ts', '.js', 'jsx', 'tsx' ];

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

	$base_file_name = basename( $source_file, '.po' );

	$js_translations = new Translations();
	$js_translations->setHeader( 'Language', $all_translations->getLanguage() );
	$js_translations->setHeader( 'PO-Revision-Date', $all_translations->getHeader( 'PO-Revision-Date' ) );

	$plural_forms = $all_translations->getPluralForms();
	if ( $plural_forms ) {
		list( $count, $rule ) = $plural_forms;
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
	$success = Jed::toFile( $js_translations, $destination_file, [
		'json'   => 0,
		'source' => SOURCE,
	] );

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
