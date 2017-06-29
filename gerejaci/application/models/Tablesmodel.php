<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tablesmodel extends CI_Model
{

	function __construct(){
		 parent::__construct();
	}

	function insert($table,$data){
		$table = strtolower($table);
		if(isset($data['passwd'])){
			$data['passwd'] = md5($data['passwd']);
		}
		$this->db->insert($table,$data);
	}
	function getAll($table,$page=0,$order=array()){
		if($page>0){
			//limit (10,20) produces LIMIT 20,10,, dimulai dari 10 sebanyak 20
			$this->db->limit(FETCH,($page-1)*FETCH);
		}
		foreach($order as $key=>$value){
			$this->db->order_by($key, $value);
		}
		//debug($start.$end);
		$query = $this->db->get($table);

		return $query;
	}
	function getWhere($table,$post,$page=0){
		if($page>0){
			//limit (10,20) produces LIMIT 20,10,, dimulai dari 10 sebanyak 20
			$this->db->limit(FETCH,($page-1)*FETCH);
		}
		foreach($post as $key=>$value){
			$this->db->where($key, $value);
		}
		$query = $this->db->get($table);
		//untuk lihat query yang terbentuk:
		//debug($this->db->last_query());
		return $query;
	}

	function updateMultipleOneColumn($table,$column,$post){
		$i=0;
		foreach($post['row'] as $row){
			$this->db->set($column,$row);
			$this->db->where('id',$post['id'][$i]);
			$this->db->update($table);
			$this->db->reset_query();
			$i++;
		}
	}
	function updateRow($table,$id,$column=array()){
		if(isset($column['passwd'])){
			$column['passwd'] = md5($data['passwd']);
		}
		foreach($column as $key=>$value){
			$this->db->set($key, $value);
		}
		$this->db->where('id',$id);
		$this->db->update($table);
	}
	function delete($table, $post){
		foreach($post as $key=>$value){
			$this->db->where($key, $value);
			break;
		}

		$this->db->delete($table);
	}
	function countOne($table,$where=array(),$group=false){
		foreach($where as $key=>$value){
			$this->db->where($key, $value);
		}
		if($group)
			$this->db->group_by($group);
		return $this->db->count_all($table);
	}

	function countBy($table,$where,$group=false,$page=0,$order=array()){
		if($page>0){
			//limit (10,20) produces LIMIT 20,10,, dimulai dari 10 sebanyak 20
			$this->db->limit(FETCH,($page-1)*FETCH);
		}

		foreach($where as $key=>$value){
			$this->db->where($key, $value);
		}
		if($group){
			$this->db->group_by($group);

		}
		foreach($order as $key=>$value){
			$this->db->order_by($key, $value);
		}

		$this->db->select('*')->select('COUNT(*) AS count');


		//debug($this->db->get_compiled_select($table));

		//return *,count($) as count
		return $this->db->get($table);


	}
	function countRange($table,$by,$range){

		$addition = $range -1;

		$query = $this->db->query("
			SELECT
			    concat(
			    	concat($range * ($by div $range),'-'),
			    	$range * ($by div $addition) + 99) as 'range',
			    COUNT(*) as count
			FROM $table
			GROUP BY $by div $range;

		"
		);

		//range, count
		return $query;
	}
}

?>
