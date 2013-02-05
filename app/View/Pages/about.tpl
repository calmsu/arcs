<div class="about">
  <div class="about-intro">
    <h1>
      <a href="{{ html.url('/') }}">
        {{ html.image('arcs-icon-big.png') }} ARCS
      </a>
    </h1>
  </div>
  <div class="about-features">
    <section class="feature feature-left" style="margin-top:-2px">
      <div class="number">{{ html.image('about/f1.png') }}</div>
      <h2>Create, relate, organize and search digitized primary evidence</h2>
      <p>ARCS is an open-source web platform that enables individuals to 
      collaborate in creating and relating digitized primary evidence when conducting 
      research in the humanities.
      <p>{{ html.image('about/main.jpg', {'width': '640', 'class': 'shadow'}) }}
    </section>
    <div class="spacer spacer-left"></div>
    <section class="feature feature-right">
      <div class="number">{{ html.image('about/f2.png') }}</div>
      <h2>A desktop-like experience</h2>
      <p>Click and drag just like on your PC's desktop. Apply actions en masse. 
      Speed up your workflow with keyboard shortcuts.
      <p>{{ html.image('docs/selection.png', {'class': 'shadow'}) }}
    </section>
    <div class="spacer spacer-right"></div>
    <section class="feature feature-left">
      <div class="number">{{ html.image('about/f3.png') }}</div>
      <h2>Uploading your research has never been easier</h2>
      <p>Just drag and drop your files into the uploader. No more clunky upload forms.
      <p>{{ html.image('docs/uploading.png') }}
    </section>
    <div class="spacer spacer-left"></div>
    <section class="feature feature-right">
      <div class="number">{{ html.image('about/f4.png') }}</div>
      <h2>One copy, no worries.</h2>
      <p>Work on your research from anywhere. Collaborate with colleagues on 
      a shared copy of your research. Never lose another file.
      <p>{{ html.image('about/5.png') }}
    </section>
    <div class="spacer spacer-right"></div>
    <section class="feature feature-left">
      <div class="number">{{ html.image('about/f5.png') }}</div>
      <h2>Powerful annotation tools</h2>
      <p>Relate one resource to another. Transcribe handwritten text. Just start drawing 
      on an image.
      <p>{{ html.image('docs/annotating.png', {'class': 'shadow'}) }}
    </section>
    <div class="spacer spacer-left"></div>
    <section class="feature feature-right">
      <div class="number">{{ html.image('about/f6.png') }}</div>
      <h2>Whittle down large data sets with facets</h2>
      <p>Search, filter and sort by characteristics to find the needle in the haystack.
      <p>{{ html.image('docs/search-1.png', {'class': 'shadow'}) }}
    </section>
    <div class="spacer spacer-right"></div>
    <section class="feature feature-left">
      <div class="number">{{ html.image('about/f7.png') }}</div>
      <h2>It's free!</h2>
      <p>ARCS is open-source software. You can download the source code and put
      ARCS to work for your organization. 
      Check out <a href="http://github.com/calmsu/arcs">ARCS on Github</a>
      <h3>Take ARCS for a spin:</h3>
      <p><a href="{{ html.url('/search') }}" class="btn btn-large">
        <i class="icon-search"></i> Search our Public Collection</a>
    </section>
    <div class="spacer spacer-left"></div>
  </div>
</div>
