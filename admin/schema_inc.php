<?php


$gBitInstaller->registerPackageInfo( PDF_PKG_NAME, array(
	'description' => "PDF generation package for creating PDF files from Liberty content.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
	'version' => '0.1',
	'state' => 'beta',
	'dependencies' => 'html',
) );


// ### Default UserPermissions
$gBitInstaller->registerUserPermissions( PDF_PKG_NAME, array(
	array('bit_p_pdf_generation', 'Can create PDF files from content', 'registered', PDF_PKG_NAME),
) );




?>
