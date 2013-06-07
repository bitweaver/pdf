<?php
global $gBitSystem, $gLibertySystem, $gBitThemes;

$registerHash = array(
	'package_name' => 'pdf',
	'package_path' => dirname( __FILE__ ).'/',
	'service' => LIBERTY_SERVICE_DOCUMENT_GENERATION,
);
$gBitSystem->registerPackage( $registerHash );

if( $gBitSystem->isPackageActive( 'pdf' ) ) {
	$gLibertySystem->registerService( LIBERTY_SERVICE_DOCUMENT_GENERATION, PDF_PKG_NAME, array(
		'content_icon_tpl' => 'bitpackage:pdf/pdf_service_icons.tpl',
	) );
}
?>
