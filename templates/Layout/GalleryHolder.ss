<% if $Content %>
    <article class="page__text">
        $Content
    </article>
<% end_if %>
<% if $Children %>
    <section class="galleries">
        <% loop $Children %>
            <% include GalleryTeaser %>
        <% end_loop %>
    </section>
<% end_if %>