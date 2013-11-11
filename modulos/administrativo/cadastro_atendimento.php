<?
	$localModulo		=	1;
	$localOperacao		=	76;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login		= $_SESSION["Login"];
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_Acao 		= $_POST['Acao'];
	$local_Erro			= $_GET['Erro'];
	
	$local_IdAtendimento		= $_GET['IdAtendimento'];
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
	    <link rel = 'stylesheet' type = 'text/css' href = '../../classes/calendar/calendar-blue.css' />
				
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
	</head>
	<body  onLoad="ativaNome('Atendimento On-Line')">
		<? 
			include('filtro_atendimento.php'); 
		?>
		<div>	
			<?
				$i		=	0;	
				$sql	=	"select 
								Atendimento.IdAtendimento,
								Atendimento.Login,
								Atendimento.IdPessoa,
								Pessoa.TipoPessoa,
								substr(Pessoa.Nome,1,35) Nome, 
								substr(Pessoa.RazaoSocial,1,35) RazaoSocial, 
								substr(Atendimento.Data,12,5) Hora,
								Atendimento.Data,
								Usuario.TipoPessoaUsuario,
								substr(Usuario.NomeUsuario,1,35) NomeUsuario, 
								substr(Usuario.RazaoSocialUsuario,1,35),
			 					RazaoSocialUsuario,
								Atendimento.Mensagem,
								Atendimento.Origem
							 from 
							    Atendimento LEFT JOIN (select Login, TipoPessoa as TipoPessoaUsuario, Nome as NomeUsuario, RazaoSocial as RazaoSocialUsuario from Usuario,Pessoa where Usuario.IdPessoa = Pessoa.IdPessoa) Usuario ON (Atendimento.Login = Usuario.Login),
								Pessoa
							where
								Atendimento.IdPessoa = Pessoa.IdPessoa and
								Atendimento.IdAtendimento = $local_IdAtendimento";
				$res	=	mysql_query($sql,$con);
				while($lin	=	mysql_fetch_array($res)){
					if($i == 0){
						$diasemana =	date("w", $lin[Data]);
						
						switch($diasemana){  
							case "0": $diasemana = "Domingo";       break;  
							case "1": $diasemana = "Segunda-Feira"; break;  
							case "2": $diasemana = "Terça-Feira";   break;  
							case "3": $diasemana = "Quarta-Feira";  break;  
							case "4": $diasemana = "Quinta-Feira";  break;  
							case "5": $diasemana = "Sexta-Feira";   break;  
						 	case "6": $diasemana = "Sábado";        break;  
						}
						
						$dia		=	substr($lin[Data],8,2);
						$mes		=	mes(substr($lin[Data],5,2));
						$ano		=	substr($lin[Data],0,4);
						echo"
						<table cellpading='0' cellspacing='0' style='width:100%; border-bottom:1px #004492 solid;'>
							<tr>
								<td colspan='2' style='padding:3px;'><B>Data da Sessão: $diasemana, $dia de $mes de $ano</B></td>
							</tr>
						";
					}
					
					if($lin[TipoPessoa]=='1'){
						$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
					}
					
					$aux	=	explode(' ',$lin[Nome]);
					
					if(strlen($aux[1]) > 2){
						$lin[Nome]	=	$aux[0].' '.$aux[1]; 
					}else{
						$lin[Nome]	=	$aux[0].' '.$aux[2]; 
					} 
					
					
					if($lin[TipoPessoaUsuario]=='1'){
						if($lin[trim(getCodigoInterno(3,24))] == 'Nome'){
							$lin[NomeUsuario]	=	$lin[NomeUsuario];
						}else{
							$lin[NomeUsuario]	=	$lin[RazaoSocialUsuario];			
						}	
					}	
					
					$aux	=	explode(' ',$lin[NomeUsuario]);
					
					if(strlen($aux[1]) > 2){
						$lin[NomeUsuario]	=	$aux[0].' '.$aux[1]; 
					}else{
						$lin[NomeUsuario]	=	$aux[0].' '.$aux[2]; 
					} 
					
					echo"<tr>";
					
					$lin[Hora]	=	substr($lin[Data],11,5);
					
					if($lin[Origem] == 1){
						echo"
							<td style='padding:3px; width:20%'>($lin[Hora]) $lin[NomeUsuario]:</td>
							<td style='padding:3px'>$lin[Mensagem]</td>
						";
					}else{
						echo"
							<td style='background-color:#E2E7ED; padding:3px; width:20%'>($lin[Hora]) <B>$lin[Nome]</B>:</td>
							<td style='background-color:#E2E7ED; padding:3px'><B>$lin[Mensagem]</B></td>
						";
					}
					echo"</tr>";
					$i++;
				}
				if($i>0){
					echo"</table>";
				}
			?>
		</div>
	</div>
</body>
</html>

