<?php

class MemberModel extends Model {

    protected $tableName = 'members';

    public function allInRoom($room) {
        $rows = $this->where("room = '{$room}'")->select();
        $members = array();
        foreach($rows as $row) {
            $members[] = array(
                'id' => $row['uid'],
                'nick' => $row['nick']
            );
        }
        return members;
    }

    public function rooms($uid) {
        $rows = $this->where("uid = '{$uid}'")->select();
        $rooms = array();
        foreach($rows as $row) {
            $rooms[] = $row['room'];
        }
        return $rooms;
    }

    public function join($room, $uid, $nick) {
        $this->create(array(
            'uid' => $uid,
            'room' => $room,
            'nick' => $nick
        ));
        $this->joined = date( 'Y-m-d H:i:s' );
        $this->add();
    }

}
