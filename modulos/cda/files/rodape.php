<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="20"><img src="img/rp1.png" /></td>
		<td class="transparente">
		<?
			$Empresa	=	getParametroSistema(95,3);
			$Telefone	=	getParametroSistema(95,4);
			$Emails		=	getParametroSistema(95,5);
			$Email		=	"";
			
			$aux	=	explode(';',$Emails);
			$tam	=	sizeof($aux);
			
			$i=0;
			while($i < $tam){
				if($Email != ""){
					$Email	.=	";";	
				}	
				$Email	.=	"<a href='mailto:".$aux[$i]."'>".$aux[$i]."</a>";
				$i++;
			}

			echo $Empresa." - tel: ".$Telefone." - email: ".$Email;
		?>
		</td>
		<td width="20"><img src="img/rp2.png" /></td>
	</tr>
</table>