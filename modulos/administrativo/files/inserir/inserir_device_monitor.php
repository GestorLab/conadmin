<?php

include ('../../../../files/conecta.php');
include ('../../../../files/funcoes.php');
include ('../../../../rotinas/verifica.php');
include ('../../rotinas/device.php');

class DeviceMonitor extends Entity
{
	private $log = array(
		'IdDevicePerfil',
		'IdDevicePerfilMonitor',
		'ValorComparacao' => 'Valor Comparação',
		'IdDeviceAlarme',
	);
	
	public function cadastrar()
	{
		$this->_transaction();
		
		$lin = $this->getID('IdDeviceMonitor');
		
		if($lin['IdDeviceMonitor'] != null){
			$this->id = $lin['IdDeviceMonitor'];
		}else{
			$this->id = 1;
		}
		
		$data = array(
			'IdLoja' => $this->dataSession['IdLoja'],
			'IdDevice' => $this->dataPOST['data']['IdDevice'],
			'IdDeviceMonitor' => $this->dataPOST['data']['IdDeviceMonitor'],
			'IdDevicePerfil' => $this->dataPOST['data']['IdDevicePerfil'],
			'IdDevicePerfilMonitor' => $this->dataPOST['data']['IdDevicePerfilMonitor']
		);
		//$data = array_filter($data);
		
		$obj = new Sql();
		$sql = $obj->select();
		$lin = $sql->from($this->table)
				   ->colunas(array('IdDeviceMonitor'))
				   ->where($data)
				   ->prepareObject()
				   ->execute();
		//echo $sql->_sql;die;
		$obj = new Sql();
		if($lin == null){
			$sql = $obj->insert($this->table);
		}else{
			$sql = $obj->update($this->table);
		}
		
		//$this->dataPOST['data'] = $this->arrayDiff($data, $this->dataPOST['data']);
		//print_r($this->dataPOST['data']);die;
		//$dataUrl = $data;
		foreach($this->dataPOST['data'] as $key => $value){
			if($lin == null){
				$sql->colunas(array(
					$key => $value,
					'IdLoja' => $this->dataSession['IdLoja'],
					'IdDeviceMonitor' => $this->id
				));
				$dataUrl['tipoMsg'] = 3;
			}else{
				$sql->set(array($key => $value))
					->where($data);
				$dataUrl['tipoMsg'] = 4;
			}
			
		}
		$result = $sql->prepareObject()
					  ->execute();
		//echo $sql->_sql;die;
		if($result){
			//echo $this->dataPOST['data']['IdDevice'];die;
			$this->_commit();
			$dataUrl['IdDevice'] = trim($this->dataPOST['data']['IdDevice']);
			$dataUrl['DescricaoDevice'] = $this->dataPOST['DescricaoDevice'];
			$dataUrl['IdTipoDevice'] = $this->dataPOST['IdTipoDevice'];
			$dataUrl['IdDevicePerfil'] = $this->dataPOST['data']['IdDevicePerfil'];
			$this->_redirect('../../aviso_monitor_device.php', $dataUrl);
		}
	}
	
	protected function arrayDiff($dataWhere, $dataForm)
	{
		$dataForm = array_filter($dataForm);
		$obj = new Sql();
		$sql = $obj->select();
		$lin = $sql->from($this->table)
		->colunas(
				array(
						'IdDevice',
						'IdDeviceMonitor',
						'IdDevicePerfil',
						'IdDevicePerfilMonitor',
						'Operador',
						'ValorComparacao',
						'IdDeviceAlarme',
						'SalvarLog'
				)
		)->where($dataWhere)
		->prepareObject()
		->execute();
		
		if($lin != null){
			$lin = array_filter($lin);
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

if(isset($_POST['action']['action_insert']))
{
	$subOperacao = "I";
}elseif(isset($_POST['action']['action_alterar']))
{
	$subOperacao = "U";
}elseif(isset($_POST['action']['action_excluir']))
{
	$subOperacao = "D";
}

if(!permissaoSubOperacao($localModulo,$localOperacao,$subOperacao)){
	$local_Erro = 2;
	echo "Sem permicao";die;
}else{
	$obj = new DeviceMonitor($_POST, $_SESSION);
	if($_POST['action'] == 'Excluir'){
		$obj->Excluir();
	}else{
		foreach($_POST['action'] as $key => $value){
			if(strstr($key, "insert")){
				$obj->Cadastrar();
			}
				
			else{
				$obj->$value();
			}
		
		}
	}
}