<?php
global $gBitInstaller;
$gBitInstaller->registerPackageInfo( PDF_PKG_NAME, array(
	'description' => "PDF generation package for creating PDF files from Liberty content.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
) );

// ### Default UserPermissions
$gBitInstaller->registerUserPermissions( PDF_PKG_NAME, array(
	array('p_pdf_generation', 'Can create PDF files from content', 'registered', PDF_PKG_NAME),
) );
?>
