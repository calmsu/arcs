<?php
class Bookmark extends AppModel {
    public $name = 'Bookmark';
    public $belongsTo = array('User');
}
