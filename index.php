<?php

// $Header: /cvsroot/bitweaver/_bit_pdf/index.php,v 1.1 2005/06/19 04:57:46 bitweaver Exp $

// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once( '../bit_setup_inc.php' );

require_once( LIBERTY_PKG_PATH.'lookup_content_inc.php');

$gBitSystem->verifyPackage( 'pdf' );
//$gBitSystem->verifyFeature( 'feature_pdf_generation' );

$gBitSystem->setBrowserTitle( 'Create PDF for '.$gContent->mInfo['title'] );

$gBitSystem->display( 'config_pdf.tpl');

?>
