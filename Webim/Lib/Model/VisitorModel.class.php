<?php

class VisitorModel extends Model {

	protected $tableName = 'visitors';

    public function findOrCreate() {
        global $_COOKIE, $_SERVER;
        if (isset($_COOKIE['_webim_visitor_id'])) {
            $id = $_COOKIE['_webim_visitor_id'];
        } else {
            $id = substr(uniqid(), 6);
            setcookie('_webim_visitor_id', $id, time() + 3600 * 24 * 30, "/", "");
        }
        $vid = 'vid:'. $id;
        $visitor = $this->where('name', $vid)->find();
        if( !$visitor ) {
            $ipaddr = isset($_SERVER['X-Forwarded-For']) ? $_SERVER['X-Forwarded-For'] : $_SERVER["REMOTE_ADDR"];
            $loc = IP::find($ipaddr);
            if(is_array($loc)) $loc = implode('',  $loc);
            $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
            $this->create(array(
                "name" => $vid,
                "ipaddr" => $ipaddr,
                "url" => $_SERVER['REQUEST_URI'],
                "referer" => $referer,
                "location" => $loc,
            ));
            $this->created = date( 'Y-m-d H:i:s' );
            $this->add();
        }
        return (object) array(
            'id' => $vid,
            'nick' => "v".$id,
            'group' => "visitor",
            'presence' => 'online',
            'show' => "available",
            'avatar' => WEBIM_IMAGE('male.png'),
            'role' => 'visitor',
            'url' => "#",
            'status' => "",
        );
    }

    /**
     * visitors by vids
     */
    public function visitorsByIds($vids) {
        if( count($vids)  == 0 ) return array();
        $vids = implode("','", $vids);
        $rows = $this->where("name in ('{$vids}')")->select();
        $visitors = array();
        foreach($rows as $v) {
            $v = (object)$v;
            $status = $v->location;
            if( $v->ipaddr ) $status = $status . '(' . $v->ipaddr .')';
            $visitors[] = (object)array(
                "id" => $v->name,
                "nick" => "v".substr($v->name, 4), //remove vid:
                "group" => "visitor",
                "url" => "#",
                "avatar" => WEBIM_IMAGE('male.png'),
                "status" => $status, 
            );
        }
        return $visitors;
    }

}

