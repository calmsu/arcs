<div class="search-home search-wrapper"></div>

<div class="accordion-wrapper">

  {% if not user.loggedIn %}
  <div style="font-weight:200">
    <i class="icon-info-sign"></i>
    You're viewing publicly available resources. 
    You'll need to {{ html.link('login', '/login') }} to see the rest.
  </div>
  {% endif %}

  <details class="unselectable" open="open" data-type="Notebook">
    <summary class="large">Notebooks</summary>
    <div></div>
  </details>

  <details class="unselectable" data-type="Notebook Page">
    <summary class="large">Notebook Pages</summary>
    <div></div>
  </details>

  <details class="unselectable" data-type="Photograph">
    <summary class="large">Photographs</summary>
    <div></div>
  </details>

  <details class="unselectable" data-type="Report">
    <summary class="large">Reports</summary>
    <div></div>
  </details>

  <details class="unselectable" data-type="Drawing">
    <summary class="large">Drawings</summary>
    <div></div>
  </details>

  <details class="unselectable" data-type="Map">
    <summary class="large">Maps</summary>
    <div></div>
  </details>

  <details class="unselectable" data-type="Inventory Card">
    <summary class="large">Inventory Cards</summary>
    <div></div>
  </details>

</div>

<script>arcs.homeView = new arcs.views.Home({el: $('.page')});</script>
