<?php
App::uses('AppHelper', 'View/Helper');

/**
 * Assets Helper
 *
 * The Assets Helper checks the application debug configuration and uses
 * it to determine how assets should be outputted.
 */
class AssetsHelper extends AppHelper {
    public $helpers = array('Html');

    /**
     * Return true if the global debug configuration is 0, false otherwise.
     */
    private function _isProd() {
        return Configure::read('debug') == 0;
    }

    /**
     * Resolve an array of paths with unix filename patterns. Returns the
     * unique array of all matched paths.
     *
     * @param array $wilds     an array of paths that may include patterns.
     * @param string $base     a base path to prepend when resolving paths.
     * @param bool $keep_base  if true, the prepended base path is included in
     *                         each resolved path. This defaults to false to 
     *                         cater to usage with Html->script.
     */
    private function _resolvePaths($wilds, $base='', $keep_base=false) {
        $paths = array();
        foreach ($wilds as $w) {
            foreach (glob($base . $w) as $match) {
                # Push the match, minus the base path.
                if (!$keep_base)
                    $match = str_replace($base, '', $match);
                array_push($paths, $match);
            }
        }
        # Return a unique set.
        return array_unique($paths);
    }

    /**
     * Returns true if any of the given paths were modified after the 
     * given timestamp.
     *
     * @param array $paths  array of existing paths
     * @param int $time     unix timestamp
     */
    private function _modifiedAfter($paths, $time) {
        foreach($paths as $p)
            if (filemtime($p) > $time) return true;
        return false;
    }

    /**
     * Ensures that the templates asset is up-to-date and returns
     * a script tag to include it.
     *
     * This method doesn't need to run in production mode.
     */
    private function _templates() {
        $JST   = APP . 'View' . DS . 'JST' . DS;
        $BUILT = WWW_ROOT . 'assets' . DS . 'templates.js';
        $FILES = Configure::read('templates.files');
        $NS    = Configure::read('templates.namespace');

        $paths = $this->_resolvePaths($FILES, $JST, true);

        $build = !file_exists($BUILT) || 
            $this->_modifiedAfter($paths, filemtime($BUILT));

        if ($build) 
            $this->_buildTemplates($paths, $JST, $BUILT, $NS);

        return $this->Html->script("/assets/templates.js");
    }

    /**
     * Builds a file of javascript strings--assigned as properties of the 
     * given namespace--given an array of template containing file paths. 
     * We'll strip newlines and escape doublequotes.
     *
     * @param array $paths
     * @param string $base
     * @param string $dst
     * @param string $namespace
     */
    private function _buildTemplates($paths, $base, $dst, $namespace) {
        $fhandle = fopen($dst, "w");
        fwrite($fhandle, "window.$namespace = window.$namespace || {};\n");
        foreach($paths as $p) {
            if (!is_file($p)) continue;
            $contents = file_get_contents($p);
            $contents = str_replace('"', '\"', $contents);
            $contents = str_replace("\n", '', $contents);
            $name = str_replace($base, '', $p);
            $name = str_replace('.jst', '', $name);
            $jst = $namespace . "[\"$name\"] = \"$contents\";\n";
            fwrite($fhandle, $jst);
        }
        fclose($fhandle);
    }

    /**
     * Read scripts from the config file, then resolve and output them.
     */
    public function scripts() {
        if ($this->_isProd()) {
            $path = Configure::read('js.prod');
            return $this->Html->script('/assets/' . $path);
        } else {
            $paths = Configure::read('js.files');
            if (Configure::read('js.dev')) {
                $paths = array_merge($paths, Configure::read('js.dev'));
            }
            return $this->Html->script($this->_resolvePaths($paths, JS)) .
                $this->_templates();
        }
    }

    /**
     * Read script specs from the config file, then resolve and output them.
     */
    public function specs() {
        $paths = Configure::read('js.tests');
        return $this->Html->script($this->_resolvePaths($paths, JS));
    }
        
    /**
     * Read stylesheets from the config file, then resolve and output them.
     */
    public function stylesheets() {
        if ($this->_isProd()) {
            $path = Configure::read('css.prod');
            return $this->Html->css('/assets/' . $path);
        } else {
            $paths = Configure::read('css.files');
            if (Configure::read('css.dev')) {
                $paths = array_merge($paths, Configure::read('css.dev'));
            }
            return $this->Html->css($this->_resolvePaths($paths, CSS));
        }
    }
}
