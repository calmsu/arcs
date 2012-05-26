<h1 id="bulk-actions">Bulk Actions</h1>
<p>One of the key features of ARCS is the ability to map an action onto a large
number of resources. ARCS allows you to edit, flag, keyword, download, open,
preview and much more--en masse. This functionality is exposed through our
search interface.</p>
<p>All of the bulk actions are accessible through the toolbar:</p>
<h2 id="selection">Selection</h2>
<p>Before you can do anything, you'll need to select some resources. If you've
ever selected files on your Mac or PC's desktop, you'll be right at home.</p>
<p>Click on a resource to select it. Drag over a group of resources to select them
all. To select a resource without deselecting the others, hold down the control
(or command) key and click on it. Likewise, click on a selected resource with
the control key held down, and only it will be deselected.</p>
<p><img alt="selection" src="../img/docs/selection.png" /></p>
<p>You can even select resources using keyboard shortcuts. Use <code>ctrl+a</code> to select
all of the resources on screen. See all of the available keyboard shortcuts for
a particular page by pressing the <code>?</code> key anywhere in ARCS.</p>
<p>Actions are applied to each selected resource. For example, if I click
<code>Keyword</code> and type a new keyword, that keyword is added to each selected
resource. When combined with the search, this makes for a very powerful tool.</p>
<p>You can right-click on any resource to reveal a context menu. The context menu
echoes some of the more commonly used toolbar actions.</p>
<p><img alt="context-menu" src="../img/docs/context-menu.png" /></p>
<p>You can also preview the selected resources by hitting the spacebar, or through
the context menu. This can be useful when you want a better look at a resource,
but don't want to open it in the viewer. Use the arrow keys to flip through
previews for a group of resources.</p>
<h2 id="flagging-resources">Flagging Resources</h2>
<p>You can flag multiple resources through ARCS. To flag the selected resources,
click <code>Flag</code>, then select a reason and provide an explanation of the issue. For
more details check the <a href="about-resources#flagging">Resources</a> section.</p>
<h2 id="keywording-resources">Keywording Resources</h2>
<p>In addition to building collections, you can use keywords to group multiple
resources in ARCS by shared characteristics. For more details check the
<a href="about-resources#keywording">Resources</a> section.</p>
<h2 id="editing-resources">Editing Resources</h2>
<p>You can edit resource attributes and metadata through the <code>Edit</code> dialog.</p>
<h4 id="single-resource">Single Resource</h4>
<p>If you've only selected a <strong>single resource</strong> this is very simple. The
resource's existing information will be filled in for you. Make any desired
changes and click <code>Save</code> to update the resource with your changes.</p>
<h4 id="multiple-resources-batch-editing">Multiple Resources (Batch editing)</h4>
<p>For <strong>multiple resources</strong>, the process is a little more involved. If you've
ever edited song information for multiple songs in iTunes, the concept here is
very similar.</p>
<ul>
<li>
<p>A checkbox next to each field determines whether or not that field will
  saved. When <code>Save</code> is clicked, fields that are unchecked will not be saved.
  This means you can safely bulk edit the <code>Location</code> of several resources with
  different types--just leave the <code>Type</code> field unchecked.</p>
</li>
<li>
<p>When all of the selected resources share the same value for a field, the
  field will be pre-filled and checked. For example, when two resources have
  the same <code>Subject</code>, that field will be filled in automatically. </p>
</li>
<li>
<p>When you click <code>Save</code>, ARCS will compute the differences and update the 
  resources to reflect your changes.</p>
</li>
</ul>
<p><img alt="edit-multiple-resources" src="../img/docs/edit-multiple-resources.png" /></p>
<h2 id="exporting-resources">Exporting Resources</h2>
<p>Sometimes you just need to get things out of ARCS. </p>
<ol>
<li>Click <code>Export</code> &gt; <code>Download</code> to download each of selected resources
   individually. Your browser may ask you if it's ok to let ARCS download
   multiple files.</li>
<li>Click <code>Export</code> &gt; <code>Download as zipfile</code> to download each of the
   selected resources as a single zip archive. This might be helpful to keep
   things organized.</li>
</ol>
<h2 id="opening-results">Opening Results</h2>
<p>The easiest way to open a single resource is to double-click on it. </p>
<p>To open a group of resources, we provide two options:</p>
<ol>
<li>Click <code>Open</code> &gt; <code>In separate windows</code> to open the resources in separate 
   tabs (or windows, depending on your browser).</li>
<li>Click <code>Open</code> &gt; <code>In a collection view</code> to open the selected resources 
   together in a single resource viewer.</li>
</ol>
<h2 id="other-actions">Other Actions</h2>
<p>In addition to the primary actions outlined above, there are some additional
actions tucked under the cog menu. Depending on your account type, you may not
see all of these options.</p>
<h4 id="set-access">Set Access</h4>
<p>As a Sr. Researcher, you can set the access level for the selected resources.
ARCS currently supports two access levels:</p>
<ul>
<li><strong>Public</strong> resources are publicly accessible. Users do not need an account to
  view public resources.</li>
<li><strong>Private</strong> resources can only be viewed by users with an account. Signup in
  ARCS is invite-only, so this setting is ideal for resources that should
  remain private to your organization.</li>
</ul>
<h4 id="re-thumbnail-re-preview">Re-thumbnail &amp; Re-preview</h4>
<p>Sometimes the thumbnail and preview images that ARCS makes may be noisey, 
distorted, or even missing. If this happens, you can use the <code>Re-thumbnail</code>
and <code>Re-preview</code> options to tell ARCS to try again.</p>
<h4 id="delete">Delete</h4>
<p>ARCS strives to maintain a high level of data integrity. Normally, files cannot
be permanently deleted. However, in some special cases this may be desirable.
For example, to remove accidentally uploaded files and those with sensitive
data. Administrators may permanently delete resources from ARCS by clicking
<code>Delete</code> and confirming the deletion.</p>