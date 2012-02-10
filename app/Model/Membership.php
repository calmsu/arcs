<?php

class Membership extends AppModel {
    public $name = 'Membership';
    public $belongsTo = array(
        'Resource', 'Collection'
    );
}
