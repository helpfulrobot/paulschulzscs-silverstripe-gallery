<% loop $Thumbnails %>
	<div class="galleryimage">
		<a href="$Image.Link" rel="gallery[pp_gal]" title="$Image.Title">
			$Thumbnail
		</a>
	</div>
<% end_loop %>