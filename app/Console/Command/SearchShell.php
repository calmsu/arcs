<?php

require_once(APPLIBS . DS . 'Search.php');

/**
 * The Search shell can be used to debug the Search class.
 *
 * Pass it a JSON query string, or use the interactive query
 * builder.
 */
class SearchShell extends AppShell {

    public $uses = array('Resource');

    public function main() {
        # Get the config.
        $dbo = $this->Resource->getDataSource();
        $config = $dbo->config;

        # Get the query.
        if (isset($this->args[0])) {
            $query = $this->args[0];
        } else {
            $query = $this->promptFacets();
        }

        # Set up a Search instance.
        $search = new Search($config, $query);

        # Output results and debug.
        $ids = $search->results();
        $results = $this->Resource->find('list', array(
            'fields' => array('Resource.id', 'Resource.title'),
            'conditions' => array(
                'Resource.id' => $ids
            )
        ));

        $this->out("SQL");
        $this->hr();

        # Make the query a little more readable.
        $sql = $search->getSQL();
        $sql = implode("\nINNER JOIN ", explode(" INNER JOIN ", $sql));
        $sql = implode("\nWHERE\n ", explode(" WHERE ", $sql));
        $sql = implode(" AND\n ", explode(" AND ", $sql));
        $sql = implode(" OR\n ", explode(" OR ", $sql));
        $sql = implode("\nLIMIT ", explode(" LIMIT ", $sql));
        $this->out($sql . "\n");

        $this->out("Values");
        $this->hr();
        foreach ($search->getValues() as $k => $v) {
            $this->out($k . " | " . $v);
        }

        $this->out();
        $this->out("Results");
        $this->hr();
        foreach ($results as $id => $title) {
            $this->out($id . " | " . $title);
        }
    }

    /**
     * Interactively prompt facets from STDIN.
     */
    public function promptFacets() {
        $in = 'y';
        $facets = array();
        while (strtolower($in) == 'y') {
            $category = $this->in('category');
            $value = $this->in('value');
            array_push($facets, array(
                'category' => $category,
                'value' => $value
            ));
            $in = $this->in('Add another? [y/N]');
        }
        return $facets;
    }

    public function startup() {
        # Override the default welcome message.
    }
}
