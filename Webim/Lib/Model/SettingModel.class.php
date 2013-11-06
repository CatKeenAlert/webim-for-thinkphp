<?php

class SettingModel extends Model {

	protected $tableName = 'settings';

	public function set($uid, $data, $type='web') {
		$setting = $this->where("uid=".$uid)->select();
		if( $setting ) {
			if ( !is_string( $data ) ){
				$data = json_encode( $data );
			}
			$setting['data'] = $data;
			$Setting->save($setting);
		}
	}

	public function get($uid, $type = "web") {
		$setting = $this->where('uid='.$uid)->select();	
		if($setting) {
			return json_decode($setting->data);
		}
		return new stdClass();
	}
		
	
}
