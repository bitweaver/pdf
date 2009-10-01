<?php
/**
* Pdf system class for outputing pdf file images
*
* @author   
* @version  $Revision: 1.9 $
* @package  pdf
*/

// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See below for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.

/**
* required setup
*/
//include_once("tiki-setup_base.php");
include_once( '../bit_setup_inc.php' );

require_once( LIBERTY_PKG_PATH.'LibertySystem.php');
require_once( LIBERTY_PKG_PATH.'LibertyStructure.php');
require_once( WIKI_PKG_PATH.'BitPage.php');
require_once( UTIL_PKG_PATH.'zip_lib.php' );
require_once( WIKI_PKG_PATH.'export_lib.php' );
//include_once ('lib/pdflib/pdflib.php');
require_once( PDF_PKG_PATH.'BitPdf.php' );

$gBitSystem->verifyPackage( 'pdf' );
$gBitSystem->verifyPermission( 'p_wiki_view_page' );

global $gLibertySystem;

$pdflib = &new BitPdf();

// Get pages data

global $gStructure;

require_once( LIBERTY_PKG_PATH.'lookup_content_inc.php' );

if( is_object( $gStructure ) ) {
	$convertpages = array();
	$gStructure->getContentArray( $gStructure->mStructureId, $convertpages );
	// always put the first root page in it's own PDF page - it usually has a {toc}
	$rootConId = array_shift( $convertpages );
	if( $conPage = $gLibertySystem->getLibertyObject( $rootConId ) ) {
		$pdata = "\n<C:page:".$conPage->mInfo['title'].">\n<br/>\n";
		$pdata .= $conPage->getPreview();
		$pdata = utf8_decode( $pdata );
		$pdflib->add_linkdestination( $conPage->mInfo['title'] );
		$pdflib->insert_html( $pdata );
		$pdflib->ezNewPage();
$downloadTitle = $conPage->mInfo['title'];
	}
} else {
	$convertpages = array( $gContent->mContentId );
}

$pdata = '';

if ($pdflib->mSettings['autobreak'] == 'on') {
	foreach ($convertpages as $conId) {
		if( $conPage = $gLibertySystem->getLibertyObject( $conId ) ) {
			if( empty( $downloadTitle ) ) { 
				$downloadTitle = $conPage->mInfo['title'];
			}
			$pdata = "\n<C:page:".$conPage->mInfo['title'].">\n<br/>\n";
			$pdata .= $conPage->getPreview();
			$pdata = utf8_decode( $pdata );
			$pdflib->add_linkdestination( $conPage->mInfo['title'] );
			$pdflib->insert_html( $pdata );
			$pdflib->ezNewPage();
		}
	}
} else {
	$linkDest = array();
	foreach ($convertpages as $conId) {
		if( $conPage = $gLibertySystem->getLibertyObject( $conId ) ) {
			if( empty( $downloadTitle ) ) { 
				$downloadTitle = $conPage->mInfo['title'];
			}
			$pdata .= "\n<C:page:".$conPage->mInfo['title'].">\n<br/>\n";
			$pdata .= $conPage->getPreview();
			array_push( $linkDest, $conPage->mInfo['title'] );
/* 
		$wikiPage = new BitPage();
		$wikiPage->findByPageName($page);
		$wikiPage->load();
		
		$info = $wikiPage->mInfo;
		//$info = $wikilib->get_page_info($page);

		$data .= "\n<C:page:$page>\n<br/>\n";
		$data .= $gBitSystem->parseData($info);
*/
		}	
	}

	//todo: add linkdestinations for titlebars
	$pdflib->insert_linkdestinations( $linkDest );
	// now add data
	$pdata = utf8_decode( $pdata );
	// TODO - SPIDER this quiets html errors, however, the PDF is usually screwed if there are errors.
	@$pdflib->insert_html( $pdata );
}
//vd( $pdflib->messages );
$pdfdebug = false;
if ($pdfdebug) {
	$pdfcode = $pdflib->output(1);

	$pdfcode = str_replace("\n", "\n<br>", htmlspecialchars($pdfcode));
	echo '<html>';
	echo trim($pdfcode);
	echo '</body>';
} else {
	$hopts = array( 'Content-Disposition' => urlencode( str_replace( ' ', '_', $downloadTitle.'.pdf' ) ) );

	$pdflib->ezStream($hopts);
	//$output = $pdflib->ezOutput($hopts);
	//vd($output);
	//vd($pdflib);
	
}

?>
