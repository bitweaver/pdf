<?php

// $Header: /cvsroot/bitweaver/_bit_pdf/index.php,v 1.6 2010/02/08 21:27:24 wjames5 Exp $

// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See below for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.

/**
* required setup
*/
require_once( '../kernel/setup_inc.php' );

require_once( LIBERTY_PKG_PATH.'lookup_content_inc.php');

$gBitSystem->verifyPackage( 'pdf' );
//$gBitSystem->verifyFeature( 'feature_pdf_generation' );

$gBitSystem->setBrowserTitle( 'Create PDF for '.$gContent->mInfo['title'] );

$gBitSystem->display( 'config_pdf.tpl', NULL, array( 'display_mode' => 'display' ));

?>
