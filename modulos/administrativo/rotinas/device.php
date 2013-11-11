<?php

abstract class Entity
{
	protected $table;
	protected $dataPOST;
	protected $dataEntity;
	protected $dataSession;
	protected $objEntity;
	protected $id;
	const SQLINSERT = "INSERT INTO %s (%s) VALUES (%s)";
	
	public function __construct($dataPOST = null, $dataSession = null, $objEntity = null)
	{
		$this->table = get_class($this);
		$this->dataPOST = $dataPOST;
		$this->dataSession = $dataSession;
		$this->objEntity = $objEntity;
	}
	
	public function getID($columnID, $idTable = null)
	{
		$dataWhere["IdLoja"] = $this->dataSession['IdLoja'];
		if(!is_null($idTable))
			$dataWhere[$columnID] = $idTable;
		$obj = new Sql();
		$sql = $obj->select();
		$lin = $sql->from($this->table)
		->where($dataWhere)
		->geraID($columnID)
		->execute();
		
		return $lin;
	}
	
	protected function _transaction()
	{
		$sql = "START TRANSACTION";
		mysql_query($sql);
	}
	
	protected function _commit()
	{
		$sql = "COMMIT";
		mysql_query($sql);
	}
	
	protected function _rallback()
	{
		$sql = "RALLBACK";
		mysql_query($sql);
	}
	
	protected function _redirect($url, $dataUrl = null)
	{
		$url = (!is_null($dataUrl)) ? $url . "?" . json_encode($dataUrl) : $url;
		header("Location: $url");
	}
	
	public function prepareArray($value)
	{
		echo $value;
		echo "<br />";
		if(!empty($value)){
			return $value;
		}
	}
	
	public function logUser($logString)
	{
		$obj = new Sql();
			$sql = $obj->select();
			$lin = $sql->from($this->table)
			->colunas(array("Observacao"))
			->where(array("IdLoja" => $this->dataSession['IdLoja'], "IdDevice" => $this->id))
			->prepareObject()
			->execute();
		   //echo $sql->_sql;die;
			$Observacao =	($lin['Observacao'] != '') ? $lin['Observacao'] ."\n" : '';
		
			$obj = new Sql();
			$sql = $obj->update($this->table);
			$sql->set(array("Observacao" => trim($logString) ."\n". $Observacao))
			->where(array("IdLoja" => $this->dataSession['IdLoja'], "IdDevice" => $this->id))
			->prepareObject()
			->execute();
	}
	
	//public function findByOne($table, $columnWhere, $id){
	public function findByOne($table, $data){
		$obj = new Sql();
		$sql = $obj->select();
		$lin = $sql->from($table)
				   ->colunas(array('*'))
				   ->where($data)
				   ->prepareObject()
				   ->execute();
			//echo $sql->_sql;die;
		//print_r($lin);
		return $lin;
	}
	
}

class Sql
{
	public function select()
	{
		return new Select();
	}

	public function insert($table)
	{
		return new Insert($table);
	}

	public function update($table)
	{
		return new Update($table);
	}

	public function delete($table)
	{
		return new Delete($table);
	}

	public function __destruct()
	{
		unset($this);
	}
}

class Select
{
	const SQLGERAID = "SELECT (LAST_INSERT_ID(%s)+1) %s FROM %s
					   WHERE %s ORDER BY %s DESC LIMIT 1";

	protected $_sqlSelect = array(
			'select' => 'SELECT %s FROM %s ',
			'where' => 'WHERE %s'
	);

	protected $_table;
	protected $_colunas;
	protected $_where;
	public $_sql;

	public function from($table)
	{
		$this->_table = $table;
		return $this;
	}

	public function colunas($data)
	{
		foreach($data as $key => $value){
			$this->_colunas[] = (is_numeric($key)) ? $value : $key . " AS " . $value; 
		}
		
		return $this;
	}

	public function where($data)
	{
		foreach($data as $key => $value)
		{
			$value = (is_numeric($value)) ? $value : "'" . trim(mysql_real_escape_string($value), '\'\"') . "'";
				
			if(count($this->_where) == 0)
			{
				$this->_where[] = $key . " = " . $value;
			}else
			{
				$this->_where[] = " AND " . $key . " = " . $value;
			}
		}
		return $this;
	}

	public function prepareObject()
	{
		if(count($this->_colunas) > 0)
		{
			$this->_sql = sprintf($this->_sqlSelect['select'], join(", ", $this->_colunas), $this->_table);
		}
		if(count($this->_where) > 0)
		{
			$this->_sql .= sprintf($this->_sqlSelect['where'], join(" ", $this->_where));
		}
		return $this;
	}

	public function geraID($colunaID)
	{
		$this->_sql = sprintf(self::SQLGERAID, $colunaID, $colunaID, $this->_table, join(" ", $this->_where), $colunaID);
		return $this;
	}

	public function execute()
	{
		if(empty($this->_sql))
		{
			throw new Exception("_sql nao pode servazio");
		}
		$result = mysql_query($this->_sql);
		if(!$result)
		{
			throw new Exception("Error sql: " . $this->_sql);
		}
		$lin = @mysql_fetch_assoc($result);
		return $lin;
	}

	public function __destruct()
	{
		unset($this);
	}


}

class Insert
{
	const SQLINSERT = "INSERT INTO %s (%s) VALUES (%s)";

	protected $_table;
	protected $_data = array();
	public $_sql;

	public function __construct($table)
	{
		$this->_table = $table;
	}

	public function colunas($data)
	{
		foreach($data as $key => $value)
		{
			if($value != "")
				$value = (is_numeric($value)) ? $value : "'" . trim(mysql_real_escape_string($value), '\'\"') . "'";
			else
				$value = 'NULL';
			$this->_data[$key] = $value;
		}
		return $this;
	}

	public function prepareObject()
	{
		$this->_sql = sprintf(self::SQLINSERT, $this->_table, join(", ", array_keys($this->_data)), join(", ", $this->_data));
		return $this;
	}

	public function execute()
	{
		$result = mysql_query($this->_sql);
		if(!$result)
		{
			throw new Exception("Error sql: " . $this->_sql);
		}
		return $result;
	}

	public function __destruct()
	{
		unset($this);
	}
}

class Update
{
	const SQLUPDATE = "UPDATE %s SET %s WHERE %s";

	protected $_table;
	protected $_data;
	protected $_where;
	public $_sql;

	public function __construct($table)
	{
		$this->_table = $table;
	}

	public function set($data)
	{
		foreach($data as $key => $value)
		{
			if($value != "")
				$value = (is_numeric($value)) ? $value : "'" . trim(mysql_real_escape_string($value), '\'\"') . "'";
			else
				$value = 'NULL';
			$this->_data[] = $key . " = " . $value;
		}
		return $this;
	}

	public function where($data)
	{
		foreach($data as $key => $value)
		{
			$value = (is_numeric($value)) ? $value : "'" . trim(mysql_real_escape_string($value), '\'\"') . "'";

			if(count($this->_where) == 0)
			{
				$this->_where[] = $key . " = " . $value;
			}else
			{
				$this->_where[] = " AND " . $key . " = " . $value;
			}
		}
		return $this;
	}

	public function prepareObject()
	{
		$this->_sql = sprintf(self::SQLUPDATE, $this->_table, join(", ", $this->_data), join(" ", $this->_where));
		return $this;
	}

	public function execute()
	{
		$result = mysql_query($this->_sql);
		if(!$result)
		{
			throw new Exception("Error sql: " . $this->_sql);
		}
		return $result;
	}

	public function __destruct()
	{
		unset($this);
	}
}

class Delete
{
	const SQLDELETE = "DELETE FROM %s WHERE %s";

	protected $_table;
	protected $_data;
	protected $_where;
	public $_sql;

	public function __construct($table)
	{
		$this->_table = $table;
	}

	public function where($data)
	{
		foreach($data as $key => $value)
		{
			$value = (is_numeric($value)) ? $value : "'" . trim(mysql_real_escape_string($value), '\'\"') . "'";

			if(count($this->_where) == 0)
			{
				$this->_where[] = $key . " = " . $value;
			}else
			{
				$this->_where[] = " AND " . $key . " = " . $value;
			}
		}
		return $this;
	}

	public function prepareObject()
	{
		$this->_sql = sprintf(self::SQLDELETE, $this->_table, join(" ", $this->_where));
		return $this;
	}

	public function execute()
	{
		$result = mysql_query($this->_sql);
		if(!$result)
		{
			throw new Exception("Error sql: " . $this->_sql);
		}
		return $result;
	}

	public function __destruct()
	{
		unset($this);
	}


}