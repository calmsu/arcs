<?php
/**
 * Email Task
 *
 * Abstracted email sender.
 * 
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
App::uses('CakeEmail', 'Network/Email');
class EmailTask extends AppShell {

    public function execute($data) {
        $email = new CakeEmail('default');
        $email->to($data['to'])
            ->subject($data['subject'])
            ->emailFormat('html')
            ->viewVars($data['vars'] ?: array())
            ->helpers('Html')
            ->template($data['template'], 'default')
            ->send();
    }
}
