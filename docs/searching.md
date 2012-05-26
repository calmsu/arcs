Searching ARCS
==============
Searching a resource, collection or notebook has been implemented in a faceted
fashion in ARCS so that it is easier for you to find a specific resource even
when you do not know all the details about the same.
 
Facets
------
A facet can be defined as a particular aspect of the resource such as the user,
or the date of creation of the resource etc.

Facets are a very powerful method of data mining based on searching by data
attribute but the general idea is that you’re filtering resources based on the
data we store about them.

Facets work by typing in the attribute you would like to filter the resources
by, which can be anything as general as the type of resource to something as
specific as a title or you can chain them up together.  For example, you know
that John Doe uploaded an image, and you know that he uploaded it yesterday,
but you do not know anything else. You can construct a search query that will
find everything that John uploaded yesterday.

![searching](../img/docs/searching.png)

>Searching is still in development to include more general searching, but in
>the meantime, you can find a list of available facets below.

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
   
Auto-completion
---------------
Facet values will be auto-completed when possible. For certain facets,
like `user`, we'll only auto-complete the values if you're logged in.
