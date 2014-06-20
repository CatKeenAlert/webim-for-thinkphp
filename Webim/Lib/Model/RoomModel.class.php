<?php

class RoomModel extends Model {

	protected $tableName = 'rooms';

    public function insert($data) {
        $name = $data['name'];
		$room = $this->where("name={$name}")->find();
        if($room) return $room;
        $this->create($data);
        $this->created = date( 'Y-m-d H:i:s' );
        $this->add();
        return (object)$data;
    }

    public function rooms($uid) {
        $rooms = D('Member')->rooms($uid);
        if(empty($rooms)) return array();
        $names = implode("','", $rooms);
        $rows = $this->where("name in ('{$names}')")->select();
        $rooms = array();
        foreach($rows as $row) {
            $rooms[] = (object)array(
               'id' => $row['name'],
               'name' => $row['name'],
               'nick' => $row['nick'],
               "url" => $row['url'],
               "avatar" => WEBIM_IMAGE("room.png"),
               "status" => "",
               "temporary" => true,
               "blocked" => false
            );
        }
        return $rooms;
    }

    public function roomsByIds($uid, $ids) {
       if(empty($ids)) return array();
       $ids = implode("','",  $ids);
       $rows = $this->where("name in ('{$ids}')")->select();
       $rooms = array();
       foreach($rows as $row) {
           $rooms[] = (object)array(
               'id' => $row['name'],
               'name' => $row['name'],
               'nick' => $row['nick'],
               "url" => $row['url'],
               "avatar" => WEBIM_IMAGE("room.png"),
               "status" => "",
               "temporary" => true,
               "blocked" => false);     
       }
        return $rooms;
    }

    public function invite($room, $members) {
        foreach($members as $member) {
            D('member')->join($room, $member->id, $member->nick);
        }
    }

    public function leave($room, $uid) {
        D('member')->where("room = '{$room}' and uid = '{$uid}'")->delete();
        $count = D('member')->where("room = '{$room}'")->count('id');
        if($count === 0) {
            $this->where("name = '$room'")->delete();
        }
    }

}
