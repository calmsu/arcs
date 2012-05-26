Bulk Actions
============

One of the key features of ARCS is the ability to map an action onto a large
number of resources. ARCS allows you to edit, flag, keyword, download, open,
preview and much more--en masse. This functionality is exposed through our
search interface.

All of the bulk actions are accessible through the toolbar:

Selection
---------
Before you can do anything, you'll need to select some resources. If you've
ever selected files on your Mac or PC's desktop, you'll be right at home.

Click on a resource to select it. Drag over a group of resources to select them
all. To select a resource without deselecting the others, hold down the control
(or command) key and click on it. Likewise, click on a selected resource with
the control key held down, and only it will be deselected.

![selection](../img/docs/selection.png)

You can even select resources using keyboard shortcuts. Use `ctrl+a` to select
all of the resources on screen. See all of the available keyboard shortcuts for
a particular page by pressing the `?` key anywhere in ARCS.

Actions are applied to each selected resource. For example, if I click
`Keyword` and type a new keyword, that keyword is added to each selected
resource. When combined with the search, this makes for a very powerful tool.

You can right-click on any resource to reveal a context menu. The context menu
echoes some of the more commonly used toolbar actions.

![context-menu](../img/docs/context-menu.png)

You can also preview the selected resources by hitting the spacebar, or through
the context menu. This can be useful when you want a better look at a resource,
but don't want to open it in the viewer. Use the arrow keys to flip through
previews for a group of resources.

Flagging Resources
------------------
You can flag multiple resources through ARCS. To flag the selected resources,
click `Flag`, then select a reason and provide an explanation of the issue. For
more details check the [Resources](about-resources#flagging) section.

Keywording Resources
--------------------
In addition to building collections, you can use keywords to group multiple
resources in ARCS by shared characteristics. For more details check the
[Resources](about-resources#keywording) section.

Editing Resources
-----------------
You can edit resource attributes and metadata through the `Edit` dialog.

#### Single Resource

If you've only selected a **single resource** this is very simple. The
resource's existing information will be filled in for you. Make any desired
changes and click `Save` to update the resource with your changes.

#### Multiple Resources (Batch editing)

For **multiple resources**, the process is a little more involved. If you've
ever edited song information for multiple songs in iTunes, the concept here is
very similar.

- A checkbox next to each field determines whether or not that field will
  saved. When `Save` is clicked, fields that are unchecked will not be saved.
  This means you can safely bulk edit the `Location` of several resources with
  different types--just leave the `Type` field unchecked.

- When all of the selected resources share the same value for a field, the
  field will be pre-filled and checked. For example, when two resources have
  the same `Subject`, that field will be filled in automatically. 

- When you click `Save`, ARCS will compute the differences and update the 
  resources to reflect your changes.

![edit-multiple-resources](../img/docs/edit-multiple-resources.png)

Exporting Resources
-------------------
Sometimes you just need to get things out of ARCS. 

1. Click `Export` > `Download` to download each of selected resources
   individually. Your browser may ask you if it's ok to let ARCS download
   multiple files.
2. Click `Export` > `Download as zipfile` to download each of the
   selected resources as a single zip archive. This might be helpful to keep
   things organized.

Opening Results
---------------
The easiest way to open a single resource is to double-click on it. 

To open a group of resources, we provide two options:

1. Click `Open` > `In separate windows` to open the resources in separate 
   tabs (or windows, depending on your browser).
2. Click `Open` > `In a collection view` to open the selected resources 
   together in a single resource viewer.

Other Actions
-------------
In addition to the primary actions outlined above, there are some additional
actions tucked under the cog menu. Depending on your account type, you may not
see all of these options.

#### Set Access
As a Sr. Researcher, you can set the access level for the selected resources.
ARCS currently supports two access levels:

- **Public** resources are publicly accessible. Users do not need an account to
  view public resources.
- **Private** resources can only be viewed by users with an account. Signup in
  ARCS is invite-only, so this setting is ideal for resources that should
  remain private to your organization.

#### Re-thumbnail & Re-preview
Sometimes the thumbnail and preview images that ARCS makes may be noisey, 
distorted, or even missing. If this happens, you can use the `Re-thumbnail`
and `Re-preview` options to tell ARCS to try again.

#### Delete
ARCS strives to maintain a high level of data integrity. Normally, files cannot
be permanently deleted. However, in some special cases this may be desirable.
For example, to remove accidentally uploaded files and those with sensitive
data. Administrators may permanently delete resources from ARCS by clicking
`Delete` and confirming the deletion.
