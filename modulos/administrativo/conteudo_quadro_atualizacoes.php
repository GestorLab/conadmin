<ul>
	<?
		$file = @file("http://update.cntsistemas.com.br/busca.php?Build=$IdVersao&Licenca=$IdLicenca");

		if($file == false){			
			echo "<li>Não foi possível conectar no servidor da CNTSistemas.</li>";
		}else{
			if(@end($file) == 'false' || getCodigoInterno(46,1) == 0){
				echo "<li>Não há atualizações disponíveis.</li>";
			}else{
				for($i=0; $i<count($file); $i++){
					
					$DadosVersao = explode("|",$file[$i]);

					if($DadosVersao[2] != ''){
						echo "<li><b>$DadosVersao[2]</b> - ".dataConv($DadosVersao[1],"Y-m-d H:i:s","d/m/Y H:i:s")." - <a href='cadastro_atualizacao.php?IdVersao=$DadosVersao[0]&DescricaoVersao=$DadosVersao[2]'>Atualizar Agora!</a></li>";
					}
				}
			}
		}
	?>
</ul>