<div class="gallery__meta">
    <ul>
        <li><i class="fa fa-calendar"></i>$Date.Format('d.m.Y')</li>
        <li><i class="fa fa-map-marker"></i>$Location</li>
    </ul>
</div>
<% if $Content %>
    <article class="page__text">
        $Content
    </article>
<% end_if %>
<% if $Images %>
    <section class="gallery__images">
        <ul>
            <% loop $Images.Sort('SortOrder') %>
                <li>
                    <a href="$FitMax(1280, 1024).Link" data-lightbox="gallery">$FocusFill(400, 300)</a>
                </li>
            <% end_loop %>
        </ul>
    </section>
<% end_if %>