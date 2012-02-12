<?php
App::uses('AppHelper', 'View/Helper');

class AssetsHelper extends AppHelper {
    public $helpers = array('Html');

    /**
     * Return true if the global debug configuration is 0, false otherwise.
     */
    private function isProd() {
        return Configure::read('debug') == 0;
    }

    /**
     * Resolve an array of paths with unix filename patterns. Returns the
     * unique array of all matched paths.
     *
     * @param wilds  an array of paths that may include patterns.
     * @param base   a base path to prepend when resolving paths.
     */
    private function resolvePaths($wilds, $base='') {
        $paths = array();
        foreach ($wilds as $w) {
            foreach (glob($base . $w) as $match) {
                # Push the match, minus the base path.
                array_push($paths, str_replace($base, '', $match));
            }
        }
        # Return a unique set.
        return array_unique($paths);
    }

    /**
     * Read scripts from the assets.ini config file and output them.
     */
    public function scripts() {
        if ($this->isProd()) {
            $path = Configure::read('js.prod');
            return $this->Html->script($path);
        } else {
            $paths = Configure::read('js.files');
            if (Configure::read('js.dev')) {
                $paths = array_merge($paths, Configure::read('js.dev'));
            }
            return $this->Html->script($this->resolvePaths($paths, JS));
        }
    }
        
    /**
     * Read stylesheets from the assets.ini config file and output them.
     */
    public function stylesheets() {
        if ($this->isProd()) {
            $path = Configure::read('css.prod');
            return $this->Html->css($path);
        } else {
            $paths = Configure::read('css.files');
            if (Configure::read('css.dev')) {
                $paths = array_merge($paths, Configure::read('css.dev'));
            }
            return $this->Html->css($this->resolvePaths($paths, CSS));
        }
    }
}
