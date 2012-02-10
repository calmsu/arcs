ARCS API Documentation
======================

The ARCS API can be queried by sending the appropriate HTTP request to the 
given URL. If the request is a `GET`, the response will always be a JSON object.
If the request is a `POST` or `DELETE`, the response will be empty and the HTTP 
status code will reflect the result of the operation. For reference, the status 
codes are:

`POST`

* `200` OK
* `201` CREATED
* `202` QUEUED
* `401` NOT AUTHORIZED
* `404` NOT FOUND
* `500` SERVER ERROR

`DELETE`

* `204` OK
* `401` NOT AUTHORIZED
* `404` NOT FOUND
* `500` SERVER ERROR
        

Resources
---------

### Creating a new resource (`POST`)

    api/resource/new


### Listing resources uploaded by a specific user (`GET`)

    api/resources/user/:id

### Searching resources with a simple query (`GET`)

    api/resources/search/:query

### Searching resources with a faceted query (`POST`)

    api/resources/search

ARCS uses Visual Search for faceted searching. Results are resources that meet
each facet. This functionality is also available through the API. To use it, 
POST a JSON object containing the facets. For example:

    {
        "user": "Nick Reynolds",
        "tag": "East Field",
        "collection": "Hex 92"
    }

`user`, `collection`, `tag`, and `date` are some of the possible facets.

The response format of the faceted query is identical to that of the simple
query.

### Showing a specific resource (`GET`)

    api/resource/:id

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

Collections
-----------

Comments
--------

### Creating a new comment (`POST`)

    api/comment/new

The POST should contain a JSON object with the following information:
    
    {
        "resource_id": "4f136ac5-b264-485f-952e-343c54c5f7e9",
        "content": "This a sample comment."
    }

If all goes well, a `200` status code is returned. If not logged in, or
not permitted to comment, a `401` code is returned. Finally, if something
goes wrong, a `500` is returned.

### Deleting a comment (`DELETE`)

    api/comment/:id 

After sending a DELETE to the url above, if deleting the comment was 
successful, a `204` status will be returned. If the request was not 
authenticated, a `401` will be returned. Finally, if a comment with the
given `id` did not exist, a `404` will be returned.

### Showing a specific comment (`GET`)

    api/comment/:id 

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

    api/tag/new

The POST should contain a JSON object with the following information:
    
    {
        "resource_id": 
        "":
    }

### Deleting a comment (`DELETE`)

    api/tag/delete/:id 

After sending a DELETE to the url above, if deleting the tag was 
successful, a `204` status will be returned. If the request was not 
authenticated, a `401` will be returned. Finally, if a tag with the given 
`id` did not exist, a `404` will be returned.

### Showing a specific comment (`GET`)

    api/tag/:id 
