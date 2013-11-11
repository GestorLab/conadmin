<?php

//print_r($_POST['atributos']);die;

include ('../../../../files/conecta.php');
include ('../../../../files/funcoes.php');
include ('../../../../rotinas/verifica.php');
include ('../../rotinas/device.php');
include('inserir_porta.php');
include('inserir_atributo_device.php');

header ('Content-type: text/html; charset=iso-8859-1');

class Device extends Entity
{
	private $log = array(
			'IdTipoDevice' => 'Tipo',
			'DescricaoDevice' => 'Descrição',
			'IdGrupoDevice' => 'Grupo Device'
	);

	public function __construct($dataPOST, $dataSession)
	{
		parent::__construct($dataPOST, $dataSession);

		if(isset($this->dataPOST['remove'])){
			$objPorta = new DevicePorta($dataPOST, $dataSession, $this);
			$objPorta->Excluir();
		}
	}

	public function Cadastrar()
	{
		$this->_transaction();

		$lin = $this->getID('IdDevice');
			
		if($lin['IdDevice'] != null){
			$this->id = $lin['IdDevice'];
		}else{
			$this->id = 1;
		}

		if(trim($this->dataPOST['historico']) != "")
			$logAux[] = date("d/m/Y H:i:s") . " [{$this->dataSession['Login']}] -" . "Observação: " . $this->dataPOST['historico'] . "\n";
			
		/*
		 * Monta dinamicamente as colunas com os valores que irao ser inseridos
		*/
		$data = array(
				'IdLoja' => $this->dataSession['IdLoja'],
				'IdDevice' => array_shift($this->dataPOST['dados'])
		);
		
		$this->dataPOST['dados'] = $this->arrayDiff($data, $this->dataPOST['dados']);

		$obj = new Sql;
		if(trim($data['IdDevice']) == ""){
			$sql = $obj->insert($this->table);
		}else{
			$sql = $obj->update($this->table);
			/*$dataWhere = array(
				'IdDevicePerfil' => $this->dataPOST['dados']['IdDevicePerfil']
			);*/
			$lin = $this->findByOne('Device', $data);
			$dataGrupo = array(
				'IdLoja' => $this->dataSession['IdLoja'],
				'IdGrupoDevice' => $lin['IdGrupoDevice']
			);
			$lin2 = $this->findByOne('GrupoDevice', $dataGrupo);
		}

		foreach($this->dataPOST['dados'] as $key => $value){
			if(trim($data['IdDevice']) == "" ){
				$sql->colunas(array(
						$key => $value,
						"IdDevice" => $this->id,
						"IdLoja" => $this->dataSession['IdLoja'],
						"LoginCriacao" => $this->dataSession['Login'],
						"DataCriacao" => date("Y-m-d H:i:s")
				));
				$acao = 'Cadastrou Device';
				$dataUrl['Tipo'] = 3;
			}else{
				$sql->set(array($key => $value));
				$sql->where(array("IdLoja" => $this->dataSession['IdLoja'], "IdDevice" => $data['IdDevice']));
				$acao = 'Alterou Device';
				$dataUrl['Tipo'] = 4;
			}
				
			if(array_key_exists($key, $this->log)){
				if($key == 'IdTipoDevice' && trim($value) != ''){
					$value = getParametroSistema(276,$value);
					
					if($lin[$key] != null)
						$lin[$key] = getParametroSistema(276,$lin[$key]);
				}
				if($key == 'IdGrupoDevice')
					$value = $this->dataPOST['DescricaoGrupoDevice'];
				if($dataUrl['Tipo'] == 3){
					$logAux[] = ($value != 'NULL' && trim($value) != '') ? date("d/m/Y H:i:s") . " [{$this->dataSession['Login']}] - cadastrou {$this->log[$key]}: $value\n" : '';
				}
				else{
					if($key == 'IdGrupoDevice' && $lin[$key] != "")
						$valueAux = $lin2['DescricaoGrupoDevice'];
					else 
						$valueAux = $lin[$key];
					$logAux[] = ($value != 'NULL' && trim($value) != '') ? date("d/m/Y H:i:s") . " [{$this->dataSession['Login']}] - alterou {$this->log[$key]}: [{$valueAux} > $value]\n" : '';
				}
					
			}

		}
		$logAux = join("", $logAux);
		//echo $logAux;die;
		$result = $sql->prepareObject()
		->execute();

		if($result){
			if(trim($data['IdDevice']) != "")
				$this->id = $data['IdDevice'];
			if(mysql_affected_rows() != 0){
				$this->logUser($logAux);
			}

			if(isset($this->dataPOST['portas'])){
				$objPorta = new DevicePorta($this->dataPOST, $this->dataSession, $this);
				$result = $objPorta->Cadastrar();
				if(!$result){
					$this->_rallback();
					$dataUrl['Tipo'] = 5;
					$dataUrl['IdDevice'] = $this->id;
					$this->_redirect('../../cadastro_device.php', $dataUrl);
					exit;
				}else{
					$dataUrl['Tipo'] = $result;
				}
			}
				
			if(isset($this->dataPOST['atributos'])){
				$objAtributo = new DeviceAtributo($this->dataPOST, $this->dataSession, $this);
				$result = $objAtributo->Cadastrar();
				if(!$result){
					$this->_rallback();
					$dataUrl['Tipo'] = 8;
					$dataUrl['IdDevice'] = $this->id;
					$this->_redirect('../../cadastro_device.php', $dataUrl);
					exit;
				}else{
					$dataUrl['Tipo'] = $result;
				}
				$dataUrl['IdDevicePerfil'] = $this->dataPOST['atributos'][0]['IdDevicePerfil'];
			}
				
			$this->_commit();
			//$dataUrl['Tipo'] = 3;
			$dataUrl['DescricaoDevice'] = $this->dataPOST['dados']['DescricaoDevice'];
			$dataUrl['IdTipoDevice'] = $this->dataPOST['dados']['IdTipoDevice'];
			$dataUrl['IdDevicePerfil'] = $this->dataPOST['dados']['IdDevicePerfil'];
			if(trim($data['IdDevice']) != "")
				$dataUrl['IdDevice'] = $data['IdDevice'];
			else
				$dataUrl['IdDevice'] = $this->id;
			$this->_redirect('../../cadastro_device.php', $dataUrl);
			exit;
		}
	}

	public function Excluir()
	{
		$result = true;

		$this->_transaction();

		$this->id = $this->dataPOST['dados']['IdDevice'];
		//echo $this->id;die;

		$obj = new Sql();
		$sql = $obj->select();
		$lin2 = $sql->from($this->table)
		->colunas(array(
				'IdDevice',
				'IdDevicePerfil',
		))->where(array('IdLoja' => $this->dataSession['IdLoja'], 'IdDevice' => $this->id))
		->prepareObject()
		->execute();

		$lin = $this->getID("IdDevice", $this->id);

		if(isset($this->dataPOST['portas'])){
			$objPorta = new DevicePorta($this->dataPOST, $this->dataSession, $this);
			$result = $objPorta->Excluir();
		}

		if($result != false && isset($this->dataPOST['atributos'])){
			$objAtributo = new DeviceAtributo($this->dataPOST, $this->dataSession, $this);
			$result = $objAtributo->Excluir();
		}

		if(isset($this->dataPOST['dados']['IdDevicePerfil'])){
			$IdDevicePerfil = $this->dataPOST['dados']['IdDevicePerfil'];
		}elseif($lin2['IdDevicePerfil'] != 0){
			$IdDevicePerfil = $lin2['IdDevicePerfil'];
		}

		if($result != false && $this->dataPOST['dados']['IdDevicePerfil'] > 0 || $lin2['IdDevicePerfil'] != 0){
			$obj = new Sql();
			$sql = $obj->delete('DeviceMonitor');
			$result = $sql->where(array(
					'IdLoja' => $this->dataSession['IdLoja'],
					'IdDevice' => $this->id,
					'IdDevicePerfil' => $IdDevicePerfil
			))->prepareObject()
			->execute();
			//echo $sql->_sql;die;
		}

		if($result != false && isset($this->dataPOST['dados']) || $this->id){

			$obj = new Sql();
			$sql = $obj->delete($this->table);
			$result = $sql->where(array("IdLoja" => $this->dataSession['IdLoja'], "IdDevice" => $this->id))
			->prepareObject()
			->execute();
		}

		if($result){
			$this->_commit();
			if($_POST['pageTipo'] != 'listar'){
				$dataUrl['Tipo'] =  7;
				$dataUrl['IdDevice'] = $this->id;
				$this->_redirect('../../cadastro_device.php', $dataUrl);
			}else{
				echo 7;
			}

		}else{
			$this->_rallback();
			if($_POST['pageTipo'] != 'listar'){
				$dataUrl['Tipo'] =  6;
				$dataUrl['IdDevice'] = $this->id;
				$this->_redirect('../../cadastro_device.php', $dataUrl);
			}else{
				echo 6;
			}
			
		}
	}

	protected function arrayDiff($dataWhere, $dataForm)
	{
		$dataForm = array_filter($dataForm);
		
		$obj = new Sql();
		$sql = $obj->select();
		$lin = $sql->from($this->table)
		->colunas(array_keys($dataForm))->where($dataWhere)
		->prepareObject()
		->execute();
		
		if($lin != null){
			$lin = array_filter($lin);
			if(array_diff_assoc($dataForm, $lin)){
				return array_diff_assoc($dataForm, $lin);
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
	//print_r($_POST);die;
	//echo $_POST['action'];
	$obj = new Device($_POST, $_SESSION);
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