<?
    
	$vi_tempabertura  = getCodigoInterno(3,112); 
	//Permissao para visualizar Quadro Ordem Servico
	$sql	=	"select 
					IdParametroSistema IdQuadroAviso
				from 
					ParametroSistema,
					GrupoUsuarioQuadroAviso
				where   
					GrupoUsuarioQuadroAviso.IdLoja = $local_IdLoja and
					GrupoUsuarioQuadroAviso.IdQuadroAviso = ParametroSistema.IdParametroSistema and 
					IdGrupoParametroSistema = 56 and 
					IdParametroSistema = 1 and
					IdGrupoUsuario in (select UsuarioGrupoUsuario.IdGrupoUsuario from 	UsuarioGrupoUsuario, GrupoUsuario, Usuario, Pessoa where UsuarioGrupoUsuario.IdLoja = $local_IdLoja and UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and UsuarioGrupoUsuario.Login = Usuario.Login and UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and Usuario.IdPessoa = Pessoa.IdPessoa and Pessoa.TipoUsuario = 1 and UsuarioGrupoUsuario.Login = '$local_Login' group by	UsuarioGrupoUsuario.IdGrupoUsuario);";
	$res	=	mysql_query($sql,$con);
	if(mysql_num_rows($res)>=1){
		$vi_cidade			=	getCodigoInterno(3,96);
		$vi_tipo			=	getCodigoInterno(3,94);
		$vi_subtipo			=	getCodigoInterno(3,95);
		$vi_bairro			= 	getCodigoInterno(3,198);
		$vi_responsavel 	=	getCodigoInterno(3,220);
		
		$where1		=	"";
		
		if($_SESSION["RestringirAgenteAutorizado"] == true){
			$sqlAgente	=	"select 
								AgenteAutorizado.IdGrupoPessoa 
							from 
								AgenteAutorizado
							where 
								AgenteAutorizado.IdLoja = $local_IdLoja  and 
								AgenteAutorizado.IdAgenteAutorizado = '$local_IdPessoaLogin' and 
								AgenteAutorizado.Restringir = 1 and 
								AgenteAutorizado.IdStatus = 1 and
								AgenteAutorizado.IdGrupoPessoa is not null";
			$resAgente	=	@mysql_query($sqlAgente,$con);
			while($linAgente	=	@mysql_fetch_array($resAgente)){
				$where1    .=	" and Pessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
			}
		}
		if($_SESSION["RestringirAgenteCarteira"] == true){
			$sqlAgente	=	"select 
								AgenteAutorizado.IdGrupoPessoa 
							from 
								AgenteAutorizado,
								Carteira
							where 
								AgenteAutorizado.IdLoja = $local_IdLoja  and 
								AgenteAutorizado.IdLoja = Carteira.IdLoja and
								AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
								Carteira.IdCarteira = '$local_IdPessoaLogin' and 
								AgenteAutorizado.Restringir = 1 and 
								AgenteAutorizado.IdStatus = 1 and 
								AgenteAutorizado.IdGrupoPessoa is not null";
			$resAgente	=	@mysql_query($sqlAgente,$con);
			while($linAgente	=	@mysql_fetch_array($resAgente)){
				$where1    .=	" and Pessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
			}
		}
		
		$i = 0;
		$LoginAtendimento =  $local_Login;
		$visivel = false;
		
		$sql2	=	"select
						OrdemServico.IdOrdemServico,
						OrdemServico.IdGrupoUsuarioAtendimento,
						GrupoUsuario.DescricaoGrupoUsuario
					from
						OrdemServico LEFT JOIN (
							select 
								Pessoa.IdPessoa,
								GrupoPessoa.IdGrupoPessoa 
							from
								Pessoa left join (
									PessoaGrupoPessoa, 
									GrupoPessoa
								) on (
									Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
									PessoaGrupoPessoa.IdLoja = '$local_IdLoja' and 
									PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
									PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
								)
						) Pessoa ON (
							OrdemServico.IdPessoa = Pessoa.IdPessoa
						),
						GrupoUsuario
					where
						OrdemServico.IdLoja = $local_IdLoja and
						OrdemServico.IdLoja = GrupoUsuario.IdLoja and
						OrdemServico.IdGrupoUsuarioAtendimento = GrupoUsuario.IdGrupoUsuario and
						if(OrdemServico.IdGrupoUsuarioAtendimento is NOT NULL,
							OrdemServico.IdGrupoUsuarioAtendimento in (
								select 
									IdGrupoUsuario 
								from 
									UsuarioGrupoUsuario 
								where 
									IdLoja = $local_IdLoja and 
									Login = '$local_Login'
							),
							(OrdemServico.IdGrupoUsuarioAtendimento is NULL and OrdemServico.LoginCriacao = '$local_Login')
						) and 
						(
							(OrdemServico.IdStatus >= 300 and OrdemServico.IdStatus <= 399) or 
							(OrdemServico.IdStatus >= 100 and OrdemServico.IdStatus <= 199)
						) $where1
					group by 
						OrdemServico.IdGrupoUsuarioAtendimento";
		$res2 = mysql_query($sql2,$con);
		while($lin2 = mysql_fetch_array($res2)){
			$local_CampoOrderByVet[$i]	= $_GET['CampoOrderById'.$i];
			$local_OrderVet[$i]			= $_GET['OrderId'.$i];
			
			if($local_CampoOrderByVet[$i] == ''){
				switch(getCodigoInterno(3,229)){
					case 'Id':
						$local_CampoOrderByVet[$i] = 1;
						break;
					case 'Cliente':
						$local_CampoOrderByVet[$i] = 2;
						break;
					case 'Descrição':
						$local_CampoOrderByVet[$i] = 3;
						break;
					case 'Bairro':
						$local_CampoOrderByVet[$i] = 4;
						break;
					case 'Cidade':
						$local_CampoOrderByVet[$i] = 5;
						break;
					case 'Tipo':
						$local_CampoOrderByVet[$i] = 6;
						break;
					case 'SubTipo':
						$local_CampoOrderByVet[$i] = 7;
						break;
					case 'Atend.':
						$local_CampoOrderByVet[$i] = 8;
						break;
					case 'Data':
						$local_CampoOrderByVet[$i] = 9;
						break;
					case 'Responsável':
						$local_CampoOrderByVet[$i] = 10;
						break;
					case 'Tempo Aber.':
						$local_CampoOrderByVet[$i] = 11;
						break;
					case 'Status':
						$local_CampoOrderByVet[$i] = 12;
						break;
					default:
						$local_CampoOrderByVet[$i] = 9;// posição inicial da seta de ordenação
						break;
				}
			}
			
			if($local_OrderVet[$i] == '' || $local_OrderVet[$i] == 1){
				$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_descending.gif'>";																		
				$local_OrderBy  = 'DESC';
				$local_OrderVet[$i] = 2;																
			}else{
				$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_ascending.gif'>"; 																	
				$local_OrderBy = 'ASC';
				$local_OrderVet[$i] = 1;																
			}
			$local_ImagemSeta = array();
			$local_ImagemSeta[$local_CampoOrderByVet[$i]] = $local_ImagemSetaDefault;
			
			switch($local_CampoOrderByVet[$i]){
				case 1:
					$local_CampoOrdemByVet[$i] = 'OrdemServico.IdOrdemServico';
					break;
				case 2:
					$local_CampoOrdemByVet[$i] = 'Pessoa.Nome';
					break;
				case 3:
					$local_CampoOrdemByVet[$i] = 'OrdemServico.DescricaoOS';
					break;
				case 4:
					$local_CampoOrdemByVet[$i] = 'PessoaEndereco.Bairro';
					break;
				case 5:
					$local_CampoOrdemByVet[$i] = 'Cidade.NomeCidade';
					break;
				case 6:
					$local_CampoOrdemByVet[$i] = 'TipoOrdemServico.DescricaoTipoOrdemServico';
					break;
				case 7:
					$local_CampoOrdemByVet[$i] = 'SubTipoOrdemServico.DescricaoSubTipoOrdemServico';
					break;
				case 8:
					$local_CampoOrdemByVet[$i] = 'OrdemServico.LoginAtendimento';
					break;
				case 9:
					$local_CampoOrdemByVet[$i] = 'OrdemServico.DataAgendamentoAtendimento';
					break;
				case 10:
					$local_CampoOrdemByVet[$i] = 'OrdemServico.LoginSupervisor';
					break;
				case 11:
					$local_CampoOrdemByVet[$i] = 'OrdemServico.DataCriacao';
					break;
				case 12:
					$local_CampoOrdemByVet[$i] = 'OrdemServico.IdStatus';
					break;
			}
			
			$where = "";
			
			if($LoginAtendimento!= ''){
				$where	.=	" and if(OrdemServico.IdGrupoUsuarioAtendimento is NOT NULL,OrdemServico.IdGrupoUsuarioAtendimento in (select IdGrupoUsuario from UsuarioGrupoUsuario where Login = '$LoginAtendimento'),(OrdemServico.IdGrupoUsuarioAtendimento is NULL and OrdemServico.LoginCriacao = '$LoginAtendimento')) ";
				$where	.=  " and ((OrdemServico.IdStatus >= 300 and OrdemServico.IdStatus <= 399) or (OrdemServico.IdStatus >= 100 and OrdemServico.IdStatus <= 199))";
			}				
			if($lin2[IdGrupoUsuarioAtendimento] != ""){
				$where	.=	" and OrdemServico.IdGrupoUsuarioAtendimento = $lin2[IdGrupoUsuarioAtendimento]";
				$IdGrupoUsuarioAtendimento = $lin2[IdGrupoUsuarioAtendimento];
			}	

			$sql	=	"select
							OrdemServico.IdOrdemServico,
							OrdemServico.IdContrato,
							OrdemServico.IdTipoOrdemServico,
							OrdemServico.EmAtendimento,
							TipoOrdemServico.DescricaoTipoOrdemServico,
							TipoOrdemServico.Cor CorTipo,
							OrdemServico.IdSubTipoOrdemServico,
							SubTipoOrdemServico.DescricaoSubTipoOrdemServico,
							SubTipoOrdemServico.Cor CorSubTipo,
							OrdemServico.IdPessoa,
							Pessoa.TipoPessoa,
							OrdemServico.DescricaoOS DescricaoOSAlt,
							substr(OrdemServico.DescricaoOS, 1, 20) DescricaoOS,
							Pessoa.Nome NomeAlt,
							substr(Pessoa.Nome, 1, 20) Nome,
							Pessoa.RazaoSocial,
							OrdemServico.IdStatus,
							OrdemServico.DataAgendamentoAtendimento,
							OrdemServico.LoginAtendimento,
							OrdemServico.LoginSupervisor,
							OrdemServico.DataCriacao,
							Cidade.NomeCidade,
							Estado.SiglaEstado,
							OrdemServico.IdMarcador,
							Servico.DescricaoServico,
							Servico.DetalheServico,
							PessoaEndereco.Bairro
						from
							OrdemServico left join Pessoa on 
							(
								OrdemServico.IdPessoa = Pessoa.IdPessoa
							) left join PessoaEndereco on 
							(
								Pessoa.IdPessoa = PessoaEndereco.IdPessoa and 
								PessoaEndereco.IdPessoaEndereco = OrdemServico.IdPessoaEndereco
							) left join Pais on 
							(
								Pais.IdPais = PessoaEndereco.IdPais
							) left join Estado on 
							(
								Pais.IdPais = Estado.IdPais and 
								Estado.IdEstado = PessoaEndereco.IdEstado
							) left join Cidade on 
							(
								Pais.IdPais = Cidade.IdPais and 
								Estado.IdEstado = Cidade.IdEstado and 
								Cidade.IdCidade = PessoaEndereco.IdCidade
							) left join Servico on 
							(
								OrdemServico.IdLoja = Servico.IdLoja and 
								OrdemServico.IdServico = Servico.IdServico
							),
							TipoOrdemServico,
							SubTipoOrdemServico
						where
							OrdemServico.IdLoja = $local_IdLoja and
							OrdemServico.IdLoja = TipoOrdemServico.IdLoja and
							TipoOrdemServico.IdLoja = SubTipoOrdemServico.IdLoja and
							OrdemServico.IdTipoOrdemServico = TipoOrdemServico.IdTipoOrdemServico and
							OrdemServico.IdSubTipoOrdemServico = SubTipoOrdemServico.IdSubTipoOrdemServico and
							TipoOrdemServico.IdTipoOrdemServico = SubTipoOrdemServico.IdTipoOrdemServico and
							OrdemServico.IdGrupoUsuarioAtendimento = $lin2[IdGrupoUsuarioAtendimento]
							$where 
						order by
							$local_CampoOrdemByVet[$i]/*,
							OrdemServico.IdOrdemServico*/ $local_OrderBy	
			";	
			$res	=	mysql_query($sql,$con);
		#if($_SESSION["filtro_".$OrdemDeServico] != 2){
			if(@mysql_num_rows($res) >=1){
				#if($_SESSION["filtro_".$OrdemDeServico] != 2){
				$OrdemDeServico = "OrdemDeServico$lin2[DescricaoGrupoUsuario]";
				echo $local_Seta;
				echo"<div style='padding-top:5px;'><div class='quadroAviso' style='width:100%-10px; margin:0 5px 0 5px;' id='Fatura'>
				<p class='tit'><a href='listar_ordem_servico.php' style='color:#FFF'>ORDEM DE SERVIÇO - GRUPO: $lin2[DescricaoGrupoUsuario]</a> <a href='cadastro_ordem_servico.php' style='color:#FFF'>[+]</a></p>
				<div style='text-align:right; margin-top:-18px; padding-bottom:2px; cursor:move;'><img id='botao_OS_grupo_".$lin2[DescricaoGrupoUsuario]."' style='cursor:pointer; margin-right:4px;' onClick=\"ocultarQuadro(this, 'tabela".$OrdemDeServico."');\" title='Minimizar' alt='Minimizar' src='../../img/estrutura_sistema/ico_seta_up.gif' /></div>
				<div id='tabela".$OrdemDeServico."'>
				<table border='0' cellpadding='0' cellspacing='0' style=' display: $ocultar; width:100%; margin-bottom: 0;' id='Fatura'>
					<tr >
						<td width='40px' ><a href='conteudo.php?CampoOrderById$i=1&OrderId$i=$local_OrderVet[$i]' target='_self'><B>Id</B>$local_ImagemSeta[1]</a></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td><a href='conteudo.php?CampoOrderById$i=2&OrderId$i=$local_OrderVet[$i]' target='_self'><B>Cliente</B>$local_ImagemSeta[2]</a></td>					
						<td><a href='conteudo.php?CampoOrderById$i=3&OrderId$i=$local_OrderVet[$i]' target='_self'><B>Descrição</B>$local_ImagemSeta[3]</a></td>";
				if($vi_bairro == 1){
					echo"<td><a href='conteudo.php?CampoOrderById$i=4&OrderId$i=$local_OrderVet[$i]' target='_self'><B>Bairro</B>$local_ImagemSeta[4]</a></td>";
				}
				if($vi_cidade == 1){
				   echo"<td><a href='conteudo.php?CampoOrderById$i=5&OrderId$i=$local_OrderVet[$i]' target='_self'><B>Cidade</B>$local_ImagemSeta[5]</a></td>";
				}
				if($vi_tipo == 1){		
				   echo"<td><a href='conteudo.php?CampoOrderById$i=6&OrderId$i=$local_OrderVet[$i]' target='_self'><B>Tipo</B>$local_ImagemSeta[6]</a></td>";
				}	
				if($vi_subtipo == 1){		
				   echo"<td><a href='conteudo.php?CampoOrderById$i=7&OrderId$i=$local_OrderVet[$i]' target='_self'><B>SubTipo</B>$local_ImagemSeta[7]</a></td>";
				}
					echo"<td><a href='conteudo.php?CampoOrderById$i=8&OrderId$i=$local_OrderVet[$i]' target='_self'><B>Atend.</B>$local_ImagemSeta[8]</a></td>";
				if($vi_responsavel == 1){	
					echo"<td style='width: 82px;'><a href='conteudo.php?CampoOrderById$i=10&OrderId$i=$local_OrderVet[$i]' target='_self'><B>Responsável</B>$local_ImagemSeta[10]</a></td>";
				}
					echo"<td width='122px' style='padding-right: 5px'><a href='conteudo.php?CampoOrderById$i=9&OrderId$i=$local_OrderVet[$i]' target='_self'><B>Data</B>$local_ImagemSeta[9]</a></td>";			
				if($vi_tempabertura == 1){	
					echo"<td style='width: 82px;'><a href='conteudo.php?CampoOrderById$i=11&OrderId$i=$local_OrderVet[$i]' target='_self'><B>Tempo Aber.</B>$local_ImagemSeta[11]</a></td>";
				}
				   echo"<td style='width: 72px;'><a href='conteudo.php?CampoOrderById$i=12&OrderId$i=$local_OrderVet[$i]' target='_self'><B>Status</B>$local_ImagemSeta[12]</a></td>							
					</tr>";
				while($lin	=	@mysql_fetch_array($res)){
					$HoraAgendamentoAtendimento = explode(" ",$lin[DataAgendamentoAtendimento]);
					if($HoraAgendamentoAtendimento[1] == "00:00:00"){
						$HoraAgendamentoAtendimento = "23:59:59";
						$lin[DataAgendamentoAtendimento] = substr($lin[DataAgendamentoAtendimento],0,10);
						$lin[DataAgendamentoAtendimento] = $lin[DataAgendamentoAtendimento]." ".$HoraAgendamentoAtendimento;
					}
					
					$tempo = MinutosRestantes($lin[DataAgendamentoAtendimento]);
					
					$DataAgendamentoAtendimento = dataConv($lin[DataAgendamentoAtendimento],'Y-m-d H:i:s', 'd/m/Y H:i:s');
					
					if($tempo > 0){
						$expirado = true;
						
						$dataAgendamento = "<a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>".$DataAgendamentoAtendimento."</a>";
						if($tempo > 1){
							$s = "s";
						}
						if($tempo < 60){
							$sol = "<img src='../../img/estrutura_sistema/estrela.gif' onmousemove=\"quadro_alt(event, this, '".$tempo." Minuto$s restante$s');\"/> ";
						}
					}else{
						$expirado = false;
						
						if($DataAgendamentoAtendimento == ""){
							$dataAgendamento = "";
							$quadroAlt = "";
						}else{
							$dataAgendamento = "Expirado";
							$quadroAlt = "onmousemove=\"quadro_alt(event, this, '".$DataAgendamentoAtendimento."');\"";
						}
					}
					$local_CorOrdemServico 	= "";
					$local_AltOrdermServico = "";
					$local_Marcador 		= "";

					if(($lin[DataAgendamentoAtendimento] != '' && dataConv($lin[DataAgendamentoAtendimento], 'Y-m-d', 'Ymd') <= date('Ymd') && getCodigoInterno(13,2) == 1) || trim($lin[DataAgendamentoAtendimento]) == '' || getCodigoInterno(13,2) == 2){
				
						$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=40 and IdParametroSistema=$lin[IdStatus]";
						$res3 = @mysql_query($sql3,$con);
						$lin3 = @mysql_fetch_array($res3);
						
						$sql4 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=120 and IdParametroSistema=$lin[IdMarcador]";
						$res4 = @mysql_query($sql4,$con);
						$lin4 = @mysql_fetch_array($res4);

						switch($lin[IdMarcador]){ //seleciona o marcador
							case 1:
								$local_Marcador = '<font style="padding: 2px; color: '.getParametroSistema(155,1).'">&#8226;</font>';							
								break;
							case 2:
								$local_Marcador = '<font style="padding: 2px; color: '.getParametroSistema(155,2).'">&#8226;</font>';
								break;
							case 3:
								$local_Marcador = '<font style="padding: 2px; color: '.getParametroSistema(155,3).'">&#8226;</font>';
								break;
						}

						if($lin[DetalheServico] != ""){
							$DetalheServico = htmlespecialchars($lin[DetalheServico]);
							$DetalheServico = nltobr($DetalheServico);
							$local_AltOrdermServico =  "Descrição Serviço:<br/> ".$DetalheServico;
						}else{
							if($lin[DescricaoServico] != ""){
								$local_AltOrdermServico =  "Descrição Serviço:<br/> ".$lin[DescricaoServico];
							}
						}	
						if($lin[DescricaoServico] != "" && $lin[DescricaoOSAlt] != ""){
							$local_AltOrdermServico .=  "<br/><br/>";
						}							
						if($lin[DescricaoOSAlt] != ""){
							$DescricaoOSAlt = htmlespecialchars($lin[DescricaoOSAlt]);
							$DescricaoOSAlt = nltobr($DescricaoOSAlt);
							$local_AltOrdermServico .=  "Descrição OS:<br/> ".$DescricaoOSAlt;
						}
						if($lin[EmAtendimento] == "1"){
							$imagem = "<img src='../../img/estrutura_sistema/atendimento_small.gif' onmousemove=\"quadro_alt(event, this, 'Em Atendimento');\"/>";
						}else{
							$imagem = "";
						}
						
						$local_CorOrdemServico = getOrdemServicoCor($lin[IdOrdemServico]);
						
						if($lin[DescricaoServico] != ""){
							if($lin[IdContrato] != ""){
								$lin[IdContrato] = "[CO ".$lin[IdContrato]."] ";
							} else{
								$lin[IdContrato] = "";
							}
							
							$lin[NomeAlt] .= " <br/>".$lin[IdContrato].$lin[DescricaoServico];
						}
						$lin[DescricaoOS]		= str_replace("<", "&lt;", $lin[DescricaoOS]);
						$lin[DescricaoOS]		= str_replace(">", "&gt;", $lin[DescricaoOS]);
						
						$local_AltOrdermServico	= str_replace("<", "&lt;", $local_AltOrdermServico);
						$local_AltOrdermServico	= str_replace(">", "&gt;", $local_AltOrdermServico);
						
						echo"<tr>";
						#if($_SESSION["filtro_".$OrdemDeServico] == 1){
							echo "<td style='background-color: $local_CorOrdemServico;'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$lin[IdOrdemServico]</a></td>
							<td style='background-color: $local_CorOrdemServico;'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$imagem</a></td>
							<td style='background-color: $local_CorOrdemServico;'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]' onmousemove=\"quadro_alt(event, this, '$lin4[ValorParametroSistema]');\">$local_Marcador</a></td>
							<td style='background-color: $local_CorOrdemServico;'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]' onmousemove=\"quadro_alt(event, this, '$lin[NomeAlt]');\">$lin[Nome]</a></td>
							<td style='background-color: $local_CorOrdemServico;'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]' onmousemove=\"quadro_alt(event, this, '$local_AltOrdermServico');\">$lin[DescricaoOS]</a></td>";
						if($vi_bairro == 1){
							echo"<td style='background-color: $local_CorOrdemServico;'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$lin[Bairro]</a></td>";
						}		
						if($vi_cidade == 1){
							echo"<td style='background-color: $local_CorOrdemServico;'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$lin[NomeCidade]</a></td>";
						}
						if($vi_tipo == 1){		
							echo"<td style='background-color: $local_CorOrdemServico;'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$lin[DescricaoTipoOrdemServico]</a></td>";
						}	
						if($vi_subtipo == 1){	
							echo"<td style='background-color: $local_CorOrdemServico;'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$lin[DescricaoSubTipoOrdemServico]</a></td>";
						}
						echo"<td style='background-color: $local_CorOrdemServico;'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$lin[LoginAtendimento]</a></td>";
						if($vi_responsavel == 1){	
							echo"<td style='background-color: $local_CorOrdemServico;'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$lin[LoginSupervisor]</a></td>";
						}
						
						if($expirado){
							echo"<td style='background-color: $local_CorOrdemServico;' width='145'>$sol$dataAgendamento</td>";
						}else{
							echo"<td style='background-color: $local_CorOrdemServico; cursor:default;' $quadroAlt>$dataAgendamento</td>";
						}
						/*if(substr($lin[DataAgendamentoAtendimento],11,19) != "00:00:00"){														
							echo"<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>".dataConv($lin[DataAgendamentoAtendimento],'Y-m-d H:i:s', 'd/m/Y H:i:s')."</a></td>";
						}else{						
							echo"<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>".dataConv($lin[DataAgendamentoAtendimento],'Y-m-d', 'd/m/Y')."</a></td>";
						}*/
						if($vi_tempabertura == 1){	
							if(($lin[IdStatus] >= 0 && $lin[IdStatus] <= 99) || ($lin[IdStatus] >= 200 && $lin[IdStatus] <= 299)){
								$local_TempoAbertura = "";			
							}else{
								$local_TempoAbertura = diferencaData($lin[DataCriacao], date("Y-m-d H:i:s"));
							}
						
							echo"<td style='background-color: $local_CorOrdemServico;'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$local_TempoAbertura</a></td>";							
						}
						echo"<td style='background-color: $local_CorOrdemServico;'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$lin3[ValorParametroSistema]</a></td>";
						#}
						echo "</tr>";					
					}	
				}
				echo"
				</table>	
			</div></div></div>";
			#}
				if(empty($_SESSION["filtro_".$OrdemDeServico])){
					$_SESSION["filtro_".$OrdemDeServico] = 1;
				} else if($_SESSION["filtro_".$OrdemDeServico] == 2){
					echo "<script type=\"text/javascript\"> 
								window.onload = ocultarQuadro(document.getElementById('botao_OS_grupo_".$lin2[DescricaoGrupoUsuario]."'), 'tabela".$OrdemDeServico."') 
						</script>";
				}
			}
			
			$i++;
		}
		
		/// Individual
		if($local_CampoOrderBy == ''){
			$local_CampoOrderBy = 9;
		}									     
		if($local_Order == '' || $local_Order == 1){										
			$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_descending.gif'>";																		
			$local_OrderBy  = 'DESC';
			$local_Order = 2;																
		}else{
			$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_ascending.gif'>"; 																	
			$local_OrderBy = 'ASC';
			$local_Order = 1;																
		}
		
		$local_ImagemSeta = array();
		$local_ImagemSeta[$local_CampoOrderBy] = $local_ImagemSetaDefault; 
		
		switch($local_CampoOrderBy){
			case 1:
				$local_CampoOrdemBy = 'OrdemServico.IdOrdemServico';
				break;
			case 2:
				$local_CampoOrdemBy = 'Pessoa.Nome';
				break;
			case 3:
				$local_CampoOrdemBy = 'OrdemServico.DescricaoOS';
				break;
			case 4:
				$local_CampoOrdemBy = 'PessoaEndereco.Bairro';
				break;
			case 5:
				$local_CampoOrdemBy = 'Cidade.NomeCidade';
				break;
			case 6:
				$local_CampoOrdemBy = 'TipoOrdemServico.DescricaoTipoOrdemServico';
				break;
			case 7:
				$local_CampoOrdemBy = 'SubTipoOrdemServico.DescricaoSubTipoOrdemServico';
				break;					
			case 8:
				$local_CampoOrdemBy = 'OrdemServico.LoginAtendimento';
				break;
			case 9:
				$local_CampoOrdemBy = 'OrdemServico.DataAgendamentoAtendimento';
				break;
			case 10:
				$local_CampoOrdemBy = 'OrdemServico.DataCriacao';
				break;
			case 11:
				$local_CampoOrdemBy = 'OrdemServico.IdStatus';
				break;														
		}	
		
		$where = '';
		
		if($LoginAtendimento != ''){
			$where	.=  " and OrdemServico.LoginAtendimento = '$LoginAtendimento'";
			$where	.=  " and ((OrdemServico.IdStatus >= 300 and OrdemServico.IdStatus <= 399) or (OrdemServico.IdStatus >= 100 and OrdemServico.IdStatus <= 199))";
		}
		
		$sql	=	"select
						OrdemServico.IdOrdemServico,
						OrdemServico.IdTipoOrdemServico,
						OrdemServico.IdContrato,
						OrdemServico.EmAtendimento,
						TipoOrdemServico.DescricaoTipoOrdemServico,
						TipoOrdemServico.Cor CorTipo,
						OrdemServico.IdSubTipoOrdemServico,
						SubTipoOrdemServico.DescricaoSubTipoOrdemServico,
						SubTipoOrdemServico.Cor CorSubTipo,
						OrdemServico.IdPessoa,
						Pessoa.TipoPessoa,
						OrdemServico.DescricaoOS DescricaoOSAlt,
						substr(OrdemServico.DescricaoOS, 1, 20) DescricaoOS,
						Pessoa.Nome NomeAlt,
						substr(Pessoa.Nome, 1, 20) Nome,
						Pessoa.RazaoSocial,
						OrdemServico.IdStatus,
						OrdemServico.DataAgendamentoAtendimento,
						OrdemServico.LoginAtendimento,
						OrdemServico.LoginSupervisor,
						OrdemServico.DataCriacao,
						OrdemServico.LoginAlteracao,
						OrdemServico.LoginCriacao,
						Cidade.NomeCidade,
						Estado.SiglaEstado,
						OrdemServico.IdMarcador,
						Servico.DescricaoServico,
						Servico.DetalheServico,
						PessoaEndereco.Bairro
					from
						OrdemServico left join Pessoa on 
						(
							OrdemServico.IdPessoa = Pessoa.IdPessoa
						) left join PessoaEndereco on 
						(
							Pessoa.IdPessoa = PessoaEndereco.IdPessoa and 
							PessoaEndereco.IdPessoaEndereco = OrdemServico.IdPessoaEndereco
						) left join Pais on 
						(
							Pais.IdPais = PessoaEndereco.IdPais
						) left join Estado on 
						(
							Pais.IdPais = Estado.IdPais and 
							Estado.IdEstado = PessoaEndereco.IdEstado
						) left join Cidade on 
						(
							Pais.IdPais = Cidade.IdPais and 
							Estado.IdEstado = Cidade.IdEstado and 
							Cidade.IdCidade = PessoaEndereco.IdCidade
						) left join Servico on 
						(
							OrdemServico.IdLoja = Servico.IdLoja and 
							OrdemServico.IdServico = Servico.IdServico
						),
						TipoOrdemServico,
						SubTipoOrdemServico
					where
						OrdemServico.IdLoja = $local_IdLoja and
						OrdemServico.IdLoja = TipoOrdemServico.IdLoja and
						TipoOrdemServico.IdLoja = SubTipoOrdemServico.IdLoja and
						OrdemServico.IdTipoOrdemServico = TipoOrdemServico.IdTipoOrdemServico and
						OrdemServico.IdSubTipoOrdemServico = SubTipoOrdemServico.IdSubTipoOrdemServico and
						TipoOrdemServico.IdTipoOrdemServico = SubTipoOrdemServico.IdTipoOrdemServico 							
						$where 
					order by
						$local_CampoOrdemBy $local_OrderBy,
						OrdemServico.IdOrdemServico $local_OrderBy
		";	
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			echo"<div style='padding-top:5px;'><div class='quadroAviso' style='width:100%-10px; margin:0 5px 0 5px;' id='Fatura'>
			<p class='tit'><a href='listar_ordem_servico.php' style='color:#FFF'>ORDEM DE SERVIÇO - INDIVIDUAL</a> <a href='cadastro_ordem_servico.php' style='color:#FFF'>[+]</a></p>
			<table cellpadding='0' cellspacing='0' style='width:100%; margin-bottom: 0' id='Fatura'>
				<tr>
					<td width='40px'><a href='conteudo.php?CampoOrderBy=1&Order=$local_Order' target='_self'><B>Id</B>$local_ImagemSeta[1]</a></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td><a href='conteudo.php?CampoOrderBy=2&Order=$local_Order' target='_self'><B>Cliente</B>$local_ImagemSeta[2]</a></td>					
					<td><a href='conteudo.php?CampoOrderBy=3&Order=$local_Order' target='_self'><B>Descrição</B>$local_ImagemSeta[3]</a></td>";
			if($vi_bairro == 1){
			   echo"<td><a href='conteudo.php?CampoOrderBy=4&Order=$local_Order' target='_self'><B>Bairro</B>$local_ImagemSeta[4]</a></td>";
			}	
			if($vi_cidade == 1){
			   echo"<td><a href='conteudo.php?CampoOrderBy=5&Order=$local_Order' target='_self'><B>Cidade</B>$local_ImagemSeta[5]</a></td>";
			}
			if($vi_tipo == 1){		
			   echo"<td><a href='conteudo.php?CampoOrderBy=6&Order=$local_Order' target='_self'><B>Tipo</B>$local_ImagemSeta[6]</a></td>";
			}	
			if($vi_subtipo == 1){		
			   echo"<td><a href='conteudo.php?CampoOrderBy=7&Order=$local_Order' target='_self'><B>SubTipo</B>$local_ImagemSeta[7]</a></td>";
			}  
				echo"<td><a href='conteudo.php?CampoOrderBy=8&Order=$local_Order' target='_self'><B>Atend.</B>$local_ImagemSeta[8]</a></td>";
			if($vi_responsavel == 1){	
			   echo"<td style='width: 82px;'><a href='conteudo.php?CampoOrderBy=10&Order=$local_Order' target='_self'><B>Responsável</B>$local_ImagemSeta[10]</a></td>";
			}
				echo"<td width='122px' style='padding-right: 5px'><a href='conteudo.php?CampoOrderBy=9&Order=$local_Order' target='_self'><B>Data</B>$local_ImagemSeta[9]</a></td>";
			
			if($vi_tempabertura == 1){	
			   echo"<td style='width: 82px;'><a href='conteudo.php?CampoOrderBy=11&Order=$local_Order' target='_self'><B>Tempo Aber.</B>$local_ImagemSeta[11]</a></td>";
			}
			   echo"<td style='width: 72px;'><a href='conteudo.php?CampoOrderBy=12&Order=$local_Order' target='_self'><B>Status</B>$local_ImagemSeta[12]</a></td>							
				</tr>";
				
			while($lin	=	@mysql_fetch_array($res)){
				$HoraAgendamentoAtendimento = explode(" ",$lin[DataAgendamentoAtendimento]);
				if($HoraAgendamentoAtendimento[1] == "00:00:00"){
					$HoraAgendamentoAtendimento = "23:59:59";
					$lin[DataAgendamentoAtendimento] = substr($lin[DataAgendamentoAtendimento],0,10);
					$lin[DataAgendamentoAtendimento] = $lin[DataAgendamentoAtendimento]." ".$HoraAgendamentoAtendimento;
				}
				
				$tempo = MinutosRestantes($lin[DataAgendamentoAtendimento]);
				
				$DataAgendamentoAtendimento = dataConv($lin[DataAgendamentoAtendimento],'Y-m-d H:i:s', 'd/m/Y H:i:s');
				
				if($tempo > 0){
					$expirado = true;
					
					$dataAgendamento = "<a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>".$DataAgendamentoAtendimento."</a>";
					if($tempo > 1){
						$s = "s";
					}
					if($tempo < 60){
						$sol = "<img src='../../img/estrutura_sistema/estrela.gif' onmousemove=\"quadro_alt(event, this, '".$tempo." Minuto$s restante$s');\"/> ";
					}
				}else{
					$expirado = false;
					
					if($DataAgendamentoAtendimento == ""){
						$dataAgendamento = "";
						$quadroAlt = "";
					}else{
						$dataAgendamento = "Expirado";
						$quadroAlt = "onmousemove=\"quadro_alt(event, this, '".$DataAgendamentoAtendimento."');\"";
					}
				}
				
				$local_CorOrdemServico 	= "";
				$local_AltOrdermServico = "";
				$local_Marcador 		= "";
					
				$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=40 and IdParametroSistema=$lin[IdStatus]";
				$res3 = @mysql_query($sql3,$con);
				$lin3 = @mysql_fetch_array($res3);
				
				$sql4 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=120 and IdParametroSistema=$lin[IdMarcador]";
				$res4 = @mysql_query($sql4,$con);
				$lin4 = @mysql_fetch_array($res4);
				
				
				switch($lin[IdMarcador]){
					case 1:
						$local_Marcador = '<font style="padding: 2px; color: '.getParametroSistema(155,1).'">&#8226;</font>';							
						break;
					case 2:
						$local_Marcador = '<font style="padding: 2px; color: '.getParametroSistema(155,2).'">&#8226;</font>';
						break;
					case 3:
						$local_Marcador = '<font style="padding: 2px; color: '.getParametroSistema(155,3).'">&#8226;</font>';
						break;
				}
				
				if($lin[DetalheServico] != ""){
					$DetalheServico = htmlespecialchars($lin[DetalheServico]);
					$DetalheServico = nltobr($DetalheServico);
					$local_AltOrdermServico =  "Descrição Serviço:<br/> ".$DetalheServico;
				}else{
					if($lin[DescricaoServico] != ""){
						$local_AltOrdermServico =  "Descrição Serviço:<br/> ".$lin[DescricaoServico];
					}
				}	
				if($lin[DescricaoServico] != "" && $lin[DescricaoOSAlt] != ""){
					$local_AltOrdermServico .=  "<br/><br/>";
				}							
				if($lin[DescricaoOSAlt] != ""){
					$DescricaoOSAlt = htmlespecialchars($lin[DescricaoOSAlt]);
					$DescricaoOSAlt = nltobr($DescricaoOSAlt);
					$local_AltOrdermServico .=  "Descrição OS:<br/> ".$DescricaoOSAlt;
				}
				if($lin[EmAtendimento] == "1"){
					$imagem = "<img src='../../img/estrutura_sistema/atendimento_small.gif' onmousemove=\"quadro_alt(event, this, 'Em Atendimento');\"/>";
				}else{
					$imagem = "";
				}
				$local_CorOrdemServico = getOrdemServicoCor($lin[IdOrdemServico]);
				
				if($lin[DescricaoServico] != ""){
					if($lin[IdContrato] != ""){
						$lin[IdContrato] = "[CO ".$lin[IdContrato]."] ";
					} else{
						$lin[IdContrato] = "";
					}
					
					$lin[NomeAlt] .= " <br/>".$lin[IdContrato].$lin[DescricaoServico];
				}
				$lin[DescricaoOS]		= str_replace("<", "&lt;", $lin[DescricaoOS]);
				$lin[DescricaoOS]		= str_replace(">", "&gt;", $lin[DescricaoOS]);
				
				$local_AltOrdermServico	= str_replace("<", "&lt;", $local_AltOrdermServico);
				$local_AltOrdermServico	= str_replace(">", "&gt;", $local_AltOrdermServico);
				
				echo"<tr>
					<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$lin[IdOrdemServico]</a></td>
					<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$imagem</a></td>
					<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]' onmousemove=\"quadro_alt(event, this, '$lin4[ValorParametroSistema]');\">$local_Marcador</a></td>
					<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]' onmousemove=\"quadro_alt(event, this, '$lin[NomeAlt]');\">$lin[Nome]</a></td>
					<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]' onmousemove=\"quadro_alt(event, this, '$local_AltOrdermServico');\">$lin[DescricaoOS]</a></td>";								
				if($vi_bairro == 1){
				echo"<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$lin[Bairro]</a></td>";
				}
				if($vi_cidade == 1){
				echo"<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$lin[NomeCidade]</a></td>";
				}
				if($vi_tipo == 1){		
				echo"<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$lin[DescricaoTipoOrdemServico]</a></td>";
				}							
				if($vi_subtipo == 1){
				echo"<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$lin[DescricaoSubTipoOrdemServico]</a></td>";						
				}
				echo"<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$lin[LoginAtendimento]</a></td>";
				if($vi_responsavel == 1){
					echo"<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$lin[LoginSupervisor]</a></td>";						
				}
				
				if($expirado){
					echo"<td style='background-color: $local_CorOrdemServico' width='145'>$sol$dataAgendamento</td>";
				}else{
					echo"<td style='background-color: $local_CorOrdemServico; cursor:default;' $quadroAlt>$dataAgendamento</td>";
				}
				/*if(substr($lin[DataAgendamentoAtendimento],11,19) != "00:00:00"){														
					echo"<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>".dataConv($lin[DataAgendamentoAtendimento],'Y-m-d H:i:s', 'd/m/Y H:i:s')."</a></td>";
				}else{
					echo"<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>".dataConv($lin[DataAgendamentoAtendimento],'Y-m-d', 'd/m/Y')."</a></td>";
				}*/
				
				$alerta = '';
				
				if(($lin[LoginAtendimento] != $lin[LoginAlteracao] && $lin[LoginAlteracao] != '') || ($lin[LoginAlteracao] == '' && $lin[LoginAtendimento] != $lin[LoginCriacao])){
					$alerta = "<img src='../../img/estrutura_sistema/estrela.gif' />";
				}
				
				if($vi_tempabertura == 1){	
					if(($lin[IdStatus] >= 0 && $lin[IdStatus] <= 99) || ($lin[IdStatus] >= 200 && $lin[IdStatus] <= 299)){
						$local_TempoAbertura = "";			
					}else{
						$local_TempoAbertura = diferencaData($lin[DataCriacao], date("Y-m-d H:i:s"));
					}					
					echo"<td style='background-color: $local_CorOrdemServico'>$alerta<a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$local_TempoAbertura</a></td>";							
				}
				
				echo"<td style='background-color: $local_CorOrdemServico'>$alerta<a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$lin3[ValorParametroSistema]</a></td>
				</tr>";						
			}	
			echo"
			</table>	
		</div></div>";		
		}
	}				
	
	/// Em faturamento
	if($local_CampoOrderBy4 == ''){
		$local_CampoOrderBy4 = 7;
	}									
	if($local_Order4 == '' || $local_Order4 == 1){										
		$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_descending.gif'>";																		
		$local_OrderBy  = 'DESC';
		$local_Order4 = 2;																
	}else{
		$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_ascending.gif'>"; 																	
		$local_OrderBy = 'ASC';
		$local_Order4 = 1;																
	}
	$local_ImagemSeta = array();
	$local_ImagemSeta[$local_CampoOrderBy4] = $local_ImagemSetaDefault; 
	switch($local_CampoOrderBy4){
		case 1:
			$local_CampoOrdemBy4 = 'OrdemServico.IdOrdemServico';
			break;
		case 2:
			$local_CampoOrdemBy4 = 'Pessoa.Nome';
			break;
		case 3:
			$local_CampoOrdemBy4 = 'Servico.DetalheServico';
			break;
		case 4:
			$local_CampoOrdemBy4 = 'ValorTotal';
			break;
		case 5:
			$local_CampoOrdemBy4 = 'OrdemServico.DescricaoOS';
			break;
		case 6:
			$local_CampoOrdemBy4 = 'OrdemServico.LoginAtendimento';
			break;
		case 7:
			$local_CampoOrdemBy4 = 'OrdemServico.DataAgendamentoAtendimento';
			break;
		case 8:
			$local_CampoOrdemBy4 = 'OrdemServico.DataCriacao';
			break;														
	}	
	
	//Permissao para visualizar Quadro Ordem Servico Em Faturamento
	$sql	=	"select 
				GrupoUsuarioQuadroAviso.IdQuadroAviso
		from 
				GrupoUsuarioQuadroAviso
		where   
				GrupoUsuarioQuadroAviso.IdLoja = $local_IdLoja and
				GrupoUsuarioQuadroAviso.IdQuadroAviso = 7 and
				IdGrupoUsuario in (select UsuarioGrupoUsuario.IdGrupoUsuario from UsuarioGrupoUsuario, GrupoUsuario, Usuario, Pessoa where UsuarioGrupoUsuario.IdLoja = $local_IdLoja and UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and UsuarioGrupoUsuario.Login = Usuario.Login and UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and Usuario.IdPessoa = Pessoa.IdPessoa and Pessoa.TipoUsuario = 1 and UsuarioGrupoUsuario.Login = '$local_Login' group by	UsuarioGrupoUsuario.IdGrupoUsuario);";
	$res	=	mysql_query($sql,$con);
	if(mysql_num_rows($res)>=1){
		$where = '';						
		$where	.=  " and (OrdemServico.IdStatus >= 400 and OrdemServico.IdStatus <= 499)";
		
		$sql	=	"select
						OrdemServico.IdOrdemServico,
						OrdemServico.IdTipoOrdemServico,
						OrdemServico.IdContrato,
						TipoOrdemServico.DescricaoTipoOrdemServico,
						TipoOrdemServico.Cor CorTipo,
						OrdemServico.IdSubTipoOrdemServico,
						SubTipoOrdemServico.DescricaoSubTipoOrdemServico,
						SubTipoOrdemServico.Cor CorSubTipo,
						OrdemServico.IdPessoa,
						Pessoa.TipoPessoa,
						OrdemServico.DescricaoOS DescricaoOSAlt,
						substr(OrdemServico.DescricaoOS, 1, 20) DescricaoOS,
						Pessoa.Nome NomeAlt,
						substr(Pessoa.Nome, 1, 20) Nome,
						Pessoa.RazaoSocial,
						OrdemServico.IdStatus,
						OrdemServico.DataAgendamentoAtendimento,
						OrdemServico.LoginAtendimento,
						OrdemServico.DataCriacao,
						substr(NomeCidade,1,20) NomeCidade,
						Estado.SiglaEstado,
						OrdemServico.IdMarcador,
						substr(Servico.DescricaoServico, 1, 15) DescricaoServico,
						Servico.DescricaoServico DescricaoServicoAlt,
						Servico.DetalheServico,
						sum(Valor+ValorOutros) ValorTotal,
						PessoaEndereco.Bairro
					from
						OrdemServico left join Pessoa on (OrdemServico.IdPessoa = Pessoa.IdPessoa) left join PessoaEndereco on (Pessoa.IdPessoa = PessoaEndereco.IdPessoa and PessoaEndereco.IdPessoaEndereco = OrdemServico.IdPessoaEndereco) left join Pais on (Pais.IdPais = PessoaEndereco.IdPais) left join Estado on (Pais.IdPais = Estado.IdPais and Estado.IdEstado = PessoaEndereco.IdEstado) left join Cidade on (Pais.IdPais = Cidade.IdPais and Estado.IdEstado = Cidade.IdEstado and Cidade.IdCidade = PessoaEndereco.IdCidade) left join Servico on (OrdemServico.IdLoja = Servico.IdLoja and OrdemServico.IdServico = Servico.IdServico),
						TipoOrdemServico,
						SubTipoOrdemServico
					where
						OrdemServico.IdLoja = $local_IdLoja and
						OrdemServico.IdLoja = TipoOrdemServico.IdLoja and
						TipoOrdemServico.IdLoja = SubTipoOrdemServico.IdLoja and
						OrdemServico.IdTipoOrdemServico = TipoOrdemServico.IdTipoOrdemServico and
						OrdemServico.IdSubTipoOrdemServico = SubTipoOrdemServico.IdSubTipoOrdemServico and
						TipoOrdemServico.IdTipoOrdemServico = SubTipoOrdemServico.IdTipoOrdemServico 							
						$where
					group by
						IdOrdemServico
					order by
						$local_CampoOrdemBy4 $local_OrderBy,
						OrdemServico.IdOrdemServico $local_OrderBy";	
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			$NomeBLOrdemServico = "Fatura";
			echo "<div style='margin-top:5px;'><div class='quadroAviso' style='width:100%-10px; margin:0 5px 0 5px;' id='Fatura'>
			<p class='tit'><a href='listar_ordem_servico.php' style='color:#FFF'>ORDEM DE SERVIÇO - ".strToUpper(getParametroSistema(40,400))."</a> <a href='cadastro_ordem_servico.php' style='color:#FFF'>[+]</a></p>
			<div style='text-align:right; margin-top:-18px; padding-bottom:2px; cursor:move;'><img id='botao_ordem_servico' style='cursor:pointer; margin-right:4px;' onClick=\"ocultarQuadro(this, 'tabela".$NomeBLOrdemServico."');\" title='Minimizar' alt='Minimizar' src='../../img/estrutura_sistema/ico_seta_up.gif' /></div>
			<div id='tabela".$NomeBLOrdemServico."'>
				<table cellpadding='0' cellspacing='0' style='width:100%; margin-bottom: 0'>
					<tr>
						<td width='40px'><a href='conteudo.php?CampoOrderBy4=1&Order4=$local_Order4' target='_self'><B>Id</B>$local_ImagemSeta[1]</a></td>
						<td>&nbsp;</td>
						<td><a href='conteudo.php?CampoOrderBy4=2&Order4=$local_Order4' target='_self'><B>Cliente</B>$local_ImagemSeta[2]</a></td>
						<td><a href='conteudo.php?CampoOrderBy4=3&Order4=$local_Order4' target='_self'><B>Serviço</B>$local_ImagemSeta[3]</a></td>
						<td style='text-align:right; padding-right:6px'><a href='conteudo.php?CampoOrderBy4=4&Order4=$local_Order4' target='_self'><B>Valor (".getParametroSistema(5,1).")</B>$local_ImagemSeta[4]</a></td>
						<td><a href='conteudo.php?CampoOrderBy4=5&Order4=$local_Order4' target='_self'><B>Descrição</B>$local_ImagemSeta[5]</a></td>
						<td><a href='conteudo.php?CampoOrderBy4=6&Order4=$local_Order4' target='_self'><B>Atend.</B>$local_ImagemSeta[6]</a></td>
						<td style='padding-right: 5px'><a href='conteudo.php?CampoOrderBy4=7&Order4=$local_Order4' target='_self'><B>Data</B>$local_ImagemSeta[7]</a></td>";
			if($vi_tempabertura == 1){	
			   echo"	<td style='width: 82px;'><a href='conteudo.php?CampoOrderBy4=8&Order4=$local_Order4' target='_self'><B>Tempo Aber.</B>$local_ImagemSeta[8]</a></td>";
			}	
			   echo"</tr>";
			while($lin	=	@mysql_fetch_array($res)){
				$HoraAgendamentoAtendimento = explode(" ",$lin[DataAgendamentoAtendimento]);
				if($HoraAgendamentoAtendimento[1] == "00:00:00"){
					$HoraAgendamentoAtendimento = "23:59:59";
					$lin[DataAgendamentoAtendimento] = substr($lin[DataAgendamentoAtendimento],0,10);
					$lin[DataAgendamentoAtendimento] = $lin[DataAgendamentoAtendimento]." ".$HoraAgendamentoAtendimento;
				}
				
				$tempo = MinutosRestantes($lin[DataAgendamentoAtendimento]);
				
				$DataAgendamentoAtendimento = dataConv($lin[DataAgendamentoAtendimento],'Y-m-d H:i:s', 'd/m/Y H:i:s');
				
				if($tempo > 0){
					$expirado = true;
					
					$dataAgendamento = "<a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>".$DataAgendamentoAtendimento."</a>";
					if($tempo > 1){
						$s = "s";
					}
					if($tempo < 60){
						$sol = "<img src='../../img/estrutura_sistema/estrela.gif' onmousemove=\"quadro_alt(event, this, '".$tempo." Minuto$s restante$s');\"/> ";
					}
				}else{
					$expirado = false;
					
					if($DataAgendamentoAtendimento == ""){
						$dataAgendamento = "";
						$quadroAlt = "";
					}else{
						$dataAgendamento = "Expirado";
						$quadroAlt = "onmousemove=\"quadro_alt(event, this, '".$DataAgendamentoAtendimento."');\"";
					}
				}
				
				$local_CorOrdemServico 	= "";
				$local_AltOrdermServico = "";
				$local_Marcador 		= "";
				
				$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=40 and IdParametroSistema=$lin[IdStatus]";
				$res3 = @mysql_query($sql3,$con);
				$lin3 = @mysql_fetch_array($res3);
				
				$sql4 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=120 and IdParametroSistema=$lin[IdMarcador]";
				$res4 = @mysql_query($sql4,$con);
				$lin4 = @mysql_fetch_array($res4);
				
				
				switch($lin[IdMarcador]){
					case 1:
						$local_Marcador = '<font style="padding: 2px; width: 14px; color: '.getParametroSistema(155,1).'">&#8226;</font>';							
						break;
					case 2:
						$local_Marcador = '<font style="padding: 2px; width: 14px; color: '.getParametroSistema(155,2).'">&#8226;</font>';
						break;
					case 3:
						$local_Marcador = '<font style="padding: 2px; width: 14px; color: '.getParametroSistema(155,3).'">&#8226;</font>';
						break;
				}
				
				if($lin[DetalheServico] != ""){
					$DetalheServico = htmlespecialchars($lin[DetalheServico]);
					$DetalheServico = nltobr($DetalheServico);
					$local_AltOrdermServico =  "Descrição Serviço:<br/> ".$DetalheServico;
				}else{
					if($lin[DescricaoServico] != ""){
						$local_AltOrdermServico =  "Descrição Serviço:<br/> ".$lin[DescricaoServico];
					}
				}	
				if($lin[DescricaoServico] != "" && $lin[DescricaoOSAlt] != ""){
					$local_AltOrdermServico .=  "<br/><br/>";
				}							
				if($lin[DescricaoOSAlt] != ""){
					$DescricaoOSAlt = htmlespecialchars($lin[DescricaoOSAlt]);
					$DescricaoOSAlt = nltobr($DescricaoOSAlt);
					$local_AltOrdermServico .=  "Descrição OS:<br/> ".$DescricaoOSAlt;
				}
				
				$local_CorOrdemServico = getOrdemServicoCor($lin[IdOrdemServico]);
				
				if($lin[DescricaoServico] != ""){
					if($lin[IdContrato] != ""){
						$lin[IdContrato] = "[CO ".$lin[IdContrato]."] ";
					} else{
						$lin[IdContrato] = "";
					}
					
					$lin[NomeAlt] .= " <br/>".$lin[IdContrato].$lin[DescricaoServico];
				}
				$lin[DescricaoOS]		= str_replace("<", "&lt;", $lin[DescricaoOS]);
				$lin[DescricaoOS]		= str_replace(">", "&gt;", $lin[DescricaoOS]);
				
				$local_AltOrdermServico	= str_replace("<", "&lt;", $local_AltOrdermServico);
				$local_AltOrdermServico	= str_replace(">", "&gt;", $local_AltOrdermServico);
				
				echo"<tr>
					<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$lin[IdOrdemServico]</a></td>
					<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]' onmousemove=\"quadro_alt(event, this, '$lin4[ValorParametroSistema]');\">$local_Marcador</a></td>
					<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]' onmousemove=\"quadro_alt(event, this, '$lin[NomeAlt]');\">$lin[Nome]</a></td>
					<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]' title='$lin[DescricaoServicoAlt]'>$lin[DescricaoServico]</a></td>
					<td style='background-color: $local_CorOrdemServico; text-align:right; padding-right:6px'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>".str_replace(".",",",$lin[ValorTotal])."</a></td>
					<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]' onmousemove=\"quadro_alt(event, this, '$local_AltOrdermServico');\">$lin[DescricaoOS]</a></td>
					<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$lin[LoginAtendimento]</a></td>";
				if($expirado){
					echo"<td style='background-color: $local_CorOrdemServico' width='145'>$sol$dataAgendamento</td>";
				}else{
					echo"<td style='background-color: $local_CorOrdemServico; cursor:default;' $quadroAlt>$dataAgendamento</td>";
				}
				if($vi_tempabertura == 1){	
					if(($lin[IdStatus] >= 0 && $lin[IdStatus] <= 99) || ($lin[IdStatus] >= 200 && $lin[IdStatus] <= 299)){
						$local_TempoAbertura = "";			
					}else{
						$local_TempoAbertura = diferencaData($lin[DataCriacao], date("Y-m-d H:i:s"));
					}					
					echo"<td style='background-color: $local_CorOrdemServico'><a href='cadastro_ordem_servico.php?IdOrdemServico=$lin[IdOrdemServico]'>$local_TempoAbertura</a></td>";							
				}
				echo"</tr>";				
			
			}	
			echo"
			</table>	
		</div></div></div>";		

			if(empty($_SESSION["filtro_".$NomeBLOrdemServico])){
				$_SESSION["filtro_".$NomeBLOrdemServico] = 1;
			} else if($_SESSION["filtro_".$NomeBLOrdemServico] == 2){
				echo "<script type=\"text/javascript\"> ocultarQuadro(document.getElementById('botao_ordem_servico'), 'tabela".$NomeBLOrdemServico."'); </script>";
			}
		}
	}
?>