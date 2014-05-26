<?php

class BlockModel extends Model {

    protected $tableName = 'blocked';

    public function insert($room, $uid) {
        $this->create(array('room' => $room, 'uid' => $uid));
        $this->blocked = date( 'Y-m-d H:i:s' );
        $this->add();
    }

    public function remove($room, $uid) {
        $this->where("room = '{$room}' and uid = '{$uid}'")->delete(); 
    }

}

?>
