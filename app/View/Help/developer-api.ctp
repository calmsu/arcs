<h1 id="api-reference">API Reference</h1>
<p>We provide a RESTful API that lets you interact with ARCS using anything that
can send an HTTP request. This makes it easy to write custom import and export
scripts.</p>
<blockquote>
<p>As we continue to develop ARCS and our API, we'll sometimes need to break old
functionality. When this happens, we'll let you know, and update the changes 
here.</p>
</blockquote>
<h2 id="schema">Schema</h2>
<p>All request/response data is sent and received as JSON. We've tried to adhere
to the HTTP specification defined in <a href="http://www.w3.org/Protocols/rfc2616/rfc2616.html">RFC 2616</a>.</p>
<h3 id="requests">Requests</h3>
<p>You can make API requests using a number of programming languages. PHP, 
Python, Ruby, JavaScript, and many others, have libraries for making 
HTTP requests.</p>
<p>Each API action expects a certain HTTP method. These methods, defined in 
RFC 2616, can be referenced <a href="http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9">here</a>.</p>
<p>When using the <code>POST</code> and <code>PUT</code> methods, the request's <code>Content-Type</code> should
be set to <code>application/json</code>, and the body of the request must be JSON.</p>
<p>For examples in this document, we'll use <code>curl</code>. The following request would
create a new comment for the authenticated user:</p>
<pre><code>curl -X POST \
  -H "Content-Type: application/json" \
  -d '{"content": "Some comment", "resource_id": "4f7351c..."}' \
  http://arcs.cal.msu.edu/api/comments
</code></pre>
<h3 id="responses">Responses</h3>
<p>All responses will contain a JSON object. In most non-<code>GET</code> requests, this
object will be empty. An example response to a <code>GET</code> request is below:</p>
<pre><code>{
  "id": "4f136ac5-b264-485f-952e-343c54c5f7e9",
  "user_id": "4f1366de-9188-4bc7-b9e8-2dc02308e057",
  "sha": "9d9d991b3010de942b3c1633d556b1eec4bcfa64",
  "public": true,
  "file_name": "RS4883_67-ARC-001.pdf-1.jpeg",
  "mime_type": "image/jpeg",
  "title": "RS4883_67-ARC-001.pdf-1.jpeg",
  "created": "2012-01-15 19:09:41",
  "modified": "2012-01-15 19:09:41",
  "url": "http://arcs.cal.msu.edu/arcs-data/9/.../RS4883_67-ARC-001.pdf-1.jpeg",
  "thumb": "http://arcs.cal.msu.edu/arcs-data/9/.../thumb.png"}
}
</code></pre>
<h3 id="authentication">Authentication</h3>
<p>To access private resources, or make edits or deletions, you'll need to 
authenticate your request. </p>
<p>You can do this by sending a <code>POST</code> to the login action, as shown below:</p>
<pre><code>curl -X POST \
  -H "Content-Type: application/json" \
  -d '{"username": "ndreynolds", "password": "pAsSwOrD"}' \
  http://arcs.cal.msu.edu/login
</code></pre>
<blockquote>
<p>We plan to support authentication using OAuth2 in a later release.</p>
</blockquote>
<h3 id="status-codes">Status Codes</h3>
<p>Each response is accompanied by an HTTP status code that indicates the result
of the requested action. You can read about these in detail in <a href="http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10">RFC 2616</a>.
The ones we've used frequently are also explained below:</p>
<table>
<thead>
<tr>
<th>Status Code</th>
<th>Explanation</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>200 Ok</code></td>
<td>We found the object(s) referenced by your URI, completed</td>
</tr>
<tr>
<td></td>
<td>the action you've requested, and have included any relevant</td>
</tr>
<tr>
<td></td>
<td>information.</td>
</tr>
<tr>
<td><code>201 Created</code></td>
<td>Creating the object was successful.</td>
</tr>
<tr>
<td><code>202 Accepted</code></td>
<td>We've accepted your request, but it's not completed yet.</td>
</tr>
<tr>
<td></td>
<td>(We'll use this for long-running tasks like PDF splits.)</td>
</tr>
<tr>
<td><code>400 Bad Request</code></td>
<td>Your request wasn't formatted correctly. It's either</td>
</tr>
<tr>
<td></td>
<td>missing information or is inappropriate.</td>
</tr>
<tr>
<td><code>401 Unauthorized</code></td>
<td>You're not logged in.</td>
</tr>
<tr>
<td><code>403 Forbidden</code></td>
<td>You're logged in, but not allowed to do that.</td>
</tr>
<tr>
<td><code>404 Not Found</code></td>
<td>That object doesn't exist.</td>
</tr>
<tr>
<td><code>500 Server Error</code></td>
<td>Something went wrong on our end. Let us know if this</td>
</tr>
<tr>
<td></td>
<td>happens repeatedly.</td>
</tr>
</tbody>
</table>
<h2 id="resources">Resources</h2>
<h3 id="getting-a-resource-by-id">Getting a resource by id</h3>
<pre><code>GET /api/resources/:id
</code></pre>
<p><strong>Response</strong> <br />
<code>200 Ok</code></p>
<pre><code>{
  "id": "4f136ac5-b264-485f-952e-343c54c5f7e9",
  "user_id": "4f1366de-9188-4bc7-b9e8-2dc02308e057",
  "sha": "9d9d991b3010de942b3c1633d556b1eec4bcfa64",
  "public": true,
  "file_name": "RS4883_67-ARC-001.pdf-1.jpeg",
  "mime_type": "image/jpeg",
  "title": "RS4883_67-ARC-001.pdf-1.jpeg",
  "created": "2012-01-15 19:09:41",
  "modified": "2012-01-15 19:09:41",
  "url": "http://arcs.cal.msu.edu/arcs-data/9/.../RS4883_67-ARC-001.pdf-1.jpeg",
  "thumb": "http://arcs.cal.msu.edu/arcs-data/9/.../thumb.png"}
}
</code></pre>
<hr />
<h3 id="creating-a-new-resource">Creating a new resource</h3>
<pre><code>POST /api/resources
</code></pre>
<p><strong>Input</strong></p>
<table>
<thead>
<tr>
<th>Key</th>
<th>Value</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>title</code></td>
<td>A title for the resource</td>
</tr>
<tr>
<td><code>public</code></td>
<td>Whether or not the resource is publicly accessible.</td>
</tr>
<tr>
<td><code>url</code></td>
<td>Provide a url and we'll download the file.</td>
</tr>
</tbody>
</table>
<p><strong>Response</strong> <br />
<code>200 Ok</code></p>
<hr />
<h3 id="searching-resources-with-a-simple-query">Searching resources with a simple query</h3>
<pre><code>GET /api/search/:query
</code></pre>
<p><strong>Response</strong>  <br />
<code>200 Ok</code></p>
<pre><code>[
  {
    "id": "4f1ee6c9-3fa0-469c-910c-3f6c2308e057",
    "user_id":"4f1ee67a-5c5c-4f05-a8e0-3f6d2308e057",
    "sha":"cc0ad4b316520b76a3892ee0f33588f7c9eadb80",
    "public":true,
    "file_name":"RS4887_71-ARC-001.pdf",
    "mime_type":"application/pdf",
    "title":"Architecture 1971-1",
    "created":"2012-01-24 12:13:45",
    "modified":"2012-01-24 12:13:45",
    "url":"http://arcs.cal.msu.edu/arcs-...",
    "thumb":"http://arcs.cal.msu.edu/arc..."
  },
  {
    "id": "4f1ee6c9-3a57-469c-91743-3f6c2308e057",
    "user_id":"4f1ee67a-5c5c-4f05-a8e0-3f6d2308e057",
    "sha":"cc0ad4b316520b76a3892ee0f33588f7c9eadb80",
    "public":true,
    "file_name":"RS4887_71-ARC-002.pdf",
    "mime_type":"application/pdf",
    "title":"Architecture 1971-2",
    "created":"2012-01-24 12:13:47",
    "modified":"2012-01-24 12:13:47",
    "url":"http://arcs.cal.msu.edu/arcs-...",
    "thumb":"http://arcs.cal.msu.edu/arc..."
  }
]
</code></pre>
<hr />
<h3 id="searching-resources-with-a-faceted-query">Searching resources with a faceted query</h3>
<pre><code>POST /api/search
</code></pre>
<p><strong>Input</strong></p>
<table>
<thead>
<tr>
<th>Key</th>
<th>Value</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>category</code></td>
<td>Facet category. See the Searching <a href="searching">documentation</a>.</td>
</tr>
<tr>
<td><code>value</code></td>
<td>The value you are testing for.</td>
</tr>
</tbody>
</table>
<p>Provide an array of facet objects. For example:</p>
<pre><code>[
  {
    "category": "user",
    "value": "Nick Reynolds"
  },
  {
    "category": "keyword",
    "value": "East Field",
  }
]
</code></pre>
<p><strong>Response</strong>   <br />
<code>200 Ok</code></p>
<pre><code>[
  {
    "id": "4f1ee6c9-3fa0-469c-910c-3f6c2308e057",
    "user_id":"4f1ee67a-5c5c-4f05-a8e0-3f6d2308e057",
    "sha":"cc0ad4b316520b76a3892ee0f33588f7c9eadb80",
    "public":true,
    "file_name":"RS4887_71-ARC-001.pdf",
    "mime_type":"application/pdf",
    "title":"Architecture 1971-1",
    "created":"2012-01-24 12:13:45",
    "modified":"2012-01-24 12:13:45",
    "url":"http://arcs.cal.msu.edu/arcs-...",
    "thumb":"http://arcs.cal.msu.edu/arc..."
  },
  {
    "id": "4f1ee6c9-3a57-469c-91743-3f6c2308e057",
    "user_id":"4f1ee67a-5c5c-4f05-a8e0-3f6d2308e057",
    "sha":"cc0ad4b316520b76a3892ee0f33588f7c9eadb80",
    "public":true,
    "file_name":"RS4887_71-ARC-002.pdf",
    "mime_type":"application/pdf",
    "title":"Architecture 1971-2",
    "created":"2012-01-24 12:13:47",
    "modified":"2012-01-24 12:13:47",
    "url":"http://arcs.cal.msu.edu/arcs-...",
    "thumb":"http://arcs.cal.msu.edu/arc..."
  }
]
</code></pre>
<hr />
<h3 id="deleting-a-resource-by-id">Deleting a resource by id</h3>
<pre><code>DELETE /api/resources/:id
</code></pre>
<p><strong>Response</strong>  <br />
<code>204 Deleted</code></p>
<hr />
<h2 id="metadata">Metadata</h2>
<h3 id="getting-metadata-for-a-resource">Getting metadata for a resource</h3>
<pre><code>GET /api/metadata/:id
</code></pre>
<p><strong>Response</strong><br />
<code>200 Ok</code></p>
<pre><code>{
  "language": "Hebrew",
  "description": "An artifact",
  "location": "Egypt",
  "creator": "Nick Reynolds"
}
</code></pre>
<hr />
<h3 id="setting-metadata-for-a-resource">Setting metadata for a resource</h3>
<pre><code>POST /api/metadata/:id
</code></pre>
<p><strong>Input</strong></p>
<pre><code>{
  "copyright": "2012, Michigan State University",
  "language": "English"
  "location": "East Lansing, MI"
}
</code></pre>
<p><strong>Response</strong><br />
<code>201 Created</code></p>
<hr />
<h2 id="comments">Comments</h2>
<h3 id="getting-a-comment-by-id">Getting a comment by id</h3>
<pre><code>GET /api/comments/:id
</code></pre>
<p><strong>Response</strong>  <br />
<code>200 Ok</code></p>
<pre><code>{
  "id": "4f136ac5-b264-485f-952e-343c54c5f7e9",
  "content": "This is a sample comment.",
  "created": "2012-01-15 19:09:41",
  "user_id": "4f1366de-9188-4bc7-b9e8-2dc02308e057"
  "resource_id":
}
</code></pre>
<hr />
<h3 id="creating-a-new-comment">Creating a new comment</h3>
<pre><code>POST /api/comments
</code></pre>
<p><strong>Input</strong></p>
<table>
<thead>
<tr>
<th>Key</th>
<th>Value</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>resource_id</code></td>
<td>The id of the resource being commented on.</td>
</tr>
<tr>
<td><code>content</code></td>
<td>Comment, as a string.</td>
</tr>
</tbody>
</table>
<p>For example:</p>
<pre><code>{
  "resource_id": "4f136ac5-b264-485f-952e-343c54c5f7e9",
  "content": "This a sample comment."
}
</code></pre>
<p><strong>Response</strong>  <br />
<code>201 Created</code></p>
<hr />
<h3 id="deleting-a-comment">Deleting a comment</h3>
<pre><code>DELETE /api/comments/:id
</code></pre>
<p><strong>Response</strong>   <br />
<code>204 Deleted</code></p>
<hr />
<h2 id="keywords">Keywords</h2>
<h3 id="getting-a-keyword-by-id">Getting a keyword by id</h3>
<pre><code>GET /api/keywords/:id
</code></pre>
<p><strong>Response</strong>   <br />
<code>200 Ok</code></p>
<pre><code>STUB
</code></pre>
<hr />
<h3 id="getting-keywords-for-a-resource">Getting keywords for a resource</h3>
<pre><code>GET /api/resources/keywords/:id
</code></pre>
<p><strong>Response</strong>
<code>200 Ok</code></p>
<pre><code>STUB
</code></pre>
<hr />
<h3 id="creating-a-new-keyword">Creating a new keyword</h3>
<pre><code>POST /api/keywords
</code></pre>
<p><strong>Input</strong></p>
<table>
<thead>
<tr>
<th>Key</th>
<th>Value</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>resource_id</code></td>
<td>The id of the resource that the keyword will belong</td>
</tr>
<tr>
<td></td>
<td>to.</td>
</tr>
<tr>
<td><code>keyword</code></td>
<td>Keyword string.</td>
</tr>
</tbody>
</table>
<p>For example:</p>
<pre><code>{
  "resource_id": "4f136ac5-b264-485f-952e-343c54c5f7e9",
  "keyword": "East-Field"
}
</code></pre>
<p><strong>Response</strong>   <br />
<code>201 Created</code></p>
<hr />
<h3 id="updating-a-keyword">Updating a keyword</h3>
<pre><code>POST|PUT /api/keywords/:id
</code></pre>
<p><strong>Input</strong></p>
<table>
<thead>
<tr>
<th>Key</th>
<th>Value</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>keyword</code></td>
<td>Keyword string.</td>
</tr>
</tbody>
</table>
<p>For example:</p>
<pre><code>{
  "keyword": "West-Field"
}
</code></pre>
<p><strong>Response</strong> <br />
<code>200 Ok</code></p>
<hr />
<h3 id="deleting-a-keyword">Deleting a keyword</h3>
<pre><code>DELETE /api/keywords/:id
</code></pre>
<p><strong>Response</strong>  <br />
<code>204 Deleted</code></p>
<hr />