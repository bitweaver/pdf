{strip}

<div class="pdf">
	{jstabs}
		{jstab title="PDF Settings"}
			{form legend="PDF Settings"}
				<input type="hidden" name="page" value="{$page}" />

			{if $gBitSystem->isPackageActive('pdf')}
				<div class="control-group">
					{formlabel label="Font" for="font"}
					{forminput}
						<input type="text" name="font" id="font" size="50" value="{$pdfSettings.font|escape}" />
					{/forminput}
				</div>


				<div class="control-group">
					{formlabel label="Text Height" for="textheight"}
					{forminput}
						<input type="text" name="textheight" id="textheight" size="5" value="{$pdfSettings.textheight|escape}" />
					{/forminput}
				</div>

				<div class="control-group">
					{formlabel label="Height of top Heading &lt;H1&gt;" for="h1height"}
					{forminput}
						<input type="text" name="h1height" id="h1height" size="5" value="{$pdfSettings.h1height|escape}" />
					{/forminput}
				</div>

				<div class="control-group">
					{formlabel label="Height of mid Heading &lt;H2&gt;" for="h2height"}
					{forminput}
						<input type="text" name="h2height" id="h2height" size="5" value="{$pdfSettings.h2height|escape}" />
					{/forminput}
				</div>

				<div class="control-group">
					{formlabel label="Height of inner Heading &lt;H3&gt;" for="h3height"}
					{forminput}
						<input type="text" name="h3height" id="h3height" size="5" value="{$pdfSettings.h3height|escape}" />
					{/forminput}
				</div>

				<div class="control-group">
					{formlabel label="tbheight" for="tbheight"}
					{forminput}
						<input type="text" name="tbheight" id="tbheight" size="5" value="{$pdfSettings.tbheight|escape}" />
					{/forminput}
				</div>

				<div class="control-group">
					{formlabel label="Image Scale" for="imagescale"}
					{forminput}
						<input type="text" name="imagescale" id="imagescale" size="5" value="{$pdfSettings.imagescale|escape}" />
					{/forminput}
				</div>

				<div class="control-group">
					{formlabel label="Automatic Page Breaks" for="autobreak"}
					{forminput}
						<input type="checkbox" {if $pdfSettings.autobreak eq 'on'}checked="checked"{/if} name="autobreak" id="autobreak" />
					{/forminput}
				</div>
			{/if}

				<div class="control-group submit">
					<input type="submit" class="btn btn-default" name="save" value="{tr}Apply Settings{/tr}" />
				</div>
			{/form}
		{/jstab}

		{jstab title="PDF Information Sheet"}
			<h2>Available Sizes</h2>

			{cycle values="even,odd" print=false}

			<table class="table data">
				<tbody>
					<tr>
						<th colspan="2">Standard Book Sizes</th>
					</tr>
					<tr class="{cycle}">
						<td align="center" style="width:30%">Trade Paperback</td>
						<td>152 × 229 mm / 6" x 9" (432.00 x 648.00 px)</td>
					</tr>
					<tr class="{cycle}">
						<td align="center">Mass Market / Digest</td>
						<td>106 × 171 mm / 4 3/16" x 6 3/4" (? x ? px)</td>
					</tr>
					<tr class="{cycle}">
						<td align="center">Comic</td>
						<td>168 × 260 mm / 6 5/8" x 10 1/4" (? x ? px)</td>
					</tr>
				</tbody>
			</table>

			<br/>

			<table class="table data">
				<tbody>
					<tr>
						<th colspan="2">North American Paper Sizes</th>
					</tr>

					<tr class="{cycle}">
						<td align="center" style="width:30%">LETTER</td>
						<td>216 × 279 mm (612.00 x 792.00 px)</td>
					</tr>

					<tr class="{cycle}">
						<td align="center">LEGAL</td>
						<td>216 × 356 mm (612.00 x 1008.00 px)</td>
					</tr>

					<tr class="{cycle}">
						<td align="center">EXECUTIVE</td>
						<td>190 × 254 mm (521.86 x 756.00 px)</td>
					</tr>

					<tr class="{cycle}">
						<td align="center">FOLIO</td>
						<td>216 × ? mm (612.00 x 936.00 px)</td>
					</tr>
				</tbody>
			</table>

			<br/>

			<table class="table data">
				<colgroup span="2"></colgroup>
				<colgroup span="2"></colgroup>
				<colgroup span="2"></colgroup>

				<tbody>
					<tr>
						<th colspan="2">ISO A Series Formats</th>
						<th colspan="2">ISO B Series Formats</th>
						<th colspan="2">ISO C Series Formats (Envelopes)</th>
					</tr>

					<tr>
						<th colspan="6">A0, A1 - technical drawings, posters</th>
					</tr>

					<tr class="{cycle}">
						<td>A0</td>
						<td>841 × 1189 mm</td>
						<td>B0</td>
						<td>1000 × 1414 mm</td>
						<td>C0</td>
						<td>917 × 1297 mm</td>
					</tr>

					<tr class="{cycle}">
						<td>2A0</td>
						<td>1189 × 1682 mm</td>
						<td>–</td>
						<td>–</td>
						<td>–</td>
						<td>–</td>
					</tr>

					<tr class="{cycle}">
						<td>4A0</td>
						<td>1682 × 2378 mm</td>
						<td>–</td>
						<td>–</td>
						<td>–</td>
						<td>–</td>
					</tr>

					<tr>
						<th colspan="6">A1, A2 - flip charts</th>
					</tr>

					<tr class="{cycle}">
						<td>A1</td>
						<td>594 × 841 mm</td>
						<td>B1</td>
						<td>707 × 1000 mm</td>
						<td>C1</td>
						<td>648 × 917 mm</td>
					</tr>

					<tr>
						<th colspan="6">A2, A3 - drawings, diagrams, large tables</th>
					</tr>

					<tr class="{cycle}">
						<td>A2</td>
						<td>420 × 594 mm</td>
						<td>B2</td>
						<td>500 × 707 mm</td>
						<td>C2</td>
						<td>458 × 648 mm</td>
					</tr>

					<tr>
						<th colspan="6">B4, A3 - newspapers, supported by most copying machines in addition to A4</th>
					</tr>

					<tr class="{cycle}">
						<td>A3</td>
						<td>297 × 420 mm</td>
						<td>B3</td>
						<td>353 × 500 mm</td>
						<td>C3</td>
						<td>324 × 458 mm</td>
					</tr>

					<tr>
						<th colspan="6">A4 - letters, magazines, forms, catalogs, laser printer and copying machine output</th>
					</tr>

					<tr class="{cycle}">
						<td><b>A4</b></td>
						<td><b> 210 × 297 mm</b></td>
						<td>B4</td>
						<td>250 × 353 mm</td>
						<td>C4</td>
						<td>229 × 324 mm</td>
					</tr>

					<tr>
						<th colspan="6">A5, B5, A6, B6 - note pads, books</th>
					</tr>

					<tr class="{cycle}">
						<td>A5</td>
						<td>148 × 210 mm</td>
						<td>B5</td>
						<td>176 × 250 mm</td>
						<td>C5</td>
						<td>162 × 229 mm</td>
					</tr>

					<tr>
						<th colspan="6">A6 - postcards</th>
					</tr>

					<tr class="{cycle}">
						<td>A6</td>
						<td>105 × 148 mm</td>
						<td>B6</td>
						<td>125 × 176 mm</td>
						<td>C6</td>
						<td>114 × 162 mm</td>
					</tr>

					<tr class="{cycle}">
						<td>A7</td>
						<td>74 × 105 mm</td>
						<td>B7</td>
						<td>88 × 125 mm</td>
						<td>C7</td>
						<td>81 × 114 mm</td>
					</tr>

					<tr>
						<th colspan="6">A8, B8 - playing cards</th>
					</tr>

					<tr class="{cycle}">
						<td>A8</td>
						<td>52 × 74 mm</td>
						<td>B8</td>
						<td>62 × 88 mm</td>
						<td>C8</td>
						<td>57 × 81 mm</td>
					</tr>

					<tr class="{cycle}">
						<td>A9</td>
						<td>37 × 52 mm</td>
						<td>B9</td>
						<td>44 × 62 mm</td>
						<td>C9</td>
						<td>40 × 57 mm</td>
					</tr>

					<tr class="{cycle}">
						<td>A10</td>
						<td>26 × 37 mm</td>
						<td>B10</td>
						<td>31 × 44 mm</td>
						<td>C10</td>
						<td>28 × 40 mm</td>
					</tr>

					<tr>
						<th colspan="6">C4, C5, C6</th>
					</tr>

					<tr class="{cycle}">
						<td colspan="6">envelopes for A4 letters: unfolded (C4), folded once (C5), folded twice (C6)</td>
					</tr>
				</tbody>
			</table>
			All you ever wanted to know about <a href="http://www.cl.cam.ac.uk/~mgk25/iso-paper.html">Standard Paper Sizes</a>.
		{/jstab}
	{/jstabs}
</div><!-- end .pdf -->

{/strip}
