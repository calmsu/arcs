API Reference
=============
The ARCS API can be queried by sending the appropriate HTTP request to the 
given URL. 

Responses and Status Codes
--------------------------
If the request is a `GET` and the request is authorized, the response
will always be a JSON object. If the request is a `POST` or `DELETE`, the 
response will be empty and the HTTP status code will reflect the result of the 
operation. For reference, the relevant status codes are:

#### GET

* `200` OK
* `401` UNAUTHORIZED
* `403` FORBIDDEN
* `404` NOT FOUND
* `500` SERVER ERROR

#### POST

* `200` OK
* `201` CREATED
* `202` ACCEPTED
* `401` UNAUTHORIZED
* `403` FORBIDDEN
* `404` NOT FOUND
* `500` SERVER ERROR

#### DELETE

* `204` OK
* `401` NOT AUTHORIZED
* `403` FORBIDDEN
* `404` NOT FOUND
* `500` SERVER ERROR

### Forbidden vs. Unauthorized

When there is no user authenticated (and the request requires being 
authenticated), a `403` is returned. If the user *is* authenticated, but not
permitted to request the specified action, a `401` is returned.

### Accepted

Some requested actions take longer to perform than a normal Request-Response
loop allows. In these cases, ARCS will queue the action and it will be performed
as soon as possible. The `202` code is returned in such a scenario--meaning
it's been accepted, and we're working on it.

Formatting a request
--------------------
Blah, blah jQuery, blah, blah MooTools, blah, blah Zepto


Resources
---------

### Creating a new resource (`POST`)

    /resources

### Searching resources with a simple query (`GET`)

    /resources/search/:query
    /search/:query
    
### Searching resources with a faceted query (`POST`)

    /resources/search
    /search

ARCS uses Visual Search for faceted searching. Results are resources that meet
each facet. This functionality is also available through the API. To use it, 
make a `POST` with a JSON array containing facet objects. For example:

    [
        {
            "category": "user",
            "value": "Nick Reynolds"
        },
        {
            "category": "tag",
            "value": "East Field",
        }
    ]

An example request using `jQuery.ajax`:

    $.ajax({
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

The response will be an array of objects containing resources (and related user,
tags, comments, and hotspots:

    [
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
            "Tag": [],
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


### Showing a specific resource (`GET`)

    /resources/view/:id
    /resource/:id

If the resource exists and the request is authorized, a JSON object like
this will be returned:

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
        "url": "http://arcs.dev.cal.msu.edu/arcs-data/9/.../RS4883_67-ARC-001.pdf-1.jpeg",
        "thumb": "http://arcs.dev.cal.msu.edu/arcs-data/9/.../thumb.png"}
    }

### Deleting a resource (`DELETE`)

    /resources/:id

### Listing resources uploaded by a specific user (`GET`)

    /resources/users/:id
 
 
Collections
-----------
 
Comments
--------

### Creating a new comment (`POST`)

    /comments

The `POST` should contain a JSON object with the following information:
    
    {
        "resource_id": "4f136ac5-b264-485f-952e-343c54c5f7e9",
        "content": "This a sample comment."
    }

If all goes well, a `200` status code is returned.

### Deleting a comment (`DELETE`)

    /comments/:id 

After sending a `DELETE` to the url above, if deleting the comment was 
successful, a `204` status will be returned.

### Showing a specific comment (`GET`)

    /comments/:id 

If the comment exists and the request is authorized, a JSON object like
this will be returned:

    {
        "id": "4f136ac5-b264-485f-952e-343c54c5f7e9",
        "content": "This is a sample comment.",
        "created": "2012-01-15 19:09:41",
        "user_id": "4f1366de-9188-4bc7-b9e8-2dc02308e057"
        "resource_id":
    }


Tags
----

### Creating a new tag (`POST`)

    /tags

The `POST` should contain a JSON object with the following information:
    
    {
        "tag": "East-Field",
        "resource_id": "4f136ac5-b264-485f-952e-343c54c5f7e9"
    }

### Updating a tag (`PUT`)

    /tags/:id

The `POST` should contain a JSON object with information that will be updated:
    
    {
        "tag": "West-Field"
    }

### Deleting a tag (`DELETE`)

    /tags/:id

After sending a `DELETE` to the url above, if deleting the tag was 
successful, a `204` status will be returned. 

### Showing a specific tag (`GET`)

    /tags/:id 

After sending a `GET` to the url above, a JSON object containing the tag's
properties will be returned.
