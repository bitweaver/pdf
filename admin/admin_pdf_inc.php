<?php

require_once( PDF_PKG_PATH.'BitPdf.php' );

//defaults
$pdf = new BitPdf();

if( !empty( $_REQUEST['save'] ) ) {
	if( $gBitSystem->isPackageActive( 'pdf' ) ) {
		$pdf->storeSettings( $_REQUEST );
	}
}

// assign to smarty
$gBitSmarty->assign('pdfSettings', $pdf->mSettings );
?>
