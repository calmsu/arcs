<h1>API Reference</h1>
<p>The ARCS API can be queried by sending the appropriate HTTP request to the 
given URL. </p>
<h2>Responses and Status Codes</h2>
<p>If the request is a <code>GET</code> and the request is authorized, the response
will always be a JSON object. If the request is a <code>POST</code> or <code>DELETE</code>, the 
response will be empty and the HTTP status code will reflect the result of the 
operation. For reference, the relevant status codes are:</p>
<h4>GET</h4>
<ul>
<li><code>200</code> OK</li>
<li><code>401</code> UNAUTHORIZED</li>
<li><code>403</code> FORBIDDEN</li>
<li><code>404</code> NOT FOUND</li>
<li><code>500</code> SERVER ERROR</li>
</ul>
<h4>POST</h4>
<ul>
<li><code>200</code> OK</li>
<li><code>201</code> CREATED</li>
<li><code>202</code> ACCEPTED</li>
<li><code>401</code> UNAUTHORIZED</li>
<li><code>403</code> FORBIDDEN</li>
<li><code>404</code> NOT FOUND</li>
<li><code>500</code> SERVER ERROR</li>
</ul>
<h4>DELETE</h4>
<ul>
<li><code>204</code> OK</li>
<li><code>401</code> NOT AUTHORIZED</li>
<li><code>403</code> FORBIDDEN</li>
<li><code>404</code> NOT FOUND</li>
<li><code>500</code> SERVER ERROR</li>
</ul>
<h3>Forbidden vs. Unauthorized</h3>
<p>When there is no user authenticated (and the request requires being 
authenticated), a <code>401</code> is returned. If the user <em>is</em> authenticated, but not
permitted to request the specified action, a <code>403</code> is returned.</p>
<p>For further clarification, see this StackOverflow <a href="http://stackoverflow.com/questions/3297048/403-forbidden-vs-401-unauthorized-http-responses#answer-6937030">answer</a>.</p>
<h3>Accepted</h3>
<p>Some requested actions take longer to perform than a normal Request-Response
loop allows. In these cases, ARCS will queue the action and it will be performed
as soon as possible. The <code>202</code> code is returned in such a scenario--meaning
it's been accepted, and we're working on it.</p>
<h2>Resources</h2>
<h3>Creating a new resource (<code>POST</code>)</h3>
<pre><code>/resources
</code></pre>
<h3>Searching resources with a simple query (<code>GET</code>)</h3>
<pre><code>/resources/search/:query
/search/:query
</code></pre>
<h3>Searching resources with a faceted query (<code>POST</code>)</h3>
<pre><code>/resources/search
/search
</code></pre>
<p>ARCS uses Visual Search for faceted searching. Results are resources that meet
each facet. This functionality is also available through the API. To use it, 
make a <code>POST</code> with a JSON array containing facet objects. For example:</p>
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
<p>An example request using <code>jQuery.ajax</code>:</p>
<pre><code>$.ajax({
    url: '/search',
    type: 'POST',
    contentType: 'application/json',
    data: JSON.stringify(facets),
    success: function(data) {
        // do something
    },
    error: function() {
        // do something
    }
});
</code></pre>
<p>The response will be an array of objects containing resources (and related user,
keywords, comments, and hotspots:</p>
<pre><code>[
    {
        "Resource": {
            "id": "4f1ee6c9-3fa0-469c-910c-3f6c2308e057",
            "user_id":"4f1ee67a-5c5c-4f05-a8e0-3f6d2308e057",
            "sha":"cc0ad4b316520b76a3892ee0f33588f7c9eadb80",
            "public":true,
            "file_name":"RS4887_71-ARC-001.pdf",
            "mime_type":"application\/pdf",
            "title":"Architecture 1971",
            "created":"2012-01-24 12:13:45",
            "modified":"2012-01-24 12:13:45",
            "url":"http:\/\/arcs.dev.cal.msu.edu\/arcs-...",
            "thumb":"http:\/\/arcs.dev.cal.msu.edu\/arc..."
        },
        "User": {
            ...
        },
        "keyword": [],
        "Comment": []
    },
    {
        "Resource": {
            ...
        },
        "User": {
            ...
        }
    }
]
</code></pre>
<h3>Showing a specific resource (<code>GET</code>)</h3>
<pre><code>/resources/view/:id
/resource/:id
</code></pre>
<p>If the resource exists and the request is authorized, a JSON object like
this will be returned:</p>
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
    "url": "http://arcs.dev.cal.msu.edu/arcs-data/9/.../RS4883_67-ARC-001.pdf-1.jpeg",
    "thumb": "http://arcs.dev.cal.msu.edu/arcs-data/9/.../thumb.png"}
}
</code></pre>
<h3>Deleting a resource (<code>DELETE</code>)</h3>
<pre><code>/resources/:id
</code></pre>
<h3>Listing resources uploaded by a specific user (<code>GET</code>)</h3>
<pre><code>/resources/users/:id
</code></pre>
<h2>Collections</h2>
<h2>Comments</h2>
<h3>Creating a new comment (<code>POST</code>)</h3>
<pre><code>/comments
</code></pre>
<p>The <code>POST</code> should contain a JSON object with the following information:</p>
<pre><code>{
    "resource_id": "4f136ac5-b264-485f-952e-343c54c5f7e9",
    "content": "This a sample comment."
}
</code></pre>
<p>If all goes well, a <code>200</code> status code is returned.</p>
<h3>Deleting a comment (<code>DELETE</code>)</h3>
<pre><code>/comments/:id
</code></pre>
<p>After sending a <code>DELETE</code> to the url above, if deleting the comment was 
successful, a <code>204</code> status will be returned.</p>
<h3>Showing a specific comment (<code>GET</code>)</h3>
<pre><code>/comments/:id
</code></pre>
<p>If the comment exists and the request is authorized, a JSON object like
this will be returned:</p>
<pre><code>{
    "id": "4f136ac5-b264-485f-952e-343c54c5f7e9",
    "content": "This is a sample comment.",
    "created": "2012-01-15 19:09:41",
    "user_id": "4f1366de-9188-4bc7-b9e8-2dc02308e057"
    "resource_id":
}
</code></pre>
<h2>keywords</h2>
<h3>Creating a new keyword (<code>POST</code>)</h3>
<pre><code>/keywords
</code></pre>
<p>The <code>POST</code> should contain a JSON object with the following information:</p>
<pre><code>{
    "keyword": "East-Field",
    "resource_id": "4f136ac5-b264-485f-952e-343c54c5f7e9"
}
</code></pre>
<h3>Updating a keyword (<code>PUT</code>)</h3>
<pre><code>/keywords/:id
</code></pre>
<p>The <code>POST</code> should contain a JSON object with information that will be updated:</p>
<pre><code>{
    "keyword": "West-Field"
}
</code></pre>
<h3>Deleting a keyword (<code>DELETE</code>)</h3>
<pre><code>/keywords/:id
</code></pre>
<p>After sending a <code>DELETE</code> to the url above, if deleting the keyword was 
successful, a <code>204</code> status will be returned. </p>
<h3>Showing a specific keyword (<code>GET</code>)</h3>
<pre><code>/keywords/:id
</code></pre>
<p>After sending a <code>GET</code> to the url above, a JSON object containing the keyword's
properties will be returned.</p>