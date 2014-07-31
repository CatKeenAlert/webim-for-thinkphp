<?php

class AskModel extends Model {

	protected $tableName = 'asks';

    public function insert($data) {
        $this->create(array(
            'from_id' => $data['from_id'],
            'from_nick' => $data['from_nick'],
            'to_id' => $data['to_id'],
            'to_nick' => $data['to_nick'],
            'answer' => 0,
            'initiated' => date( 'Y-m-d' )
        ));
        $this->add();
    }

    public function all($uid) {
        /* select * from webim_asks where (to_id = 2 and answer = 0) or (from_id = 2 and answer >0) order by id desc limit 10; */
        $rows = $this->where("(to_id = '$uid' and answer = 0) or (from_id = '$uid' and answer >0)")
            ->order('id desc')->limit(10)->select();
        $asks = array();
        foreach($rows as $row) { 
            if($row['answer'] == 0) {
                $ask = array(
                    'from' => $row['from_id'],
                    'nick' => $row['from_nick'],
                    'to' => $row['to_id'],
                    'time' => $this->_format($row['initiated'])
                );
            } else {
                $ask = array(
                    'from' => $row['to_id'],
                    'nick' => $row['to_nick'],
                    'to' => $row['from_id'],
                    'time' => $this->_format($row['answered'])
                );
            }
            $ask['id'] = $row['id'];
            $ask['answer'] = $row['answer'];
            $asks[] = (object)$ask; 
        }
        return array_reverse($asks);
    }

    private function _format($time) {
        $date = new DateTime($time);
        return $date->format('m-d');
    }

    public function accept($uid, $askid) {
        /* select * from webim_asks where id = $askid and to_id = '$uid' */
        $ask = $this->where("id = $askid and `to_id` = '$uid'")->find();
        if( $ask ) {
            /* update webim_asks set answer = 2, answered = NOW() where id = $askid; */
            $ask['answer'] = 1;
            $ask['answered'] = date( 'Y-m-d H:i:s' );
            $this->save($ask);
        }
    }

    public function reject($uid, $askid) {
        /* select * from webim_asks where id = $askid and to_id = '$uid' */
        $ask = $this->where("id = $askid and `to_id` = '$uid'")->find();
        if( $ask ) {
            /* update webim_asks set answer = 1, updated = NOW() where id = $askid; */
            $ask['answer'] = 2;
            $ask['answered'] = date( 'Y-m-d H:i:s' );
            $this->save($ask);
        }
    }

}

