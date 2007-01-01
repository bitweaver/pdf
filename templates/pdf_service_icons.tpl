{strip}
{if $gBitSystem->isPackageActive( 'pdf' ) && $gContent && $gContent->hasUserPermission( 'p_pdf_generation' )}
	{if $structureInfo.root_structure_id}
		<a title="{tr}create PDF{/tr}" href="{$smarty.const.PDF_PKG_URL}?structure_id={$structureInfo.root_structure_id}">{biticon ipackage="pdf" iname="pdf" iexplain="PDF"}</a>
	{else}
		<a title="{tr}create PDF{/tr}" href="{$smarty.const.PDF_PKG_URL}?content_id={$serviceHash.content_id}">{biticon ipackage="pdf" iname="pdf" iexplain="PDF"}</a>
	{/if}
{/if}
{/strip}
