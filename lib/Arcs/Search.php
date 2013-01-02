<?php 

/**
 * @namespace
 */
namespace Arcs;


/**
 * Search
 *
 * Base Search class that defines facet mappings.
 */
class Search {

    /**
     * Map facets to table and field names. Also holds instructions for how
     * tables should be joined onto the query, and for how a facet fields should
     * be compared.
     *
     * model        provide an alias that we'll use to refer to the table. This
     *              will default to the $model property.
     *
     * field        field (or column) name that the facet corresponds to. This
     *              option is required.
     *
     * joins        an array of table => predicate pairs which will be 
     *              outer-joined, in the order that they are defined. The 
     *              predicate must be a two-member associative array following 
     *              the pattern:
     *
     *                  array(
     *                      'TableA' => 'field',
     *                      'TableB' => 'field'
     *                  )
     *
     *              where TableA is the primary table (or another previously
     *              joined), and TableB is the one being joined.
     *
     * comparison   string specifiying how the fields should be compared. By
     *              default, the equality comparison is used. Others include:
     *
     *                date - uses the DATE() function.
     *                match - uses the MATCH() function with IN BOOLEAN MODE.
     *                like - uses the LIKE() function.
     *
     * values       keyed array to use as a translation table for values when 
     *              forming conditions. An example usage would be translating 
     *              strings into booleans (e.g. 'public' => true) for comparing
     *              on BOOL fields. If a translation is not found, the value will 
     *              remain unchanged.
     */
    public $mappings = array(
        'keyword' => array(
            'model' => 'Keyword',
            'field' => 'keyword',
            'joins' => array(
                'keywords' => array(
                    'Resource' => 'id', 
                    'Keyword' => 'resource_id'
                )
            )
        ),
        'user' => array(
            'model' => 'User',
            'field' => 'name',
            'joins' => array(
                'users' => array(
                    'Resource' => 'user_id', 
                    'User' => 'id'
                )
            )
        ),
        'modified' => array(
            'field' => 'modified',
            'comparison' => 'date'
        ),
        'created' => array(
            'field' => 'created',
            'comparison' => 'date'
        ),
        'uploaded' => array(
            'field' => 'created',
            'comparison' => 'date'
        ),
        'comment' => array(
            'model' => 'Comment',
            'field' => 'content',
            'joins' => array(
                'comments' => array(
                    'Resource' => 'id', 
                    'Comment' => 'resource_id'
                )
            ),
            'comparison' => 'match'
        ),
        'transcription' => array(
            'model' => 'Annotation',
            'field' => 'description',
            'joins' => array(
                'hotspots' => array(
                    'Resource' => 'id', 
                    'Annotation' => 'resource_id'
                )
            ),
            'comparison' => 'match'
        ),
        'collection' => array(
            'model' => 'Collection',
            'field' => 'title',
            'joins' => array(
                'memberships' => array(
                    'Resource' => 'id',
                    'Membership' => 'resource_id'
                ),
                'collections' => array(
                    'Membership' => 'collection_id',
                    'Collection' => 'id'
                )
            )
        ),
        'collection_id' => array(
            'model' => 'Collection',
            'field' => 'id',
            'joins' => array(
                'memberships' => array(
                    'Resource' => 'id',
                    'Membership' => 'resource_id'
                ),
                'collections' => array(
                    'Membership' => 'collection_id',
                    'Collection' => 'id'
                )
            )
        ),
        'metadata' => array(
            'model' => 'Metadata',
            'field' => 'value',
            'field2' => 'attribute',
            'joins' => array(
                'metadata' => array(
                    'Resource' => 'id', 
                    'Metadata' => 'resource_id'
                )
            )
        ),
        'sha' => array(
            'field' => 'sha'
        ),
        'title' => array(
            'field' => 'title'
        ),
        'id' => array(
            'field' => 'id'
        ),
        'type' => array(
            'field' => 'type'
        ),
        'filetype' => array(
            'field' => 'mime_type'
        ),
        'filename' => array(
            'field' => 'file_name'
        ),
        'access' => array(
            'field' => 'public',
            'values' => array(
                'public' => true,
                'private' => false 
            )
        ),
    );
}
