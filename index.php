<?php

// $Header: /cvsroot/bitweaver/_bit_pdf/index.php,v 1.3 2008/06/25 22:21:15 spiderr Exp $

// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

/**
* required setup
*/
require_once( '../bit_setup_inc.php' );

require_once( LIBERTY_PKG_PATH.'lookup_content_inc.php');

$gBitSystem->verifyPackage( 'pdf' );
//$gBitSystem->verifyFeature( 'feature_pdf_generation' );

$gBitSystem->setBrowserTitle( 'Create PDF for '.$gContent->mInfo['title'] );

$gBitSystem->display( 'config_pdf.tpl', NULL, array( 'display_mode' => 'display' ));

?>
