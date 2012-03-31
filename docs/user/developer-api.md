API v1 Reference
================
We provide a REST API that lets you interact with ARCS using anything that can
send an HTTP request. This makes it easy to write custom import and export
scripts.

>As we continue to develop ARCS and our API, we'll sometimes need to break old
functionality. When this happens, we'll let you know, and update the changes 
here.

Schema
------
All request/response data is sent and received as JSON. We've tried to adhere
to the HTTP specification defined in [RFC 2616][1].

### Requests

You can make API requests using a number of programming languages. PHP, 
Python, Ruby, JavaScript, and many others, have libraries for making 
HTTP requests.

Each API action expects a certain HTTP method. These methods, defined in 
RFC 2616, can be referenced [here][2].

When using the `POST` and `PUT` methods, the request's `Content-Type` should
be set to `application/json`, and the body of the request must be JSON.

For examples in this document, we'll use `curl`. The following request would
create a new comment for the authenticated user:

    curl -X POST \
      -H "Content-Type: application/json" \
      -d '{"content": "Some comment", "resource_id": "4f7351c..."}' \
      http://arcs.cal.msu.edu/comments

### Responses

All responses will contain a JSON object. In most non-`GET` requests, this
object will be empty. An example response to a `GET` request is below:

    {
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

### Authentication

To access private resources, or make edits or deletions, you'll need to 
authenticate your request. 

You can do this by sending a `POST` to the login action, as shown below:

    curl -X POST \
      -H "Content-Type: application/json" \
      -d '{"username": "ndreynolds", "password": "pAsSwOrD"}' \
      http://arcs.cal.msu.edu/login

>We plan to support authentication using OAuth2 in a later release.

### Status Codes

Each response is accompanied by an HTTP status code that indicates the result
of the requested action. You can read about these in detail in [RFC 2616][2].
The ones we've used frequently are also explained below:

Status Code        | Explanation
------------------ | -----------------------------------------------------------
`200 Ok`           | We found the object(s) referenced by your URI, completed 
                   | the action you've requested, and have included any relevant
                   | information.
`201 Created`      | Creating the object was successful.
`202 Accepted`     | We've accepted your request, but it's not completed yet.
                   | (We'll use this for long-running tasks like PDF splits.)
`400 Bad Request`  | Your request wasn't formatted correctly. It's either 
                   | missing information or is inappropriate.
`401 Unauthorized` | You're not logged in.
`403 Forbidden`    | You're logged in, but not allowed to do that.
`404 Not Found`    | That object doesn't exist.
`500 Server Error` | Something went wrong on our end. Let us know if this 
                   | happens repeatedly.

Resources
---------

### Getting a resource by id

    GET /resources/:id

**Response**   
`200 Ok`

    {
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
---

### Creating a new resource

    POST /resources

**Input**

Key      | Value
---------|----------------------------------------------------
`title`  | A title for the resource
`public` | Whether or not the resource is publicly accessible.
`url`    | Provide a url and we'll download the file.

**Response**   
`200 Ok`
---

### Searching resources with a simple query

    GET /resources/search/:query

**Response**    
`200 Ok`
    
    [
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
---

### Searching resources with a faceted query

    POST /resources/search

**Input**

    [
      {
        "category": "user",
        "value": "Nick Reynolds"
      },
      {
        "category": "keyword",
        "value": "East Field",
      }
    ]

**Response**     
`200 Ok`

    [
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
---

### Deleting a resource by id

    DELETE /resources/:id

**Response**    
`204 Deleted`
---
 
Collections
-----------
 
Comments
--------

### Getting a comment by id

    GET /comments/:id

**Response**    
`200 Ok`

    {
      "id": "4f136ac5-b264-485f-952e-343c54c5f7e9",
      "content": "This is a sample comment.",
      "created": "2012-01-15 19:09:41",
      "user_id": "4f1366de-9188-4bc7-b9e8-2dc02308e057"
      "resource_id":
    }
---

### Creating a new comment

    POST /comments

**Input**
    
    {
      "resource_id": "4f136ac5-b264-485f-952e-343c54c5f7e9",
      "content": "This a sample comment."
    }

**Response**    
`201 Created`
---

### Deleting a comment

    DELETE /comments/:id 

**Response**     
`204 Deleted`
---

Keywords
--------

### Getting a keyword by id

    GET /keywords/:id 

**Response**     
`200 Ok`

    STUB
---

### Creating a new keyword

    POST /keywords

**Input**
    
    {
      "keyword": "East-Field",
      "resource_id": "4f136ac5-b264-485f-952e-343c54c5f7e9"
    }

**Response**     
`201 Created`
---

### Updating a keyword

    POST|PUT /keywords/:id

**Input**
    
    {
      "keyword": "West-Field"
    }

**Response**   
`200 Ok`
---

### Deleting a keyword

    DELETE /keywords/:id

**Response**    
`204 Deleted`
---

[1]:http://www.w3.org/Protocols/rfc2616/rfc2616.html
[2]:http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9
[3]:http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10
