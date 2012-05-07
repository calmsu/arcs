Searching ARCS
==============
Searching a resource, collection or notebook has been implemented in a faceted
fashion in ARCS so that it is easier for you to find a specific resource even
when you do not know all the details about the same.
 
Facets
------
A facet can be defined as a particular aspect of the resource such as the user,
or the date of creation of the resource etc.

For example, you know that John Doe uploaded an image, and you know that he
uploaded it yesterday, but you do not know anything else. You can construct a
search query that will find everything that John uploaded yesterday.

![searching](../img/docs/search-1.png)

The following table gives the description of the different facets that can be
used to search a resource in ARCS:


Facet         | Description
------------- | -----------------------------------------------------
`all`\*       | Default facet. Matches all fields.
              |
`caption`     | Matches annotation caption text.
              |
`collection`  | Matches resources within a collection. For example:
              | `collection: Bones 1980's`
              |
`comment`     | Matches comment text.
              |
`created`     | Matches the date on which the resource was uploaded.
              | Provide a date using month-day-year format. You can also 
              | use the aliases `today` and `yesterday`.
              | 
`filetype`    | Matches the filetype of the resource. Some common
              | filetypes are `pdf`, `jpeg`, and `png`.
              |
`filename`    | Matches the filename that resource was uploaded with.
              | For example: `filename: RS_547_832.pdf`
              |
`id`          | Matches the resource's unique id.
              |
`modified`    | Matches the date on which the resource was last 
              | changed. 
              |
`keyword`     | Matches resources with a keyword. For example: 
              | `keyword: east-field`
              |
`title`       | Matches the title of the resource. For example:
              | `title: Bones 1989`
              |
`type`        | Matches the resource type given when uploading a new
              | resource. These values can vary depending on how ARCS
              | is configured.
              |
`user`        | Matches the owner of the resource. This will usually
              | be the user that uploaded it. For example:
              | `user: John Doe`
   
Boolean Logic
-------------
\* Not implemented.

Auto-completion
---------------
Facet values will be auto-completed when possible. For certain facets, like
`user`, we'll only auto-complete the values if you're logged in.
