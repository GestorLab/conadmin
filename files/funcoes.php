<?
	@include("funcoes_personalizado.php");
	
	/* VARIÁVEIS */

	/* ARRAY COM AS VERSÕES DOS BROWSER QUE SÃO PERMITIDO NO SISTEMA */
	$versao_browser = array(
		"GC" => array(
			"img/estrutura_sistema/ico_chrome.png",
			"Google Chrome 27.0x",
			"Google Chrome 28.0x"
		),
		"MF" => array(
			"img/estrutura_sistema/ico_mozilla.png",
			"Mozilla Firefox 21.0x",
			"Mozilla Firefox 22.0x"
		),
		"IE" => array(
			"img/estrutura_sistema/ico_ie.png",
			"Internet Explorer 9.0x",
			"Internet Explorer 10.0x"
		),
		"OP" => array(
			"img/estrutura_sistema/ico_opera.png",
			"Opera 12.1x",
			"Opera 15.0x"
		),
		"AS" => array(
			"img/estrutura_sistema/ico_safari.png",
			"Apple Safari 5.0x",
			"Apple Safari 5.1x"
		)
	);
	
	/* ARRAY COM ARQUIVOS PERMITIDOS DE UPLOAD: TK, OS, PE */
	$extensao_anexo = array(
		"jpg",
		"jpeg",
		"png",
		"tif",
		"avi",
		"3gp",
		"flv",
		"mp4",
		"gif",
		"rtf",
		"doc",
		"docx",
		"xls",
		"xlsx",
		"rar",
		"zip",
		"html",
		"htm",
		"ret",
		"dat",
		"txt",
		"xml",
		"pdf",
		"pds"
	);
	
	/* ################################################### */

	function LogAcessoAtualiza(){
		
		global $con;

		$sql = "update LogAcesso set DataUltimaAtualizacao=concat(curdate(),' ',curtime()) where IdLogAcesso=".$_SESSION["IdLogAcesso"];
		@mysql_query($sql,$con);

		$TempoAtualizacao = SegHora(getParametroSistema(108,1)*2);

		$sql = "update LogAcesso set  Fechada='1' where (DataUltimaAtualizacao < ADDTIME(concat(curdate(),' ',curtime()), '-$TempoAtualizacao') and Fechada = 2) or DataUltimaAtualizacao is null";
		@mysql_query($sql,$con);

		$sql = "select
					Fechada
				from
					LogAcesso
				where
					IdLogAcesso = ".$_SESSION["IdLogAcesso"];
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);

		if($lin[Fechada] == 1){
			header("Location: ../../rotinas/sair.php");
		}
	}

	function horaSegundo($Hora){
		$Hora = explode(":",$Hora);

		$Hora[0] = $Hora[0] * 60 * 60;
		$Hora[1] = $Hora[1] * 60;

		return $Hora[0] + $Hora[1] + $Hora[2];
	}

	function SegHora($Segundo){

		$Hora	= '00';
		$Min	= '00';
		$Seg	= '00';

		if($Segundo / 60 / 60 >= 1){
			$Hora = (int)($Segundo/60/60);
			if($Hora < 10){
				$Hora = '0'.$Hora;
			}
		}

		if((($Segundo - ($Hora * 60 * 60))/60) >=1){
			$Min = (int)(($Segundo - ($Hora * 60 * 60))/60);
			if($Min < 10){
				$Min = '0'.$Min;
			}
		}

		if(($Segundo - ($Hora * 60 * 60) - ($Min * 60)) >=1){
			$Seg = (int)$Segundo - ($Hora * 60 * 60) - ($Min * 60);
			if($Seg < 10){
				$Seg = '0'.$Seg;
			}
		}

		return $Hora.":".$Min.":".$Seg;
	}
	
	//$data  formato(0000-00-00 00:00:00)
	//$data1 	formato(0000-00-00 00:00:00)
	//retorna valor em segundos
	function SubHora($data1,$data2,$tipo){

		$data1 = str_replace(array(":","/","-")," ",$data1);
		$data2 = str_replace(array(":","/","-")," ",$data2);
		
		list ($diamaior, $mesmaior, $anomaior, $horamaior, $minutomaior, $segundomaior) = explode(" ",$data1);
		list ($diamenor, $mesmenor, $anomenor, $horamenor, $minutomenor, $segundomenor) = explode(" ",$data2);
		
		$segundos =	mktime($horamaior,$minutomaior,$segundomaior,$mesmaior,$diamaior, $anomaior)-mktime($horamenor,$minutomenor,$segundomenor,$mesmenor,$diamenor, $anomenor); 		
		
		switch($tipo){			
			case "s": // Segundo				
				$diferenca = $segundos;				
				break;			
			case "m": // Minuto				
				$diferenca = $segundos/60;				
				break;			
			case "H": // Hora				
				$diferenca = $segundos/3600;				
				break;			
			case "h": // Hora Arredondada				
				$diferenca = round($segundos/3600);				
				break;			
			case "D": // Dia				
				$diferenca = $segundos/86400;				
				break;			
			case "d": // Dia Arredondado				
				$diferenca = round($segundos/86400);				
				break;		
		}		

		return $diferenca;	
	}
	
	function getParametroSistema($IdGrupoParametroSistema,$IdParametroSistema){

		global $con;

		// Regras fixas

		# Título do Sistema
		if($IdGrupoParametroSistema == 4 && $IdParametroSistema == 1){
			$Complemento = " - ConAdmin - Sistema Administrativo de Qualidade";
		}
		
		$sql	= "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=$IdGrupoParametroSistema and IdParametroSistema=$IdParametroSistema";
		$res	= @mysql_query($sql,$con);
		if($lin	= @mysql_fetch_array($res)){
			if(($IdGrupoParametroSistema == '4' && ($IdParametroSistema == 1 || $IdParametroSistema == 3)) || ($IdGrupoParametroSistema == 95 && $IdParametroSistema == 3)){
				$lin[ValorParametroSistema] = html_codes($lin[ValorParametroSistema],'encode');
			}
			
			return $lin[ValorParametroSistema].$Complemento;
		}else{
			$Erro = "Erro - Parametro do Sistema $IdGrupoParametroSistema,$IdParametroSistema não foi encontrado. SCRIPT_NAME: ".$_SERVER['SCRIPT_NAME'];

			$sql = "update `ParametroSistema` set  ValorParametroSistema='$Erro' where IdGrupoParametroSistema='135' and IdParametroSistema='1'";
			@mysql_query($sql,$con);

			echo $Erro;
		}
	}
	function lojas_permissao_login($Login){
		global $con;
		
		$i=0;
		$loja[$i] = NULL;

		if($Login == 'root'){		
			$sql	= "select
							IdLoja
						from
							Loja
						where
							Loja.IdStatus = 1";
			$res	= mysql_query($sql,$con);
			while($lin = mysql_fetch_array($res)){
				$loja[$i] = $lin[IdLoja];
				$i++;	
			}
		}
		
		$sql	= "select
						GrupoPermissaoSubOperacao.IdLoja
					from
					    UsuarioGrupoPermissao,
					    GrupoPermissaoSubOperacao
					where
					    UsuarioGrupoPermissao.Login = '$Login' and
					    UsuarioGrupoPermissao.IdGrupoPermissao = GrupoPermissaoSubOperacao.IdGrupoPermissao
					group by IdLoja";
		$res	= mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			$loja[$i] = $lin[IdLoja];
			$i++;	
		}
		
		$sql	= "select
					    UsuarioSubOperacao.IdLoja
					from
					    UsuarioSubOperacao
					where
					    UsuarioSubOperacao.Login = '$Login'
					group by IdLoja";
		$res	= mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			if(!in_array($lin[IdLoja], $loja)){
				$loja[$i] = $lin[IdLoja];
				$i++;
			}
		}
		
		$retorno = "";
		for($ii=0;$ii<$i;$ii++){
			$retorno .= $loja[$ii];
			if($ii+1<$i){
				$retorno .= ",";
			}
		}
		return $retorno;
	}

	function formatText($str,$acao){
		if($acao == ""){
			$acao	=	getParametroSistema(4,4);
		}
		# $acao
		# MA   - Maiúscula
		# MI   - Minúscula
		# NULL - Default

		$str = str_replace("'","\'",$str);
		$str = str_replace('"','\"',$str);
		
		switch ($acao){
			case 'MA':
				$from = 'àáãâéêíóõôúüç';    
				$to   = 'ÁÁÃÂÉÊÍÓÕÔÚÜÇ';  
				$str  =	strtr($str, $from, $to);
				
				$str = trim(strtoupper($str));
				break;

			case 'MI':
				$str = trim(strtolower($str));
				break;
		}
		return trim($str);
	}

	function permissaoSubOperacao($IdModulo, $IdOperacao, $IdSubOperacao){

		global $con;
		global $bloqueio;
		
		$Login 	= $_SESSION["Login"];		
		$IdLoja = $_SESSION["IdLoja"];

		if($Login == ''){	
			$IdLoja = $_SESSION["IdLojaHD"];
			$Login  = $_SESSION["LoginHD"];
		}
		//verifica se a permissao existe no grupo ADM Master
		$sqlPermissoes="select 
							IdSubOperacao 
						from
							GrupoPermissaoSubOperacao 
						where
							IdLoja = '$IdLoja' and
							IdGrupoPermissao = 1 and
							IdModulo = '$IdModulo' and
							IdOperacao = '$IdOperacao' and
							IdSubOperacao = '$IdSubOperacao'";
		$resPermissoes = mysql_query($sqlPermissoes,$con);
		if(@mysql_num_rows($resPermissoes) == 0){
			$sql = "insert into 
						GrupoPermissaoSubOperacao
					set 
						IdLoja 				= $IdLoja,
						IdGrupoPermissao 	= 1,
						IdModulo 			= $IdModulo,
						IdOperacao 			= $IdOperacao,
						IdSubOperacao 		= '$IdSubOperacao',
						LoginCriacao 		= 'automatico',
						DataCriacao 		= now()";
						mysql_query($sql,$con);
		}

		// Verifica se o cliente pode cadastrar contratos
		if($IdModulo == 1 && $IdOperacao == 2 && $IdSubOperacao == "I" && ContratoFree() == false){
			return false;
		}
		
		if($Login == 'root' || $bloqueio == 'disabled'){
			return true;
		}
		
		$bloqueio_temp	= true;
		
		$sql = "select
					    count(*) quant
					from 
					    UsuarioSubOperacao
					where
						Login='$Login' and
				    	IdLoja=$IdLoja and
					    IdModulo=$IdModulo and
					    IdOperacao=$IdOperacao and
					    IdSubOperacao='$IdSubOperacao'";
		$res = mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		if($lin[quant] > 0){	
			$bloqueio_temp = false;	
		}
		
		$sql = "select
				count(*) quant
			from
			    UsuarioGrupoPermissao,
			    GrupoPermissaoSubOperacao
			where
			    UsuarioGrupoPermissao.Login='$Login' and
			    UsuarioGrupoPermissao.IdGrupoPermissao = GrupoPermissaoSubOperacao.IdGrupoPermissao and
		    	GrupoPermissaoSubOperacao.IdLoja = $IdLoja and
			    GrupoPermissaoSubOperacao.IdModulo = $IdModulo and
			    GrupoPermissaoSubOperacao.IdOperacao = $IdOperacao and
			    GrupoPermissaoSubOperacao.IdSubOperacao = '$IdSubOperacao'";
		$res = mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		if($lin[quant] > 0){	
			$bloqueio_temp = false;	
		}

//		logAcao($Acao);
		 
		if($bloqueio_temp == true){
			return false;
		}else{
			return true;
		}
	}

	function logAcao($Acao){
		echo $_SESSION[IdLogAcesso];
	}

	function dataConv($data,$formatEntrada,$formatSaida){
		if($data=='' || $data=='NULL'){
			return "";
		}
	
		switch ($formatEntrada){
			case 'd/m/Y':
				$dia	=	substr($data,0,2);
				$mes	=	substr($data,3,2);
				$ano	=	substr($data,6,4);
				break;
			case 'Y-m-d':
				$dia	=	substr($data,8,2);
				$mes	=	substr($data,5,2);
				$ano	=	substr($data,0,4);
				break;
			case 'YmdHis':
				$dia	=	substr($data,6,2);
				$mes	=	substr($data,4,2);
				$ano	=	substr($data,0,4);
				$hora	=	substr($data,8,2).":".substr($data,10,2).":".substr($data,12,2);
				break;
			case 'Ymd':
				$dia	=	substr($data,6,2);
				$mes	=	substr($data,4,2);
				$ano	=	substr($data,0,4);
				break;
			case 'Ym':
				$mes	=	substr($data,4,2);
				$ano	=	substr($data,0,4);
				break;
			case 'd/m/Y H:i:s':
				$dia	=	substr($data,0,2);
				$mes	=	substr($data,3,2);
				$ano	=	substr($data,6,4);
				$hora	=	endArray(explode(" ",$data));
				break;
			case 'Y-m-d H:i:s':
				$dia	=	substr($data,8,2);
				$mes	=	substr($data,5,2);
				$ano	=	substr($data,0,4);
				$hora	=	endArray(explode(" ",$data));
				$hr		=	substr($hora,0,2);
				$min	=	substr($hora,3,2);
				$seg	=	substr($hora,6,2);
				break;
			case 'm/Y':
				$mes	=	substr($data,0,2);
				$ano	=	substr($data,3,4);
				break;
			case 'Y-m':
				$ano	=	substr($data,0,4);
				$mes	=	substr($data,5,2);
				break;
			case 'm-Y':
				$ano	=	substr($data,3,4);
				$mes	=	substr($data,0,2);
				break;
		}
		switch ($formatSaida){
			case 'd/m/y':
				$ano	=	substr($ano,2,2);
				return $dia."/".$mes."/".$ano;
				break;
			case 'dmy':
				$ano	=	substr($ano,2,2);
				return $dia.$mes.$ano;
				break;
			case 'dmY':
				return $dia.$mes.$ano;
				break;
			case 'd/m/Y':
				return $dia."/".$mes."/".$ano;
				break;
			case 'Y-m-d':
				return $ano."-".$mes."-".$dia;
				break;
			case 'Ymd':
				return $ano.$mes.$dia;
				break;	
			case 'YmdHis':
				return $ano.$mes.$dia.$hr.$min.$seg;
				break;
			case 'Ym':
				return $ano.$mes;
				break;	
			case 'Y-m':
				return $ano.'-'.$mes;
				break;	
			case 'm-Y':
				return $mes.'-'.$ano;
				break;
			case 'm/Y':
				return $mes.'/'.$ano;
				break;
			case 'ym':
				return substr($ano,2,2).$mes;
				break;
			case 'd/m/Y H:i:s':
				return $dia."/".$mes."/".$ano." ".$hora;
				break;
			case 'd.m.Y H:i':
				$ano	=	substr($ano,2,2);
				return $dia.".".$mes.".".$ano." ".$hr.":".$min;
				break;
			case 'd/m/Y H:i':
				$hr		=	substr($hora,0,2);
				$min	=	substr($hora,3,2);
				return $dia."/".$mes."/".$ano." ".$hr.":".$min;
				break;
			case 'd/m/y':
				$ano	=	substr($ano,0,2);
				return $dia."/".$mes."/".$ano;
				break;
			case 'd/m':
				return $dia."/".$mes;
				break;
			case 'd/m/y H:i':
				$ano	=	substr($ano,0,2);
				$hr		=	substr($hora,0,2);
				$min	=	substr($hora,3,2);
				return $dia."/".$mes."/".$ano." ".$hr.":".$min;
				break;
			case 'Y-m-d H:i:s':
				return $ano."-".$mes."-".$dia." ".$hora;
				break;
			case 'DDMMAA':
				return $dia.$mes.substr($ano,2,4);	
				break;		
			case 'AAMM':
				return substr($ano,2,4).$mes;	
				break;
		}
	}
	function getCodigoInterno($IdGrupoCodigoInterno,$IdCodigoInterno){
		global $con;
		
		$IdLoja = $_SESSION["IdLoja"];
		
		if($IdLoja == ''){
			global $IdLoja;
		}
		
		$sql	= "select 
						ValorCodigoInterno 
				   from 
				   		CodigoInterno 
				   where 
				   		IdGrupoCodigoInterno=$IdGrupoCodigoInterno and 
				   		IdLoja = $IdLoja and
						IdCodigoInterno=$IdCodigoInterno;";
		$res	= mysql_query($sql,$con);
		if($lin	= @mysql_fetch_array($res)){
			return $lin[ValorCodigoInterno];
		}else{
			$Erro = "Erro - Codigo Interno ($IdLoja)$IdGrupoCodigoInterno,$IdCodigoInterno não foi encontrado. SCRIPT_NAME: ".$_SERVER['SCRIPT_NAME'];

			$sql = "update CodigoInterno set ValorCodigoInterno='$Erro' where IdGrupoCodigoInterno='31' and IdLoja='$IdLoja' and IdCodigoInterno='1'";
			mysql_query($sql,$con);

			echo $Erro;

		}
	}
	function compara($campo1,$campo2,$returnTrue, $returnFalse){
		if($campo1==$campo2){
			return $returnTrue;
		}else{
			return $returnFalse;
		}		
	}
	function visualizarNumber($numero){
		if($numero < 10){
			$numero = "0".$numero;
		}
		return $numero;
	}
	function url_string_xsl($str,$acao,$lpa = true){
		$acao = strtoupper($acao);
		
		if($lpa){
			$str = substituir_string($str);
		} else{
			$DataBrowser = getDataBrowser();
			$str = urldecode($str);
			$temp = utf8_decode($str);
			
			if($acao == "URL"){
				if(preg_match("([áàãâéêíôõóüúçÁÀÃÂÉÊÍÔÓÕÜÚÇ])", $temp)){
					$str = $temp;
				}
			} elseif($DataBrowser["abbreviation"] != "MF" && $DataBrowser["abbreviation"] != "OP"){
				$str = utf8_encode($str);
			}
		}
		
		if($acao == "CONVERT"){
			$str_tmp = preg_replace("/&[\w\W]*/", null, $str);

			$str = str_replace($str_tmp, null, $str);
			parse_str($str, $query);
			
			foreach($query as $key => $value){
				$query[$key] = $value;
			}

			if($lpa == false){
				$str = $str_tmp."&".http_build_query($query);
			} else{
				$str = $str_tmp.http_build_query($query);
			}
			
			if(!strpos($_SERVER["HTTP_USER_AGENT"], "Navigator")){
				$str = str_replace("&", "&amp;", $str);
			}
			
#			$str = str_replace("%", "[por100tagem]", $str);
			$str = str_replace("<", "&lt;", $str);
			$str = str_replace(">", "&gt;", $str);
		} else{			
			$str = str_replace("[por100tagem]","%", $str);
		}
		
		return $str;
	}
	function menu_acesso_rapido($IdCodigoInterno){

		$dadosMenu = getCodigoInterno(10,$IdCodigoInterno);
		$dadosMenu = explode("\n",$dadosMenu);
		
		$i=0;
		while($dadosMenu[$i]){
			$dadosMenuTemp = explode("^", $dadosMenu[$i]);
			$OpcMenu[$dadosMenuTemp[0]] = $dadosMenuTemp;
			$i++;
		}	
		
		ksort($OpcMenu);
		reset($OpcMenu);
		$menu  = "<ul>";
		$i=0;
		while (list($chave, $valor) = each($OpcMenu)) {
			if($valor[3] == 'NOVO'){	$valor[3] = "[+]"; }
			if($i%2 == 0){				$class = 1;	}else{	$class = 2; }
			$menu .= "<li class='color$class'><a href='$valor[2]' id='$valor[5]'>$valor[1]</a><a style='margin-left: 3px' href='$valor[4]' id='$valor[5]Novo'>$valor[3]</a></li>";
			$i++;
		}
		
		$menu .= "</ul>";
		
		return $menu;
	}
	function menu_acesso_rapido_help_desk($IdParametroSistema){

		$dadosMenu = getParametroSistema(145,$IdParametroSistema);
		$dadosMenu = explode("\n",$dadosMenu);
		
		$i=0;
		while($dadosMenu[$i]){
			$dadosMenuTemp = explode("^", $dadosMenu[$i]);
			$OpcMenu[$dadosMenuTemp[0]] = $dadosMenuTemp;
			$i++;
		}	
		
		ksort($OpcMenu);
		reset($OpcMenu);
		$menu  = "<ul>";
		$i=0;
		while (list($chave, $valor) = each($OpcMenu)) {
			if($valor[3] == 'NOVO'){	$valor[3] = "[+]"; }
			if($i%2 == 0){				$class = 1;	}else{	$class = 2; }
			$menu .= "<li class='color$class'><a href='$valor[2]' id='$valor[5]'>$valor[1]</a><a style='margin-left: 3px' href='$valor[4]' id='$valor[5]Novo'>$valor[3]</a></li>";
			$i++;
		}
		
		$menu .= "</ul>";
		
		return $menu;
	}
	function formata_double($campo){
		
		$quant = explode(".",$campo);
		//echo $quant[1];
		if($quant[1] != NULL){
			if(strlen($quant[1]) == 1){
				$campo = $campo . "0";
			}
		}else if($quant[1] == NULL || $quant[1] == ''){
			$campo = $campo . ".00";
		}
		return $campo;
	}
	function formTexto($texto){
		return str_replace("\n", "<BR/>", $texto);
	}
	function executaRotina($UrlRotina){
		if(file_exists($UrlRotina)){
			$UrlAtual 	= getenv('HTTP_REFERER');
			$UrlAtual 	= substr($UrlAtual,0,strrpos($UrlAtual, "/")+1);
			$UrlRotina 	= $UrlAtual.$UrlRotina;
			file($UrlRotina);
		}
	}
	function executaRotinaCodigoInterno($IdGrupoCodigoInterno){
		global $con;
		
		$sql	=	"select UrlRotinaAlteracao from GrupoCodigoInterno where IdGrupoCodigoInterno=$IdGrupoCodigoInterno";
		$res	=	mysql_query($sql,$con);
		$lin	=	mysql_fetch_array($res);
		
		if($lin[UrlRotinaAlteracao]!=''){
			executaRotina($lin[UrlRotinaAlteracao]);
		}
	}
	function subOperacao_permissao_login($Login, $IdLoja, $IdModulo, $IdOperacao){
		global $con;
		
		$i=0;
		$IdSubOperacao[$i] = NULL;
		
		$sql	= "select
				    IdSubOperacao
				  from
				    UsuarioSubOperacao
				  where
				    UsuarioSubOperacao.Login='$Login' and
				    UsuarioSubOperacao.IdLoja=$IdLoja and
				    UsuarioSubOperacao.IdModulo=$IdModulo and
				    UsuarioSubOperacao.IdOperacao=$IdOperacao";
		$res	= mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			$IdSubOperacao[$i] = $lin[IdSubOperacao];
			$i++;	
		}
		
		$sql	= "select
				    GrupoPermissaoSubOperacao.IdSubOperacao
				  from
				    UsuarioGrupoPermissao,
				    GrupoPermissaoSubOperacao
				  where
				    UsuarioGrupoPermissao.Login='$Login' and
				    GrupoPermissaoSubOperacao.IdGrupoPermissao = UsuarioGrupoPermissao.IdGrupoPermissao and
				    GrupoPermissaoSubOperacao.IdLoja=$IdLoja and
				    GrupoPermissaoSubOperacao.IdModulo=$IdModulo and
				    GrupoPermissaoSubOperacao.IdOperacao=$IdOperacao";
		$res	= mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			if(!in_array($lin[IdSubOperacao], $IdSubOperacao)){			
				$IdSubOperacao[$i] = $lin[IdSubOperacao];
				$i++;
			}
		}
		
		$retorno = "";
		for($ii=0;$ii<$i;$ii++){
			$retorno .= $IdSubOperacao[$ii];
			if($ii+1<$i){
				$retorno .= ",";
			}
		}
		return $retorno;
	}
	
	function nDiasIntervalo($DataInicial,$DataFinal){
		if($DataInicial == '' || $DataFinal == ''){
			return 0;
		}
        $DataInicial 	= explode("-",$DataInicial);
        $DataInicialAno = (int)$DataInicial[0];
        $DataInicialMes = (int)$DataInicial[1];
        $DataInicialDia = (int)$DataInicial[2];

        $DataFinal 		= explode("-",$DataFinal);        
        $DataFinalAno 	= (int)$DataFinal[0];
        $DataFinalMes 	= (int)$DataFinal[1];
        $DataFinalDia 	= (int)$DataFinal[2];
        
        $data_inicial 	= mktime(0,0,0,$DataInicialMes,$DataInicialDia,$DataInicialAno);
        $data_final 	= mktime(0,0,0,$DataFinalMes,$DataFinalDia,$DataFinalAno);
        
        $tempo_unix 	= $data_final - $data_inicial; // Acha a diferença em segundos
        $periodo 		= round($tempo_unix /(24*60*60)); // Converte em dias 24(horas) 60(minutos) 60(segundos)
        $periodo++;
        
        return $periodo;
    }
	
	function IntervaloMKTime($DataFinal,$DataInicial){
		if($DataInicial == '' || $DataFinal == ''){
			return 0;
		}
        $DataInicialAno	= substr($DataInicial,0,4);
        $DataInicialMes	= substr($DataInicial,5,2);
        $DataInicialDia	= substr($DataInicial,8,2);
        $DataInicialHor	= substr($DataInicial,11,2);
        $DataInicialMin	= substr($DataInicial,14,2);
        $DataInicialSeg	= substr($DataInicial,17,2);

        $DataFinalAno	= substr($DataFinal,0,4);
        $DataFinalMes	= substr($DataFinal,5,2);
        $DataFinalDia	= substr($DataFinal,8,2);
        $DataFinalHor	= substr($DataFinal,11,2);
        $DataFinalMin	= substr($DataFinal,14,2);
        $DataFinalSeg	= substr($DataFinal,17,2);
		
        $data_inicial 	= mktime($DataInicialHor,$DataInicialMin,$DataInicialSeg,$DataInicialMes,$DataInicialDia,$DataInicialAno);
        $data_final 	= mktime($DataFinalHor,$DataFinalMin,$DataFinalSeg,$DataFinalMes,$DataFinalDia,$DataFinalAno);
        
        $periodo[s] 	= $data_final - $data_inicial; // Acha a diferença em segundos
        $periodo[i] 	= (int)($periodo[s]/60); // Acha a diferença em minutos
        $periodo[H] 	= (int)($periodo[s]/60/60); // Acha a diferença em horas
        $periodo[d] 	= (int)($periodo[s]/60/60/24); // Acha a diferença em dias
		
        return $periodo;
    }


	function ultimoDiaMes($Mes, $Ano){
		$Dia = 31;

		// Acha a quantidade de dias que o MêsRefeencia possui, verificando a existencia dos mesmo
		while(checkdate($Mes, $Dia, $Ano) == false){
			$Dia--;
		}

		return $Dia;
	}

	function incrementaMesReferencia($MesReferencia, $N){

		// MM-AAAA
		$AnoReferenciaTemp = (int)substr($MesReferencia, 3, 4);
		$MesReferenciaTemp = (int)substr($MesReferencia, 0, 2);
		
		while($N != 0){
			if($N > 0){
				if($MesReferenciaTemp == 12){
					$MesReferenciaTemp = 1;
					$AnoReferenciaTemp++;
				}else{
					$MesReferenciaTemp++;
				}
				$N--;
			}else{
				if($MesReferenciaTemp == 1){
					$MesReferenciaTemp = 12;
					$AnoReferenciaTemp--;
				}else{
					$MesReferenciaTemp--;
				}
				$N++;
			}			
		}

		if($MesReferenciaTemp <= 9){
			$MesReferenciaTemp = "0".$MesReferenciaTemp;
		}

		return $MesReferenciaTemp."/".$AnoReferenciaTemp;
	}

	function incrementaData($Data,$Incremento){

		if($Data == ''){	return false;	}

		// Formato da Data de Entrada YYYY-mm-ddd
		$DataAnoTemp	= (int)substr($Data,0,4);
		$DataMesTemp	= (int)substr($Data,5,2);
		$DataDiaTemp	= (int)substr($Data,8,2);

		$DataDiaTemp	+= $Incremento;

		if($DataDiaTemp > 0){
			$IncrementoTemp = 0;

			while(!checkdate($DataMesTemp, $DataDiaTemp, $DataAnoTemp)){
				while(!checkdate($DataMesTemp, $DataDiaTemp, $DataAnoTemp)){
					$DataDiaTemp--;
					$IncrementoTemp++;
				}
				if($IncrementoTemp > 0){
					$DataDiaTemp = $IncrementoTemp;
					$IncrementoTemp = 0;

					if($DataMesTemp == 12){	
						$DataMesTemp = 1;
						$DataAnoTemp++;
					}else{
						$DataMesTemp++;
					}

				}
			}
		}else{
/*			if($DataDiaTemp <= 0){

				$DataDiaTemp += 31;
				$DataMesTemp--;
			
				if($DataMesTemp <= 0){

					$DataMesTemp += 12;
					$DataAnoTemp--;

				}
			}*/

			while(!checkdate($DataMesTemp, $DataDiaTemp, $DataAnoTemp)){
				$DataDiaTemp2 = 31;

				if($DataMesTemp == 1){
					$DataMesTemp = 12;
					$DataAnoTemp--;
				}else{
					$DataMesTemp--;
				}
				while(!checkdate($DataMesTemp, $DataDiaTemp2, $DataAnoTemp)){
					$DataDiaTemp2--;
				}
				$DataDiaTemp += $DataDiaTemp2;
			}
		}

		if($DataMesTemp < 10){	$DataMesTemp = "0".$DataMesTemp;	}
		if($DataDiaTemp < 10){	$DataDiaTemp = "0".$DataDiaTemp;	}

		return $DataAnoTemp."-".$DataMesTemp."-".$DataDiaTemp;
	}
	function Numberformat($number){
		$number	=	str_replace(".", ",", $number);
		return $number;
	}
	function visualizarBuscaPessoa($nome){
		switch($nome){
			case 'Nome':
				return 'Nome Pessoa';
				break;
			case 'RazaoSocial':
				return 'Razão Social';
				break;
		}
	}
	function substituir_string($str){
		$str = str_replace("á", "a", $str);
		$str = str_replace("à", "a", $str);
		$str = str_replace("ã", "a", $str);
		$str = str_replace("â", "a", $str);
		$str = str_replace("é", "e", $str);
		$str = str_replace("ê", "e", $str);
		$str = str_replace("í", "i", $str);
		$str = str_replace("ô", "o", $str);
		$str = str_replace("õ", "o", $str);
		$str = str_replace("ó", "o", $str);
		$str = str_replace("ü", "u", $str);
		$str = str_replace("ú", "u", $str);
		
		$str = str_replace("Á", "A", $str);
		$str = str_replace("À", "A", $str);
		$str = str_replace("Ã", "A", $str);
		$str = str_replace("Â", "A", $str);
		$str = str_replace("É", "E", $str);
		$str = str_replace("Ê", "E", $str);
		$str = str_replace("Í", "I", $str);
		$str = str_replace("Ô", "O", $str);
		$str = str_replace("Ó", "O", $str);
		$str = str_replace("Õ", "O", $str);
		$str = str_replace("Ü", "U", $str);
		$str = str_replace("Ú", "U", $str);
		
		
		$str = str_replace("Ç", "C", $str);
		$str = str_replace("ç", "c", $str);
		
		
		$str = str_replace("+", "", $str);
		
		return $str;
	}
	
	function LogAcesso(){
		global $con;
		$browser = getDataBrowser();
		$sql = "select
					IdParametroSistema IdNavegador
				from
					ParametroSistema
				where
					IdGrupoParametroSistema = 89 and
					ValorParametroSistema = '".$browser[name]."'";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);

		if($lin[IdNavegador] == ''){
			$sqlVerifica = "select
								max(IdParametroSistema) IdParametroSistema
							from
								ParametroSistema
							where
								IdGrupoParametroSistema = 89";
			$resVerifica = mysql_query($sqlVerifica,$con);
			$linVerifica = mysql_fetch_array($resVerifica);
			
			if($linVerifica[IdParametroSistema] != NULL){
				$IdNavegador = $linVerifica[IdParametroSistema] + 1;
			} else{
				$IdNavegador = 1;
			}
			
			$sql = "insert into 
						ParametroSistema 
					set
						IdGrupoParametroSistema		= '89', 
						IdParametroSistema			= '".$IdNavegador."', 
						DescricaoParametroSistema	= 'Navegador (".$browser[name].")', 
						ValorParametroSistema		= '".$browser[name]."', 
						LoginCriacao				= '".$login."', 
						DataCriacao					= concat(curdate(),' ',curtime());";
			mysql_query($sql,$con);
		} else{
			$IdNavegador = $lin[IdNavegador];
		}

		$sql = "select
					AcessoSimultaneo
				from
					Usuario
				where
					Login = '".$_SESSION["Login"]."' and
					AcessoSimultaneo = 2";
		$res = mysql_query($sql,$con);
		
		if($lin = @mysql_fetch_array($res)){
			$sql = "update LogAcesso set Fechada='1' where Login='".$_SESSION["Login"]."'";
			@mysql_query($sql,$con);
		}

 		$sql = "insert into 
					LogAcesso 
				set
					IdLoja		= '".$_SESSION["IdLoja"]."', 
					IdNavegador	= '".$IdNavegador."',
					Login		= '".$_SESSION["Login"]."', 
					IP			= '".$_SERVER["REMOTE_ADDR"]."', 
					DataHora	= concat(curdate(), ' ', curtime());";
		@mysql_query($sql,$con);

		$sql = "select
					max(IdLogAcesso) IdLogAcesso
				from
					LogAcesso";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		return $lin[IdLogAcesso];
	}
	
	function sem_quebra_string($string){
		$temp	=	substr($string, 0, strrpos($string, " "));
		
		if($temp != '')		return $temp;
		else				return $string;
	}

	function mes($mes){

		$mes = (int)$mes;

		$Months[1]	= "Janeiro";
		$Months[2]	= "Fevereiro";
		$Months[3]	= "Março";
		$Months[4]	= "Abril";
		$Months[5]	= "Maio";
		$Months[6]	= "Junho";
		$Months[7]	= "Julho";
		$Months[8]	= "Agosto";
		$Months[9]	= "Setembro";
		$Months[10]	= "Outubro";
		$Months[11]	= "Novembro";
		$Months[12]	= "Dezembro";
		
		return $Months[$mes];
	}

	function diadoano($Ano,$Mes,$Dia){
		$nDias = 0;

		for($mesTemp = 1; $mesTemp < $Mes; $mesTemp++){
			$diasMes = 31;			
			
			# Acha a quantidade de dias que o Mês possui, verificando a existencia dos mesmo 
			while(checkdate($mesTemp, $diasMes, $Ano) == false){
				$diasMes--;
			}
			
			$nDias += $diasMes;					
		}
		$nDias += $Dia;	
		
		return $nDias;
		
	}

	function diadoanoReverso($Ano,$diadoano){

		$Mes = 1;

		while($diadoano > 0){

			$UltimoDiaMes	= ultimoDiaMes($Mes, $Ano);

			if($diadoano > $UltimoDiaMes){
				$Mes++;
				$diadoano -= $UltimoDiaMes;
			}else{
				$Dia = $diadoano;
				break;
			}
		}
		$Mes = str_pad($Mes, 2, "0", STR_PAD_LEFT);
		$Dia = str_pad($Dia, 2, "0", STR_PAD_LEFT);

		return "$Ano-$Mes-$Dia";
	}

	# extenso( 12345678.90, "real", "reais", "centavo", "centavos" ) ;
	function extenso( $valor, $moedaSing, $moedaPlur, $centSing, $centPlur ) {

	   $centenas = array( 0,
		   array(0, "cento",        "cem"),
		   array(0, "duzentos",     "duzentos"),
		   array(0, "trezentos",    "trezentos"),
		   array(0, "quatrocentos", "quatrocentos"),
		   array(0, "quinhentos",   "quinhentos"),
		   array(0, "seiscentos",   "seiscentos"),
		   array(0, "setecentos",   "setecentos"),
		   array(0, "oitocentos",   "oitocentos"),
		   array(0, "novecentos",   "novecentos") ) ;

	   $dezenas = array( 0,
				"dez",
				"vinte",
				"trinta",
				"quarenta",
				"cinqüenta",
				"sessenta",
				"setenta",
				"oitenta",
				"noventa" ) ;

	   $unidades = array( 0,
				"um",
				"dois",
				"três",
				"quatro",
				"cinco",
				"seis",
				"sete",
				"oito",
				"nove" ) ;

	   $excecoes = array( 0,
				"onze",
				"doze",
				"treze",
				"quatorze",
				"quinze",
				"dezeseis",
				"dezesete",
				"dezoito",
				"dezenove" ) ;

	   $extensoes = array( 0,
		   array(0, "",       ""),
		   array(0, "mil",    "mil"),
		   array(0, "milhão", "milhões"),
		   array(0, "bilhão", "bilhões"),
		   array(0, "trilhão","trilhões") ) ;

	   $valorForm = trim( number_format($valor,2,".",",") ) ;

	   $inicio    = 0 ;

	   if ( $valor <= 0 ) {
		  return ( $valorExt ) ;
	   }

	   for ( $conta = 0; $conta <= strlen($valorForm)-1; $conta++ ) {
		  if ( strstr(",.",substr($valorForm, $conta, 1)) ) {
			 $partes[] = str_pad(substr($valorForm, $inicio, $conta-$inicio),3," ",STR_PAD_LEFT) ;
			 if ( substr($valorForm, $conta, 1 ) == "." ) {
				break ;
			 }
			 $inicio = $conta + 1 ;
		  }
	   }

	   $centavos = substr($valorForm, strlen($valorForm)-2, 2) ;

	   if ( !( count($partes) == 1 and intval($partes[0]) == 0 ) ) {
		  for ( $conta=0; $conta <= count($partes)-1; $conta++ ) {

			 $centena = intval(substr($partes[$conta], 0, 1)) ;
			 $dezena  = intval(substr($partes[$conta], 1, 1)) ;
			 $unidade = intval(substr($partes[$conta], 2, 1)) ;

			 if ( $centena > 0 ) {

				$valorExt .= $centenas[$centena][($dezena+$unidade>0 ? 1 : 2)] . ( $dezena+$unidade>0 ? " e " : "" ) ;
			 }

			 if ( $dezena > 0 ) {
				if ( $dezena>1 ) {
				   $valorExt .= $dezenas[$dezena] . ( $unidade>0 ? " e " : "" ) ;

				} elseif ( $dezena == 1 and $unidade == 0 ) {
				   $valorExt .= $dezenas[$dezena] ;

				} else {
				   $valorExt .= $excecoes[$unidade] ;
				}

			 }

			 if ( $unidade > 0 and $dezena != 1 ) {
				$valorExt .= $unidades[$unidade] ;
			 }

			 if ( intval($partes[$conta]) > 0 ) {
				$valorExt .= " " . $extensoes[(count($partes)-1)-$conta+1][(intval($partes[$conta])>1 ? 2 : 1)] ;
			 }

			 if ( (count($partes)-1) > $conta and intval($partes[$conta])>0 ) {
				$conta3 = 0 ;
				for ( $conta2 = $conta+1; $conta2 <= count($partes)-1; $conta2++ ) {
				   $conta3 += (intval($partes[$conta2])>0 ? 1 : 0) ;
				}

				if ( $conta3 == 1 and intval($centavos) == 0 ) {
				   $valorExt .= " e " ;
				} elseif ( $conta3>=1 ) {
				   $valorExt .= ", " ;
				}
			 }

		  }

		  if ( count($partes) == 1 and intval($partes[0]) == 1 ) {
			 $valorExt .= $moedaSing ;

		  } elseif ( count($partes)>=3 and ((intval($partes[count($partes)-1]) + intval($partes[count($partes)-2]))==0) ) {
			 $valorExt .= " de " + $moedaPlur ;

		  } else {
			 $valorExt = trim($valorExt) . " " . $moedaPlur ;
		  }

	   }

	   if ( intval($centavos) > 0 ) {

		  $valorExt .= (!empty($valorExt) ? " e " : "") ;

		  $dezena  = intval(substr($centavos, 0, 1)) ;
		  $unidade = intval(substr($centavos, 1, 1)) ;

		  if ( $dezena > 0 ) {
			 if ( $dezena>1 ) {
				$valorExt .= $dezenas[$dezena] . ( $unidade>0 ? " e " : "" ) ;

			 } elseif ( $dezena == 1 and $unidade == 0 ) {
				$valorExt .= $dezenas[$dezena] ;

			 } else {
				$valorExt .= $excecoes[$unidade] ;
			 }

		  }

		  if ( $unidade > 0 and $dezena != 1 ) {
			 $valorExt .= $unidades[$unidade] ;
		  }

		  $valorExt .= " " . ( intval($centavos)>1 ? $centPlur : $centSing ) ;

	   }

	   return ( $valorExt ) ;

	}
 	function getBrowser(){
return		$Browser = $_SERVER['HTTP_USER_AGENT'];
	
		/* Get the name the browser calls itself and what version */
		$Browser_Name = strtok($Browser, "/");
		$Browser_Version = strtok( " ");
	
		/* MSIE lies about its name */
/*		if(ereg( "MSIE", $Browser)){
			$Browser_Name = "Internet Explorer";
			$Browser_Version = strtok( "MSIE");
			$Browser_Version = strtok( " ");
			$Browser_Version = strtok( ";");
		}else if( ereg("Chrome", $Browser)){
			$Browser_Name 	 = "Chrome";
			$Browser_Version = $_SERVER['HTTP_USER_AGENT'];
			$Browser_Version = explode("Chrome/",$Browser_Version);	
			$Browser_Version = explode(" ",$Browser_Version[1]);
			$Browser_Version = $Browser_Version[0];	
		}else{
			$Browser_Name = ObtenerNavegador($Browser);
			$Browser_Version =	end(explode("/",$Browser));
			
			if($Browser_Name == 'Desconhecido')	$Browser_Version  = '';
		}
	
		return $Browser_Name." ".$Browser_Version;*/
	}
	function ObtenerNavegador($user_agent) {	
		$Browser = $_SERVER['HTTP_USER_AGENT'];
	
		/* Get the name the browser calls itself and what version */
		$Browser_Name = strtok($Browser, "/");
		$Browser_Version = strtok( " ");
	
      	$navegadores = array(
           'Opera' => 'Opera',
           'Safari'=>'Safari',
           'Mozilla Firefox'=> '(Firebird)|(Firefox)',
           'Galeon' => 'Galeon',
           'Mozilla'=>'Gecko',
           'MyIE'=>'MyIE',
           'Lynx' => 'Lynx',
           'Chorme' => 'Chorme',
           'Netscape' => '(Mozilla/4\.75)|(Netscape6)|(Mozilla/4\.08)|(Mozilla/4\.5)|(Mozilla/4\.6)|(Mozilla/4\.79)',
           'Konqueror'=>'Konqueror',
        );
        
        
 		foreach($navegadores as $navegador=>$pattern){
     		if (eregi($pattern, $Browser))
    			return $navegador;
    	}
	 	return 'Desconhecido';
 	} 	
	function formatNumber($number){
		$number	=	str_replace(".", ",", $number);
		return $number;
	}	
	function inserir_mascara($campo){
		$campo	=	str_replace(".", "", $campo);
		$campo	=	str_replace("-", "", $campo);
		$campo	=	str_replace("/", "", $campo);
		
		$tam	=	strlen($campo);
		
		switch($tam){
			case '11':
				$retorno	=	substr($campo,0,3).'.'.substr($campo,3,3).'.'.substr($campo,6,3).'-'.substr($campo,9,2);
				break;
			case '14':
				$retorno	=	substr($campo,0,2).'.'.substr($campo,2,3).'.'.substr($campo,5,3).'/'.substr($campo,8,4).'-'.substr($campo,12,2);
				break;
			default:
				$retorno	=	$campo;
		}
		return $retorno;
	}

	function NomeNavegador($Navegador){

		$Numeros = array("0","1","2","3","4","5","6","7","8","9","10");

		$DescricaoNavegador = explode(' ',$Navegador);

		$Char = substr($DescricaoNavegador[1], 0, 1);

		if(!in_array($Char,$Numeros)){
			$DescricaoNavegador = $DescricaoNavegador[0]." ".$DescricaoNavegador[1];
		}else{
			$DescricaoNavegador = $DescricaoNavegador[0];
		}

		return $DescricaoNavegador;
	}

	function InstrucoesBoleto($IdContaReceber){

		global $IdLoja;
		global $con;

		$i = 0;

		$sql = "select
					if(DescricaoParametroDemonstrativo != '',concat(DescricaoParametroDemonstrativo,': ', ValorParametroDemonstrativo),'') ParametroDemonstrativo
				from
					LancamentoFinanceiroContaReceber,
					DemonstrativoDescricao
				where
					LancamentoFinanceiroContaReceber.IdLoja = $IdLoja and
					LancamentoFinanceiroContaReceber.IdLoja = DemonstrativoDescricao.IdLoja and
					LancamentoFinanceiroContaReceber.IdContaReceber = $IdContaReceber and
					LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = DemonstrativoDescricao.IdLancamentoFinanceiro";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			if($ParametroDemonstrativo != ''){
				$ParametroDemonstrativo .= ' / ';
			}
			$ParametroDemonstrativo .= $lin[ParametroDemonstrativo];
		}

		$sql = "select
					ContaReceber.IdContaReceber
				from
					ContaReceber ContaReceberCarne,
					ContaReceber
				where
					ContaReceberCarne.IdLoja = $IdLoja and
					ContaReceberCarne.IdContaReceber = $IdContaReceber and
					ContaReceberCarne.IdCarne = ContaReceber.IdCarne
				order by
					ContaReceber.IdContaReceber";
		$res = mysql_query($sql,$con);
		$PosCarne = 0;
		while($lin = mysql_fetch_array($res)){
			$PosCarne++;
			if($lin[IdContaReceber] == $IdContaReceber){
				break;
			}
		}
		$TituloCarne = $PosCarne;

		$sql = "select
					ContaReceberVencimento.DataVencimento DataVencimentoOriginal,
					ContaReceber.DataVencimento
				from
					ContaReceberVencimento,
					ContaReceber
				where
					ContaReceberVencimento.IdLoja = $IdLoja and
					ContaReceberVencimento.IdLoja = ContaReceber.IdLoja and
					ContaReceberVencimento.IdContaReceber = $IdContaReceber and
					ContaReceberVencimento.IdContaReceber = ContaReceber.IdContaReceber
				order by
					ContaReceberVencimento.DataCriacao ASC
				limit 0,1";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);	

		$DataVencimentoOriginal = $lin[DataVencimentoOriginal];
		$DataVencimento			= $lin[DataVencimento];

		$sql = "SELECT
					distinct
					LancamentoFinanceiro.IdContrato
				FROM
					LancamentoFinanceiroContaReceber,
					LancamentoFinanceiro
				WHERE
					LancamentoFinanceiroContaReceber.IdLoja = $IdLoja AND
					LancamentoFinanceiroContaReceber.IdContaReceber = $IdContaReceber AND
					LancamentoFinanceiroContaReceber.IdLoja = LancamentoFinanceiro.IdLoja AND
					LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			if($lin[IdContrato] != ""){
				if($IdContrato != ''){
					$IdContrato .= ", ";
				}
				$IdContrato .= $lin[IdContrato];
			}
		}

		$sql = "select
					ContaReceber.DataVencimento,
					LocalCobrancaParametro.ValorLocalCobrancaParametro Instrucao,
					ContaReceber.ValorLancamento,
					LocalCobranca.PercentualMulta,
					LocalCobranca.PercentualJurosDiarios
				from
					ContaReceber,
					LocalCobranca,
					LocalCobrancaParametro
				where
					ContaReceber.IdLoja = $IdLoja and
					ContaReceber.IdContaReceber = $IdContaReceber and
					LocalCobranca.IdLoja = ContaReceber.IdLoja and
					LocalCobrancaParametro.IdLoja = ContaReceber.IdLoja and
					LocalCobranca.IdLocalCobranca = ContaReceber.IdLocalCobranca and
					LocalCobrancaParametro.IdLocalCobranca = ContaReceber.IdLocalCobranca and
					LocalCobrancaParametro.IdLocalCobrancaParametro like 'Instrucoes%'";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){

			$ValorMulta = $lin[ValorLancamento] * $lin[PercentualMulta] / 100;
			$ValorMulta = number_format($ValorMulta, 2, ',', '');
			$ValorMulta = getParametroSistema(5,1).$ValorMulta;

			$ValorJurosDiarios = $lin[ValorLancamento] * $lin[PercentualJurosDiarios] / 100;
			$ValorJurosDiarios = number_format($ValorJurosDiarios, 2, ',', '');
			$ValorJurosDiarios = getParametroSistema(5,1).$ValorJurosDiarios;

			$lin[Instrucao] = str_replace('$ValorMulta', $ValorMulta, $lin[Instrucao]);
			$lin[Instrucao] = str_replace('$ValorJurosDiarios', $ValorJurosDiarios, $lin[Instrucao]);
			$lin[Instrucao]	= str_replace('$ValorDespesaLocalCobranca', $ValorDespesaLocalCobranca, $lin[Instrucao]);
			$lin[Instrucao]	= str_replace('$ParametroDemonstrativo', $ParametroDemonstrativo, $lin[Instrucao]);
			$lin[Instrucao]	= str_replace('$TituloCarne', $TituloCarne, $lin[Instrucao]);
			$lin[Instrucao]	= str_replace('$VencimentoOriginal', dataConv($DataVencimentoOriginal,"Y-m-d","d/m/Y"), $lin[Instrucao]);
			$lin[Instrucao]	= str_replace('$Vencimento', dataConv($lin[DataVencimento],"Y-m-d","d/m/Y"), $lin[Instrucao]);
			$lin[Instrucao]	= str_replace('$IdContrato', $IdContrato, $lin[Instrucao]);

			$Instrucao[$i] = $lin[Instrucao];
			$i++;
		}	

		$sql = "select
					ContaReceberDados.ValorFinal,
					sum(LancamentoFinanceiro.ValorDescontoAConceber) ValorDescontoAConceber,
					min(LancamentoFinanceiro.LimiteDesconto) LimiteDesconto,					
					LancamentoFinanceiro.DataReferenciaInicial,
					LancamentoFinanceiro.DataReferenciaFinal
				from
					ContaReceberDados,
					LancamentoFinanceiroContaReceber,
					LancamentoFinanceiro
				where
					ContaReceberDados.IdLoja = $IdLoja and
					ContaReceberDados.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
					LancamentoFinanceiroContaReceber.IdLoja = LancamentoFinanceiro.IdLoja and
					ContaReceberDados.IdContaReceber = $IdContaReceber and
					ContaReceberDados.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
					LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro
				group by
					ContaReceberDados.IdContaReceber";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		$InstrucaoDesconto = getCodigoInterno(3,128);
		
		$DataReferenciaInicial  = dataConv($lin[DataReferenciaInicial],'Y-m-d','d/m/Y');
		$DataReferenciaFinal	= dataConv($lin[DataReferenciaFinal],'Y-m-d','d/m/Y');
		
		$InstrucaoDesconto = str_replace('$DataReferenciaInicial', $DataReferenciaFinal, $InstrucaoDesconto);
		$InstrucaoDesconto = str_replace('$DataReferenciaFinal', $DataReferenciaFinal, $InstrucaoDesconto);

		if($lin[ValorDescontoAConceber] > 0){
			
			$sql = "select
						ManterDescontoAConceber
					from
						ContaReceberVencimento
					where
						IdLoja = $IdLoja and
						IdContaReceber = $IdContaReceber and
						DataVencimento = '$DataVencimento' and
						ManterDescontoAConceber != 1";
			$res = mysql_query($sql,$con);
			if(mysql_fetch_array($res)){
				$lin[ValorDescontoAConceber] = 0;
			}else{
				if($lin[ValorDescontoAConceber] > 0 && $lin[LimiteDesconto] <= 10000 && $InstrucaoDesconto != ''){			

					$LimiteDesconto			= $lin[LimiteDesconto];
					$DataLimiteDesconto		= incrementaData($DataVencimento,$LimiteDesconto);
					$DataLimiteDesconto		= dataConv($DataLimiteDesconto,'Y-m-d','d/m/Y');
			
					$ValorDescontoBaseVencimento		= getParametroSistema(5,1).number_format($lin[ValorDescontoAConceber], 2, ',', '');

					$PercentualDescontoBaseVencimento	= @(100*$lin[ValorDescontoAConceber]/$lin[ValorFinal]);
					$PercentualDescontoBaseVencimento	= number_format($PercentualDescontoBaseVencimento, 2, ',', '');

					$InstrucaoDesconto = str_replace('$ValorDescontoVencimento', $ValorDescontoBaseVencimento, $InstrucaoDesconto);
					$InstrucaoDesconto = str_replace('$PercentualDescontoVencimento', $PercentualDescontoBaseVencimento, $InstrucaoDesconto);
					$InstrucaoDesconto = str_replace('$DataBaseDesconto', $DataLimiteDesconto, $InstrucaoDesconto);

					for($i=0; $i<count($Instrucao); $i++){
						if($Instrucao[$i] == ''){
							$Instrucao[$i] = $InstrucaoDesconto;
							$InstrucaoDesconto = '';
							break;
						}
					}
					
					if($InstrucaoDesconto != ''){
						$Instrucao[count($Instrucao)] = $InstrucaoDesconto;
					}
				}
			}
		}

		return $Instrucao;
	}

	function InstrucoesBoletoHTML($IdContaReceber){
		$Instrucao = InstrucoesBoleto($IdContaReceber);
		for($i=0; $i<count($Instrucao); $i++){			
			echo $Instrucao[$i]."<br>";					
		}
	}
	
	function byte_convert($bytes, $precision) {
		$units = array('', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		$unit = 0;

		$divisor = 1048576;
   
		do{
			$bytes /= $divisor;
			$unit++;

			if($unit > 0){
				$divisor = 1024;
			}

		}while ($bytes > $divisor);
		
		$bytes	=	(double) $bytes;
		
		$valor	=	number_format($bytes,$precision,",","");
   
		return $valor." ".$units[$unit];
	}
	
	function conta_eventual($local_IdLoja,$local_IdPessoa,$local_DescricaoContaEventual,$local_FormaCobranca,$local_OcultarReferencia,$local_IdContratoAgrupador,$local_IdLocalCobranca,$local_ArrayValor,$local_ArrayDespesa,$local_ValorDespesaLocalCobranca,$local_QtdParcela,$local_ArrayVencimento,$local_ObsContaEventual,$local_Login){
		//	$local_ArrayValor				 -> formato = 00,0
		//	$local_ArrayDespesa				 -> formato = 00,0
		//	$local_ValorDespesaLocalCobranca -> formato = 00,0
			
		//	if($local_FormaCobranca == 2){
		//		$local_ArrayVencimento		-> formato = dd/mm/YYYY
		//	}else{
		//		$local_ArrayVencimento		-> formato = mm/YYYY
		//	}
		
		// 	separador dos Array	= #
		
		global $con;
		
		$local_QtdParcelaContrato		= $local_QtdParcela;
		$local_QtdParcelaIndividual		= $local_QtdParcela;
		$local_IdStatus					= 1;
		$valor							= 0;
		
		$ArrayValor	=	explode('#',$local_ArrayValor);
		$ArrayDesp	=	explode('#',$local_ArrayDespesa);
		$ArrayData	=	explode('#',$local_ArrayVencimento);
		
		if($local_ArrayDespesa	== "") $local_ArrayDespesa	= 0;
		if($local_ArrayValor	== "") $local_ArrayValor	= 0;
		
		for($i=1;$i<=$local_QtdParcela;$i++){
			$local_Valor[$i] = $ArrayValor[($i-1)]; 
			$local_Desp[$i]  = $ArrayDesp[($i-1)]; 
			$local_Data[$i]  = $ArrayData[($i-1)]; 
			
			$valor	=	number_format($local_Valor[$i],2,'.','');
			
			$local_ValorTotalContrato	= $local_ValorTotalContrato + $valor;
			$local_ValorTotalIndividual	= $local_ValorTotalIndividual + $valor;	
		}
		
		include('files/inserir/inserir_conta_eventual.php');
		
		if($sql == "COMMIT;"){
			include('rotinas/confirmar_conta_eventual.php');
		}

		if($sql == "COMMIT;"){
			return $local_IdContaEventual;
		}else{
			return false;
		}
	}
	function OpcoesExtrasRelatorio($Opcoes){
		if($Opcoes != ''){
			$opc = explode("\n",$Opcoes);
			$qtd = count($opc);
			
			for($i = 0; $i < $qtd; $i++){
				$opcDados = explode("#",$opc[$i]);
				
				if($qtd > 1){
					$opcDados[$i+1] = substr($opcDados[$i+1],0,-1);
				}
				
				echo "<reg>";			
					echo	"<Operacao><![CDATA[".$opcDados[0]."]]></Operacao>";
					echo	"<Link><![CDATA[".$opcDados[1]."]]></Link>";
					echo	"<Tipo><![CDATA[".$opcDados[2]."]]></Tipo>";
				echo "</reg>";
			}
		}
	}
	
	function LogAcessoCDA(){
		global $con;
		$browser = getDataBrowser();
		$sql = "select
					IdParametroSistema IdNavegador
				from
					ParametroSistema
				where
					IdGrupoParametroSistema = 89 and
					ValorParametroSistema = '".$browser[name]."'";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
	
		if($lin[IdNavegador] == ''){
			$sqlVerifica = "select
								max(IdParametroSistema) IdParametroSistema
							from
								ParametroSistema
							where
								IdGrupoParametroSistema = 89";
			$resVerifica = mysql_query($sqlVerifica,$con);
			$linVerifica = mysql_fetch_array($resVerifica);
			
			if($linVerifica[IdParametroSistema] != NULL){
				$IdNavegador = $linVerifica[IdParametroSistema] + 1;
			} else{
				$IdNavegador = 1;
			}
			
			$sql = "insert into 
						ParametroSistema 
					set 
						IdGrupoParametroSistema		= '89', 
						IdParametroSistema			= '".$IdNavegador."', 
						DescricaoParametroSistema	= 'Navegador (".$browser[name].")', 
						ValorParametroSistema		= '".$browser[name]."', 
						LoginCriacao				= '".$login."', 
						DataCriacao					= concat(curdate(),' ',curtime());";
			mysql_query($sql,$con);

		} else{
			$IdNavegador = $lin[IdNavegador];
		}
		
		$sql2 = "select (max(IdLogAcesso) + 1) IdLogAcesso from LogAcessoCDA";
		$res2 = mysql_query($sql2,$con);
		$lin2 = mysql_fetch_array($res2);
		
		if($lin2[IdLogAcesso] != ''){
			$IdLogAcesso = $lin2[IdLogAcesso];
		} else{
			$IdLogAcesso = 1;
		}
		
 		$sql = "insert into 
					LogAcessoCDA 
				set
					IdLogAcesso	= '".$IdLogAcesso."', 
					IdNavegador	= '".$IdNavegador."',
					IdPessoa	= '".$_SESSION["IdPessoaCDA"]."', 
					IP			= '".$_SERVER["REMOTE_ADDR"]."', 
					DataHora	= concat(curdate(), ' ', curtime());";
		mysql_query($sql,$con);
		
		return $IdLogAcesso;
	}
	function diaSemanaExtenso($data){
		$diasemana =	date("w", strtotime($data));
						
		switch($diasemana){  
			case "0": $diasemana = "Domingo";       break;  
			case "1": $diasemana = "Segunda-Feira"; break;  
			case "2": $diasemana = "Terça-Feira";   break;  
			case "3": $diasemana = "Quarta-Feira";  break;  
			case "4": $diasemana = "Quinta-Feira";  break;  
			case "5": $diasemana = "Sexta-Feira";   break;  
		 	case "6": $diasemana = "Sábado";        break;  
		}
		
		return $diasemana;
	}

	function KeyCode($IdLicenca,$Solicitacao){

		// Alimentação das variáveis
		$Data			= date('Ymdhis');				// [14]
		$Token			= rand(100000000,999999999);	//  [9]

		$IdLicencaArray[0]	= substr($IdLicenca,0,4);		// Licença Ano
		$IdLicencaArray[1]	= substr($IdLicenca,4,1);		// Licença Versão
		$IdLicencaArray[2]	= (int)substr($IdLicenca,5,3);	// Licença Pos

		$IdLicencaArray[0]	= dechex($IdLicencaArray[0]);	// [3]

		$IdLicencaArray[2]	= dechex($IdLicencaArray[2]);
		$IdLicencaArray[2]	= str_pad($IdLicencaArray[2], 3, "x", STR_PAD_LEFT);	// [3]

		$IdLicencaArray[1]	= hexdec($IdLicencaArray[1]);
		$IdLicencaArray[1]	= pow($IdLicencaArray[1],2);	// [3]

		$Bloco[Licenca] = $IdLicencaArray[1].$IdLicencaArray[0].$IdLicencaArray[2]; // [9]

		$DataArray[Hor]	= substr($Data,8,2);
		$DataArray[Hor]	+= 12;
		$DataArray[Hor]	= dechex($DataArray[Hor]);
		$DataArray[Hor]	= str_pad($DataArray[Hor], 2, "x", STR_PAD_LEFT);	// [2]
		
		$DataArray[Min]	= substr($Data,10,2);	// 2

		$DataArray[Seg]	= substr($Data,12,2);
		$DataArray[Seg]	= substr($DataArray[Seg],1,1).substr($DataArray[Seg],0,1);
		$DataArray[Seg] = str_pad($DataArray[Seg], 2, "x", STR_PAD_LEFT);	// [2]

		$DataArray[Hora] = $DataArray[Hor].$DataArray[Seg].$DataArray[Min];	// 6

		$DataArray[Ano]	= substr($Data,0,4);
		$DataArray[Mes]	= substr($Data,4,2);
		$DataArray[Dia]	= substr($Data,6,2);

		$DataArray[DiaAno]	= diadoano($DataArray[Ano],$DataArray[Mes],$DataArray[Dia]);
		$DataArray[DiaAno]	= dechex($DataArray[DiaAno]);
		$DataArray[DiaAno]	= str_pad($DataArray[DiaAno], 3, "x", STR_PAD_LEFT);					// [3]

		$DataArray[Ano]	= dechex(substr($DataArray[Ano],2,2));
		$DataArray[Ano]	= str_pad($DataArray[Ano], 2, "x", STR_PAD_LEFT);							// [2]

		$Bloco[Data] = $DataArray[DiaAno].$DataArray[Ano].$DataArray[Hora];	// 11

		$Bloco[Solicitacao] = $Solicitacao;	// 1
		
		$Bloco = strtoupper($Bloco[Licenca].$Bloco[Data].$Bloco[Solicitacao]);

		$Chave[0] = substr($Bloco,0,2);
		$Chave[1] = substr($Token,0,1);
		$Chave[2] = substr($Bloco,2,2);
		$Chave[3] = substr($Token,1,1);
		$Chave[4] = substr($Bloco,4,2);
		$Chave[5] = substr($Token,2,1);
		$Chave[6] = substr($Bloco,6,2);
		$Chave[7] = substr($Token,3,1);
		$Chave[8] = substr($Bloco,8,2);
		$Chave[9] = substr($Token,4,1);
		$Chave[10] = substr($Bloco,10,2);
		$Chave[11] = substr($Token,5,1);
		$Chave[12] = substr($Bloco,12,2);
		$Chave[13] = substr($Token,6,1);
		$Chave[14] = substr($Bloco,14,2);
		$Chave[15] = substr($Token,7,1);
		$Chave[16] = substr($Bloco,16,2);
		$Chave[17] = substr($Token,8,1);
		$Chave[18] = substr($Bloco,18,2);
		$Chave[19] = substr($Bloco,20,1);

		$Key[0] = $Chave[14].$Chave[6].$Chave[5].$Chave[13];	// 2 2 1 1 
		$Key[1] = $Chave[4].$Chave[19].$Chave[7].$Chave[12];	// 2 1 1 2
		$Key[2] = $Chave[3].$Chave[8].$Chave[18].$Chave[11];	// 1 2 2 1
		$Key[3] = $Chave[9].$Chave[17].$Chave[2].$Chave[0];		// 1 1 2 2
		$Key[4] = $Chave[16].$Chave[10].$Chave[1].$Chave[15];	// 2 2 1 1

		$Chave = $Key[0]."-".$Key[1]."-".$Key[2]."-".$Key[3]."-".$Key[4];

		sleep(rand(0,3));

		return $Chave;
	}

	function KeyDecode($KeyCode){

		if(strlen($KeyCode) != 34){
			return false;
		}

		$KeyCode = explode('-',$KeyCode);

		$Chave[14]	= substr($KeyCode[0],0,2);
		$Chave[6]	= substr($KeyCode[0],2,2);
		$Chave[5]	= substr($KeyCode[0],4,1);
		$Chave[13]	= substr($KeyCode[0],5,1);

		$Chave[4]	= substr($KeyCode[1],0,2);
		$Chave[19]	= substr($KeyCode[1],2,1);
		$Chave[7]	= substr($KeyCode[1],3,1);
		$Chave[12]	= substr($KeyCode[1],4,2);

		$Chave[3]	= substr($KeyCode[2],0,1);
		$Chave[8]	= substr($KeyCode[2],1,2);
		$Chave[18]	= substr($KeyCode[2],3,2);
		$Chave[11]	= substr($KeyCode[2],5,1);

		$Chave[9]	= substr($KeyCode[3],0,1);
		$Chave[17]	= substr($KeyCode[3],1,1);
		$Chave[2]	= substr($KeyCode[3],2,2);
		$Chave[0]	= substr($KeyCode[3],4,2);

		$Chave[16]	= substr($KeyCode[4],0,2);
		$Chave[10]	= substr($KeyCode[4],2,2);
		$Chave[1]	= substr($KeyCode[4],4,1);
		$Chave[15]	= substr($KeyCode[4],5,1);

		for($i=0; $i<=18; $i++){
			if($i%2 == 0){
				$Bloco .= $Chave[$i];
			}else{
				$Token .= $Chave[$i];
			}
		}
		$Bloco .= $Chave[$i];

		$Licenca			= substr($Bloco,0,9);

		$LicencaArray[0]	= substr($Licenca,3,3);
		$LicencaArray[0]	= hexdec($LicencaArray[0]);

		$LicencaArray[1]	= substr($Licenca,0,3);
		$LicencaArray[1]	= sqrt($LicencaArray[1]);
		$LicencaArray[1]	= dechex($LicencaArray[1]);
		$LicencaArray[1]	= strtoupper($LicencaArray[1]);

		$LicencaArray[2]	= substr($Licenca,6,3);
		$LicencaArray[2]	= hexdec($LicencaArray[2]);
		$LicencaArray[2]	= str_pad($LicencaArray[2], 3, "0", STR_PAD_LEFT);

		$IdLicenca		= $LicencaArray[0].$LicencaArray[1].$LicencaArray[2];

		$Solicitacao	= substr($Bloco,20,1);

		$Data			= substr($Bloco,9,11);

		$DataArray[Ano] = substr($Data,3,2);
		$DataArray[Ano]	= hexdec($DataArray[Ano]);
		$DataArray[Ano] = str_pad($DataArray[Ano], 2, "0", STR_PAD_LEFT);
		$DataArray[Ano]	= "20".$DataArray[Ano];

		$DataArray[DiaAno]  = substr($Data,0,3);
		$DataArray[DiaAno]  = hexdec($DataArray[DiaAno]);

		$DataArray[Data] = diadoanoReverso($DataArray[Ano],$DataArray[DiaAno]);

		$DataArray[Hora] = substr($Data,5,2);
		$DataArray[Hora] = hexdec($DataArray[Hora]);
		$DataArray[Hora] -= 12;
		$DataArray[Hora] = str_pad($DataArray[Hora], 2, "0", STR_PAD_LEFT);
		
		$DataArray[Seg] = substr($Data,8,1).substr($Data,7,1);

		$DataArray[Min]	= substr($Data,9,2);
		$DataArray[Min]	= str_pad($DataArray[Min], 2, "0", STR_PAD_LEFT);

		$DataArray[Hora] = $DataArray[Hora].":".$DataArray[Min].":".$DataArray[Seg];

		$Data = $DataArray[Data]." ".$DataArray[Hora];

		$KeyArray[IdLicenca]	= $IdLicenca;
		$KeyArray[Token]		= $Token;
		$KeyArray[Solicitacao]	= $Solicitacao;
		$KeyArray[Data]			= $Data;

		return $KeyArray;
	}

	function KeyLicenca($KeyLicenca){

		$KeyLicenca = explode('-',$KeyLicenca);

		for($i=0; $i<=5; $i++){

			$var = $KeyLicenca[$i];

			switch($i){
				case 0:
					$Chave[29] = $var[0];
					$Chave[20] = $var[1];
					$Chave[25] = $var[2];
					$Chave[9]  = $var[3];
					$Chave[7]  = $var[4];
					break;
				case 1:
					$Chave[23] = $var[0];
					$Chave[28] = $var[1];
					$Chave[10] = $var[2];
					$Chave[4]  = $var[3];
					$Chave[8]  = $var[4];
					break;
				case 2:
					$Chave[16] = $var[0];
					$Chave[13] = $var[1];
					$Chave[27] = $var[2];
					$Chave[2]  = $var[3];
					$Chave[18] = $var[4];
					break;
				case 3:
					$Chave[14] = $var[0];
					$Chave[26] = $var[1];
					$Chave[11] = $var[2];
					$Chave[0]  = $var[3];
					$Chave[5]  = $var[4];
					break;
				case 4:
					$Chave[15] = $var[0];
					$Chave[24] = $var[1];
					$Chave[22] = $var[2];
					$Chave[6]  = $var[3];
					$Chave[1]  = $var[4];
					break;
				case 5:
					$Chave[17] = $var[0];
					$Chave[12] = $var[1];
					$Chave[21] = $var[2];
					$Chave[3]  = $var[3];
					$Chave[19] = $var[4];
					break;
			}
		}
		for($i=0; $i< count($Chave); $i++){
			$Bloco .= $Chave[$i]; 
		}

		$IdLicenca = substr($Bloco,0,9);

		$IdLicencaArray[0] = substr($IdLicenca,3,3);
		$IdLicencaArray[0] = hexdec($IdLicencaArray[0]);

		$IdLicencaArray[1] = substr($IdLicenca,0,3);
		$IdLicencaArray[1] = sqrt($IdLicencaArray[1]);
		$IdLicencaArray[1] = dechex($IdLicencaArray[1]);
		$IdLicencaArray[1] = strtoupper($IdLicencaArray[1]);

		$IdLicencaArray[2] = substr($IdLicenca,6,3);
		$IdLicencaArray[2] = hexdec($IdLicencaArray[2]);
		$IdLicencaArray[2] = str_pad($IdLicencaArray[2], 3, "0", STR_PAD_LEFT);

		$IdLicenca = $IdLicencaArray[0].$IdLicencaArray[1].$IdLicencaArray[2];

		$Data = substr($Bloco,9,5);

		$DataArray[0] = substr($Data,0,3);
		$DataArray[0] = hexdec($DataArray[0]);

		$DataArray[1] = substr($Data,3,2);
		$DataArray[1] = hexdec($DataArray[1]);
		$DataArray[1] = "20".substr($DataArray[1],0,2);

		$Data = diadoanoReverso($DataArray[1],$DataArray[0]);

		$Solicitacao = substr($Bloco,14,1);
		$Solicitacao = hexdec($Solicitacao);
		$Solicitacao = bindec($Solicitacao);
		
		$Token = substr($Bloco,15,15);

		$TokenArray[A] = substr($Token,0,4);
		$TokenArray[A] = hexdec($TokenArray[A]);
		$TokenArray[A] = str_pad($TokenArray[A], 4, "0", STR_PAD_LEFT);
		$DiasLicenca[2] = substr($TokenArray[A],3,1);
		$TokenArray[A] = substr($TokenArray[A],0,3);

		$TokenArray[B] = substr($Token,6,5);
		$TipoLicenca   = substr($TokenArray[B],0,1);
		$TokenArray[B] = substr($TokenArray[B],1,4);
		$TokenArray[B] = hexdec($TokenArray[B]);
		$TokenArray[B] = str_pad($TokenArray[B], 4, "0", STR_PAD_LEFT);
		$DiasLicenca[0] = substr($TokenArray[B],3,1);
		$TokenArray[B] = substr($TokenArray[B],0,3);

		$TokenArray[C] = substr($Token,11,4);
		$TokenArray[C] = hexdec($TokenArray[C]);
		$TokenArray[C] = str_pad($TokenArray[C], 4, "0", STR_PAD_LEFT);
		$DiasLicenca[1] = substr($TokenArray[C],0,1);
		$TokenArray[C] = substr($TokenArray[C],1,3);

		$DiasLicenca[3] = substr($Token,4,2);

		$Token = $TokenArray[A].$TokenArray[B].$TokenArray[C];

		$DiasLicenca[Soma] = (int)$DiasLicenca[3];

		if($DiasLicenca[Soma] == ($DiasLicenca[0]+$DiasLicenca[1]+$DiasLicenca[2])){
			$DiasLicenca = $DiasLicenca[0].$DiasLicenca[1].$DiasLicenca[2];
			$DiasLicenca = (int)$DiasLicenca;
		}else{
			return false;
		}

		$KeyArray[IdLicenca]	= $IdLicenca;
		$KeyArray[Token]		= $Token;
		$KeyArray[Solicitacao]	= $Solicitacao;
		$KeyArray[Data]			= $Data;
		$KeyArray[DiasLicenca]  = $DiasLicenca;
		$KeyArray[TipoLicenca]  = $TipoLicenca;

		return $KeyArray;
	}

	function KeyProcess($KeyCode, $KeyLicenca){
		
		global $con;

		$KeyCode	= KeyDecode($KeyCode);
		$KeyLicenca = KeyLicenca($KeyLicenca);

		if($KeyCode[IdLicenca] != $KeyLicenca[IdLicenca]){		return false; }
		if($KeyCode[Token] != $KeyLicenca[Token]){				return false; }
		if($KeyCode[Solicitacao] != $KeyLicenca[Solicitacao]){	return false; }
		if(substr($KeyCode[Data],0,10) != $KeyLicenca[Data]){	return false; }
		if(substr($KeyCode[Data],0,10) != date("Y-m-d")){		return false; }

		if($KeyLicenca[DiasLicenca] > 0){

			// Sala a solicitação
			$sql = "insert into Licenca (DataGeracao, TipoSolicitacao, DiasLicenca, DataCriacao) values ('$KeyCode[Data]', '$KeyCode[Solicitacao]', '$KeyLicenca[DiasLicenca]', concat(curdate(),' ',curtime()))";
			@mysql_query($sql,$con);

			AtualizaConfig($KeyCode[IdLicenca], $KeyLicenca[TipoLicenca], $KeyLicenca[DiasLicenca]);

		}else{
			return false;
		}
	}

	function AtualizaConfig($IdLicenca, $TipoLicenca, $DiasLicenca){

		if(((int)$DiasLicenca) < 0){ $DiasLicenca = 0; }

		$Patch = getParametroSistema(6,1);
		$File = $Patch."files/config.sys";
		@unlink($File);

		$FileVars = $Patch."files/vars.ini";
		@unlink($FileVars);

		$DiasLicenca	= str_pad($DiasLicenca, 3, "0", STR_PAD_LEFT);
		$TipoLicenca	= str_pad($TipoLicenca, 1, "0", STR_PAD_LEFT);
		$IdLicenca		= str_pad($IdLicenca, 8, "0", STR_PAD_LEFT);

		###############################
		# date("Ymd")					[8]		[0-7]
		# $DiasLicenca					[3]		[8-10]
		# $TipoLicenca					[1]		[11-11]
		# $IdLicenca					[8]		[12-19]
		###############################

		$Key = date("Ymd").$DiasLicenca.$TipoLicenca.$IdLicenca;

		$ConteudoTemp[0]  = "4+oV59AGkqY7C01lIbqkQ+FRcN9K0eMgycvZoOwiPBphI01xrOLwBLBCAX4aP2n+BkI3701Sqmex";
		$ConteudoTemp[1]  = "lOKwbxKn8GWc6u/Ctb5ryQrCSO207X0GPKfXdgK8Gkw7ubGreJ9RcYBqODaAqhIPwcBdPOM+AMCn";
		$ConteudoTemp[2]  = "lQY4vZiak9IBTRPMIQ3T3XpPyrBwRw2j5UAcLxI9zGHbmFP2/HoPYsl+RZCuAa1MYnw84yQ0uLNS";
		$ConteudoTemp[3]  = "Rx10+2TCy8STh+55Pyjz9FzOONUMPbq+a1HzCubURPzT3wefJo+c9pgzId0VGiaG/o4bgaW4hbsC";
		$ConteudoTemp[4]  = "beuK28ZkBtjP3UfGmyFJLBJdr6jhtUJjEu8uKKQQ5AVGrLvhhDu37DQR62cEYnyCgwkeb/ShcRaq";
		$ConteudoTemp[5]  = "37h+/ZPOASK4FJrnLqiEr3MaX5TP0ZcgsBZs26soIATuqe0kPaseI4rj5KMNehhVoikxq9RVlWUv";
		$ConteudoTemp[6]  = "vyhiyP6Ao708RZXkuXcVrZkz8doCL86kpt/P1nACzAgziJj+gaivEn41yeDNq1oog4xseXgxcoEX";
		$ConteudoTemp[7]  = "NhRCWQc4HJjm1c+A1ofEvqlhhfYuddBQ8VmoI/2L7v/Jgg9dETn7Ah2DxzBrE6JDe6j9uEqvXLJ/";
		$ConteudoTemp[9]  = "dlH4Hkh3lOGHvbo2W5iKCWJgybt/GiJEH0dGf224kirk7u/5GZgNf0Ctz0KaQYV+73WD6uIOWXF9";
		$ConteudoTemp[8]  = "GKgYQWUtmuMQDQxElOSGo0OkErHrUHMgtJScaSpPN3Ca3gjSaXErfiPUsy+glDtlCraS7Zx9b6yb";
		$ConteudoTemp[10] = "oIJK7RzxGFeUJ835UPiRVCBckx8/qE0qmmmVZQviTXRWQ3TaphJKn76yq82cxYQ43R35WP/l+shq";
		$ConteudoTemp[11] = "loCHi0UAa1wnV2PekRpdD1+23PaIVbYGWpGhdWLmJ7kQtHseGQ/GsmVR2vZ/+xlTFbpWLAxkDLSY";
		$ConteudoTemp[12] = "ebmpiav6VJwWEZaCVOgFN/5xBBGp6zcVA5T2i82w0wefOqtTIvTYnRW6p/iGu3IvVN+gXgoSJg2F";
		$ConteudoTemp[13] = "ediPx06U8gDb7b7HygEe1tbR6yJXhRa/34Ovhbmhm0WoftTRm52v6nFZqB717576AGhXJyKVjrrT";
		$ConteudoTemp[14] = "rfVprAZMTPHl9j/hw/Lkbk09/R8wSdIw4S6h+3e/Qk7lsSyNaYMypCsXTJC0JDKeH1iurUiTDhuC";
		$ConteudoTemp[15] = "2DqTXaw2dGqnVs1BXRCKB+/dLKYCBhrNlh+3AQ1BU0wc37g9OPMsA/vMcjSA4iNTEUtxK1+0PqA/";
		$ConteudoTemp[16] = "Z9YEhQtK5e181miu4kzYXQUjI4bO1BjtGq+H1wEb0CcU8iqbVwFa0vLQqVstdITdAFrdEWu2T7IF";
		$ConteudoTemp[17] = "tSxOs86K+7xPwQJ1oN3NXacG9oIvdx9K/rGZO8jn/fQuFtUBDpMLcjUVzA9XRzDByVG65YyDc7BO";
		$ConteudoTemp[18] = "locwmBSa9twfLyCMdRPVYa4WgXlU9VxCivmU+hD1+YkcPCAibk0LcjgNdeqPVssMhouczOfQcX9Q";
		$ConteudoTemp[19] = "A6PbZMQp51b9+8nTN50esAyAlT7vSk06ZtkuPy7mvEmTKLEQbo0vLQg4olhg15wTvAzueZOFVBab";
		$ConteudoTemp[20] = "NfIssdAv8HDnHWVffmeg3KA3NUMpmHOxLHq0yPwhO8eTBNHBo6ZbHy16o7eclifhZTBu/4dfkq5f";
		$ConteudoTemp[21] = "w0BNnPR9uCzPNv7GSm5ptaS+SUhfWfgUCrMa0locUCmUsWbWwAq+E5mqIC2ZDw5LrgyHN2N/rWNs";
		$ConteudoTemp[22] = "yw9m34XwJfshITByYHsElVIXeBVJdRhPRTgEipRYgCZ+C5oUyhszpy+Vy1t+xkQZiUiZam3eN6MY";
		$ConteudoTemp[23] = "A+O2L547EayOhme38WpLNvMQ0X5OK8HRoFlDLwS1Co7NjZvcdraikinTmpq8/URo3fR7YeTveNZ/";
		$ConteudoTemp[24] = "fDqE2PZ7shGsFigNkM94u+zS7HewbXiovKhxH+tPMraqHO9+pvl8mbdLetN6K3Xuur32t88Uarc8";
		$ConteudoTemp[25] = "2xR2VQM60M14jSJ80U9pAmXUbwNS4I3AZWSIl3JOUbZrr4QlrcKI+z7qfIb9k2RZ8FoHPvjp4pya";
		$ConteudoTemp[26] = "hblRBUHyUN8iqUAr/stS9lf8/xXf0rAR805iW1Xb7kN4FdmIGSCKz6wx1JY7O2ucUHcqLEcJOjOZ";
		$ConteudoTemp[27] = "dxR03uLFQt+NZiAk3dpS0OL5GzqdVKvE12MUYEf0UCxTfba9uQHgCCDQpwNvHSANFMDfB9c+Ywlr";
		$ConteudoTemp[28] = "AgY5KCMIE+CQYaVLExaYr8Z1HQUtODI1+UIPZ4EW09mcbFxo1F6KANVjX0DY2IbvQBarcwgD6exH";
		$ConteudoTemp[29] = "q1Bxlno0uvy934j1XCTOAHZPj5vEYrW4NlLZUJFErFZYk9dcl8rUfdWR4EtKzgdGSbrgx841/FPX";
		$ConteudoTemp[30] = "ytWj+KjbyrBR6Mc9Cr3HpFfTJGGgZANbBU4deYXqkhfIAc0erAEWCUhupf43mMMYvlyYuCAE6GVc";
		$ConteudoTemp[31] = "l3uZq3L6/tTg9CwLBIA67n01izzjbc0s1eYktFYHqoZEjXFo8m/yBnImhYAvZ9zxncaQuxtFfmkl";
		$ConteudoTemp[32] = "XCK3bjIf7l/NE4Gn615/gVqX4aVU9+NGS7P00kMx6HTNmrCvvPNBjfh9yOf8nPC9KOUbhlK1YYcH";
		$ConteudoTemp[33] = "1LXnMEZZ5FtE+8v2YVxTcJbb2QpHvwaWEU5DopwwAjSfC6Xb8O5Gh3iuZydl1kU4MhUpOoiGFb2z";
		$ConteudoTemp[34] = "dMIIQ9vaWUKmIfWVJbMojWZ4XLWWkAjngOadbEmJNnYVTt50jWOHvTUHPPqjKmSTXDeTBVmkMKtX";
		$ConteudoTemp[35] = "FrfTlB02MjCrY/dstPHi92kdLv6D5P+35mmucrWq1TFyuKbMjQ2AdN822q6hOElmu5fc3tebqD6/";
		$ConteudoTemp[36] = "q4QVswx3OyiSz2iYz50ClX/0qOWgW6cNcfUoARcwbhgCcJKpHObaP9g38D1UzxGWsFbhVBGnCXLP";
		$ConteudoTemp[37] = "VykEUVV2+4Q9qYsedNkc1OmjsUIA0nvwJwSeQ7FvE3urfZK3hlQQoTWlwpazbeuWCVZE3NInby4V";
		$ConteudoTemp[38] = "JSv4ljXqusqIstmj+9uFfGi5wXrV5QjFqEePA1HWOX6VR8DxwxruUNi92yvmlp5HmtJPjTf1SOpb";
		$ConteudoTemp[39] = "JcjeWaTjPh5XPRTd2Tsmn4ePjgF5T1b9tc332p7r+hGYrFZLNHzdjHS55CzL1fAnjrDWuDb6Botc";
		$ConteudoTemp[40] = "KBvGD9B19iFtFbEDCfsqJEVdFRi5CRQdwWCQbrmU6o2QNWRuVbw6Xs4zzCh0qbJSSogaFgeOQy3P";
		$ConteudoTemp[41] = "scKXUl6KhC+YLoKqEOFM/rGprEl0I64auLMpn7rUIY/B5cZafvx20giiQdaVr9p+3YHfmWyLhNeT";
		$ConteudoTemp[42] = "v71ZFJdJozZimgSE19P1BRgrgYiVCWlykK3FfhBx6tcHl0YA3XSFooZ9Pl5aREsYsmyNjkP44WPM";
		$ConteudoTemp[43] = "UwpvlMkyvs0TeVnVA34unYneRXFRLEnQwevkYlXfk0s2JzUhdFwpZEnMb6/k0Bq05xw6Yv7di3g/";
		$ConteudoTemp[44] = "8RMwU0Knbj2ITgIZjUr8TNxsgHiXFvVBFfFzG4RDG+PF+0N7KUpGcSemvahUi8fX7fxKMstpo7uh";

		$i = 0;
		while($i < 44){
			$pos = rand(0,44);
			if($ConteudoTemp[$pos] != ''){
				$Conteudo[$i] = $ConteudoTemp[$pos];
				$ConteudoTemp[$pos] = '';
				$i++;
			}
		}

		$Conteudo[17][48] = $Key[0];
		$Conteudo[38][1]  = $Key[1];
		$Conteudo[4][0]   = $Key[2];
		$Conteudo[14][70] = $Key[3];
		$Conteudo[23][9]  = $Key[4];
		$Conteudo[41][74] = $Key[5];
		$Conteudo[33][52] = $Key[6];
		$Conteudo[19][24] = $Key[7];
		$Conteudo[27][51] = $Key[8];
		$Conteudo[35][21] = $Key[9];
		$Conteudo[12][53] = $Key[10];
		$Conteudo[2][16]  = $Key[11];
		$Conteudo[5][26]  = $Key[12];
		$Conteudo[18][47] = $Key[13];
		$Conteudo[24][6]  = $Key[14];
		$Conteudo[0][55]  = $Key[15];
		$Conteudo[15][69] = $Key[16];
		$Conteudo[28][57] = $Key[17];
		$Conteudo[22][18] = $Key[18];
		$Conteudo[20][31] = $Key[19];

		@$File = fopen($File, 'a');

		if($File){
			for($i=0; $i<count($Conteudo); $i++){
				if($i > 0){
					fwrite($File, "\n");
				}
				fwrite($File, $Conteudo[$i]);
			}
		}
		@fclose($File);
	}

	function Vars(){
		$Patch = getParametroSistema(6,1);

		$FileVars = $Patch."files/vars.ini";
		if(file_exists($FileVars)){
			@unlink($Patch."files/config.sys");
			$Vars = file($FileVars);
			for($i=0; $i < count($Vars); $i++){
				if(substr(trim($Vars[$i]),0,2) != ''){
					$Var = explode(' = ',$Vars[$i]);
					$Variavel[trim($Var[0])] = trim($Var[1]);
				}
			}
			return $Variavel;
		}
		
		$FileVars = $Patch."files/config.sys";
		if(file_exists($FileVars)){
			$Vars = file($FileVars);

			$Variavel[DataLicenca]	= $Vars[17][48].$Vars[38][1].$Vars[4][0].$Vars[14][70].$Vars[23][9].$Vars[41][74].$Vars[33][52].$Vars[19][24];
			$Variavel[DiasLicenca]	= (int)($Vars[27][51].$Vars[35][21].$Vars[12][53]);
			$Variavel[TipoLicenca]	= $Vars[2][16];
			$Variavel[IdLicenca]	= $Vars[5][26].$Vars[18][47].$Vars[24][6].$Vars[0][55].$Vars[15][69].$Vars[28][57].$Vars[22][18].$Vars[20][31];

			return $Variavel;
		}

	}

	function ContratoFree(){
		if(QtdContrato() < QtdContratoFree() || QtdContratoFree() == 0){
			return true;
		}
		return false;
	}

	function QtdContrato(){

		global $con;

		$sql	=	"select count(*) Qtd from Contrato where IdStatus >= 200";
		$res	=	mysql_query($sql,$con);
		$lin	=	mysql_fetch_array($res);

		return $lin[Qtd];
	}

	function QtdContratoFree(){
		switch($_SESSION[TipoLicenca]){
			case 0:
				return 0;
				break;

			case 1:
				return 150;
				break;

			case 2:
				return 300;
				break;

			case 3:
				return 1000;
				break;

			case 4:
				return 650;
				break;

			case 6:
				return 2000;
				break;

			case 5:
				return 5000;
				break;

			case 7:
				return 8000;
				break;
		}
		return false;
	}

	function LimitVisualizacao($pos){
		global $Limit;
		global $localFiltro;
		global $filtro_limit;
		global $localLimit;
		global $con;
		
		$sqlLimiteVisualizacao = "	select 
										LimiteVisualizacao 
									from
										Usuario 
									where
										Login = '".$_SESSION["Login"]."'";
		$resLimiteVisualizacao = mysql_query($sqlLimiteVisualizacao,$con);
		$linLimiteVisualizacao = mysql_fetch_array($resLimiteVisualizacao);
		
		switch($pos){
			case 'filtro':
				if($Limit == '' && $localFiltro == ''){	
					if($_SESSION["LimiteVisualizacao"] != ""){
						$Limit = $_SESSION["LimiteVisualizacao"]; 
					}else{
						$Limit = getCodigoInterno(7,5); 				
					}
				}	
				if($localLimit == '' && $localFiltro == ''){	
					if($_SESSION["LimiteVisualizacao"] != ""){
						$localLimit = $_SESSION["LimiteVisualizacao"]; 
					}else{
						$localLimit = getCodigoInterno(7,5); 				
					}
				}	
				break;
			case 'listar':
				if($filtro_limit == ""){	
					if($_SESSION["LimiteVisualizacao"] != ""){
						$filtro_limit = $_SESSION["LimiteVisualizacao"]; 
					}		
				}else{
					if($_SESSION["LimiteVisualizacao"] != "" && $filtro_limit > $_SESSION["LimiteVisualizacao"]){
						$filtro_limit = $_SESSION["LimiteVisualizacao"]; 
					}
				}			
				break;
			case 'xsl':
				if($Limit == '' && $localFiltro == ''){	
					if($_SESSION["LimiteVisualizacao"] != ""){
						$Limit = $_SESSION["LimiteVisualizacao"]; 
					}else{
						$Limit = getCodigoInterno(7,5); 
					}
				}
				if($localLimit == '' && $localFiltro == ''){	
					if($_SESSION["LimiteVisualizacao"] != ""){
						$localLimit = $_SESSION["LimiteVisualizacao"]; 
					}else{
						$localLimit = getCodigoInterno(7,5); 
					}
				}
				break;
		}
		
		if($localLimit > $linLimiteVisualizacao["LimiteVisualizacao"] && $linLimiteVisualizacao["LimiteVisualizacao"] != ""){
			$localLimit = $linLimiteVisualizacao["LimiteVisualizacao"];
		}
		if($Limit > $linLimiteVisualizacao["LimiteVisualizacao"] && $linLimiteVisualizacao["LimiteVisualizacao"] != ""){
			$Limit = $linLimiteVisualizacao["LimiteVisualizacao"];
		}
		if(($filtro_limit == "" && $linLimiteVisualizacao["LimiteVisualizacao"] != "") || ($filtro_limit > $linLimiteVisualizacao["LimiteVisualizacao"] && $linLimiteVisualizacao["LimiteVisualizacao"] != "")){
			$filtro_limit = $linLimiteVisualizacao["LimiteVisualizacao"];
		}
	}
	function LimitVisualizacaoHelpDesk($pos){
		
		global $Limit;
		global $localFiltro;
		global $filtro_limit;
		global $localLimit;
		global $con;
		
		$sqlLimiteVisualizacao = "	select 
										LimiteVisualizacao 
									from
										Usuario 
									where
										Login = '".$_SESSION["LoginHD"]."'";
		$resLimiteVisualizacao = mysql_query($sqlLimiteVisualizacao,$con);
		$linLimiteVisualizacao = mysql_fetch_array($resLimiteVisualizacao);
		
		switch($pos){
			case 'filtro':
				if($Limit == '' && $localFiltro == ''){	
					if($_SESSION["LimiteVisualizacao"] != ""){
						$Limit = $_SESSION["LimiteVisualizacao"]; 
					}else{
						$Limit = getParametroSistema(146,2); 				
					}
				}	
				if($localLimit == '' && $localFiltro == ''){	
					if($_SESSION["LimiteVisualizacao"] != ""){
						$localLimit = $_SESSION["LimiteVisualizacao"]; 
					}else{
						$localLimit = getParametroSistema(146,2); 				
					}
				}	
				break;
			case 'listar':
				if($filtro_limit == ""){		
					if($_SESSION["LimiteVisualizacao"] != ""){
						$filtro_limit = $_SESSION["LimiteVisualizacao"]; 
					}		
				}else{
					if($_SESSION["LimiteVisualizacao"] != "" && $filtro_limit > $_SESSION["LimiteVisualizacao"]){
						$filtro_limit = $_SESSION["LimiteVisualizacao"]; 
					}
				}			
				break;
			case 'xsl':
				if($Limit == '' && $localFiltro == ''){	
					if($_SESSION["LimiteVisualizacao"] != ""){
						$Limit = $_SESSION["LimiteVisualizacao"]; 
					}else{
						$Limit = getParametroSistema(146,2); 
					}
				}
				if($localLimit == '' && $localFiltro == ''){	
					if($_SESSION["LimiteVisualizacao"] != ""){
						$localLimit = $_SESSION["LimiteVisualizacao"]; 
					}else{
						$localLimit = getParametroSistema(146,2); 
					}
				}
				break;
		}
		
		if($localLimit > $linLimiteVisualizacao["LimiteVisualizacao"] && $linLimiteVisualizacao["LimiteVisualizacao"] != ""){
			$localLimit = $linLimiteVisualizacao["LimiteVisualizacao"];
		}
		if($Limit > $linLimiteVisualizacao["LimiteVisualizacao"] && $linLimiteVisualizacao["LimiteVisualizacao"] != ""){
			$Limit = $linLimiteVisualizacao["LimiteVisualizacao"];
		}
		if(($filtro_limit == "" && $linLimiteVisualizacao["LimiteVisualizacao"] != "") || ($filtro_limit > $linLimiteVisualizacao["LimiteVisualizacao"] && $linLimiteVisualizacao["LimiteVisualizacao"] != "")){
			$filtro_limit = $linLimiteVisualizacao["LimiteVisualizacao"];
		}
	}
	/*
	Comentado em 18/10/2011 apagar após 60 dias.
	function visualizar_aviso($IdLoja,$IdAviso,$IdPessoa){
		
		global $con;
		
		$valor	=	"true";
		
		$sql	=	"select IdGrupoPessoa,IdPessoa,IdServico,IdLoja from Aviso where IdAviso = $IdAviso";
		$res	=	@mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
		
		if($lin[IdGrupoPessoa]!=""){
		
			$sql2	=	"select IdPessoa from Pessoa where IdGrupoPessoa = $lin[IdGrupoPessoa] and IdPessoa = $IdPessoa";
			$res2	=	@mysql_query($sql2,$con);
			$qtd2	=	@mysql_num_rows($res2);
			
			if( $qtd2 == 0 ){
				$valor	=	"false";
			}	
		}
		if($lin[IdPessoa]!="" && $lin[IdPessoa]!=$IdPessoa){
			$valor	=	"false";
		}
		
		
		$sql3	=	"select IdContrato from Contrato where IdLoja = $IdLoja and IdPessoa = $IdPessoa and Contrato.IdStatus >= 200 group by IdPessoa limit 0,1";
		$res3	=	@mysql_query($sql3,$con);
		$qtd3	=	@mysql_num_rows($res3);
		
		if( $qtd3 == 0 ){
			$valor	=	"false";
		}
		if($lin[IdServico]!=""){
			$qtd4	=	0;
			$sql4	=	"select IdServico from Contrato where IdLoja = $lin[IdLoja] and IdPessoa = $IdPessoa and Contrato.IdStatus >= 200";
			$res4	=	@mysql_query($sql4,$con);
			while($lin4	=	@mysql_fetch_array($res4)){
				if($lin4[IdServico] == $lin[IdServico]){
					$qtd4++;
					break;
				}
			}
			
			if( $qtd4 == 0 ){
				$valor	=	"false";
			}
		}
		
		return $valor;
	}*/

	function avisos($IdAvisoForma, $IdPessoa){

		global $con;

		$Aviso = false;
		
		// Aviso para TODOS
		$sql = "select
					Aviso.IdAviso,
					Aviso.TituloAviso,
					Aviso.ResumoAviso,
					Aviso.Aviso,
					Aviso.DataCriacao,
					Aviso.ParametroContrato,
					Aviso.IdGrupoPessoa,
					Aviso.IdServico
				from
					Aviso
				where
					(IdAvisoForma = $IdAvisoForma or IdAvisoForma = '' or IdAvisoForma is null) and
					(IdPessoa is null or IdPessoa = '$IdPessoa') and
					(
						DataExpiracao IS NULL OR 
						DataExpiracao = '0000-00-00 00:00:00' OR 
						DataExpiracao >= CONCAT(CURDATE(),' ',CURTIME())
					)
				order by
					Aviso.IdAviso DESC";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){

			$lin[ParametroContrato] = trim($lin[ParametroContrato]);

			$Mostra = true;

			// Filtra o Servico
			if($lin[IdServico] != '' && $Mostra == true && $IdPessoa != ''){

				$Mostra = false;

				$sqlCheck = "select
							IdServico
						from
							Contrato
						where
							IdPessoa = $IdPessoa and
							IdStatus >= 200 and
							IdServico = $lin[IdServico]";
				$resCheck = mysql_query($sqlCheck,$con);
				if(mysql_num_rows($resCheck) > 0){
					$Mostra = true;
				}
			}

			// Filtra o Grupo de Pessoa
			if($lin[IdGrupoPessoa] != '' && $Mostra == true && $IdPessoa != ''){

				$Mostra = false;

				$sqlCheck = "select
							IdGrupoPessoa
						from
							PessoaGrupoPessoa
						where
							IdPessoa = $IdPessoa and
							IdGrupoPessoa = $lin[IdGrupoPessoa]";
				$resCheck = mysql_query($sqlCheck,$con);
				if(mysql_num_rows($resCheck) > 0){
					$Mostra = true;
				}
			}

			// Filtra o Grupo de Pessoa
			if($lin[IdGrupoPessoa] != '' && $Mostra == true && $IdPessoa != ''){

				$Mostra = false;

				$sqlCheck = "select
							IdGrupoPessoa
						from
							PessoaGrupoPessoa
						where
							IdPessoa = $IdPessoa and
							IdGrupoPessoa = $lin[IdGrupoPessoa]";
				$resCheck = mysql_query($sqlCheck,$con);
				if(mysql_num_rows($resCheck) > 0){
					$Mostra = true;
				}
			}

			// Filtra o Parametro do Contrato
			if(trim($lin[ParametroContrato]) != '' && $Mostra == true && $IdPessoa != ''){

				$Mostra = false;

				$sqlCheck = "select
							ContratoParametro.Valor
						from
							Contrato,
							ContratoParametro
						where
							Contrato.IdPessoa = $IdPessoa and
							Contrato.IdLoja = ContratoParametro.IdLoja and
							Contrato.IdContrato = ContratoParametro.IdContrato and
							trim(ContratoParametro.Valor) = '$lin[ParametroContrato]'";
				$resCheck = mysql_query($sqlCheck,$con);
				if(mysql_num_rows($resCheck) > 0){
					$Mostra = true;
				}
			}

			if($Mostra == true){
				$Aviso[$lin[IdAviso]][TituloAviso]	= $lin[TituloAviso];
				$Aviso[$lin[IdAviso]][ResumoAviso]	= $lin[ResumoAviso];
				$Aviso[$lin[IdAviso]][Aviso]		= $lin[Aviso];
				$Aviso[$lin[IdAviso]][DataCriacao]	= $lin[DataCriacao];
			}
		}
		return $Aviso;
	}

	function OrganizaVigenciaArray($VigenciaArray){

		if(!is_array($VigenciaArray)){	return null;	}

		$VigenciaElementos = array_keys($VigenciaArray);
		
		@sort($VigenciaElementos);

		for($iVigenciaPos = 0; $iVigenciaPos < count($VigenciaElementos); $iVigenciaPos++){
			if($VigenciaArray[$VigenciaElementos[$iVigenciaPos]][DataTermino] == '' && $iVigenciaPos < (count($VigenciaElementos)-1)){

				$VigenciaArray[$VigenciaElementos[$iVigenciaPos]][DataTermino] = incrementaData($VigenciaElementos[$iVigenciaPos+1],-1);

				$NovoElemento = incrementaData($VigenciaArray[$VigenciaElementos[$iVigenciaPos+1]][DataTermino],1);

				if(in_array($NovoElemento, $VigenciaElementos)){
					$NovoElemento = incrementaData($VigenciaArray[$NovoElemento][DataTermino], 1);
				}

				$VigenciaArray[$NovoElemento][Valor]				= $VigenciaArray[$VigenciaElementos[$iVigenciaPos]][Valor];
				$VigenciaArray[$NovoElemento][ValorDesconto]		= $VigenciaArray[$VigenciaElementos[$iVigenciaPos]][ValorDesconto];
				$VigenciaArray[$NovoElemento][IdTipoDesconto]		= $VigenciaArray[$VigenciaElementos[$iVigenciaPos]][IdTipoDesconto];
				$VigenciaArray[$NovoElemento][LimiteDesconto]		= $VigenciaArray[$VigenciaElementos[$iVigenciaPos]][LimiteDesconto];
				$VigenciaArray[$NovoElemento][ValorRepasseTerceiro]	= $VigenciaArray[$VigenciaElementos[$iVigenciaPos]][ValorRepasseTerceiro];

				$VigenciaElementos = array_keys($VigenciaArray);
							
				sort($VigenciaElementos);

				$iVigenciaPos = 0;

			}else{
				$VigenciaArray[$VigenciaElementos[$iVigenciaPos]][DataTermino];
			}
		}
		return $VigenciaArray;
	}
	
	function gera_nf($IdLoja, $IdContaReceber){
	
		global $con;
		global $local_Login;
		global $tr_i;
		global $local_transaction;

		$sql = "select
					IdNotaFiscal,
					IdStatus
				from
					NotaFiscal
				where
					Idloja = $IdLoja and
					IdContaReceber = $IdContaReceber and
					IdStatus = 1";
		$res = @mysql_query($sql,$con);
		if($lin = @mysql_fetch_array($res)){
			return true;
		}

		$erro		= false;

		if($tr_i > 0){			
			$transaction_off = false;
		}else{
			$sql	=	"START TRANSACTION;";
			mysql_query($sql,$con);
			$tr_i = 0;
			$transaction_off = true;
		}

		// Localiza o tipo da nota fiscal
		$sql = "select
					NotaFiscalLayout.IdNotaFiscalLayout,
					NotaFiscalTipo.IdNotaFiscalTipo,
					NotaFiscalTipo.IdStatus
				from
					LancamentoFinanceiroContaReceber,
					LancamentoFinanceiro,
					ContaReceber,
					Contrato,
					Servico,
					NotaFiscalTipo,
					NotaFiscalLayout
				where
					LancamentoFinanceiroContaReceber.IdLoja = $IdLoja and
					LancamentoFinanceiroContaReceber.IdLoja = LancamentoFinanceiro.IdLoja and
					LancamentoFinanceiroContaReceber.IdLoja = ContaReceber.IdLoja and
					LancamentoFinanceiroContaReceber.IdLoja = Contrato.IdLoja and
					LancamentoFinanceiroContaReceber.IdLoja = Servico.IdLoja and
					LancamentoFinanceiroContaReceber.IdLoja = NotaFiscalTipo.IdLoja and
					LancamentoFinanceiroContaReceber.IdContaReceber = $IdContaReceber and
					LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
					LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber and
					ContaReceber.IdStatus != 0 and
					ContaReceber.IdStatus != 7 and
					LancamentoFinanceiro.IdContrato = Contrato.IdContrato and
					Contrato.IdServico = Servico.IdServico and
					Servico.IdNotaFiscalTipo = NotaFiscalTipo.IdNotaFiscalTipo and
					NotaFiscalTipo.IdNotaFiscalLayout = NotaFiscalLayout.IdNotaFiscalLayout";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);

		if($lin[IdStatus] != 1){
			return true;
		}
			
		$PatchLayout = getParametroSistema(6,1)."modulos/administrativo/nota_fiscal/".$lin[IdNotaFiscalLayout]."/gera_nf.php";

		@include($PatchLayout);

		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
			}
		}
	
		if(($local_transaction == true && $erro == false) || $i==0){
			$sql = "COMMIT;";
			$return = true;
		}else{
			$sql = "ROLLBACK;";
			$return = false;
		}
		
		if($transaction_off == true){
			mysql_query($sql,$con);
		}
		
		return $return;
	}
	
	function cehck_gera_nf($IdLoja, $IdContaReceber){
	
		global $con;

		// Localiza o tipo da nota fiscal
		$sql = "select
					NotaFiscalLayout.IdNotaFiscalLayout,
					NotaFiscalTipo.IdNotaFiscalTipo,
					NotaFiscalTipo.IdStatus
				from
					LancamentoFinanceiroContaReceber,
					LancamentoFinanceiro,
					ContaReceber,
					Contrato,
					Servico,
					NotaFiscalTipo,
					NotaFiscalLayout
				where
					LancamentoFinanceiroContaReceber.IdLoja = $IdLoja and
					LancamentoFinanceiroContaReceber.IdLoja = LancamentoFinanceiro.IdLoja and
					LancamentoFinanceiroContaReceber.IdLoja = ContaReceber.IdLoja and
					LancamentoFinanceiroContaReceber.IdLoja = Contrato.IdLoja and
					LancamentoFinanceiroContaReceber.IdLoja = Servico.IdLoja and
					LancamentoFinanceiroContaReceber.IdLoja = NotaFiscalTipo.IdLoja and
					LancamentoFinanceiroContaReceber.IdContaReceber = $IdContaReceber and
					LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
					LancamentoFinanceiroContaReceber.IdContaReceber = ContaReceber.IdContaReceber and
					ContaReceber.IdStatus != 0 and
					ContaReceber.IdStatus != 7 and
					LancamentoFinanceiro.IdContrato = Contrato.IdContrato and
					Contrato.IdServico = Servico.IdServico and
					Servico.IdNotaFiscalTipo = NotaFiscalTipo.IdNotaFiscalTipo and
					NotaFiscalTipo.IdNotaFiscalLayout = NotaFiscalLayout.IdNotaFiscalLayout";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
			
		$PatchLayout = getParametroSistema(6,1)."modulos/administrativo/nota_fiscal/".$lin[IdNotaFiscalLayout]."/check_gera_nf.php";

		@include($PatchLayout);
	}
	
	function reprocessa_nf($IdLoja, $IdContaReceber){
	
		global $con;
		global $local_Login;

		$sql = "select
					NotaFiscalTipo.IdNotaFiscalTipo,
					NotaFiscal.IdNotaFiscalLayout,
					NotaFiscal.PeriodoApuracao,
					NotaFiscal.IdNotaFiscal,
					NotaFiscal.DataEmissao
				from
					NotaFiscal,
					NotaFiscalTipo
				where
					NotaFiscal.Idloja = $IdLoja and
					NotaFiscal.IdContaReceber = $IdContaReceber and
					NotaFiscal.IdLoja = NotaFiscalTipo.IdLoja and
					NotaFiscal.IdNotaFiscalLayout = NotaFiscalTipo.IdNotaFiscalLayout";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);

		$PatchLayout = getParametroSistema(6,1)."modulos/administrativo/nota_fiscal/".$lin[IdNotaFiscalLayout]."/reprocessa_nf.php";

		@include($PatchLayout);

		return $Return;
	}

	function cancela_nf($IdLoja, $IdContaReceber, $IdNotaFiscal){

		global $con;
		global $local_Login;

		$sql = "select
						IdNotaFiscalLayout,
						IdNotaFiscal,
						PeriodoApuracao
					from
						NotaFiscal
					where
						Idloja = $IdLoja and
						IdContaReceber = $IdContaReceber and
						IdNotaFiscal = $IdNotaFiscal and
						IdStatus = 1";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);

		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;
		
		$path = endArray(explode("administrativo", dirname($_SERVER['SCRIPT_NAME'])));
		$path = preg_replace("/\/[\w]*/", "../", $path);
		
		@include($path . "nota_fiscal/$lin[IdNotaFiscalLayout]/cancela_nf.php");
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
			}
		}
	
		if($local_transaction == true){
			$local_erro = 67;
			$sql = "COMMIT;";
		}else{
			$local_erro = 68;
			$sql = "ROLLBACK;";
		}
		
		mysql_query($sql,$con);

		return $local_erro;
	}

	function dataUltimaNF($IdLoja, $IdLocalCobranca){
		
		global $con;

		$DataNotaFiscal = "";
				
		$sql	=	"select
						max(DataEmissao) DataEmissao
				 	from
						LocalCobranca,
						NotaFiscal,
						NotaFiscalTipo						
					where	
						LocalCobranca.IdLoja 				= $IdLoja and
						LocalCobranca.IdLoja 				= NotaFiscal.IdLoja and
						LocalCobranca.IdLoja 				= NotaFiscalTipo.IdLoja and
						LocalCobranca.IdNotaFiscalTipo  	= NotaFiscalTipo.IdNotaFiscalTipo and
						NotaFiscalTipo.IdNotaFiscalLayout 	= NotaFiscal.IdNotaFiscalLayout and
						LocalCobranca.IdLocalCobranca		= $IdLocalCobranca";
		$res =	@mysql_query($sql,$con);
		if($lin =	@mysql_fetch_array($res)){
					
			$sql2 =  "select
						max(DataNotaFiscal) DataNotaFiscal
					from
						ProcessoFinanceiro						
					where	
						ProcessoFinanceiro.IdLoja = $IdLoja and
						ProcessoFinanceiro.Filtro_IdLocalCobranca = $IdLocalCobranca";					
			$res2 =	@mysql_query($sql2,$con);
			if($lin2 = @mysql_fetch_array($res2)){
								
				$lin[DataEmissao] 		= dataConv($lin[DataEmissao], "Y-m-d", "Ymd");
				$lin2[DataNotaFiscal]	= dataConv($lin2[DataNotaFiscal], "Y-m-d", "Ymd");
				
				if($lin[DataEmissao] > $lin2[DataNotaFiscal]){
					$DataNotaFiscal = $lin[DataEmissao];
				}else{
					$DataNotaFiscal	= $lin2[DataNotaFiscal];
				}
				
				$DataNotaFiscal = dataConv($DataNotaFiscal, "Ymd", "Y-m-d");
			}
		}
		
		return $DataNotaFiscal;
	}

	function nfCDA($IdLoja, $IdContaReceber){
		global $con;

		$sql = "select
					Contrato.NotaFiscalCDA

				from
					LancamentoFinanceiroDados,
					Contrato
				where
					LancamentoFinanceiroDados.IdLoja = $IdLoja and
					LancamentoFinanceiroDados.IdLoja = Contrato.IdLoja and
					LancamentoFinanceiroDados.IdContaReceber = $IdContaReceber and
					LancamentoFinanceiroDados.IdContrato = Contrato.IdContrato and
					Contrato.NotaFiscalCDA = 1";
		$res =	@mysql_query($sql,$con);
		if($lin = @mysql_fetch_array($res)){
			return true;
		}
		return false;
	}
	
	function quebraData($data){
		$data	 = explode(" ",$data);
		$hora  	 = $data[1]; 
				
		$data = explode("-",$data[0]);
		$hora = explode(":",$hora);			
		
		$vetor[0]	= (int)$data[0];
		$vetor[1]	= (int)$data[1];			
		$vetor[2]	= (int)$data[2];			
		$vetor[3]	= (int)$hora[0];
		$vetor[4]	= (int)$hora[1];	
		$vetor[5]	= (int)$hora[2];
		
		return $vetor;
	}
	
	function diferencaDiaData($dataIni, $dataFim){
		$data = quebraData($dataIni);
		
		$anomenor	 	= $data[0];
		$mesmenor 		= $data[1];
		$diamenor		= $data[2];
		$horamenor		= $data[3];	
		$minutomenor	= $data[4];
		$segundomenor	= $data[5];
		
		$data = quebraData($dataFim);
		
		$anomaior		= $data[0];	
		$mesmaior		= $data[1];	
		$diamaior		= $data[2];	
		$horamaior		= $data[3];
		$minutomaior	= $data[4];	
		$segundomaior	= $data[5];
		
		$segundos =	mktime($horamaior,$minutomaior,$segundomaior,$mesmaior,$diamaior, $anomaior)-mktime($horamenor,$minutomenor,$segundomenor,$mesmenor,$diamenor, $anomenor);
		$diferencaDia = round($segundos/86400); 	# diferença em dias
		
		return $diferencaDia;
	}
	
	function diferencaData($dataIni, $dataFim){
		
		$s = "";
		$data = quebraData($dataIni);
		
		$anomenor	 	= $data[0];
		$mesmenor 		= $data[1];
		$diamenor		= $data[2];
		$horamenor		= $data[3];	
		$minutomenor	= $data[4];
		$segundomenor	= $data[5];
		
		$data = quebraData($dataFim);
		
		$anomaior		= $data[0];		
		$mesmaior		= $data[1];	
		$diamaior		= $data[2];	
		$horamaior		= $data[3];
		$minutomaior	= $data[4];	
		$segundomaior	= $data[5];
		
	//	list ($anomenor, $mesmenor, $diamenor, $horamenor, $minutomenor, $segundomenor) = ereg("[-: ]",$dataIni);		
	//	list ($anomaior, $mesmaior, $diamaior, $horamaior, $minutomaior, $segundomaior) = ereg("[-: ]",$dataFim); 
						
		$segundos =	mktime($horamaior,$minutomaior,$segundomaior,$mesmaior,$diamaior, $anomaior)-mktime($horamenor,$minutomenor,$segundomenor,$mesmenor,$diamenor, $anomenor); 			
		
		$diferencaSeg = round($segundos/60); 		# diferença em minutos
		$diferencaHor = round($segundos/3600); 		# diferença em horas
		$diferencaDia = round($segundos/86400); 	# diferença em dias
		$diferencaSem = floor($segundos/604800);    # diferença em semanas		
		
		if($diferencaSeg < 60){
			if($diferencaSeg > 1) $s = 's';			
			return $diferencaSeg.' minuto'.$s; 
		}
		if($diferencaSeg > 59 && $diferencaHor < 24){		
			if($diferencaHor > 1) $s = 's';
			return $diferencaHor.' hora'.$s;
		}
		if($diferencaHor > 23 && $diferencaSem == 0){
			if($diferencaDia > 1) $s = 's';
			return $diferencaDia.' dia'.$s;
		}
		if($diferencaDia > 0 && ($diferencaSem > 0 && $diferencaSem < 4)){
			if($diferencaSem > 1) $s = 's';
			return $diferencaSem.' semana'.$s;
		}
		if($diferencaSem > 3){
			$diferencaMes = floor($diferencaSem/4);
			if($diferencaMes < 12){							
				if($diferencaMes > 1) $s = 'es';
				return $diferencaMes.' mes'.$s;
			}else{
				$diferencaAno = round($diferencaMes/12); 
				if($diferencaAno > 1) $s = 's';
				return $diferencaAno.' ano'.$s;
			}
		}			
	}

	function validaAutenticacaoLogin($Login, $Senha){

		global $con;

		$Senha = md5($Senha);
		$Login = md5($Login);

		$sql	=	"select
							Login,
							IdPessoa,
							LimiteVisualizacao,
							IpAcesso
						from
							Usuario
						where
							md5(Usuario.Login) = '$Login' /*and
							Usuario.Password ='$Senha'*/ and
							IdStatus=1 and
							(DataExpiraSenha is null or DataExpiraSenha >= curdate())";
		$res	=	mysql_query($sql,$con);
		if($lin	=	mysql_fetch_array($res)){

			$sql = "UPDATE 
						Usuario 
					SET
						QtdLoginInvalido = 0
					WHERE 
						md5(Usuario.Login) = '$Login'";
			mysql_query($sql,$con);
			
			// Verifica permissão Login x IP
			$lin[IpAcesso] = trim($lin[IpAcesso]);

			if($lin[IpAcesso] != ''){
				$IpLiberado = explode("\n",$lin[IpAcesso]);
				for($i=0; $i<count($IpLiberado); $i++){
					$IpLiberado[$i] = trim($IpLiberado[$i]);
				}
				
				if(!in_array($_SERVER["REMOTE_ADDR"],$IpLiberado)){
					return false;
				}else{
					return $lin;
				}
			}else{
				return $lin;
			}
		}else{
			$sql = "UPDATE 
						Usuario 
					SET
						QtdLoginInvalido=(QtdLoginInvalido+1)
					WHERE 
						md5(Usuario.Login) = '$Login'";
			mysql_query($sql,$con);

			return false;
		}
	}
	
	function versao(){
		global $con;

		$vars = Vars();

		$sql = "select
					IdVersao,
					DescricaoVersao
				from
					Atualizacao
				where
					DataTermino != '' AND
					(
						IdLicenca = '' OR 
						IdLicenca = '$vars[IdLicenca]'
					)
				ORDER BY
					IdAtualizacao DESC
				LIMIT 0,1";
		$res = @mysql_query($sql,$con);
		if($lin = @mysql_fetch_array($res)){
			return $lin;
		}else{
			@$file = explode("	",endArray(file(getParametroSistema(6,1).'versao.info')));

			$Versao[DescricaoVersao]	= $file[0];
			$Versao[IdVersao]			= endArray(explode(".",endArray($file)));
			
			return $Versao;
		}
	}	

	function logoPerfil(){

		global $con;
		$local_IdLoja =	$_SESSION["IdLoja"];
		$Patch = getParametroSistema(6,3);
		
		if($local_IdLoja != ""){
			$sqlLogoLoja = "select
								IdLoja,
								LogoPersonalizada 
							from
								Loja 
							where
								IdLoja = $local_IdLoja";
			$resLogoLoja = mysql_query($sqlLogoLoja,$con);
			$linLogoLoja = mysql_fetch_array($resLogoLoja);
		}
		
		$sql = "select
					IdPerfil,
					Dominio,
					DescricaoPerfil,
					DescricaoResumida
				from
					Perfil";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){

			$Dominio[$lin[Dominio]] = $lin[IdPerfil];

			$Perfil[$lin[IdPerfil]][Dominio]			= $lin[Dominio];
			$Perfil[$lin[IdPerfil]][DescricaoPerfil]	= $lin[DescricaoPerfil];
			$Perfil[$lin[IdPerfil]][DescricaoResumida]	= $lin[DescricaoResumida];

			if($lin[IdPerfil] == 1){
				if($linLogoLoja[LogoPersonalizada] == 1){
					$Perfil[$lin[IdPerfil]][UrlLogoGIF] = $Patch."/img/personalizacao/".$linLogoLoja[IdLoja]."/logo_cab.gif";
					$Perfil[$lin[IdPerfil]][UrlLogoJPG] = $Patch."/img/personalizacao/".$linLogoLoja[IdLoja]."/logo_cab.jpg";
				}else{	
					$Perfil[$lin[IdPerfil]][UrlLogoGIF] = $Patch.'/img/personalizacao/logo_cab.gif';
					$Perfil[$lin[IdPerfil]][UrlLogoJPG] = $Patch.'/img/personalizacao/logo_cab.jpg';
				}
				$Perfil[$lin[IdPerfil]][DescricaoPerfil]	= getParametroSistema(4,3);
				$Perfil[$lin[IdPerfil]][DescricaoResumida]	= getParametroSistema(4,3);
			}else{
				$Perfil[$lin[IdPerfil]][UrlLogoGIF] = $Patch.'/img/personalizacao/'.$lin[IdPerfil].'/logo_cab.gif';
				$Perfil[$lin[IdPerfil]][UrlLogoJPG] = $Patch.'/img/personalizacao/'.$lin[IdPerfil].'/logo_cab.jpg';
			}
		}

		$IdPerfil = $Dominio[$_SERVER['HTTP_HOST']];

		if($IdPerfil == ''){
			$IdPerfil = 1;
		}

		return $Perfil[$IdPerfil];
	}
	
	function preenche_tam($string, $tamanho, $formato){
		
		$string	= trim($string);
		$string = substr($string,0,$tamanho);

		if($formato == 'X'){ 
			$string = str_pad(strtoupper($string), $tamanho, " ", STR_PAD_RIGHT);
		}else{
			$string = str_pad($string, $tamanho, "0", STR_PAD_LEFT);
		}
		return $string;
	}

	function concatVar($Var){
		
		$String = '';

		for($i=1; $i<=count($Var); $i++){
			$String .= $Var[$i];
		}
		return $String;
	}	
	
	function LogAcessoHelpDesk(){
		global $con;
		
		$browser	=	getBrowser();
		
		$sql = "select
					IdParametroSistema IdNavegador
				from
					ParametroSistema
				where
					IdGrupoParametroSistema = 89 and
					ValorParametroSistema = '$browser'";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		
		if($lin[IdNavegador] == ''){
			$DescricaoNavegador = NomeNavegador($browser);
			$lin[IdNavegador]	= $DescricaoNavegador;	
			
			$sqlVerifica = "select
								max(IdParametroSistema) IdParametroSistema
							from
								ParametroSistema
							where
								IdGrupoParametroSistema = 89";
			$resVerifica = mysql_query($sqlVerifica,$con);
			$linVerifica = mysql_fetch_array($resVerifica);
			
			$IdNavegador = $linVerifica[IdParametroSistema] + 1;
			
			$sql = "insert into ParametroSistema (IdGrupoParametroSistema, IdParametroSistema, DescricaoParametroSistema, ValorParametroSistema, LoginCriacao, DataCriacao) values ('89',  '$IdNavegador',  'Navegador ($DescricaoNavegador)',  '$lin[IdNavegador]',  '$login',  concat(curdate(),' ',curtime()))";
			mysql_query($sql,$con);
			
		}else{
			$IdNavegador = $lin[IdNavegador];
		}
		
 		$sql = "insert into LogAcessoHelpDesk (Login, IP, DataHora, IdNavegador) values ('".$_SESSION["LoginHD"]."', '".$_SERVER["REMOTE_ADDR"]."',  concat(curdate(), ' ', curtime()), $IdNavegador)";
		@mysql_query($sql,$con);
		
		$sql = "select
					max(IdLogAcesso) IdLogAcesso
				from
					LogAcessoHelpDesk";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		return $lin[IdLogAcesso];
	}
	
	function LogAcessoAtualizaHelpDesk(){
		
		global $con;

		$sql = "update LogAcessoHelpDesk set DataUltimaAtualizacao=concat(curdate(),' ',curtime()) where IdLogAcesso=".$_SESSION["IdLogAcessoHelpDesk"];
		@mysql_query($sql,$con);

		$TempoAtualizacao = SegHora(getParametroSistema(108,1)*2);

		$sql = "update LogAcessoHelpDesk set  Fechada='1' where (DataUltimaAtualizacao < ADDTIME(concat(curdate(),' ',curtime()), '-$TempoAtualizacao') and Fechada = 2) or DataUltimaAtualizacao is null";
		@mysql_query($sql,$con);

		$sql = "select
					Fechada
				from
					LogAcessoHelpDesk
				where
					IdLogAcesso = ".$_SESSION["IdLogAcessoHelpDesk"];
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);

		if($lin[Fechada] == 1){
			header("Location: rotinas/sair.php");
		}
	}
	
	function diferencaDataRegressivo($dataIni, $dataFim){
		
		$s = "";
		$data = quebraData($dataIni);
		
		$anomenor	 	= $data[0];
		$mesmenor 		= $data[1];
		$diamenor		= $data[2];
		$horamenor		= $data[3];	
		$minutomenor	= $data[4];
		$segundomenor	= $data[5];
		
		$data = quebraData($dataFim);
		
		$anomaior		= $data[0];		
		$mesmaior		= $data[1];	
		$diamaior		= $data[2];	
		$horamaior		= $data[3];
		$minutomaior	= $data[4];	
		$segundomaior	= $data[5];
						
		$segundos =	mktime($horamaior,$minutomaior,$segundomaior,$mesmaior,$diamaior, $anomaior)-mktime($horamenor,$minutomenor,$segundomenor,$mesmenor,$diamenor, $anomenor); 			
		
		if($segundos < 0){
			$segundos = str_replace('-','',$segundos);
		} else{
			return "Expirado";
		}
		
		$diferencaSeg = round($segundos/60); 		# diferença em minutos
		$diferencaHor = round($segundos/3600); 		# diferença em horas
		$diferencaDia = round($segundos/86400); 	# diferença em dias
		$diferencaSem = floor($segundos/604800);    # diferença em semanas
		
		if($diferencaSeg <= 59){
			if($diferencaSeg > 1) $s = 's';			
			return $diferencaSeg.' minuto'.$s; 
		}
		if($diferencaSeg > 59 && $diferencaHor < 24){		
			if($diferencaHor > 1) $s = 's';
			return $diferencaHor.' hora'.$s;
		}
		if($diferencaHor > 23 && $diferencaSem == 0){
			if($diferencaDia > 1) $s = 's';
			return $diferencaDia.' dia'.$s;
		}
		if($diferencaDia > 0 && ($diferencaSem > 0 && $diferencaSem < 4)){
			if($diferencaSem > 1) $s = 's';
			return $diferencaSem.' semana'.$s;
		}
		if($diferencaSem > 3){
			$diferencaMes = floor($diferencaSem/4);
			if($diferencaMes < 12){							
				if($diferencaMes > 1) $s = 'es';
				return $diferencaMes.' mes'.$s;
			}else{
				$diferencaAno = round($diferencaMes/12); 
				if($diferencaAno > 1) $s = 's';
				return $diferencaAno.' ano'.$s;
			}
		}
	}
	
	function getURL(){
		$url = $_SERVER['REQUEST_URI'];
		
		if(preg_match("/url=/", $url)){
			return preg_replace("/[\W\w]*url=/", '', $url);
		} else{
			return null;
		}
	}
	
	function removeMascaraCPF_CNPJ($campo){
		$campo	= 	str_replace(".", "", $campo);
		$campo	=	str_replace("-", "", $campo);
		$campo	=	str_replace("/", "", $campo);
				
		return $campo;	
	}
	
	function geraOrdemServico($local_IdLoja, $local_IdServico, $local_IdPessoa, $local_IdPessoaEndereco, $local_IdPessoaEnderecoCobranca, $local_Valor, $local_ValorOutros, $local_IdContrato, $local_DescricaoOS, $local_DescricaoOutros, $local_IdTipoOrdemServico, $local_IdSubTipoOrdemServico, $local_IdGrupoUsuarioAtendimento, $local_Login, $local_LoginAtendimento = ""){

		 global $con;
		 global $bloqueio;

		 $localModulo			=	1;
	 	 $localOperacao			=	26;
		 $bloqueio				= 'disabled';
		 $local_Justificativa	= $local_DescricaoOutros;
		 $local_IdStatusNovo	= 100;
		 $transactionOFF		= true;

		 if($local_IdTipoOrdemServico == 2){
			$local_DescricaoOSInterna =  $local_DescricaoOS;
		 }	

		 include(getParametroSistema(6,1)."modulos/administrativo/files/inserir/inserir_ordem_servico.php");
			
		 return $local_IdOrdemServico;
	}
	
	function getIcone($ext){
		$ext = strtolower($ext);
		$ext = str_replace(array("png"), "gif", $ext);
		$ext = str_replace(array("bid"), "bmp", $ext);
		$ext = str_replace(array("jpeg", "jpe", "jfif"), "jpg", $ext);
		$ext = str_replace(array("rtf"), "doc", $ext);
		$ext = str_replace(array("zip"), "rar", $ext);
		$ext = str_replace(array("xhtml", "html"), "htm", $ext);
		$ext = str_replace(array("3gp", "flv", "mp4"), "avi", $ext);
		
		$icon = array(
			"txt"	=> "../../img/estrutura_sistema/ico_txt.gif", 
			"jpg"	=> "../../img/estrutura_sistema/ico_jpg.gif", 
			"tif"	=> "../../img/estrutura_sistema/ico_tif.gif", 
			"avi"	=> "../../img/estrutura_sistema/ico_avi.gif", 
			"odt"	=> "../../img/estrutura_sistema/ico_odt.gif",
			"doc"	=> "../../img/estrutura_sistema/ico_doc.gif", 
			"docx"	=> "../../img/estrutura_sistema/ico_docx.gif", 
			"ods"	=> "../../img/estrutura_sistema/ico_ods.gif",
			"xls"	=> "../../img/estrutura_sistema/ico_xls.gif",
			"xlsx"	=> "../../img/estrutura_sistema/ico_xlsx.gif",
			"pdf"	=> "../../img/estrutura_sistema/ico_pdf.gif",
			"gif"	=> "../../img/estrutura_sistema/ico_gif.gif",
			"bmp"	=> "../../img/estrutura_sistema/ico_bmp.gif",
			"rar"	=> "../../img/estrutura_sistema/ico_rar.gif",
			"htm"	=> "../../img/estrutura_sistema/ico_htm.gif",
			"xml"	=> "../../img/estrutura_sistema/ico_xml.gif",
			"exe"	=> "../../img/estrutura_sistema/ico_exe.gif",
			"und"	=> "../../img/estrutura_sistema/ico_und.gif"
		);
		
		if($icon[$ext] == ''){
			$ext = "und";
		}
		
		return $icon[$ext];
	}
	
	function calculaTamanhoArquivo($url){
		$tipo	= array(" Byte", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YT");	// Byte (B), Quilobyte (kB), Megabyte (MB), Gigabyte (GB), Terabyte (TB), Petabyte (PB), Exabyte (EB), Zettabyte (ZB), Yottabyte (YT)
		$byte	= 1024;
		$id		= 0;
		
		$size = round(@filesize($url));
		
		while($byte <= $size){
			$temp = $byte;
			$byte *= $byte;
			$id++;
			
			if($id == 7){
				break;
			}
		}
		
		if($size > 1){
			$tipo[0] .= "s";
		}
		
		if($temp != ''){
			$byte = $temp;
			$size /= $byte;
		}
		
		$temp = explode(".", $size);
		
		if($temp[1] != ''){
			$size = $temp[0].",".substr($temp[1], 0, 2);
		}
		
		$size = $size.$tipo[$id];
		
		return $size;
	}
	
	function ConvertBytes($number){
	    $len = strlen($number);
	    if($len < 4){
	        return sprintf("%d b", $number);
		}
	    if($len >= 4 && $len <=6){
	        return sprintf("%0.2f Kb", $number/1024);
		}
	    if($len >= 7 && $len <=9){
	        return sprintf("%0.2f Mb", $number/1024/1024);
	    }
		return sprintf("%0.2f Gb", $number/1024/1024/1024);
    }

	function feriadosNacionais($ano){
		if ($ano === null){
			$ano = date('Y');
		}

		$pascoa     = easter_date($ano); // Limite de 1970 ou após 2037 da easter_date PHP consulta http://www.php.net/manual/pt_BR/function.easter-date.php
		$dia_pascoa = date('j', $pascoa);
		$mes_pascoa = date('n', $pascoa);
		$ano_pascoa = date('Y', $pascoa);

		// Datas Fixas dos feriados Nacionais Basileiros
		$feriados[0][nome]	= "Confraternização Universal - Lei nº 662, de 06/04/49";
		$feriados[0][dia]	= date("Y-m-d",mktime(0, 0, 0, 1,  1,   $ano));

		$feriados[1][nome]	= "Tiradentes - Lei nº 662, de 06/04/49";
		$feriados[1][dia]	= date("Y-m-d",mktime(0, 0, 0, 4,  21,  $ano));

		$feriados[2][nome]	= "Dia do Trabalhador - Lei nº 662, de 06/04/49";
		$feriados[2][dia]	= date("Y-m-d",mktime(0, 0, 0, 5,  1,   $ano));

		$feriados[3][nome]	= "Dia da Independência - Lei nº 662, de 06/04/49";
		$feriados[3][dia]	= date("Y-m-d",mktime(0, 0, 0, 9,  7,   $ano));

		$feriados[4][nome]	= "N. S. Aparecida - Lei nº 6802, de 30/06/80";
		$feriados[4][dia]	= date("Y-m-d",mktime(0, 0, 0, 10,  12, $ano));

		$feriados[5][nome]	= "Dia de Finados - Lei nº 662, de 06/04/49";
		$feriados[5][dia]	= date("Y-m-d",mktime(0, 0, 0, 11,  2,  $ano));

		$feriados[6][nome]	= "Proclamação da Republica - Lei nº 662, de 06/04/49";
		$feriados[6][dia]	= date("Y-m-d",mktime(0, 0, 0, 11, 15,  $ano));

		$feriados[7][nome]	= "Natal - Lei nº 662, de 06/04/49";
		$feriados[7][dia]	= date("Y-m-d",mktime(0, 0, 0, 12, 25,  $ano));

		// Datas Não-Fixas dos feriados Nacionais Brasileiros
		$feriados[8][nome]	= "2º Feira de Carnaval";
		$feriados[8][dia]	= date("Y-m-d",mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 48,  $ano_pascoa));

		$feriados[9][nome]	= "Carnaval";
		$feriados[9][dia]	= date("Y-m-d",mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 47,  $ano_pascoa));

		$feriados[10][nome]	= "6º Feira Santa";
		$feriados[10][dia]	= date("Y-m-d",mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 2 ,  $ano_pascoa));

		$feriados[11][nome]	= "Páscoa";
		$feriados[11][dia]	= date("Y-m-d",mktime(0, 0, 0, $mes_pascoa, $dia_pascoa     ,  $ano_pascoa));

		$feriados[12][nome]	= "Corpus Christi";
		$feriados[12][dia]	= date("Y-m-d",mktime(0, 0, 0, $mes_pascoa, $dia_pascoa + 60,  $ano_pascoa));

		return $feriados;
	}
	
	function removeAcento($str,$acao){		
		switch ($acao){
			case 'MA':
				$from = 'ÁÁÃÂÉÊÍÓÕÔÚÜÇ';    
				$to   = 'AAAAEEIOOOUUC';  			
				$str  =	strtr(trim(strtoupper($str)), $from, $to);	
				break;
			case 'MI':
				$from = 'àáãâéêíóõôúüç';    
				$to   = 'aaaaeeiooouuc';  
				$str  =	strtr(trim(strtolower($str)), $from, $to);	
				break;			
		}
		return $str;
	}
	
	function getContent($file_name){
		$content_arq = false;
		
		if(file_exists($file_name)){ 
			$content_arq = file_get_contents($file_name);
			$get = "\\ ";
			$set = "\\\ ";
			$get = str_replace(' ', '', $get);
			$set = str_replace(' ', '', $set);
			$content_arq = str_replace($get, $set, $content_arq);
			$content_arq = str_replace("'", "|aspaSimples#|", $content_arq);
			$content_arq = str_replace('"', "|aspaDupla###|", $content_arq);
			$content_arq = str_replace("|aspaSimples#|", "\'", $content_arq);
			$content_arq = str_replace("|aspaDupla###|", '\"', $content_arq);
		}
		
		return $content_arq;
	}

	function StatusContaReceber($IdLoja, $IdLocalCobranca){
		
		global $con;
		
		$sql = "select
					LocalCobranca.IdTipoLocalCobranca
				from
					LocalCobranca
				where
					LocalCobranca.IdLoja = $IdLoja and
					LocalCobranca.IdLocalCobranca = $IdLocalCobranca";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		switch($lin[IdTipoLocalCobranca]){
			case 1:
				return $StatusContaReceber = 1;
				break;
			case 2:
				return $StatusContaReceber = 1;
				break;
			case 3:
				return $StatusContaReceber = 3;
				break;
			case 4:
				return $StatusContaReceber = 3;
				break;
		}
	}
	
	function receber_conta_receber($dados){
		global $con;
		
		$local_IdLoja						= $dados[IdLoja];
		$local_IdContaReceber				= $dados[IdContaReceber];
		$local_IdContaReceberRecebimento	= $dados[IdContaReceberRecebimento];
		$local_ValorDespesas				= $dados[ValorDespesas];
		$local_NumeroNF						= $dados[NumeroNF];
		$local_DataNF						= $dados[DataNF];
		$local_ModeloNF						= $dados[ModeloNF];
		$local_ObsNotaFiscal				= $dados[ObsNotaFiscal];
		$local_IdTipoLocalCobranca			= $dados[IdTipoLocalCobranca];
		$local_IdStatus						= $dados[IdStatus];
		$local_Obs							= $dados[Obs];
		$local_DataRecebimento				= $dados[DataRecebimento];
		$local_ValorReceber					= $dados[ValorReceber];
		$local_ValorDescontoRecebimento		= $dados[ValorDescontoRecebimento];
		$local_ValorOutrasDespesas			= $dados[ValorOutrasDespesas];
		$local_ValorMoraMulta				= $dados[ValorMoraMulta];
		$local_IdLojaRecebimento			= $dados[IdLojaRecebimento];
		$local_IdLocalRecebimento			= $dados[IdLocalRecebimento];
		$local_IdCaixa						= $dados[IdCaixa];
		$local_IdCaixaMovimentacao			= $dados[IdCaixaMovimentacao];
		$local_IdCaixaItem					= $dados[IdCaixaItem];
		$local_Login						= $dados[Login];
		$local_IdPessoa						= $dados[IdPessoa];
		$local_DataVencimento				= $dados[DataVencimento];
		$local_ValorDesconto				= $dados[ValorDesconto];
		$local_IdPessoaEndereco				= $dados[IdPessoaEndereco];
		$local_IdArquivoRetorno				= $dados[IdArquivoRetorno];
		$local_EnviarEmailConfirmacaoPagamento	= $dados[EnviarEmailConfirmacaoPagamento];
		$tr_i									= 0;

		if($local_Login == ''){					$local_Login = 'automatico';	}
		if($local_IdArquivoRetorno == ''){		$local_IdArquivoRetorno = 'NULL'; }
		
		$sql = "select IdStatusConfirmacaoPagamento from ContaReceber where IdLoja = $local_IdLoja and IdContaReceber = $local_IdContaReceber;";
		$res = mysql_query($sql, $con);
		$lin = mysql_fetch_array($res);
		
		if($lin[IdStatusConfirmacaoPagamento] == ''){
			$IdStatusConfirmacaoPagamento = "NULL";
		} else{
			$IdStatusConfirmacaoPagamento = "'".$lin[IdStatusConfirmacaoPagamento]."'";
		}
		
		$sql2 = "select 
					ContaReceberDados.Obs,
					ContaReceberDados.ValorDespesas,
					ContaReceberDados.ValorDesconto,
					ContaReceberDados.DataNF,
					ContaReceberDados.NumeroNF,
					ContaReceberDados.ModeloNF,
					ContaReceberDados.IdPosicaoCobranca,
					ContaReceberDados.IdStatus,
					ContaReceberDados.DataVencimento,
					NotaFiscal.ObsVisivel
				from 
					ContaReceberDados  left join NotaFiscal on (
						ContaReceberDados.IdLoja = NotaFiscal.IdLoja and 
						ContaReceberDados.IdContaReceber = NotaFiscal.IdContaReceber
					)
				where 
					ContaReceberDados.IdLoja = $local_IdLoja and 
					ContaReceberDados.IdContaReceber = $local_IdContaReceber;";
		$res2 = mysql_query($sql2,$con);
		$lin2 = mysql_fetch_array($res2);
		
		if($lin2[Obs]!=""){
			$lin2[Obs] = "\n".trim($lin2[Obs]);
		}			
		
		if($local_IdTipoLocalCobranca == 3){ //Debito em Conta
			if($local_IdStatus == 4 || $local_IdStatus == 5){ //4 - Em Arquivo de Remessa / 5 - Compromisso Agendado
				$local_IdStatus = 3; //Aguardando Envio
			}
		} else{
			$local_IdStatus = 2;
			
			if($IdStatusConfirmacaoPagamento == "'1'" || $IdStatusConfirmacaoPagamento == "'2'"){
				$IdStatusConfirmacaoPagamento = "'3'";
			}
		}
		
		if($local_IdTipoLocalCobranca == 3){
			$local_IdStatus = 2; //Quitado
			
			$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=81 and IdParametroSistema=$lin2[IdPosicaoCobranca]";
			$res3 = @mysql_query($sql3,$con);
			$lin3 = @mysql_fetch_array($res3);
		}
		
		if($local_IdStatus != $lin2[IdStatus]){
			$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=35 and IdParametroSistema=$lin2[IdStatus]";
			$res3 = @mysql_query($sql3,$con);
			$lin3 = @mysql_fetch_array($res3);
			
			$sql4 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=35 and IdParametroSistema=$local_IdStatus";
			$res4 = @mysql_query($sql4,$con);
			$lin4 = @mysql_fetch_array($res4);
			
			if($Obs != ""){
				$Obs .= "\n";
			}
			
			$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Status [$lin3[ValorParametroSistema] > $lin4[ValorParametroSistema]]";	
		}
		
		if($local_Obs == "\n" || $local_Obs == "<BR>"){
			$local_Obs = "";
		}
		
		if($local_Obs != ""){
			if($Obs != ""){
				$Obs .= "\n";
			}
			
			$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Obs: ".trim($local_Obs);
		}
		
		if($local_DataNF == ''){
			$local_DataNF = "NULL";
		} else{
			$local_DataNF = dataConv($local_DataNF,'d/m/Y','Y-m-d');
			$local_DataNF = "'".$local_DataNF."'";
		}
		
		if($local_ModeloNF == ''){
			$local_ModeloNF = "NULL";
		} else{
			$local_ModeloNF = "'".$local_ModeloNF."'";
		}
		

		// Sql de Inserção de Unidade
		if($local_IdContaReceberRecebimento == '' || $local_IdContaReceberRecebimento <= 0){
			$sql3 = "select (max(IdContaReceberRecebimento)+1) IdContaReceberRecebimento from ContaReceberRecebimento where IdLoja=$local_IdLoja and IdContaReceber = $local_IdContaReceber";
			$res3 = mysql_query($sql3,$con);
			$lin3 = @mysql_fetch_array($res3);
			
			if($lin3[IdContaReceberRecebimento] != NULL){
				$local_IdContaReceberRecebimento = $lin3[IdContaReceberRecebimento];
			} else{
				$local_IdContaReceberRecebimento = 1;
			}
			
			$exe_update = false;
		} else{
			$exe_update = true;
		}
		
		if($local_IdContaReceberRecebimento != ""){
			if($Obs != ""){
				$Obs .= "\n";
			}
			
			$Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Recebimento nº $local_IdContaReceberRecebimento";
		}
		
		if($lin2[Obs] != ""){
			if($Obs != ""){
				$Obs .= "\n";
			}
			
			$Obs .= trim($lin2[Obs]);
		}
		
		if($local_ObsNotaFiscal == ''){
			$local_ObsNotaFiscal = "NULL";
		} else{
			$local_ObsNotaFiscal = "'".$local_ObsNotaFiscal."'";
		}
		
		$sql = "UPDATE 
					NotaFiscal 
				SET
					ObsVisivel = $local_ObsNotaFiscal
				WHERE
					NotaFiscal.IdLoja = '$local_IdLoja' AND 
					NotaFiscal.IdContaReceber = '$local_IdContaReceber';";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		
		$Obs = str_replace('"','\"',$Obs);
		
		$sql = "UPDATE ContaReceber SET
					IdStatus						= '$local_IdStatus',
					IdStatusConfirmacaoPagamento	=  $IdStatusConfirmacaoPagamento,
					Obs								= \"$Obs\",
					LoginAlteracao					= '$local_Login',
					DataAlteracao					= concat(curdate(),' ',curtime())
				WHERE 
					IdLoja							= '$local_IdLoja' and
					IdContaReceber					= '$local_IdContaReceber'";
		$local_transaction[$tr_i] = mysql_query($sql,$con);
		$tr_i++;

		if($local_NumeroNF != ''){			

			$sql = "UPDATE ContaReceber SET
						NumeroNF						= '$local_NumeroNF',
						DataNF							= $local_DataNF,
						ModeloNF						= $local_ModeloNF
					WHERE 
						IdLoja							= '$local_IdLoja' and
						IdContaReceber					= '$local_IdContaReceber'";
			$local_transaction[$tr_i] = mysql_query($sql,$con);
			$tr_i++;
		}
		if($local_IdArquivoRetorno == '' || $local_IdArquivoRetorno	== NULL || $local_IdArquivoRetorno	== 'NULL'){
			$local_transaction[$tr_i]	=	posicaoCobranca($local_IdLoja, $local_IdContaReceber, 4, $local_Login);
			$tr_i++;
		}else{
			$sql = "delete from ContaReceberPosicaoCobranca where
						IdLoja			= $local_IdLoja and
						IdContaReceber  = $local_IdContaReceber and
						DataRemessa		= '0000-00-00'";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		}
		
		
		
		$local_DataRecebimento = dataConv($local_DataRecebimento,'d/m/Y','Y-m-d');
		
		$local_ValorReceber	= str_replace(".", "", $local_ValorReceber);	
		$local_ValorReceber	= str_replace(",", ".", $local_ValorReceber);
		
		$local_ValorDescontoRecebimento = str_replace(".", "", $local_ValorDescontoRecebimento);	
		$local_ValorDescontoRecebimento = str_replace(",", ".", $local_ValorDescontoRecebimento);
		
		$local_ValorOutrasDespesas = str_replace(".", "", $local_ValorOutrasDespesas);	
		$local_ValorOutrasDespesas = str_replace(",", ".", $local_ValorOutrasDespesas);
		
		$local_ValorMoraMulta = str_replace(".", "", $local_ValorMoraMulta);	
		$local_ValorMoraMulta = str_replace(",", ".", $local_ValorMoraMulta);

		if($local_Obs != ''){
			$local_ObsRecebimento = date("d/m/Y H:i:s")." [".$local_Login."] - Obs: $local_Obs";
		}
		
		if($local_IdContaReceberRecebimento != '' && $local_IdContaReceberRecebimento > 0 && $exe_update){
			$sql = "UPDATE ContaReceberRecebimento SET 
								DataRecebimento				= '$local_DataRecebimento', 
								ValorRecebido				= '$local_ValorReceber',
								IdStatus					= '1',
								IdRecibo					= Recibo($local_IdLoja),
								MD5							= md5(concat('$local_IdLoja','$local_IdContaReceber','$local_IdContaReceberRecebimento')),
								DataAlteracao				= concat(curdate(),' ',curtime()),
								LoginAlteracao				= '$local_Login'
							where
								IdLoja						= '$local_IdLoja' and
								IdContaReceber				= '$local_IdContaReceber' and
								IdContaReceberRecebimento	= '$local_IdContaReceberRecebimento'";
		}else{
			if($local_IdLocalRecebimento != ""){
				$sql = "INSERT INTO ContaReceberRecebimento SET 
							IdLoja						= '$local_IdLoja',
							IdContaReceber				= '$local_IdContaReceber',
							IdContaReceberRecebimento	= '$local_IdContaReceberRecebimento',
							DataRecebimento				= '$local_DataRecebimento', 
							ValorDesconto				= '$local_ValorDescontoRecebimento',
							ValorOutrasDespesas			= '$local_ValorOutrasDespesas',
							ValorMoraMulta				= '$local_ValorMoraMulta',
							ValorRecebido				= '$local_ValorReceber',
							IdArquivoRetorno			= $local_IdArquivoRetorno,
							IdStatus					= '1',
							Obs							= '$local_ObsRecebimento',
							IdRecibo					= Recibo($local_IdLoja),
							MD5							= md5(concat('$local_IdLoja','$local_IdContaReceber','$local_IdContaReceberRecebimento')),
							IdLojaRecebimento			= '$local_IdLojaRecebimento',
							IdLocalCobranca				= '$local_IdLocalRecebimento',
							DataCriacao					= concat(curdate(),' ',curtime()),
							LoginCriacao				= '$local_Login';";
			} else {
				$sql = "INSERT INTO ContaReceberRecebimento SET 
							IdLoja						= '$local_IdLoja',
							IdContaReceber				= '$local_IdContaReceber',
							IdContaReceberRecebimento	= '$local_IdContaReceberRecebimento',
							DataRecebimento				= '$local_DataRecebimento', 
							ValorDesconto				= '$local_ValorDescontoRecebimento',
							ValorOutrasDespesas			= '$local_ValorOutrasDespesas',
							ValorMoraMulta				= '$local_ValorMoraMulta',
							ValorRecebido				= '$local_ValorReceber',
							IdArquivoRetorno			= $local_IdArquivoRetorno,
							IdStatus					= '1',
							Obs							= '$local_ObsRecebimento',
							IdRecibo					= Recibo($local_IdLoja),
							MD5							= md5(concat('$local_IdLoja','$local_IdContaReceber','$local_IdContaReceberRecebimento')),
							IdLojaRecebimento			= '$local_IdLojaRecebimento',
							IdCaixa						= '$local_IdCaixa',
							IdCaixaMovimentacao			= '$local_IdCaixaMovimentacao',
							IdCaixaItem					= '$local_IdCaixaItem',
							DataCriacao					= concat(curdate(),' ',curtime()),
							LoginCriacao				= '$local_Login';";
			}
		}
		$local_transaction[$tr_i] = mysql_query($sql,$con);
		$tr_i++;
		
		if($local_IdLocalRecebimento != ""){
			$sql2 = "select ParametroRecebimento.IdParametroRecebimento from ParametroRecebimento where ParametroRecebimento.IdLoja=$local_IdLoja and ParametroRecebimento.IdLocalCobranca=$local_IdLocalRecebimento";
			$res2 = @mysql_query($sql2,$con);
			while($lin2 = @mysql_fetch_array($res2)){
				$sql = "INSERT INTO ContaReceberRecebimentoParametro SET 
							IdLoja						= '$local_IdLoja',
							IdContaReceber				= '$local_IdContaReceber',
							IdContaReceberRecebimento	= '$local_IdContaReceberRecebimento',
							IdLocalCobranca				= '$local_IdLocalRecebimento', 
							IdParametroRecebimento		= '".$lin2[IdParametroRecebimento]."',
							ValorParametro				= '".$local_Valor[$lin2[IdParametroRecebimento]]."';";
				$local_transaction[$tr_i] = mysql_query($sql,$con);
				$tr_i++;
			}
		}
		
		// Gera NF
		if(nfCDA($local_IdLoja, $local_IdContaReceber) == true){
			$local_transaction[$tr_i] = gera_nf($local_IdLoja, $local_IdContaReceber);
			enviaNotasFiscais($local_IdLoja,$local_IdContaReceber);
			$tr_i++;
		} // FIM - Gera NF
		
		for($i = 0; $i < $tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;
			}
		}
		
		if($local_transaction == true){

			$IdStatusDesbloqueio = explode("\n",getCodigoInterno(42,1));

			for($iStatus=0; $iStatus<count($IdStatusDesbloqueio); $iStatus++){
				$StatusDesbloqueio .= ",".trim($IdStatusDesbloqueio[$iStatus]);
			}

			$sqlDesbloqueio = "select
									Contrato.IdLoja,
									Contrato.IdContrato,
									Servico.UrlRotinaDesbloqueio,
									Contrato.IdStatus,
									Contrato.Obs
								from
									(select
										Contrato.IdLoja,
										Contrato.IdContrato,
										Contrato.IdContratoAgrupador
									from
										ContaReceber,
										LancamentoFinanceiroContaReceber,
										LancamentoFinanceiro,
										Contrato
									where
										ContaReceber.IdLoja = $local_IdLoja and
										ContaReceber.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
										LancamentoFinanceiroContaReceber.IdLoja = LancamentoFinanceiro.IdLoja and
										LancamentoFinanceiro.IdLoja = Contrato.IdLoja and
										ContaReceber.IdContaReceber = $local_IdContaReceber and
										ContaReceber.IdContaReceber = LancamentoFinanceiroContaReceber.IdContaReceber and
										LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and
										LancamentoFinanceiro.IdContrato = Contrato.IdContrato) Contratos,
									Contrato,
									Servico
								where
									Contrato.IdLoja = Contratos.IdLoja and
									Contrato.IdLoja = Servico.IdLoja and
									Contrato.IdServico = Servico.IdServico and
									(Contrato.IdContrato = Contratos.IdContrato or Contrato.IdContrato = Contratos.IdContratoAgrupador) and
									Contrato.IdStatus in (''$StatusDesbloqueio)";
			$resDesbloqueio = mysql_query($sqlDesbloqueio,$con);
			while($linDesbloqueio = mysql_fetch_array($resDesbloqueio)){
				$local_Obs = date("d/m/Y H:i:s")." [automatico] - Mudou status para Ativado.\n$linDesbloqueio[Obs]";
				
				$sqlDesbloqueio = "update Contrato set IdStatus='200', Obs=\"$local_Obs\" where IdLoja=$local_IdLoja and IdContrato=".$linDesbloqueio[IdContrato];
				mysql_query($sqlDesbloqueio,$con);

				if($linDesbloqueio[UrlRotinaDesbloqueio] != ''){
					$local_IdContrato = $linDesbloqueio[IdContrato];
					
					include($linDesbloqueio[UrlRotinaDesbloqueio]);
					derrubaConexaoRadius($local_IdLoja, $linDesbloqueio[IdContrato]);	
				}

				$sql = "select
							Contrato.IdLoja,
							Contrato.IdContrato,
							Contrato.IdStatus,
							ContratoAutomatico.IdContratoAutomatico
						from
							ContratoAutomatico,
							Contrato
						where
							ContratoAutomatico.IdLoja = $local_IdLoja and
							ContratoAutomatico.IdLoja = Contrato.IdLoja and
							ContratoAutomatico.IdContrato = Contrato.IdContrato and
							ContratoAutomatico.IdContrato = $linDesbloqueio[IdContrato]";
				$res = mysql_query($sql,$con);
				while($lin = mysql_fetch_array($res)){
					$sqlUpdate = "update Contrato set IdStatus='$lin[IdStatus]' where IdLoja = $lin[IdLoja] and IdContrato = $lin[IdContratoAutomatico] and IdStatus != $lin[IdStatus]";
					mysql_query($sqlUpdate,$con);
				}
			}

			if($local_EnviarEmailConfirmacaoPagamento == 1 || empty($local_EnviarEmailConfirmacaoPagamento)){	
				enviarEmailContaReceberQuitado($local_IdLoja, $local_IdContaReceber, $local_IdContaReceberRecebimento);		
			}
			return true;
		} else{
			return false;
		}
	}
	
	function conta_receber_cancelar_recebimento($dados){
		global $con;

		$tr_i									= 0;
		$local_IdLoja							= $dados["IdLoja"];
		$local_IdContaReceber					= $dados["IdContaReceber"];
		$local_IdContaReceberRecebimento		= $dados["IdContaReceberRecebimento"];
		$local_CancelarNotaFiscalRecebimento	= $dados["CancelarNotaFiscalRecebimento"];
		$local_IdCancelarNotaFiscal				= $dados["IdCancelarNotaFiscal"];
		$local_NumeroNF							= $dados["NumeroNF"];
		$local_Login							= $dados["Login"];
		$local_IdLocalEstorno					= $dados["IdLocalEstorno"];
		$local_IdContratoEstorno				= $dados["IdContratoEstorno"];
		$local_CreditoFuturo					= $dados["CreditoFuturo"];
		$local_ObsCancelamento					= $dados["ObsCancelamento"];
		
		if($local_Login == ''){
			$local_Login = 'automatico';
		}
		
		if($local_CancelarNotaFiscalRecebimento == '1'){
			if($local_IdCancelarNotaFiscal == '1'){
				if(cancela_nf($local_IdLoja, $local_IdContaReceber, $local_NumeroNF) == 68){
					$local_transaction[$tr_i] = false;
					$tr_i++;
				} else {
					$local_ObsNFC = date("d/m/Y H:i:s")." [".$local_Login."] - Nota Fiscal n° ".$local_NumeroNF." cancelada de acordo com cancelamento do Recebimento n° ".$local_IdContaReceberRecebimento.".";
				}
			} else {
				$local_ObsNFC = date("d/m/Y H:i:s")." [".$local_Login."] - Nota Fiscal n° ".$local_NumeroNF." não foi cancelada de acordo com cancelamento do Recebimento n° ".$local_IdContaReceberRecebimento.".";
			}
		}
		
		$ContaReceber = array($local_IdContaReceber);
		$ContaReceberRecebimento = array($local_IdContaReceberRecebimento);
		$ContaReceberStatus = array(1);
		$qtd = 1;
		
		$sql_ag = "SELECT 
						ContaReceberRecebimento.IdContaReceber,
						ContaReceberRecebimento.IdContaReceberRecebimento 
					FROM 
						ContaReceberAgrupado,
						ContaReceberAgrupadoParcela, 
						ContaReceberAgrupadoItem, 
						ContaReceberRecebimento 
					WHERE 
						ContaReceberAgrupado.IdLoja = '$local_IdLoja' AND 
						ContaReceberAgrupado.IdLoja = ContaReceberAgrupadoParcela.IdLoja AND 
						ContaReceberAgrupado.IdContaReceberAgrupador = ContaReceberAgrupadoParcela.IdContaReceberAgrupador AND 
						ContaReceberAgrupadoParcela.IdContaReceber = '$local_IdContaReceber' AND 
						ContaReceberAgrupadoParcela.IdLoja = ContaReceberAgrupadoItem.IdLoja AND 
						ContaReceberAgrupadoParcela.IdContaReceberAgrupador = ContaReceberAgrupadoItem.IdContaReceberAgrupador AND 
						ContaReceberAgrupadoItem.IdLoja = ContaReceberRecebimento.IdLoja AND 
						ContaReceberAgrupadoItem.IdContaReceber = ContaReceberRecebimento.IdContaReceber AND 
						ContaReceberRecebimento.IdStatus NOT IN (0, 3)";
		$res_ag = @mysql_query($sql_ag, $con);
		
		while($lin_ag = @mysql_fetch_array($res_ag)){
			$ContaReceber[$qtd] = $lin_ag["IdContaReceber"];
			$ContaReceberRecebimento[$qtd] = $lin_ag["IdContaReceberRecebimento"];
			$ContaReceberStatus[$qtd] = 8;
			$qtd++;
		}
		
		for($i = 0; $i < $qtd; $i++){
			$local_IdContaReceber = $ContaReceber[$i];
			$local_IdContaReceberRecebimento = $ContaReceberRecebimento[$i];
			$local_IdStatus = $ContaReceberStatus[$i];
			
			$sql_rc = "SELECT 
							Obs,
							ValorRecebido 
						FROM 
							ContaReceberRecebimento 
						WHERE 
							IdLoja						= '$local_IdLoja' AND 
							IdContaReceber				= '$local_IdContaReceber' AND 
							IdContaReceberRecebimento	= '$local_IdContaReceberRecebimento'";
			$res_rc = @mysql_query($sql_rc, $con);
			$lin_rc = @mysql_fetch_array($res_rc);
			
			$local_ObsRC = date("d/m/Y H:i:s")." [".$local_Login."] - Motivo Cancelamento: ".trim($local_ObsCancelamento);
			
			if($lin_rc["Obs"] != ""){
				$local_ObsRC .= "\n".trim($lin_rc["Obs"]);
			}
			
			$local_ValorCredito = $lin_rc["ValorRecebido"];
			
			if($local_IdLocalEstorno == ""){
				$local_IdLocalEstorno = 'NULL';
			}
			
			if($local_IdContratoEstorno == "" || $local_IdContratoEstorno == "0"){
				$local_IdContratoEstorno = 'NULL';
			}
			
			$sql = "UPDATE 
						ContaReceberRecebimento 
					SET
						IdStatus					= '0', 
						CreditoFuturo				= '$local_CreditoFuturo',
						IdLocalEstorno				= $local_IdLocalEstorno,
						IdContratoEstorno			= $local_IdContratoEstorno,
						Obs							= '$local_ObsRC',
						LoginAlteracao				= '$local_Login',
						DataAlteracao				= NOW()
					WHERE 
						IdLoja						= '$local_IdLoja' AND
						IdContaReceber				= '$local_IdContaReceber' AND
						IdContaReceberRecebimento	= '$local_IdContaReceberRecebimento'";
			$local_transaction[$tr_i] = mysql_query($sql,$con);
			$tr_i++;
			
			if($local_CreditoFuturo == 1){
				$sqlLancamento = "SELECT (MAX(IdLancamentoFinanceiro)+1) IdLancamentoFinanceiro FROM LancamentoFinanceiro WHERE IdLoja = $local_IdLoja";
				$resLancamento = mysql_query($sqlLancamento);
				$linLancamento = mysql_fetch_array($resLancamento);
				
				if($linLancamento["IdLancamentoFinanceiro"] != NULL){
					$IdLancamentoFinanceiro = $linLancamento["IdLancamentoFinanceiro"];
				} else {
					$IdLancamentoFinanceiro = 1;
				}
				
				$local_ObsLancamentoFinanceiro = date("d/m/Y H:i:s")." [".$local_Login."] - Obs: Estorno referente a cancelamento do recebimento nº $local_IdContaReceberRecebimento";
				
				$sqlLancamento = "INSERT INTO 
										LancamentoFinanceiro 
									SET 
										IdLoja					= '$local_IdLoja',
										IdLancamentoFinanceiro	= '$IdLancamentoFinanceiro',
										IdContrato				= $local_IdContratoEstorno,
										Valor					= '-".$local_ValorCredito."',
										IdProcessoFinanceiro	= NULL,
										IdEstorno				= '$local_IdContaReceberRecebimento',
										IdStatus				= 2,
										ObsLancamentoFinanceiro	= '$local_ObsLancamentoFinanceiro';"; //Aguardando Cobrança
				$local_transaction[$tr_i] = mysql_query($sqlLancamento,$con);
				$tr_i++;
				
				//status do recebimento = "Estorno"
				$sql = "UPDATE 
							ContaReceberRecebimento 
						SET
							IdStatus					= '3', 
							LoginAlteracao				= '$local_Login',
							DataAlteracao				= NOW()
						WHERE 
							IdLoja						= '$local_IdLoja' and
							IdContaReceber				= '$local_IdContaReceber' and
							IdContaReceberRecebimento	= '$local_IdContaReceberRecebimento'";
				$local_transaction[$tr_i] = mysql_query($sql,$con);
				$tr_i++;
			}
			
			$local_Obs = "";	
			
			if($i > 0){
				$local_Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Cancelamento recebimento nº $local_IdContaReceberRecebimento, via agrupamento Contas a receber n° $ContaReceber[0].";
			} else{
				$local_Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Cancelamento recebimento nº $local_IdContaReceberRecebimento.";
			}
			
			$local_Obs .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Motivo: ".trim($local_ObsCancelamento).".";
			
			$cont = 0;	
			$total = 0;
			$sql2 = "SELECT IdStatus FROM ContaReceberRecebimento WHERE IdLoja = '$local_IdLoja' AND IdContaReceber = '$local_IdContaReceber'";
			$res2 = mysql_query($sql2,$con);
			
			while($lin2 = mysql_fetch_array($res2)){
				if($lin2["IdStatus"] == 0 || $lin2["IdStatus"] == 3) 
					$cont++;
				
				$total++;
			}
			
			if($total != 0 && $cont == $total){
				$sql5 = "SELECT Obs, IdStatus FROM ContaReceber WHERE IdLoja = $local_IdLoja AND IdContaReceber = $local_IdContaReceber";
				$res5 = mysql_query($sql5,$con);
				$lin5 = mysql_fetch_array($res5);
				
				if($lin5["IdStatus"] != "1"){
					$sql3 = "SELECT ValorParametroSistema FROM ParametroSistema WHERE IdGrupoParametroSistema = 35 AND IdParametroSistema = $lin5[IdStatus]";
					$res3 = @mysql_query($sql3,$con);
					$lin3 = @mysql_fetch_array($res3);
					
					$sql4 = "SELECT ValorParametroSistema FROM ParametroSistema WHERE IdGrupoParametroSistema = 35 AND IdParametroSistema = 1";
					$res4 = @mysql_query($sql4,$con);
					$lin4 = @mysql_fetch_array($res4);
					
					if($local_Obs != "") 
						$local_Obs .= "\n";
					
					$local_Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Status [".$lin3["ValorParametroSistema"]." > ".$lin4["ValorParametroSistema"]."]";	
				}
				
				if($lin5[Obs] != ""){
					$local_Obs .= "\n".trim($lin5["Obs"]);
				}
				
				if($local_ObsNFC != ""){
					$local_Obs = $local_ObsNFC."\n".$local_Obs;
				}
				
				$sql = "UPDATE 
							ContaReceber 
						SET
							IdStatus		= '$local_IdStatus', 
							Obs				= '$local_Obs',
							LoginAlteracao	= '$local_Login',
							DataAlteracao	= NOW()
						WHERE 
							IdLoja			= '$local_IdLoja' AND
							IdContaReceber	= '$local_IdContaReceber'";
				$local_transaction[$tr_i] = mysql_query($sql,$con);
				$tr_i++;
			} else{
				$sql4 = "SELECT Obs, IdStatus FROM ContaReceber WHERE IdLoja = $local_IdLoja AND IdContaReceber = $local_IdContaReceber";
				$res4 = mysql_query($sql4,$con);
				$lin4 = mysql_fetch_array($res4);		
				
				if($lin4["Obs"] != ""){
					$local_Obs .= "\n".trim($lin4["Obs"]);
				}
				
				$sql = "UPDATE 
							ContaReceber 
						SET
							Obs				= '$local_Obs',
							LoginAlteracao	= '$local_Login',
							DataAlteracao	= NOW()
						WHERE 
							IdLoja			= '$local_IdLoja' AND
							IdContaReceber	= '$local_IdContaReceber'";
				$local_transaction[$tr_i] = mysql_query($sql,$con);
				$tr_i++;
			}

			$sqlQtdRecebimento = "SELECT
										COUNT(*) Qtd
									FROM
										ContaReceberRecebimento
									WHERE
										IdLoja			= $local_IdLoja AND
										IdContaReceber	= $local_IdContaReceber AND
										IdStatus		= 1";
			$resQtdRecebimento = mysql_query($sqlQtdRecebimento,$con);
			$linQtdRecebimento = mysql_fetch_array($resQtdRecebimento);

			if($linQtdRecebimento["Qtd"] == 0){
				$local_transaction[$tr_i] = posicaoCobranca($local_IdLoja, $local_IdContaReceber, 8, $local_Login);
				$tr_i++;
			}
		}
		
		return (!in_array(false, $local_transaction));
	}
	
	function numeroExtenso($valor = 0, $maiusculas = false) {
		#Função para números inteiros;
	
		$singular = array(" ", " ", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
		$plural = array(" ", " ", "mil", "milhões", "bilhões", "trilhões",
		"quatrilhões");
	
		$c = array("", "cem", "duzentos", "trezentos", "quatrocentos",
		"quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
		$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta",
		"sessenta", "setenta", "oitenta", "noventa");
		$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze",
		"dezesseis", "dezesete", "dezoito", "dezenove");
		$u = array("", "um", "dois", "três", "quatro", "cinco", "seis",
		"sete", "oito", "nove");
	
		$z = 0;
		$rt = "";
	
		$valor = number_format($valor, 2, ".", ".");
		$inteiro = explode(".", $valor);
		for($i=0;$i<count($inteiro);$i++)
		for($ii=strlen($inteiro[$i]);$ii<3;$ii++) $inteiro[$i] = "0".$inteiro[$i];
	
		$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
		for ($i=0;$i<count($inteiro);$i++) {
			$valor = $inteiro[$i];
			$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
			$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
			$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
	
			$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru;
			$t = count($inteiro)-1-$i;
			$r .= $r ? " ".($valor > 1 ? $singular[$t] : $singular[$t]) : "";
			if ($valor == "000")$z++; elseif ($z > 0) $z--;
			if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "");
			if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
		}
	
		if(!$maiusculas){
			return($rt ? $rt : "zero");
		} else {
			if ($rt) $rt=ereg_replace(" E "," e ",ucwords($rt));
				return (($rt) ? ($rt) : "Zero");
		}
	} 

	function getTipoLocalCobranca($IdLoja, $IdLocalCobranca){
		global $con;
		
		$sql = "select
					IdTipoLocalCobranca
				from					
					LocalCobranca						
				where				
					LocalCobranca.IdLoja = $IdLoja and
					IdLocalCobranca = $IdLocalCobranca";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		return $lin[IdTipoLocalCobranca];
	}
	
	function dia_util($data){
		// Formato da Data de Entrada YYYY-mm-ddd
		global $con;
		$arr = explode('-', $data);
		$rec = true;
		$dia_sem = date("w", mktime(0, 0, 0, (int)$arr[1], (int)$arr[2], (int)$arr[0]));
		$IdLoja = $_SESSION['IdLoja'];
		
		if($IdLoja == ''){
			global $IdLoja;
		}
		
		$sqlFeriado = "SELECT 
							(DatasEspeciais.Qtd+DiaSemana.DiaUtil) AS Qtd
						FROM 
							(
								SELECT
									COUNT(*) AS Qtd
								FROM
									DatasEspeciais
								WHERE 
									IdLoja = $IdLoja AND 
									Data = '$data'
							) DatasEspeciais,
							(
								SELECT 
									CASE WHEN ValorCodigoInterno = 1 
										THEN 0 
										ELSE 1 
									END AS DiaUtil
								FROM
									CodigoInterno 
								WHERE 
									IdLoja = $IdLoja AND
									IdGrupoCodigoInterno = 55 AND 
									IdCodigoInterno = ".($dia_sem + 1)."
							) DiaSemana;";
		$resFeriado = mysql_query($sqlFeriado, $con);
		$linFeriado = mysql_fetch_array($resFeriado);
		
		if((int)$linFeriado[Qtd] > 0){
			return dia_util(incrementaData($data, 1));
		} else{
			return $data;
		}
	}

	function parametrizaLojas(){

		global $con;

		$sql = "select IdLoja from Loja where IdLoja != 1";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){

			// Duplica códigos internos
			$sql1	=	"delete from CodigoInterno where IdLoja = $lin[IdLoja] and IdGrupoCodigoInterno = 10";
			mysql_query($sql1,$con);

			$sql2	=	"select IdGrupoCodigoInterno,IdCodigoInterno,DescricaoCodigoInterno,ValorCodigoInterno from CodigoInterno where IdLoja = 1";
			$res2	=	mysql_query($sql2,$con);
			while($lin2	=	mysql_fetch_array($res2)){
				$sql3	=	"select * from CodigoInterno where IdLoja=$lin[IdLoja] and IdGrupoCodigoInterno = $lin2[IdGrupoCodigoInterno] and IdCodigoInterno = $lin2[IdCodigoInterno];";
				$res3	=	mysql_query($sql3,$con);
				
				if(mysql_num_rows($res3) < 1){
					$sql	=	"INSERT INTO 
									CodigoInterno
										(IdGrupoCodigoInterno, IdLoja, IdCodigoInterno, DescricaoCodigoInterno, ValorCodigoInterno, LoginCriacao, DataCriacao) VALUES
										($lin2[IdGrupoCodigoInterno], $lin[IdLoja], $lin2[IdCodigoInterno], '$lin2[DescricaoCodigoInterno]', '$lin2[ValorCodigoInterno]', '$local_Login', concat(curdate(),' ', curtime()));";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}	
			}

			// Duplica permissões para o grupo adminsitrador master
			$sql2	=	"select IdModulo,IdOperacao,IdSubOperacao from GrupoPermissaoSubOperacao where IdLoja = 1 and IdGrupoPermissao = 1;";
			$res2	=	mysql_query($sql2,$con);
			while($lin2	=	mysql_fetch_array($res2)){
				$sql3	=	"select * from GrupoPermissaoSubOperacao where IdLoja=$lin[IdLoja] and IdGrupoPermissao = 1 and IdModulo = $lin2[IdModulo] and IdOperacao = $lin2[IdOperacao] and IdSubOperacao = '$lin2[IdSubOperacao]'";
				$res3	=	mysql_query($sql3,$con);
				
				if(mysql_num_rows($res3) < 1){
					$sql	=	"INSERT INTO 
									GrupoPermissaoSubOperacao
										(IdGrupoPermissao, IdLoja, IdModulo, IdOperacao, IdSubOperacao, LoginCriacao, DataCriacao) VALUES
										(1, $lin[IdLoja], $lin2[IdModulo], $lin2[IdOperacao], '$lin2[IdSubOperacao]', '$local_Login', concat(curdate(),' ', curtime()))";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}
			
			}

			// Duplica os Templates
			$sql2 = "select
						  IdTemplate,
						  DescricaoTemplate,
						  Estrutura,
						  LoginCriacao,
						  DataCriacao,
						  LoginAlteracao,
						  DataAlteracao
					from
						TemplateMensagem
					where
						IdLoja = 1";
			$res2 = mysql_query($sql2,$con);
			while($lin2 = mysql_fetch_array($res2)){
				$sql3 = "select IdTemplate from TemplateMensagem where IdLoja = $lin[IdLoja] and IdTemplate = $lin2[IdTemplate]";
				$res3	=	mysql_query($sql3,$con);
				
				if(mysql_num_rows($res3) < 1){
					if($lin2[LoginCriacao] == ''){
						$lin2[LoginCriacao] = 'NULL';
					} else {
						$lin2[LoginCriacao] = "'$lin2[LoginCriacao]'";
					}
					
					if($lin2[LoginAlteracao] == ''){
						$lin2[LoginAlteracao] = 'NULL';
					} else {
						$lin2[LoginAlteracao] = "'$lin2[LoginAlteracao]'";
					}
					
					$lin2[Estrutura] = str_replace("\"", "\\\"", $lin2[Estrutura]);
					$sql	=	"insert into TemplateMensagem set 
									IdLoja = $lin[IdLoja],
									IdTemplate = $lin2[IdTemplate],
									DescricaoTemplate = '$lin2[DescricaoTemplate]',
									Estrutura = \"$lin2[Estrutura]\",
									LoginCriacao = $lin2[LoginCriacao],
									DataCriacao = '$lin2[DataCriacao]',
									LoginAlteracao = $lin2[LoginAlteracao],
									DataAlteracao = '$lin2[DataAlteracao]'";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}
			}

			// Duplica os Tipos de Mensagens
			$sql2 = "select
						IdTipoMensagem,
						IdTemplate,
						IdContaEmail,
						IdContaSMS,
						Titulo,
						Assunto,
						Conteudo,
						Assinatura,
						DelayDisparo,
						IdStatus,
						PrioridadeEnvio,
						LimiteEnvioDiario,
						DataAlteracao,
						LoginAlteracao
					from
						TipoMensagem
					where
						 IdLoja = 1 and
						 IdTipoMensagem < 1000000";
			$res2 = mysql_query($sql2,$con);
			while($lin2 = mysql_fetch_array($res2)){
				$sql3 = "select IdTipoMensagem from TipoMensagem where IdLoja = $lin[IdLoja] and IdTipoMensagem = $lin2[IdTipoMensagem]";
				$res3	=	mysql_query($sql3,$con);
				
				if(mysql_num_rows($res3) < 1){
					if($lin2[DataAlteracao] == ''){
						$lin2[DataAlteracao] = 'NULL';
					} else {
						$lin2[DataAlteracao] = "'$lin2[DataAlteracao]'";
					}
					
					if($lin2[LimiteEnvioDiario] == ''){
						$lin2[LimiteEnvioDiario] = 'NULL';
					} else {
						$lin2[LimiteEnvioDiario] = "'$lin2[LimiteEnvioDiario]'";
					}
					
					$lin2[Conteudo] = str_replace("\"", "\\\"", $lin2[Conteudo]);
					$sql	=	"insert into TipoMensagem set 
									IdLoja				= $lin[IdLoja],
									IdTipoMensagem		= $lin2[IdTipoMensagem],
									IdTemplate			= $lin2[IdTemplate],
									IdContaEmail		= NULL,
									IdContaSMS			= NULL,
									Titulo				= '$lin2[Titulo]',
									Assunto				= '$lin2[Assunto]',
									Conteudo			= \"$lin2[Conteudo]\",
									Assinatura			= '$lin2[Assinatura]',
									DelayDisparo		= '$lin2[DelayDisparo]',
									IdStatus			= $lin2[IdStatus],
									PrioridadeEnvio		= $lin2[PrioridadeEnvio],
									LimiteEnvioDiario	= $lin2[LimiteEnvioDiario], 
									LoginAlteracao		= '$lin2[LoginAlteracao]',
									DataAlteracao		= $lin2[DataAlteracao]";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}
			}

			// Duplica os Tipos de Mensagens Parametros
			$sql2 = "select
						  IdTipoMensagem,
						  IdTipoMensagemParametro,
						  DescricaoTipoMensagemParametro,
						  ValorTipoMensagemParametro
					from
						TipoMensagemParametro
					where
						 IdLoja = 1 and
						 IdTipoMensagem < 1000000";
			$res2 = mysql_query($sql2,$con);
			while($lin2 = mysql_fetch_array($res2)){
				$sql3 = "select IdTipoMensagem from TipoMensagemParametro where IdLoja = $lin[IdLoja] and IdTipoMensagem = $lin2[IdTipoMensagem] and IdTipoMensagemParametro = '$lin2[IdTipoMensagemParametro]'";
				$res3	=	mysql_query($sql3,$con);
				
				if(mysql_num_rows($res3) < 1){
					$sql	=	"insert into TipoMensagemParametro set 
									IdLoja							= $lin[IdLoja],
									IdTipoMensagem					= $lin2[IdTipoMensagem],
									IdTipoMensagemParametro			= '$lin2[IdTipoMensagemParametro]',
									DescricaoTipoMensagemParametro	= '$lin2[DescricaoTipoMensagemParametro]',
									ValorTipoMensagemParametro		= '$lin2[ValorTipoMensagemParametro]'";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}
			}
		}
	}
	
	/* FUNÇÃO PARA OBTER OS DADOS DO BROWSER */
	function getDataBrowser() {
		$user_agent = $_SERVER["HTTP_USER_AGENT"];
		$platform = "Desconhecida";
		$name = "Desconhecido";
		$version = "";
		/* VER A PLANTAFORMA QUE O USUÁRIO ESTA USANDO */
		if (preg_match("/linux/i", $user_agent)) {
			$platform = "LINUX";
		} elseif (preg_match("/macintosh|mac os x/i", $user_agent)) {
			$platform = "MAC";
		} elseif (preg_match("/windows|win32/i", $user_agent)) {
			$platform = "WIN32";
		} elseif (preg_match("/windows|win64/i", $user_agent)) {
			$platform = "WIN64";
		}
		/* VER O BROWSER QUE O USUÁRIO ESTA USANDO */
		if(preg_match("/Firefox/i", $user_agent)) { 
			$name = "Mozilla Firefox"; 
			$abbreviation = "MF";
			$ub = "Firefox"; 
		} elseif(preg_match("/OPR/i", $user_agent)) {//nova versão opera 15 or more //tem que ficar antes do Chrome
			$name = "Opera"; 
			$abbreviation = "OP";
			$ub = "OPR";
		} elseif(preg_match("/Chrome/i", $user_agent)) { 
			$name = "Google Chrome"; 
			$abbreviation = "GC";
			$ub = "Chrome"; 
		} elseif(preg_match("/Safari/i", $user_agent)) { 
			$name = "Apple Safari"; 
			$abbreviation = "AS";
			$ub = "Safari"; 
		}  elseif(preg_match("/Opera/i", $user_agent)) {//old opera 12.1 or previous
			$name = "Opera"; 
			$abbreviation = "OP";
			$ub = "Opera";
		} elseif(preg_match("/MSIE/i", $user_agent) && !preg_match("/TheWorld/i", $user_agent) && !preg_match("/Avant Browser/i", $user_agent)) { 
			$name = "Internet Explorer"; 
			$abbreviation = "IE";
			$ub = "MSIE"; 
		} 
		/* VER A VERSÃO DO BROWSER QUE O USUÁRIO ESTA USANDO */
		$known = array("Version", $ub, "other");
		$pattern = "#(?<browser>" . join("|", $known) . ")[/ ]+(?<version>[0-9.|a-zA-Z.]*)#";
		
		if (!preg_match_all($pattern, $user_agent, $matches)) {
			/* VERSÃO DO BROWSER NÃO ENCONTRADA */
		}
		
		/* VER QTD */
		$i = count($matches["browser"]);
		
		if ($i != 1) {
			/* VERIFICA SE A VERSÃO É ANTES OU DEPOIS DO NOME */
			if (strripos($user_agent, "Version") < strripos($user_agent, $ub)) {
				$version = $matches["version"][0];
			} else {
				$version = $matches["version"][1];
			}
		} else {
			$version = $matches["version"][0];
		}
		
		if ($version == null || $version == '') {
			$version = '?';
		}
		/* DADOS SOBRE A VERSÃO */
		return array(
			"userAgent"		=> $user_agent,
			"name"			=> $name,
			"version"		=> $version,
			"platform"		=> $platform,
			"pattern"		=> $pattern,
			"abbreviation"	=> $abbreviation,
		);
	}
	/* VERIFICAR A VERSÃO DO BROWSER SE É COMPATIVEL COM O SISTEMA */
	function browserCompativel($user_browser){
		global $versao_browser;
		if(array_key_exists($user_browser[abbreviation], $versao_browser)){
			foreach($versao_browser[$user_browser[abbreviation]] as $id => $value){
				if($id > 0){
					$value .= "([\d.]*|)";
					
					if($list_browser == ''){
						$list_browser = '/'.$value;
					} else{
						$list_browser .= '|'.$value;
					}
				}
			}
			
			$list_browser .= '/';
		} else{
			$list_browser = "/#/";
		}
		
		return preg_match(preg_replace("/([\d\.])(x)/", '\1', $list_browser), $user_browser[name].' '.$user_browser[version]);
	}

	function ListaEmailUsuarioHelpDesk($IdLoja){

		global $con;	

		$sql = "select
					distinct
					Pessoa.Email,
					Usuario.Login
				from
					Usuario,
					UsuarioGrupoUsuario,
					GrupoUsuario,
					GrupoUsuarioQuadroAviso,
					Pessoa
				where
					UsuarioGrupoUsuario.IdLoja = $IdLoja and
					Usuario.IdStatus = 1 and
					Usuario.Login = UsuarioGrupoUsuario.Login and					
					UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and
					UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and
					GrupoUsuario.IdLoja = GrupoUsuarioQuadroAviso.IdLoja and
					GrupoUsuario.IdGrupoUsuario = GrupoUsuarioQuadroAviso.IdGrupoUsuario and
					GrupoUsuarioQuadroAviso.IdQuadroAviso = 9 and
					Usuario.IdPessoa = Pessoa.IdPessoa";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			$i = 0;

			$sql	= "select
							GrupoPermissaoSubOperacao.IdLoja
						from
							UsuarioGrupoPermissao,
							GrupoPermissaoSubOperacao
						where
							GrupoPermissaoSubOperacao.IdLoja = $IdLoja and
							UsuarioGrupoPermissao.Login = '$lin[Login]' and
							UsuarioGrupoPermissao.IdGrupoPermissao = GrupoPermissaoSubOperacao.IdGrupoPermissao and
							GrupoPermissaoSubOperacao.IdOperacao = 65 and
							GrupoPermissaoSubOperacao.IdSubOperacao = 'I'
						group by 
							IdLoja";
			$resGrupoPermissao	= mysql_query($sql,$con);
			while(mysql_fetch_array($resGrupoPermissao)){
				$i++;	
			}
			
			$sql	= "select
							UsuarioSubOperacao.IdLoja
						from
							UsuarioSubOperacao
						where
							UsuarioSubOperacao.IdLoja = $IdLoja and
							UsuarioSubOperacao.Login = '$lin[Login]' and
							UsuarioSubOperacao.IdOperacao = 65 and
							UsuarioSubOperacao.IdSubOperacao = 'I'
						group by 
							IdLoja";
			$resUsuarioSubOperacao	= mysql_query($sql,$con);
			while(mysql_fetch_array($resUsuarioSubOperacao)){					
				$i++;					
			}
			
			if($i > 0){
				$Email .= ";".$lin[Email];				
			}			
		}
		$Email = array_unique(explode(";",$Email));
		$ListaEmail = '';

		for($i=0; $i<count($Email); $i++){
			$Email[$i] = trim($Email[$i]);
			if($Email[$i] != ''){
				if($ListaEmail != ''){
					$ListaEmail .= ';';
				}
				$ListaEmail .= $Email[$i];
			}
		}
		return $ListaEmail;
	}
	
	function derrubaConexaoRadius($IdLoja, $IdContrato){

		global $con;

		/* Notify the user if the server terminates the connection */
		if(!function_exists("my_ssh_disconnect")){
			function my_ssh_disconnect($reason, $message, $language) {
			  printf("Server disconnected with reason code [%d] and message: %s\n",
					 $reason, $message);
			}
		}

		$methods = array(
		  'kex' => 'diffie-hellman-group1-sha1',
		  'client_to_server' => array(
			'crypt' => '3des-cbc',
			'comp' => 'none'),
		  'server_to_client' => array(
			'crypt' => 'aes256-cbc,aes192-cbc,aes128-cbc',
			'comp' => 'none'));

		$callbacks = array('disconnect' => 'my_ssh_disconnect');

		// Localiza o login
		$sql = "select
					Valor Login
				from
					ContratoParametro
				where
					IdLoja = $IdLoja and
					IdContrato = $IdContrato and
					IdParametroServico = 1";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);

		$Login = $lin[Login];

		$sql	=	"select
						ValorCodigoInterno 
					from 
						CodigoInterno 
					where 
						IdLoja = '$IdLoja' and 
						IdGrupoCodigoInterno = 10000 and 
						IdCodigoInterno <= 19 and 
						ValorCodigoInterno != ''";
		$res	=	mysql_query($sql,$con);
		while($lin	=	mysql_fetch_array($res)){
					
			$aux	=	explode("\n",$lin[ValorCodigoInterno]);
					
			$bd[server]		=	trim($aux[0]); //Host
			$bd[login]		=	trim($aux[1]); //Login
			$bd[senha]		=	trim($aux[2]); //Senha
			$bd[banco]		=	trim($aux[3]); //DB	
			$bd[userSSH]	=	trim($aux[4]); //Usuário SSH
			$bd[senhaSSH]	=	trim($aux[5]); //Senha SSH
			
			$conRadius	=	mysql_connect($bd[server],$bd[login],$bd[senha]);

			$sql2 = "select
						nasipaddress,
						framedprotocol,
						framedipaddress
					from
						$bd[banco].radacct
					where
						username='$Login'
					order by
						radacctid DESC
					limit 0,1";
			$res2 = mysql_query($sql2,$conRadius);
			if($lin2 = mysql_fetch_array($res2)){
				$Nasipaddress = $lin2[nasipaddress];
				$Framedprotocol = $lin2[framedprotocol];
				$FramedIPAddress = $lin2[framedipaddress];
			}

			if($Framedprotocol == 'PPP'){
				# DERRUBAR CONEXÃO PPP
				$sql3 = "select
							distinct
							secret
						from
							$bd[banco].nas";
				$res3 = mysql_query($sql3,$conRadius);
				while($lin3 = mysql_fetch_array($res3)){
					
					$comando = "echo \"User-Name = $Login\" | /usr/local/bin/radclient -r 1 -q $Nasipaddress:3799 disconnect $lin3[secret]";

					if($bd[server] != 'localhost'){
						$connection = ssh2_connect($bd[server], 22, $methods, $callbacks);
						if (!$connection) die('Connection failed');
						ssh2_auth_password($connection, $bd[userSSH], $bd[senhaSSH]);
						ssh2_exec($connection, $comando);
						ssh2_exec($connection, "quit");
					}else{
						$sql4 = "SELECT
									MAX(IdCodigoInterno) IdCodigoInterno
								FROM
									CodigoInterno
								WHERE
									IdGrupoCodigoInterno = 65 AND
									IdLoja = $IdLoja";
						$res4 = mysql_query($sql4,$con);
						$lin4 = mysql_fetch_array($res4);

						if($lin4[IdCodigoInterno] < 1){
							$lin4[IdCodigoInterno]=1;
						}else{
							$lin4[IdCodigoInterno]++;
						}

						$sql4 = "INSERT INTO CodigoInterno SET 
									IdGrupoCodigoInterno = 65,
									IdLoja = $IdLoja,
									IdCodigoInterno = $lin4[IdCodigoInterno],
									DescricaoCodigoInterno = $IdContrato,
									ValorCodigoInterno = '$comando',
									LoginCriacao = 'automatico',
									DataCriacao = now()";
						mysql_query($sql4,$con);
					}
				}
			}else{
				# DERRUBAR CONEXÃO HOTSPOT
				$sql3 = "select
							distinct
							secret
						from
							$bd[banco].nas";
				$res3 = mysql_query($sql3,$conRadius);
				while($lin3 = mysql_fetch_array($res3)){
					
					$comando = "echo \"User-Name := $Login\" | echo \"Framed-IP-Address = $FramedIPAddress\" | /usr/local/bin/radclient -r 1 -q $Nasipaddress:3799 disconnect $lin3[secret]";

					if($bd[server] != 'localhost'){
						$connection = ssh2_connect($bd[server], 22, $methods, $callbacks);
						if (!$connection) die('Connection failed');
						ssh2_auth_password($connection, $bd[userSSH], $bd[senhaSSH]);
						ssh2_exec($connection, $comando);
						ssh2_exec($connection, "quit");
					}else{
						$sql4 = "SELECT
									MAX(IdCodigoInterno) IdCodigoInterno
								FROM
									CodigoInterno
								WHERE
									IdGrupoCodigoInterno = 65 AND
									IdLoja = $IdLoja";
						$res4 = mysql_query($sql4,$con);
						$lin4 = mysql_fetch_array($res4);

						if($lin4[IdCodigoInterno] < 1){
							$lin4[IdCodigoInterno]=1;
						}else{
							$lin4[IdCodigoInterno]++;
						}

						$sql4 = "INSERT INTO CodigoInterno SET 
									IdGrupoCodigoInterno = 65,
									IdLoja = $IdLoja,
									IdCodigoInterno = $lin4[IdCodigoInterno],
									DescricaoCodigoInterno = $IdContrato,
									ValorCodigoInterno = '$comando',
									LoginCriacao = 'automatico',
									DataCriacao = now()";
						mysql_query($sql4,$con);
					}
				}
			}
		}
	}

	function BaseVencimento($DataBaseVencimento){
		$DataBaseVencimento = dia_util(incrementaData($DataBaseVencimento, 1));			
		if(getParametroSistema(136,date("w", strtotime($DataBaseVencimento))+1) != 1){	
			$DataBaseVencimento = incrementaData($DataBaseVencimento, 1);
			return BaseVencimento($DataBaseVencimento);			 			
		}
		$BaseVencimento = (nDiasIntervalo($DataBaseVencimento,date("Y-m-d"))-1);
		return $BaseVencimento;
	}
	
	function getOrdemServicoCor($IdOrdemServico){
		global $con;
		global $_SESSION;
		
		$IdLoja	= $_SESSION['IdLoja']; 
		
		$sql = "select
					OrdemServicoCorStatus.CorStatus,
					if(
						Servico.Cor != '',
						Servico.Cor,
						if(
							SubTipoOrdemServico.Cor != '',
							SubTipoOrdemServico.Cor,
							TipoOrdemServico.Cor
						)
					) Cor 
				from
					OrdemServico left join (
						select
							IdCodigoInterno IdStatus,
							ValorCodigoInterno CorStatus
						from
							CodigoInterno
						where
							IdGrupoCodigoInterno = 16
					) OrdemServicoCorStatus on (
						substring(OrdemServico.IdStatus,1,1) = OrdemServicoCorStatus.IdStatus
					),
					Servico,
					TipoOrdemServico,
					SubTipoOrdemServico
				where
					OrdemServico.IdLoja = $IdLoja and
					OrdemServico.IdOrdemServico = $IdOrdemServico and
					OrdemServico.IdLoja = Servico.IdLoja and
					OrdemServico.IdServico = Servico.IdServico and
					OrdemServico.IdLoja = TipoOrdemServico.IdLoja and
					OrdemServico.IdTipoOrdemServico = TipoOrdemServico.IdTipoOrdemServico and
					OrdemServico.IdLoja = SubTipoOrdemServico.IdLoja and
					OrdemServico.IdTipoOrdemServico = SubTipoOrdemServico.IdTipoOrdemServico and
					OrdemServico.IdSubTipoOrdemServico = SubTipoOrdemServico.IdSubTipoOrdemServico;";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		
		list($CorTexto,$CorListar) = explode("\r\n",$lin[CorStatus]);
		
		if($lin[Cor] != ''){
			$CorListar = $lin[Cor];
		}
		
		return $CorListar;
	}

	function check_execucao($FileScript){
		$tempFile = getParametroSistema(6,1)."temp/".md5(date('YmdHis'));
		@unlink($tempFile);
		system(" ps -ax | grep $FileScript > $tempFile");

		$file = file($tempFile);

		print_r($file);
		return false;
	}

	function posicaoCobranca($IdLoja, $IdContaReceber, $IdPosicaoCobranca, $Login, $IdAgrupadorAux = ''){

		global $con;
				
		$AguardandoEnvio	 = false;
		$AguardandoPagamento = false;
		$tr_i = 0;		

		# Verifica se o tipo do local de cobrança requer atualizar posicao cobrança
		
		$sql = "select
					LocalCobranca.IdLocalCobranca,
					LocalCobranca.IdTipoLocalCobranca,
					LocalCobranca.RemessaAtualizarContaReceber,
					LocalCobranca.RemessaAtualizarContaReceberViaCDA,
					ContaReceber.IdPessoa,
					ContaReceber.DataVencimento,
					ContaReceber.IdStatus
				from
					LocalCobranca,
					ContaReceber
				where
					LocalCobranca.IdLoja = $IdLoja and
					LocalCobranca.IdLoja = ContaReceber.IdLoja and
					LocalCobranca.IdLocalCobranca = ContaReceber.IdLocalCobranca and
					ContaReceber.IdContaReceber = $IdContaReceber";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		switch($lin[IdTipoLocalCobranca]){
			case 3:
				$IdContaDebito = $IdAgrupadorAux;
				
				if($IdContaDebito != ""){	
					$sql = "UPDATE ContaReceber SET  
								IdContaDebito = $IdContaDebito
							WHERE 
								IdLoja = $IdLoja and 
								IdContaReceber = $IdContaReceber";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}
				break;	
			
			case 6:
				$IdContaCartao = $IdAgrupadorAux;
				if($IdContaCartao != ""){
					$sql = "UPDATE ContaReceber SET  
								IdCartao = $IdContaCartao
							WHERE 
								IdLoja = $IdLoja and 
								IdContaReceber = $IdContaReceber";
					$local_transaction[$tr_i]	=	mysql_query($sql,$con);
					$tr_i++;
				}
				break;
		}
		
		if($IdPosicaoCobranca <= 0){	return true;	}
		if($lin[RemessaAtualizarContaReceber] != 1 && $IdPosicaoCobranca == 9){	return true;	}
		if($Login == 'cda' && $lin[RemessaAtualizarContaReceberViaCDA] != 1 && $IdPosicaoCobranca == 9){ return true;	}

		$sqlVerifica = "SELECT
							MAX(IdMovimentacao) IdMovimentacao
						FROM
							ContaReceberPosicaoCobranca
						WHERE
							IdLoja = $IdLoja and
							IdContaReceber = $IdContaReceber";
		$resVerifica = mysql_query($sqlVerifica,$con);
		$linVerifica = mysql_fetch_array($resVerifica);
		$IdMovimentacao = $linVerifica[IdMovimentacao] + 1;

		# Cobrança Registrada
		if($lin[IdTipoLocalCobranca] == 4){
			$sql = "INSERT INTO ContaReceberPosicaoCobranca set 
						IdLoja = $IdLoja,
						IdContaReceber = $IdContaReceber,
						IdMovimentacao = $IdMovimentacao,
						IdPosicaoCobranca = $IdPosicaoCobranca,							
						DataAlteracao = concat(curdate(),' ',curtime()),
						LoginAlteracao = '$Login'";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			echo mysql_error();
			switch($IdPosicaoCobranca){
				case 1: 
					# Enviar uma nova remssa para um conta a receber devolvido.
					$AguardandoEnvio = true;
					break;
				case 9:
					# Atualização geral de dados
					$AguardandoEnvio = true;
					break;
				case 10:
					# Não envia definitivamente
					if($lin[IdStatus] != 7){
						$AguardandoPagamento = true;
					} else{
						$sql = "UPDATE ContaReceber SET  
									IdPosicaoCobranca = $IdPosicaoCobranca
								WHERE 
									IdLoja = $IdLoja and 
									IdContaReceber = $IdContaReceber";
						$local_transaction[$tr_i]	=	mysql_query($sql,$con);
						$tr_i++;
						echo mysql_error();
					}
					break;
			}
		}
			
#		#Débito em Conta
		if($lin[IdTipoLocalCobranca] == 3){
			if($IdPosicaoCobranca == 1){
				$sql = "INSERT INTO ContaReceberPosicaoCobranca SET 
							IdLoja = $IdLoja,
							IdContaReceber = $IdContaReceber,
							IdMovimentacao = $IdMovimentacao,
							IdPosicaoCobranca = $IdPosicaoCobranca,
							IdPessoa = $lin[IdPessoa],
							IdContaDebito = $IdContaDebito,
							DataAlteracao = concat(curdate(),' ',curtime()),
							LoginAlteracao = '$Login'";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$tr_i++;
				echo mysql_error();
			}

			$sql = "";

			if($IdPosicaoCobranca == 5 && dataConv($lin[DataVencimento],"Y-m-d","Ymd") > dataConv(incrementaData(date("Y-m-d"),1), "Y-m-d", "Ymd")){
				
				$sql= "SELECT 
							IdContaReceber,
							IdPosicaoCobranca
					   FROM 
							ContaReceberPosicaoCobranca 
					   WHERE 
							IdLoja = $IdLoja and
							IdContaReceber = $IdContaReceber and
							DataRemessa != '0000-00-00' 
					   ORDER BY
							IdMovimentacao DESC";
				$resPosicaoCobranca = mysql_query($sql,$con);
				if($linPosicaoCobranca = mysql_fetch_array($resPosicaoCobranca)){
					if($linPosicaoCobranca[IdPosicaoCobranca] == 1){
						$sql = "INSERT INTO ContaReceberPosicaoCobranca SET 
									IdLoja = $IdLoja,
									IdContaReceber = $IdContaReceber,
									IdMovimentacao = $IdMovimentacao,
									IdPosicaoCobranca = $IdPosicaoCobranca,
									DataAlteracao = concat(curdate(),' ',curtime()),
									LoginAlteracao = '$Login'";
						$local_transaction[$tr_i]	=	mysql_query($sql,$con);
						$tr_i++;
						echo mysql_error();
					}
				}
			}
			switch($IdPosicaoCobranca){
				case 1: 
					# Enviar uma nova remssa para um conta a receber após se cancelado.
					$AguardandoEnvio = true;
					break;				
			}
		}

		if($IdPosicaoCobranca == 8){
			# Cancelar envio
			$sql = "DELETE FROM ContaReceberPosicaoCobranca WHERE IdLoja = $IdLoja and IdContaReceber = $IdContaReceber and DataRemessa = '0000-00-00'";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			echo mysql_error();
			
			$sql = "UPDATE ContaReceber SET IdStatus = 1 WHERE IdLoja = $IdLoja and IdContaReceber = $IdContaReceber and IdStatus = 3";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			echo mysql_error();
		}
		
		#Cartão de Credito
		if($lin[IdTipoLocalCobranca] == 6){
			if($IdPosicaoCobranca == 1){
				$sql = "INSERT INTO ContaReceberPosicaoCobranca SET 
							IdLoja = $IdLoja,
							IdContaReceber = $IdContaReceber,
							IdMovimentacao = $IdMovimentacao,
							IdPosicaoCobranca = $IdPosicaoCobranca,
							IdPessoa = $lin[IdPessoa],
							IdCartao = $IdContaCartao,
							DataAlteracao = concat(curdate(),' ',curtime()),
							LoginAlteracao = '$Login'";
				$local_transaction[$tr_i]	=	mysql_query($sql,$con);
				$AguardandoEnvio = true;
				$tr_i++;
				echo mysql_error();
			}
		}
		
		if($AguardandoEnvio == true){
			$sql = "UPDATE ContaReceber SET 
						IdStatus = 3, 
						IdPosicaoCobranca = $IdPosicaoCobranca
					WHERE 
						IdLoja = $IdLoja and 
						IdContaReceber = $IdContaReceber";
		}else{
			$sql = "UPDATE ContaReceber SET 
						IdPosicaoCobranca = $IdPosicaoCobranca
					WHERE 
						IdLoja = $IdLoja and 
						IdContaReceber = $IdContaReceber";
						
		}		
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		echo mysql_error();
		if($AguardandoPagamento == true){
			$sql = "UPDATE ContaReceber SET 
						IdStatus = 1, 
						IdPosicaoCobranca = $IdPosicaoCobranca
					WHERE 
						IdLoja = $IdLoja and 
						IdContaReceber = $IdContaReceber";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			echo mysql_error();
		}
				
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				return false;
			}
		}

		return true;
	}

	function dicionario($IdMensagem){

		global $con;
		global $Idioma;

		if($Idioma == ''){	$Idioma = getParametroSistema(4,9);	}

		$sql = "SELECT
					$Idioma
				FROM
					Dicionario
				WHERE
					IdMensagem = $IdMensagem";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);

		if($lin[0] == ''){
			return "[$IdMensagem]";
		}
		return $lin[0];
	}
	
	function diferencaDataUtil($DataIni, $DataFim){
		global $con;
		
		$Data = dia_util(substr($DataIni, 0, 10));
		$Hora = substr($DataIni, 10);
		
		if($Data != substr($DataIni, 0, 10)){
			$Hora = " 00:00:00";
		}
		
		$DataIni = $Data.$Hora;
		$DataMeio = incrementaData($Data, 1).$Hora;
		
		if(substr($DataMeio, 0, 10) == dia_util(substr($DataMeio, 0, 10))){
			list($YMenor, $mMenor, $dMenor, $HMenor, $iMenor, $sMenor) = quebraData($DataIni);
			list($YMaior, $mMaior, $dMaior, $HMaior, $iMaior, $sMaior) = quebraData($DataMeio);
			$sDiferenca = mktime($HMaior,$iMaior,$sMaior,$mMaior,$dMaior, $YMaior)-mktime($HMenor,$iMenor,$sMenor,$mMenor,$dMenor, $YMenor);
		} else{
			$DataMeio = incrementaData(substr($DataIni, 0, 10), 1)." 00:00:00";
			list($YMenor, $mMenor, $dMenor, $HMenor, $iMenor, $sMenor) = quebraData($DataIni);
			list($YMaior, $mMaior, $dMaior, $HMaior, $iMaior, $sMaior) = quebraData($DataMeio);
			$sDiferenca = mktime($HMaior,$iMaior,$sMaior,$mMaior,$dMaior, $YMaior)-mktime($HMenor,$iMenor,$sMenor,$mMenor,$dMenor, $YMenor);
		}
		
		while((int)str_replace("-", "", substr($DataMeio, 0, 10)) < (int)str_replace("-", "", substr($DataFim, 0, 10))){
			$DataIni = dia_util(substr($DataMeio, 0, 10)).substr($DataMeio, 10);
			$DataMeio = incrementaData(substr($DataIni, 0, 10), 1).substr($DataIni, 10);
			
			if(substr($DataMeio, 0, 10) == dia_util(substr($DataMeio, 0, 10))){
				list($YMenor, $mMenor, $dMenor, $HMenor, $iMenor, $sMenor) = quebraData($DataIni);
				list($YMaior, $mMaior, $dMaior, $HMaior, $iMaior, $sMaior) = quebraData($DataMeio);
				$sDiferenca += mktime($HMaior,$iMaior,$sMaior,$mMaior,$dMaior, $YMaior)-mktime($HMenor,$iMenor,$sMenor,$mMenor,$dMenor, $YMenor);
			} else{
				$DataMeio = incrementaData(substr($DataIni, 0, 10), 1)." 00:00:00";
				list($YMenor, $mMenor, $dMenor, $HMenor, $iMenor, $sMenor) = quebraData($DataIni);
				list($YMaior, $mMaior, $dMaior, $HMaior, $iMaior, $sMaior) = quebraData($DataMeio);
				$sDiferenca += mktime($HMaior,$iMaior,$sMaior,$mMaior,$dMaior, $YMaior)-mktime($HMenor,$iMenor,$sMenor,$mMenor,$dMenor, $YMenor);
			}
		}
		
		if((int)str_replace("-", "", substr($DataFim, 0, 10)) < (int)str_replace("-", "", dia_util(substr($DataFim, 0, 10)))){
			$DataFim = substr($DataFim, 0, 10)." 00:00:00";
		}
		
		list($YMenor, $mMenor, $dMenor, $HMenor, $iMenor, $sMenor) = quebraData($DataMeio);
		list($YMaior, $mMaior, $dMaior, $HMaior, $iMaior, $sMaior) = quebraData($DataFim);
		$sDiferenca += mktime($HMaior,$iMaior,$sMaior,$mMaior,$dMaior, $YMaior)-mktime($HMenor,$iMenor,$sMenor,$mMenor,$dMenor, $YMenor);
		
		if($sDiferenca < 0.00){
			return null;
		}
		
		$wDiferenca = floor($sDiferenca/604800);	# diferença em semanas
		$dDiferenca = round($sDiferenca/86400);		# diferença em dias
		$HDiferenca = round($sDiferenca/3600);		# diferença em horas
		$sDiferenca = round($sDiferenca/60); 		# diferença em minutos
		
		if($sDiferenca < 60){
			$sDiferenca = $sDiferenca." minuto";
			
			if($sDiferenca > 1){
				$sDiferenca .= "s";
			}
			
			return $sDiferenca;
		}
		
		if($sDiferenca > 59 && $HDiferenca < 24){
			$HDiferenca = $HDiferenca." hora";
			
			if($HDiferenca > 1){
				$$HDiferenca .= "s";
			}
			
			return $HDiferenca;
		}
		
		if($HDiferenca > 23 && $wDiferenca == 0){
			$dDiferenca = $dDiferenca." dia";
			
			if($dDiferenca > 1){
				$dDiferenca .= "s";
			}
			
			return $dDiferenca;
		}
		
		if($dDiferenca > 0 && ($wDiferenca > 0 && $wDiferenca < 4)){
			$wDiferenca = $wDiferenca." semana";
			
			if($wDiferenca > 1){
				$wDiferenca .= "s";
			}
			
			return $wDiferenca;
		}
		
		if($wDiferenca > 3){
			$mDiferenca = floor($wDiferenca/4);
			
			if($mDiferenca < 12){
				$mDiferenca = $mDiferenca." mes";
				
				if($mDiferenca > 1){
					$mDiferenca .= "es";
				}
				
				return $mDiferenca;
			} else{
				$YDiferenca = round($mDiferenca/12);
				$YDiferenca = $YDiferenca." ano";
				
				if($YDiferenca > 1){
					$YDiferenca .= "s";
				}
				
				return $YDiferenca;
			}
		}
	}
	
	function opcoesServicoParametro($rotina){

		global $con;

		# Verifico se é Código Interno
		if(stripos($rotina,"$[ci(") !== false){
			$CodigoInterno = endArray(explode("$[ci(",$rotina));
			$CodigoInterno = explode(")",$CodigoInterno);
			$CodigoInterno = explode(",",$CodigoInterno[0]);
			#verifica se existe o Código Interno
			$sqlCI = "SELECT
						IdGrupoCodigoInterno,
						IdCodigoInterno,
						ValorCodigoInterno
					FROM
						CodigoInterno
					WHERE 
						IdGrupoCodigoInterno = $CodigoInterno[0] AND 
						IdCodigoInterno = $CodigoInterno[1]";
			$resCI = mysql_query($sqlCI,$con);
			$linCI = mysql_fetch_array($resCI);
			if($linCI[ValorCodigoInterno] != ""){			
				$resultado = explode("\n",getCodigoInterno($CodigoInterno[0],$CodigoInterno[1]));
				$separador = substr($rotina,strlen("$[ci($CodigoInterno[0],$CodigoInterno[1])"),1);
				if($separador != "]"){
					$posicao = explode($separador,str_replace("]","",$rotina));
					$posicao = $posicao[1]-1;

					$count	=  	count($resultado);
					for($i=0; $i < $count; $i++){
						$resultado[$i] = explode($separador,$resultado[$i]);
						$resultado[$i] = $resultado[$i][$posicao];
					}
				}
			}
		}

		# Verifico se é Parametro do Sistema
		if(stripos($rotina,"$[ps(") !== false){
			$ParametroSistema = endArray(explode("$[ps(",$rotina));
			$ParametroSistema = explode(")",$ParametroSistema);
			$ParametroSistema = explode(",",$ParametroSistema[0]);
			$resultado = explode("\n",getParametroSistema($ParametroSistema[0],$ParametroSistema[1]));
			$separador = substr($rotina,strlen("$[ps($ParametroSistema[0],$ParametroSistema[1])"),1);
			if($separador != "]"){
				$posicao = explode($separador,str_replace("]","",$rotina));
				$posicao = $posicao[1]-1;

				$count	=  	count($resultado);
				for($i=0; $i < $count; $i++){
					$resultado[$i] = explode($separador,$resultado[$i]);
					$resultado[$i] = $resultado[$i][$posicao];
				}
			}
		}

		# Verifico se é uma url
		if(strtolower(substr($rotina,0,7)) == 'http://'){
			$resultado 	= 	@file($rotina);
		}

		$resultado[separador] = $separador;

		return $resultado;
	}

	function checkMonitor($IdLoja,$IdMonitor){
		global $con;

		// Teste da Porta
		$sql = "select 
					MonitorPorta.HostAddress, 
					MonitorPorta.Porta,
					MonitorPorta.Timeout
				from 
					MonitorPorta
				where 
					IdLoja = $IdLoja and
					IdMonitor = $IdMonitor";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		if($lin[Timeout] == ''){
			$lin[Timeout] = 1;
		}

		if($lin[Porta] > 0){
			$resultado[conectado] = @fsockopen($lin[HostAddress], $lin[Porta], $numeroDoErro, $stringDoErro, $lin[Timeout]);
		}

		// Teste da Latencia
		#$resultado[latencia] = end(explode(" = ",exec("ping -c 1 $lin[HostAddress]")));
		$resultado[latencia] = preg_replace("/^([^=]*[ =])/i", null, exec("ping -c 1 -W $lin[Timeout] $lin[HostAddress]"));
		$resultado[latencia] = explode("/",$resultado[latencia]);
		$resultado[latencia] = ($resultado[latencia][0] + $resultado[latencia][1] + $resultado[latencia][2])/3;
		

		if($resultado[latencia] > 0){
			$resultado[conectado] = true;
		}

		return $resultado;
	}

	function StrToObjectSNMP($string,$separador){
		$string = join(".",array_map("hexdec",explode($separador,$string)));
		return $string;
	}

	function endArray($array){
		return end($array);
	}
	
	function formata_telefone($telefone){
		
		$telefone = preg_replace("/[^0-9]/", "", $telefone);
		$telefone = $telefone * 1; // para tratar ex: 0983979380 -> 983979380

		if(strlen($telefone) != 10){
			$telefone = str_pad("0", 10, "0", STR_PAD_LEFT);
		}

		return $telefone;
	}

	function geraSenhaPersonalizada($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false){
		$lmin = 'abcdefghijkmnopqrstuvwxyz';
		$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$num = '1234567890';
		$simb = '!@#$%*-';
		$retorno = '';
		$caracteres = '';
		 
		$caracteres .= $lmin;
		if ($maiusculas) $caracteres .= $lmai;
		if ($numeros) $caracteres .= $num;
		if ($simbolos) $caracteres .= $simb;
		 
		$len = strlen($caracteres);
		for ($n = 1; $n <= $tamanho; $n++) {
		$rand = mt_rand(1, $len);
		$retorno .= $caracteres[$rand-1];
		}
		return $retorno;
	}
	
	function out_buffer($buffer) {
		/*
		 * FUNÇÃO UTILIZADA PARA RETORNO DE BUFFER DO PHP.
		 * EXEMPLO DE CHAMADA, ob_start("out_buffer");
		 * ASSIM COLOCADA APÓS UM ECHO OU PRINT IRÁ FORÇA O RETORNO DO BUFFER PARA A SOLICITAÇÃO.
		 */
		
		return $buffer;
	}

	function buscaIdHistoricoMensagemAnexo($local_IdLoja, $IdReEnvio, $IdHistoricoMensagem = 0){
		global $con;
		
		$sql = "select
					IdHistoricoMensagem,
					IdReEnvio
				from
					HistoricoMensagem
				where
					IdLoja = $local_IdLoja and
					IdHistoricoMensagem = $IdReEnvio";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		
		if($lin[IdReEnvio] != "" && $lin[IdReEnvio] != NULL && $lin[IdReEnvio] != 'NULL'){
			$IdReEnvio			 = $lin[IdReEnvio]; 
			$IdHistoricoMensagem = $lin[IdHistoricoMensagem]; 
			$lin[IdHistoricoMensagem] = buscaIdHistoricoMensagemAnexo($local_IdLoja, $IdReEnvio, $IdHistoricoMensagem);	
		}

		return $lin[IdHistoricoMensagem];
	}
	function getNavegador(){
		$userAgent = $_SERVER['HTTP_USER_AGENT'];	 
		if(preg_match('|MSIE ([0-9].[0-9]{1,2})|',$userAgent)) {		
			$navegador = 'IE';
		}elseif(preg_match( '|Opera/([0-9].[0-9]{1,2})|',$userAgent)) {		
			$navegador = 'Opera';
		}elseif(preg_match('|Firefox/([0-9\.]+)|',$userAgent)) {		
			$navegador = 'Firefox';
		}elseif(preg_match('|Chrome/([0-9\.]+)|',$userAgent)) {		
			$navegador = 'Chrome';
		}elseif(preg_match('|Safari/([0-9\.]+)|',$userAgent)) {		
			$navegador = 'Safari';
		}
		return $navegador;
	}
	function MinutosRestantes($Data){
		//data/hora atual------------------------------
		date_default_timezone_set('America/Sao_Paulo');
		$data = date('d/m/Y');
		$hora = date('H:i:s');
		$data = explode("/",$data);
		$hora = explode(":",$hora);
		
		$ano1 = (int) $data[2];
		$mes1 = (int) $data[1];
		$dia1 = (int) $data[0];
		$seg1 = (int) $hora[2];
		$min1 = (int) $hora[1];
		$hor1 = (int) $hora[0];
		//----------------------------------------------
		//data/hora contagem----------------------------
		$Data = explode(" ",$Data);
		$data2 = explode("-",$Data[0]);
		$hora2 = explode(":",$Data[1]);

		$ano2 = (int) $data2[0];
		$mes2 = (int) $data2[1];
		$dia2 = (int) $data2[2];
		$seg2 = (int) $hora2[2];
		$min2 = (int) $hora2[1];
		$hor2 = (int) $hora2[0];	
		//----------------------------------------------
	
		//calculo timestam das duas datas
		$timestamp1 = mktime($hor2,$min2,$seg2,$mes2,$dia2,$ano2);
		$timestamp2 = mktime($hor1,$min1,$seg1,$mes1,$dia1,$ano1);

		//diminuo a uma data a outra
		$segundos_diferenca = $timestamp1 - $timestamp2;

		//converto segundos em dias
		$minutos_diferenca = $segundos_diferenca/60;
       
        return ceil($minutos_diferenca);
    } 
	function nltobr($string) { 
		$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string); 
		return $string; 
	}
	function htmlespecialchars($string) { 
		$string = str_replace('"', "&#34;", $string);
		$string = str_replace("'", "&#39;", $string);
		$string = str_replace('(', "\&#40;", $string);
		$string = str_replace(')', "\&#41;", $string);
		return $string; 
	}
	function verificaAgenciaContaArquivoRetorno($IdLoja, $IdAgencia, $IdConta, $IdLocalRecebimento, $IdConvenio = 0){
		global $con;
		
		$local_erro = '';

		$sql = "select
					LocalCobranca.IdLocalCobrancaLayout,
					LocalCobrancaParametro.IdLocalCobrancaParametro,
					LocalCobrancaParametro.ValorLocalCobrancaParametro
				from
					LocalCobranca,
					LocalCobrancaParametro
				where
					LocalCobranca.IdLoja = $IdLoja and
					LocalCobranca.IdLoja = LocalCobrancaParametro.IdLoja and
					LocalCobranca.IdLocalCobranca = LocalCobrancaParametro.IdLocalCobranca and
					LocalCobranca.IdLocalCobranca = $IdLocalRecebimento";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			$LocalCobrancaParametro[$lin[IdLocalCobrancaParametro]] = $lin[ValorLocalCobrancaParametro];
		}

		switch($IdLocalCobrancaLayout){
			case '9': # BOLETO SANTANDER
				if(($LocalCobrancaParametro[Agencia] != $IdAgencia &&  $LocalCobrancaParametro[Agencia] != '') || ($LocalCobrancaParametro[CodigoCedente] != $IdConta && $LocalCobrancaParametro[CodigoCedente] != '')){
					$local_erro = 3020;
				}
				break;
			case '10': # BOLETO SANTANDER BANESPA - CART. COB
				if(($LocalCobrancaParametro[Agencia] != $IdAgencia &&  $LocalCobrancaParametro[Agencia] != '') || ($LocalCobrancaParametro[CodigoCedente] != $IdConta && $LocalCobrancaParametro[CodigoCedente] != '')){
					$local_erro = 3020;
				}
				break;
			case '16': # BOLETO SANTANDER BANESPA - CART. CSR-102
				if(($LocalCobrancaParametro[Agencia] != $IdAgencia &&  $LocalCobrancaParametro[Agencia] != '') || ($LocalCobrancaParametro[CodigoCedente] != $IdConta && $LocalCobrancaParametro[CodigoCedente] != '')){
					$local_erro = 3020;
				}
				break;
			case '40': # SANTANDER BANESPA - CART. CSR-102 - 3/folha
				if(($LocalCobrancaParametro[Agencia] != $IdAgencia &&  $LocalCobrancaParametro[Agencia] != '') || ($LocalCobrancaParametro[CodigoCedente] != $IdConta && $LocalCobrancaParametro[CodigoCedente] != '')){
					$local_erro = 3020;
				}
				break;
			default:
				if(($LocalCobrancaParametro[Agencia] != $IdAgencia &&  $LocalCobrancaParametro[Agencia] != '') || ($LocalCobrancaParametro[Conta] != $IdConta && $LocalCobrancaParametro[Conta] != '')){
					$local_erro = 3020;
				}else if($IdConvenio != 0 && $LocalCobrancaParametro[Convenio] != $IdConvenio){
					$local_erro = 3020;
				}
		}	

		#return $local_erro;
		return '';
	}
	function geraSenha($tamanho,$numeros,$simbolos){
		//$tamanho = Tamanho do retorno
		//$numeros = false/true inclue numero
		//$simbolos = false/true inclue simbolos
		$letrasMin = 'abcdefghijklmnopqrstuvwxyz1234567890';	
		$num = '1234567890';
		$simb = '!@#$%-';
		
		$retorno = '';
		$caracteres = '';

		$caracteres .= $letrasMin;
		if ($numeros) $caracteres .= $num;
		if ($simbolos) $caracteres .= $simb;

		$len = strlen($caracteres);

		for ($s = 1; $s <= $tamanho; $s++) {
			$rand = mt_rand(1, $len);
			$retorno .= $caracteres[$rand-1];
		}
		return $retorno;
	}
	function delimitaAteCaracter($Texto,$String){
		//$Texto = Texto onde deve ser feito a delimitação.
		//$String = Palavra de parada.
		
		if($String == "" || $Texto == ""){
			$String = "/*/";
			echo "Erro - Função declarada incorretamente use 2 parâmetros não vazios: <b>delimitaAteCaracter(Texto,String)</b><br>";
			return false;
		}
		//Inicio retirada de tags html que não precisas no retorno
		$Texto = str_replace('<br>','',$Texto);
		$Texto = str_replace('<p>','',$Texto);
		$Texto = str_replace('<\p>','',$Texto);
		$Texto = str_replace('<\b>','',$Texto);
		$Texto = str_replace('<\b>','',$Texto);
		//Fim retirada de tags html que não precisas no retorno
		if($posicaoString = strpos($Texto,$String)){
			$String = substr($Texto,0,$posicaoString-1);
		}else{
			$String = $Texto;
		}
		return $String;
	}
	function html_codes($string,$acao){
		//By: Leonardo
		//Função que converte caracteres inválidos para codigos html
		//Adicionar aqui mais caracteres se necessário no futuro
		//Fonte de pesquisa de codigos html: http://ascii.cl/htmlcodes.htm
		if($acao == 'encode'){
			$string = str_replace("&","&#38;",$string);
		}else{
			$string = str_replace("&#38;","&",$string);
		}
		
		return $string;
	}
	
	function conta_receber_cancelar_caixa_recebimento($dados){
		global $con;

		$tr_i									= 0;
		$local_IdLoja							= $dados["IdLoja"];
		$local_IdContaReceber					= $dados["IdContaReceber"];
		$local_IdContaReceberRecebimento		= $dados["IdContaReceberRecebimento"];
		$local_CancelarNotaFiscalRecebimento	= $dados["CancelarNotaFiscalRecebimento"];
		$local_IdCancelarNotaFiscal				= $dados["IdCancelarNotaFiscal"];
		$local_NumeroNF							= $dados["NumeroNF"];
		$local_Login							= $dados["Login"];
		$local_IdLocalEstorno					= $dados["IdLocalEstorno"];
		$local_IdContratoEstorno				= $dados["IdContratoEstorno"];
		$local_CreditoFuturo					= $dados["CreditoFuturo"];
		$local_ObsCancelamento					= $dados["ObsCancelamento"];
		
		if($local_Login == ''){
			$local_Login = 'automatico';
		}
		
		if($local_CancelarNotaFiscalRecebimento == '1'){
			if($local_IdCancelarNotaFiscal == '1'){
				if(cancela_nf($local_IdLoja, $local_IdContaReceber, $local_NumeroNF) == 68){
					$local_transaction[$tr_i] = false;
					$tr_i++;
				} else {
					$local_ObsNFC = date("d/m/Y H:i:s")." [".$local_Login."] - Nota Fiscal n° ".$local_NumeroNF." cancelada de acordo com cancelamento do Recebimento n° ".$local_IdContaReceberRecebimento.".";
				}
			} else {
				$local_ObsNFC = date("d/m/Y H:i:s")." [".$local_Login."] - Nota Fiscal n° ".$local_NumeroNF." não foi cancelada de acordo com cancelamento do Recebimento n° ".$local_IdContaReceberRecebimento.".";
			}
		}
		
		$ContaReceber = array($local_IdContaReceber);
		$ContaReceberRecebimento = array($local_IdContaReceberRecebimento);
		$ContaReceberStatus = array(1);
		$qtd = 1;
		
		$sql_ag = "SELECT 
						ContaReceberRecebimento.IdContaReceber,
						ContaReceberRecebimento.IdContaReceberRecebimento 
					FROM 
						ContaReceberAgrupado,
						ContaReceberAgrupadoParcela, 
						ContaReceberAgrupadoItem, 
						ContaReceberRecebimento 
					WHERE 
						ContaReceberAgrupado.IdLoja = '$local_IdLoja' AND 
						ContaReceberAgrupado.IdLoja = ContaReceberAgrupadoParcela.IdLoja AND 
						ContaReceberAgrupado.IdContaReceberAgrupador = ContaReceberAgrupadoParcela.IdContaReceberAgrupador AND 
						ContaReceberAgrupadoParcela.IdContaReceber = '$local_IdContaReceber' AND 
						ContaReceberAgrupadoParcela.IdLoja = ContaReceberAgrupadoItem.IdLoja AND 
						ContaReceberAgrupadoParcela.IdContaReceberAgrupador = ContaReceberAgrupadoItem.IdContaReceberAgrupador AND 
						ContaReceberAgrupadoItem.IdLoja = ContaReceberRecebimento.IdLoja AND 
						ContaReceberAgrupadoItem.IdContaReceber = ContaReceberRecebimento.IdContaReceber AND 
						ContaReceberRecebimento.IdStatus NOT IN (0, 3)";
		$res_ag = @mysql_query($sql_ag, $con);
		
		while($lin_ag = @mysql_fetch_array($res_ag)){
			$ContaReceber[$qtd] = $lin_ag["IdContaReceber"];
			$ContaReceberRecebimento[$qtd] = $lin_ag["IdContaReceberRecebimento"];
			$ContaReceberStatus[$qtd] = 8;
			$qtd++;
		}
		
		for($i = 0; $i < $qtd; $i++){
			$local_IdContaReceber = $ContaReceber[$i];
			$local_IdContaReceberRecebimento = $ContaReceberRecebimento[$i];
			$local_IdStatus = $ContaReceberStatus[$i];
			
			$sql_rc = "SELECT 
							Obs,
							ValorRecebido 
						FROM 
							ContaReceberRecebimento 
						WHERE 
							IdLoja						= '$local_IdLoja' AND 
							IdContaReceber				= '$local_IdContaReceber' AND 
							IdContaReceberRecebimento	= '$local_IdContaReceberRecebimento'";
			$res_rc = @mysql_query($sql_rc, $con);
			$lin_rc = @mysql_fetch_array($res_rc);
			
			$local_ObsRC = date("d/m/Y H:i:s")." [".$local_Login."] - Motivo Cancelamento: ".trim($local_ObsCancelamento);
			
			if($lin_rc["Obs"] != ""){
				$local_ObsRC .= "\n".trim($lin_rc["Obs"]);
			}
			
			$local_ValorCredito = $lin_rc["ValorRecebido"];
			
			if($local_IdLocalEstorno == ""){
				$local_IdLocalEstorno = 'NULL';
			}
			
			if($local_IdContratoEstorno == "" || $local_IdContratoEstorno == "0"){
				$local_IdContratoEstorno = 'NULL';
			}
			
			$sql = "UPDATE 
						ContaReceberRecebimento 
					SET
						IdStatus					= '0', 
						CreditoFuturo				= '$local_CreditoFuturo',
						IdLocalEstorno				= $local_IdLocalEstorno,
						IdContratoEstorno			= $local_IdContratoEstorno,
						Obs							= '$local_ObsRC',
						LoginAlteracao				= '$local_Login',
						DataAlteracao				= NOW()
					WHERE 
						IdLoja						= '$local_IdLoja' AND
						IdContaReceber				= '$local_IdContaReceber' AND
						IdContaReceberRecebimento	= '$local_IdContaReceberRecebimento'";
			$local_transaction[$tr_i] = mysql_query($sql,$con);
			$tr_i++;
			
			if($local_CreditoFuturo == 1){
				$sqlLancamento = "SELECT (MAX(IdLancamentoFinanceiro)+1) IdLancamentoFinanceiro FROM LancamentoFinanceiro WHERE IdLoja = $local_IdLoja";
				$resLancamento = mysql_query($sqlLancamento);
				$linLancamento = mysql_fetch_array($resLancamento);
				
				if($linLancamento["IdLancamentoFinanceiro"] != NULL){
					$IdLancamentoFinanceiro = $linLancamento["IdLancamentoFinanceiro"];
				} else {
					$IdLancamentoFinanceiro = 1;
				}
				
				$local_ObsLancamentoFinanceiro = date("d/m/Y H:i:s")." [".$local_Login."] - Obs: Estorno referente a cancelamento do recebimento nº $local_IdContaReceberRecebimento";
				
				$sqlLancamento = "INSERT INTO 
										LancamentoFinanceiro 
									SET 
										IdLoja					= '$local_IdLoja',
										IdLancamentoFinanceiro	= '$IdLancamentoFinanceiro',
										IdContrato				= $local_IdContratoEstorno,
										Valor					= '-".$local_ValorCredito."',
										IdProcessoFinanceiro	= NULL,
										IdEstorno				= '$local_IdContaReceberRecebimento',
										IdStatus				= 2,
										ObsLancamentoFinanceiro	= '$local_ObsLancamentoFinanceiro';"; //Aguardando Cobrança
				$local_transaction[$tr_i] = mysql_query($sqlLancamento,$con);
				$tr_i++;
				
				//status do recebimento = "Estorno"
				$sql = "UPDATE 
							ContaReceberRecebimento 
						SET
							IdStatus					= '3', 
							LoginAlteracao				= '$local_Login',
							DataAlteracao				= NOW()
						WHERE 
							IdLoja						= '$local_IdLoja' and
							IdContaReceber				= '$local_IdContaReceber' and
							IdContaReceberRecebimento	= '$local_IdContaReceberRecebimento'";
				$local_transaction[$tr_i] = mysql_query($sql,$con);
				$tr_i++;
			}
			
			$local_Obs = "";	
			
			if($i > 0){
				$local_Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Cancelamento recebimento nº $local_IdContaReceberRecebimento, via agrupamento Contas a receber n° $ContaReceber[0].";
			} else{
				$local_Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Cancelamento recebimento nº $local_IdContaReceberRecebimento.";
			}
			
			$local_Obs .= "\n".date("d/m/Y H:i:s")." [".$local_Login."] - Motivo: ".trim($local_ObsCancelamento).".";
			
			$cont = 0;	
			$total = 0;
			$sql2 = "SELECT IdStatus FROM ContaReceberRecebimento WHERE IdLoja = '$local_IdLoja' AND IdContaReceber = '$local_IdContaReceber'";
			$res2 = mysql_query($sql2,$con);
			
			while($lin2 = mysql_fetch_array($res2)){
				if($lin2["IdStatus"] == 0 || $lin2["IdStatus"] == 3) 
					$cont++;
				
				$total++;
			}
			
			if($total != 0 && $cont == $total){
				$sql5 = "SELECT Obs, IdStatus FROM ContaReceber WHERE IdLoja = $local_IdLoja AND IdContaReceber = $local_IdContaReceber";
				$res5 = mysql_query($sql5,$con);
				$lin5 = mysql_fetch_array($res5);
				
				if($lin5["IdStatus"] != "1"){
					$sql3 = "SELECT ValorParametroSistema FROM ParametroSistema WHERE IdGrupoParametroSistema = 35 AND IdParametroSistema = $lin5[IdStatus]";
					$res3 = @mysql_query($sql3,$con);
					$lin3 = @mysql_fetch_array($res3);
					
					$sql4 = "SELECT ValorParametroSistema FROM ParametroSistema WHERE IdGrupoParametroSistema = 35 AND IdParametroSistema = 1";
					$res4 = @mysql_query($sql4,$con);
					$lin4 = @mysql_fetch_array($res4);
					
					if($local_Obs != "") 
						$local_Obs .= "\n";
					
					$local_Obs .= date("d/m/Y H:i:s")." [".$local_Login."] - Alteração do Status [".$lin3["ValorParametroSistema"]." > ".$lin4["ValorParametroSistema"]."]";	
				}
				
				if($lin5[Obs] != ""){
					$local_Obs .= "\n".trim($lin5["Obs"]);
				}
				
				if($local_ObsNFC != ""){
					$local_Obs = $local_ObsNFC."\n".$local_Obs;
				}
				
				$sql = "UPDATE 
							ContaReceber 
						SET
							IdStatus		= '$local_IdStatus', 
							Obs				= '$local_Obs',
							LoginAlteracao	= '$local_Login',
							DataAlteracao	= NOW()
						WHERE 
							IdLoja			= '$local_IdLoja' AND
							IdContaReceber	= '$local_IdContaReceber'";
				$local_transaction[$tr_i] = mysql_query($sql,$con);
				$tr_i++;
			} else{
				$sql4 = "SELECT Obs, IdStatus FROM ContaReceber WHERE IdLoja = $local_IdLoja AND IdContaReceber = $local_IdContaReceber";
				$res4 = mysql_query($sql4,$con);
				$lin4 = mysql_fetch_array($res4);		
				
				if($lin4["Obs"] != ""){
					$local_Obs .= "\n".trim($lin4["Obs"]);
				}
				
				$sql = "UPDATE 
							ContaReceber 
						SET
							Obs				= '$local_Obs',
							LoginAlteracao	= '$local_Login',
							DataAlteracao	= NOW()
						WHERE 
							IdLoja			= '$local_IdLoja' AND
							IdContaReceber	= '$local_IdContaReceber'";
				$local_transaction[$tr_i] = mysql_query($sql,$con);
				$tr_i++;
			}

			$sqlQtdRecebimento = "SELECT
										COUNT(*) Qtd
									FROM
										ContaReceberRecebimento
									WHERE
										IdLoja			= $local_IdLoja AND
										IdContaReceber	= $local_IdContaReceber AND
										IdStatus		= 1";
			$resQtdRecebimento = mysql_query($sqlQtdRecebimento,$con);
			$linQtdRecebimento = mysql_fetch_array($resQtdRecebimento);

			if($linQtdRecebimento["Qtd"] == 0){
				$local_transaction[$tr_i] = posicaoCobranca($local_IdLoja, $local_IdContaReceber, 8, $local_Login);
				$tr_i++;
			}
		}
		
		return (!in_array(false, $local_transaction));
	}
	
	function verificaStatusTipoMensagem($IdLoja,$IdTipoMensagem,$Caso){
		global $con;
		
		if($IdLoja == '' || $IdTipoMensagem == '' || $Caso == ''){
			echo "Erro - Função declarada incorretamente use 3 parâmetros não vazios: <b>verificaStatusTipoMensagem(IdLoja,IdTipoMensagem,Caso)</b><br>";
		}
		
		switch($Caso){
			case 'Enviar':		
				$sql = "SELECT
							IdLoja,
							IdTipoMensagem,
							IdStatus
						FROM 
							TipoMensagem
						WHERE					
							IdLoja = $IdLoja AND 
							IdTipoMensagem = $IdTipoMensagem";
				$res = mysql_query($sql,$con);
				$lin = mysql_fetch_array($res);
				
				if($lin[IdStatus] == 1){
					return true;
				}else{
					return false;
				}
				break;
				
			case 'Reenviar':
				$sql = "select 
					HistoricoMensagem.Email,
					TipoMensagem.IdStatus,
					TipoMensagem.Titulo,
					TipoMensagem.IdTipoMensagem
				from
					HistoricoMensagem,
					TipoMensagem 
				where HistoricoMensagem.IdLoja = $IdLoja 
					AND TipoMensagem.IdTipoMensagem = HistoricoMensagem.IdTipoMensagem 
					AND IdHistoricoMensagem = $IdTipoMensagem";
				$res = mysql_query($sql, $con);
				$lin = mysql_fetch_array($res);
				
				if($lin[IdStatus] == 1){
					return true;
				}else{
					return false;
				}
				break;	
		}
	}
?>
