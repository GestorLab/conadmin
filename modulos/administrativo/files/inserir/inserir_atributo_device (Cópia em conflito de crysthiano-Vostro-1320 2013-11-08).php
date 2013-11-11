<?php

class DeviceAtributo extends Entity
{
	
	public function Cadastrar()
	{
		
		/*
		 * Cria as colunas com os valores dinamicamente
		*/
		$data = array(
			'IdLoja' => $this->dataSession['IdLoja'],
			'IdDevice' => $this->objEntity->id
		);
		foreach($this->dataPOST["atributos"] as $key => $value){
			$logAux = "";
			$DescricaoAtributo = array_shift($value);
			$data['IdAtributo'] = $value['IdAtributo'];
			$data['IdDevicePerfil'] = $value['IdDevicePerfil'];
			$obj = new Sql();
			$sql = $obj->select();
			$lin = $sql->from($this->table)
			->colunas(array('IdAtributo', 'IdDevicePerfil'))
			->where($data)
			->prepareObject()
			->execute();
			//echo $sql->_sql;die;
			
			$obj = new Sql();
			if($lin == null){
				$sql = $obj->insert($this->table);
			}else{
				$sql = $obj->update($this->table);
				$value = $this->arrayDiff($data, $value);
				//print_r($value);die;
			} 
			foreach($value as $index => $val){
				if($lin == null){
					$sql->colunas(array(
							$index => $val,
							"IdLoja" => $this->dataSession['IdLoja'],
							"IdDevice" => $this->objEntity->id
					));
					$acao = 'Cadastrou Atributo';
					$tipoMsg = 3;
				}else{
					if(!strstr($index, "Id")){
						$sql->set(array(
								$index => $val
						));
						$sql->where($data);
					}
					$acao = 'Alterou Atributo';
					$tipoMsg = 4;
				}
				
					
				$logAux[] = ($val != 'NULL' && !strstr($index, "Id") && $val != '') ? date("d/m/Y H:i:s") . " [{$this->dataSession['Login']}] - " . $DescricaoAtributo . ': ' . $val . "\n" : '';
			}
			$logAux = join(" ", $logAux);
			$result = $sql->prepareObject()
			              ->execute();
			
			if(!$result)
				return false;
			
			if(mysql_affected_rows() != 0){
				$this->objEntity->logUser($logAux);
			}
			
		
		}
		
		return $tipoMsg;
	}
	
	public function Excluir()
	{
		$obj = new Sql();
		$sql = $obj->delete($this->table);
		$result = $sql->where(array("IdLoja" => $this->dataSession['IdLoja'], "IdDevice" => $this->objEntity->id))
					  ->prepareObject()
					  ->execute();
		
		return $result;
	}
	
	protected function arrayDiff($dataWhere, $dataForm)
	{
		$dataForm = array_filter($dataForm);
		$obj = new Sql();
		$sql = $obj->select();
		$lin = $sql->from($this->table)
		->colunas(
				array(
				   'IdDevicePerfil',
				   'IdAtributo',	
				   'Valor'
				)
		)->where($dataWhere)
		->prepareObject()
		->execute();
	
		$lin = array_filter($lin);
	
		if($lin != null){
			if(array_diff($dataForm, $lin)){
				return array_diff($dataForm, $lin);
			}else{
				return $dataForm;
			}
		}else{
			return $dataForm;
		}
		//echo $sql->_sql;die;
		//return $lin;
	}
}