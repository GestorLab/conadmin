<?
	if($Dados[MotivoRejeicao] != '' && $Dados[MotivoRejeicao] != '00'){
		$log = date("d/m/Y H:i:s")." [$local_Login] - [OCORRÊNCIA] ".$MotivoRejeicao[$Dados[MotivoRejeicao]]." -> ".$MotivoRejeicao[$Dados[MotivoRejeicao]."_".$Dados[MotivoRejeicao2]]." (LJ$linContaReceber[IdLoja] CR$linContaReceber[IdContaReceber])\n".$log;
	}
?>
