{% if user_collections and user_collections|length > 0 %}
  <div class="collection-list-wrapper">
    <h2>
      <img class="profile-image thumbnail" 
        src="http://gravatar.com/avatar/{{ user.gravatar }}?s=50"/>
      Your Collections
    </h2>
    <div class="collection-list" id="user-collections"></div>
    <script>
      arcs.user_viewer = new arcs.views.CollectionList({
        model: arcs.models.Collection,
        collection: new arcs.collections.CollectionList({{ user_collections|json_encode }}),
        el: $('#user-collections')
      });
    </script>
  </div>
{% endif %}

<div class="collection-list-wrapper">
    <h2>
      {{ html.image('arcs-icon-big', {'class': 'profile-image thumbnail'}) }}
      All Collections
    </h2>
  <div class="collection-list" id="all-collections"></div>
  <script>
    arcs.user_viewer = new arcs.views.CollectionList({
      model: arcs.models.Collection,
      collection: new arcs.collections.CollectionList({{ collections|json_encode }}),
      el: $('#all-collections')
    });
  </script>
<div class="collection-list-wrapper">
