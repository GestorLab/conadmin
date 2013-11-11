<?php

class DevicePorta extends Entity
{
	private $log = array(
		'DescricaoDevicePorta' => 'Descrição',
		'Disponivel' => 'Disponivel',
		'Observacao' => 'Observação'
	);
	
	public function Cadastrar()
	{
		/*
		 * Gera ID para cadastro de portas pegando o ultimo ID inserido mais 1
		*/
		$lin = $this->getID('IdDevicePorta');
			
		if($lin["IdDevicePorta"] != null){
			$this->id = $lin["IdDevicePorta"];
		}else{
			$this->id = 1;
		}
		
		$data = array(
			'IdLoja' => $this->dataSession['IdLoja'],
			'IdDevice' => $this->objEntity->id
		);
		
		//print_r($this->dataPOST["portas"]);die;
		
		foreach($this->dataPOST["portas"] as $key => $value){
			$data['IdDevicePorta'] = array_shift($value);
			$logAux = "";
			$obj = new Sql();
			if(trim($data['IdDevicePorta']) == ""){
				$sql = $obj->insert($this->table);
			}else{
				$sql = $obj->update($this->table);
				$value = $this->arrayDiff($data, $value);
				
				$lin = $this->findByOne('DevicePorta', $data);
			}
			//if(count($value) > 0){
				foreach($value as $index => $val){
					if(trim($data['IdDevicePorta']) == ""){
						$sql->colunas(array(
								$index => $val,
								"IdDevicePorta" => $this->id,
								"IdDevice" => $this->objEntity->id,
								"IdLoja" => $this->dataSession['IdLoja']
						));
						$acao = 'Cadastrou Porta';
						$tipoMsg = 3;
					}else{
						$sql->set(array(
								$index => $val
						));
						$sql->where($data);
						$acao = 'Alterou Porta';
						$tipoMsg = 4;
					}
						
				
					if(array_key_exists($index, $this->log)){
						if($index == 'Disponivel')
							$val = ($val == 1) ? "Sim" : "Não";
						if($tipoMsg == 3){
							$logAux[] = ($val != 'NULL' && !strstr($index, "Id") && $val != '') ? date("d/m/Y H:i:s") . " [{$this->dataSession['Login']}] - cadastrou {$this->log[$index]} porta {$this->id}: $val\n" : '';
						}
						else{
							if($index == 'Disponivel')
								$valAux = ($lin[$index] == 1) ? "Sim" : "Não";
							else 
								$valAux = $lin[$index];
							$logAux[] = ($val != 'NULL' && !strstr($index, "Id") && $val != '') ? date("d/m/Y H:i:s") . " [{$this->dataSession['Login']}] - Alterou {$this->log[$index]} porta {$data[IdDevicePorta]}: [$valAux > $val]\n" : '';
						} 
							
					}
				}
				$logAux = join("", $logAux);
				$result = $sql->prepareObject()
				->execute();
				
				if(!$result)
					return false;
				
				if(mysql_affected_rows() != 0){
					$this->objEntity->logUser($logAux);
				}
					
				$this->id++;
				//}
			}
				
		return $tipoMsg;
	}
	
	public function Excluir()
	{
		if(isset($this->dataPOST['remove'])){
			foreach($this->dataPOST['remove'] as $key => $value){
				$obj = new Sql();
				$sql = $obj->delete($this->table);
				foreach($value as $index => $val){
					if(strstr($index, "Id")){
						$sql->where(array($index => $val));
					}
				}
				$result = $sql->prepareObject()
						      ->execute();
			}
		}elseif(isset($this->dataPOST['portas']) || $this->objEntity->id){
			$obj = new Sql();
			$sql = $obj->delete($this->table);
			$result = $sql->where(array("IdLoja" => $this->dataSession['IdLoja'], "IdDevice" => $this->objEntity->id))
			->prepareObject()
			->execute();
		}
		
		return $result;
	}
	
	protected function arrayDiff($dataWhere, $dataForm)
	{
		$dataForm = array_filter($dataForm);
		$obj = new Sql();
		$sql = $obj->select();
		$lin = $sql->from($this->table)
					  ->colunas(array_keys($dataForm))
					  ->where($dataWhere)
					  ->prepareObject()
					  ->execute();
		//echo $sql->_sql;die;
		$lin = array_filter($lin);
		if($lin != null){
				if(array_diff_assoc($dataForm, $lin)){
					return array_diff_assoc($dataForm, $lin);
				}else{
					return $dataForm;
				}
		}else{
			return $dataForm;
		}
	}
}