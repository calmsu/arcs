<h1>Searching ARCS</h1>
<p>We've tried to make it easy to find resources in ARCS, even when you
know very little about them. </p>
<p>Say you know that John Doe uploaded an image, and you know that he 
uploaded it yesterday, but not much else. We can construct a search 
query that will find everything that John uploaded yesterday.</p>
<p><img alt="example" src="http://arcs.dev.cal.msu.edu/img/docs/search-example.png" /></p>
<h2>Facets</h2>
<table>
<thead>
<tr>
<th>Facet</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>all</code>*</td>
<td>Default facet. Matches all fields.</td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td><code>caption</code></td>
<td>Matches annotation caption text.</td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td><code>collection</code></td>
<td>Matches resources within a collection. For example:</td>
</tr>
<tr>
<td></td>
<td><code>collection: Bones 1980's</code></td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td><code>comment</code></td>
<td>Matches comment text.</td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td><code>created</code></td>
<td>Matches the date on which the resource was uploaded.</td>
</tr>
<tr>
<td></td>
<td>Provide a date using month-day-year format. You can also</td>
</tr>
<tr>
<td></td>
<td>use the aliases <code>today</code> and <code>yesterday</code>.</td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td><code>filetype</code></td>
<td>Matches the filetype of the resource. Some common</td>
</tr>
<tr>
<td></td>
<td>filetypes are <code>pdf</code>, <code>jpeg</code>, and <code>png</code>.</td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td><code>filename</code></td>
<td>Matches the filename that resource was uploaded with.</td>
</tr>
<tr>
<td></td>
<td>For example: <code>filename: RS_547_832.pdf</code></td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td><code>id</code></td>
<td>Matches the resource's unique id.</td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td><code>modified</code></td>
<td>Matches the date on which the resource was last</td>
</tr>
<tr>
<td></td>
<td>changed.</td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td><code>keyword</code></td>
<td>Matches resources with a keyword. For example:</td>
</tr>
<tr>
<td></td>
<td><code>keyword: east-field</code></td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td><code>title</code></td>
<td>Matches the title of the resource. For example:</td>
</tr>
<tr>
<td></td>
<td><code>title: Bones 1989</code></td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td><code>type</code></td>
<td>Matches the resource type given when uploading a new</td>
</tr>
<tr>
<td></td>
<td>resource. These values can vary depending on how ARCS</td>
</tr>
<tr>
<td></td>
<td>is configured.</td>
</tr>
<tr>
<td></td>
<td></td>
</tr>
<tr>
<td><code>user</code></td>
<td>Matches the owner of the resource. This will usually</td>
</tr>
<tr>
<td></td>
<td>be the user that uploaded it. For example:</td>
</tr>
<tr>
<td></td>
<td><code>user: John Doe</code></td>
</tr>
</tbody>
</table>
<hr />
<p>* Not implemented.</p>
<h2>Auto-completion</h2>
<p>Facet values will be auto-completed when possible. For certain facets,
like <code>user</code>, we'll only auto-complete the values if you're logged in.</p>