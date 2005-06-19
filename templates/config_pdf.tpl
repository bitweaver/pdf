{strip}
<div class="pdf">
	<div class="header">
		<h1>{tr}Create PDF{/tr}</h1>
	</div>

	<div class="body">
		{form ifile="export_pdf.php" ipackage="pdf" method="post" legend="Create PDF for `$pageInfo.title`"}
			{if $structureInfo.root_structure_id}
				<input type="hidden" name="structure_id" value="{$structureInfo.root_structure_id}" />
			{else}
				<input type="hidden" name="content_id" value="{$pageInfo.content_id}" />
			{/if}

			<div class="row">
				{formlabel label="Requested Item"}
				{forminput}
					{$pageInfo.title}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Content Type"}
				{forminput}
					{$pageInfo.content_type.content_description}
				{/forminput}
			</div>

			<div class="row submit">
				<input type="submit" name="create" value="{tr}Create PDF{/tr}" />
			</div>
		{/form}
	</div><!-- end .body -->
</div><!-- end .pdf -->
{/strip}
