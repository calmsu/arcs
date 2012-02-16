Searching the ARCS catalog
==========================
We've tried to make it easy to find resources in ARCS, even when you
know very little about them. 

Say you know that John Doe uploaded an image, and you know that he 
uploaded it yesterday, but not much else. We can construct a search 
query that will find everything that John uploaded yesterday.

![example](http://arcs.dev.cal.msu.edu/img/docs/search-example.png)

Facets
------

Facet         | Description
------------- | -----------------------------------------------------
`all`\*       | Default facet. Matches all fields.
              |
`caption`\*   | Matches annotation caption text.
              |
`collection`\*| Matches resources within a collection. For example:
              | `collection: Bones 1980's`
              |
`comment`\*   | Matches comment text.
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
`tag`         | Matches resources with a tag. For example: 
              | `tag: east-field`
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
   
---
*\* Not implemented. (Yet.)*
