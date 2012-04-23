<?php
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
