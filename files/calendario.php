<?
	
	function calendario($local_IdLoja,$quantDias,$iniSemana,$m){
		
		global $con;
		
			# Declaração de Meses
		$mes[1]		=	"Janeiro";
		$mes[2]		=	"Fevereiro";
		$mes[3]		=	"Março";
		$mes[4]		=	"Abril";
		$mes[5]		=	"Maio";
		$mes[6]		=	"Junho";
		$mes[7]		=	"Julho";
		$mes[8]		=	"Agosto";
		$mes[9]		=	"Setembro";
		$mes[10]	=	"Outubro";
		$mes[11]	=	"Novembro";
		$mes[12]	=	"Dezembro";
		
		
		$pos	=	1;
		for($i=1; $i<=6; $i++){
			if($i	==	1){	$pos	=	(-1*$iniSemana)+1;	}
			echo "<tr>";
			for($ii=$pos, $iii=0;	$ii<=$pos+6;	$ii++){
				$iii++;
				if($ii	<=	0){
					echo "<td>&nbsp;</td>";
				}else{
					$dia	=	diaMax($quantDias,$ii);
					$data	=	date("Y-m");	//$data	=	"2008-06";
					
					if($dia < 10){
						$data  .=	"-0".$dia;
					}else{
						$data  .=	"-".$dia;
					}
					
					
					$sql	=	"SELECT  TipoData,DescricaoData from DatasEspeciais where IdLoja = $local_IdLoja and Data = '$data'";
					$res	=	@mysql_query($sql,$con);
					$lin	=	@mysql_fetch_array($res);
					
					$class		=	"";
					$title		=	"";
					
					if(mysql_num_rows($res) >= 1){
						$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=52 and IdParametroSistema=$lin[TipoData]";
						$res2 = @mysql_query($sql2,$con);
						$lin2 = @mysql_fetch_array($res2);
						
						$lin2[ValorParametroSistema]	=	explode("\n",$lin2[ValorParametroSistema]);
						$lin2[Cor]						=	$lin2[ValorParametroSistema][1];
						
						$class	=	"background-color:$lin2[Cor]; ";
						$title	=	"$lin[DescricaoData]";
					}
					
					if($dia == date('d')){
							$class	=	"background-color:".getParametroSistema(15,6).";";
					}
					
					$sql3	=	"select	count(IdAgenda) QTD	from Agenda left join Pessoa on (Agenda.IdPessoa = Pessoa.IdPessoa)	where Login='".$_SESSION['Login']."' and Agenda.Data = '$data'";
					$res3	=	@mysql_query($sql3,$con);
					$lin3	=	@mysql_fetch_array($res3);
					
					if($lin3[QTD] >= 1){
						$class	.=	"cursor:pointer; border: 1px #004492 solid";
						$dia	 = "<a href=\"javascript:agenda('".dataConv($data,'Y-m-d','d/m/Y')."')\">$dia</a>";
						
						if($title != ""){
							$title	.=	"\n";
						}
						
						if($lin3[QTD] == 1){
							$title	.=	$lin3[QTD]." anotacão.";	
						}else{
							$title	.=	$lin3[QTD]." anotacões.";						
						}	
					}
					
					echo "<td style='$class' title='$title'>$dia</td>";
				}
				if($ii	==	$quantDias){
					$diaFinal	=	$iii-1;
				}										
			}
			$pos	=	$pos	+	7;
			echo "</tr>";				
		}
		return $diaFinal+1;
	}
 	function diaMax($quantDias,$ii){
		if($ii<=$quantDias){
			return $ii;
		}else{
			return "&nbsp;";
		}
	}
?>
