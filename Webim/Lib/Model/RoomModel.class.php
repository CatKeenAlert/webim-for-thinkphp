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
        return $data;
    }

    public function roomsbyUid($uid) {
        $rooms = D('Member')->rooms($uid);
        if(empty($rooms)) return array();
        $names = implode(',', $rooms);
        $rows = $this->where("name in ({$names})")->select();
        $rooms = array();
        foreach($rows as $row) {
            $rooms[] = array(
               'id' => $row['name'],
               'name' => $row['name'],
               'nick' => $row['nick'],
               "url" => $row['url'],
               "pic_url" => WEBIM_IMAGE("room.png"),
               "status" => "",
               "temporary" => true,
               "blocked" => false
            );
        }
        return $rooms;
    }

    public function roomsByIds($ids) {
       if(empty($ids)) return array();
       $ids = implode(',', $ids);
       $rows = $this->where("name in ({$ids})")->select();
       $rooms = array();
       foreach($rows as $row) {
           $rooms[] = array(
               'id' => $row['name'],
               'name' => $row['name'],
               'nick' => $row['nick'],
               "url" => $row['url'],
               "pic_url" => WEBIM_IMAGE("room.png"),
               "status" => "",
               "temporary" => true,
               "blocked" => false);     
       }
        return $rooms;
    }

    public function invite($room, $members) {
    
    }

    public function join($room, $uid, $nick) {
    
    }

    public function leave($room, $uid) {
    
    }

}
