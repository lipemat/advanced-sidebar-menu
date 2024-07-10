<?php
/**
 * Extract translations from PO files and generate JSON files.
 *
 * @version 2.0.0
 */

use Gettext\Extractors\Po as PoExtractor;
use Gettext\Generators\Jed;
use Gettext\Translation;
use Gettext\Translations;

require __DIR__ . '/vendor/autoload.php';

const DOMAIN = 'advanced-sidebar-menu';
const VERSION = '2.0.0';
const RELATIVE_PATH = 'js/dist/advanced-sidebar-menu-block-editor.js';
const EXTENSIONS = [ '.ts', '.js', 'jsx', 'tsx' ];

/**
 * Jed file generator.
 *
 * Adds some more meta data to JED translation files than the default generator.
 *
 * @see \WP_CLI\I18n\JedGenerator
 */
class JedGenerator extends Jed {
	/**
	 * {@parentDoc}.
	 */
	public static function toString( Translations $translations, array $options = [] ) {
		$options += static::$options;
		$domain = $translations->getDomain() ?: 'messages';
		$messages = static::buildMessages( $translations );

		$configuration = [
			'' => [
				'domain'       => $domain,
				'lang'         => $translations->getLanguage() ?: 'en',
				'plural-forms' => $translations->getHeader( 'Plural-Forms' ) ?: 'nplurals=2; plural=(n != 1);',
			],
		];

		$data = [
			'translation-revision-date' => $translations->getHeader( 'PO-Revision-Date' ),
			'source'                    => $options['source'],
			'domain'                    => $domain,
			'generator'                 => 'OnPoint Plugins/' . VERSION,
			'locale_data'               => [
				$domain => $configuration + $messages,
			],
		];

		return json_encode( $data, $options['json'] );
	}


	/**
	 * Generates an array with all translations.
	 *
	 * @param Translations $translations
	 *
	 * @return array
	 */
	public static function buildMessages( Translations $translations ) {
		$plural_forms = $translations->getPluralForms();
		$number_of_plurals = is_array( $plural_forms ) ? ( $plural_forms[0] - 1 ) : null;
		$messages = [];
		$context_glue = chr( 4 );

		foreach ( $translations as $translation ) {
			/** @var Translation $translation */

			if ( $translation->isDisabled() ) {
				continue;
			}

			$key = $translation->getOriginal();

			if ( $translation->hasContext() ) {
				$key = $translation->getContext() . $context_glue . $key;
			}

			if ( $translation->hasPluralTranslations( true ) ) {
				$message = $translation->getPluralTranslations( $number_of_plurals );
				array_unshift( $message, $translation->getTranslation() );
			} else {
				$message = [ $translation->getTranslation() ];
			}

			$messages[ $key ] = $message;
		}

		return $messages;
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
	$js_translations->setHeader( 'X-Domain', DOMAIN );

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
	$success = JedGenerator::toFile( $js_translations, $destination_file, [
		'source'    => basename( $source_file ),
		'Generator' => 'OnPoint Plugins\/' . VERSION,
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
