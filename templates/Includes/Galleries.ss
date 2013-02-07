<% loop Children %>
	<div class="gallery">
		<a href="$Link" >
			<div><div><div>$IndexImage.PaddedImage(94, 94)</div></div></div>
			<br />
			<span>$MenuTitle</span>
		</a>
	</div>
<% end_loop %>