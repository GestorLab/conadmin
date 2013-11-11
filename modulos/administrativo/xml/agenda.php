<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Agenda(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$Login					= $_SESSION["Login"];
		$local_IdAgenda			= $_GET['IdAgenda'];
		$local_Descricao		= $_GET['Descricao'];
		$local_Data				= $_GET['Data'];
		$where					=	"";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($_GET['IdAgenda'] != ''){	$local_IdAgenda = $_GET['IdAgenda'];		}	
	
		if($local_IdAgenda != '')	{	
			$where .= " and IdAgenda = $local_IdAgenda"; 	
		}
		if($local_Descricao!=''){			
			$where .= " and Agenda.Descricao like'".$local_Descricao."%'";
		}
		if($local_Data!=''){
			if($local_Data == 'busca'){			
				$where .= " and Agenda.Data <= '2008-06-09' and Status = 1";
			}else{
				$where .= " and Agenda.Data = '".$local_Data."'";
			}
		}
		
		$sql	=	"select
							IdAgenda, 
							Data,
							Hora,
							Descricao,
							Agenda.IdPessoa,
							RazaoSocial,
							Nome,
							Status
						from 
							Agenda left join Pessoa on (Agenda.IdPessoa = Pessoa.IdPessoa)
						where
							Login='".$_SESSION['Login']."' $where 
						order by
							Data ASC,Hora ASC, IdAgenda ASC $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$lin[Nome] 			= $lin[getCodigoInterno(3,24)];
			$lin[DataAgenda]	= dataConv($lin[Data],'Y-m-d','d/m/y');
		
			$dados	.=	"\n<IdAgenda>$lin[IdAgenda]</IdAgenda>";
			$dados	.=	"\n<Data><![CDATA[$lin[Data]]]></Data>";
			$dados	.=	"\n<DataAgenda><![CDATA[$lin[DataAgenda]]]></DataAgenda>";
			$dados	.=	"\n<Hora><![CDATA[$lin[Hora]]]></Hora>";
			$dados	.=  "\n<Descricao><![CDATA[$lin[Descricao]]]></Descricao>";
			$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
			$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[Status]]]></IdStatus>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Agenda();
?>
