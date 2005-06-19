<?php
global $gBitSystem;
$gBitSystem->registerPackage( 'pdf', dirname( __FILE__ ).'/' );
if( $gBitSystem->isPackageActive( 'pdf' ) ) {
	if( !empty( $_REQUEST['style'] ) ) {
		global $gPreviewStyle;
		$gPreviewStyle = $_REQUEST['style'];
	}
	if( !empty( $_REQUEST['no_force'] ) ) {
		global $gNoForceStyle;
		$gNoForceStyle = TRUE;
	}
}
?>
