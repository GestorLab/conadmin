			<div id='quadroBuscaServico' style='width:583px;' class='quadroFlutuante'>	
				<!--div class='tit'>Busca Serviço<div class='fecha' onClick="vi_id('quadroBuscaServico', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Serviço</td>
						<td class='fecha' onClick="vi_id('quadroBuscaServico', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioServico' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Serviço</td>
								<td class='descCampo'>Grupo Serviço</td>						
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='DescricaoServico' autocomplete="off" value='' style='width:349px' maxlength='100' onkeyup="busca_servico_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
								</td>
								<td class='campo'>
									<select name='IdServicoGrupo' style='width:200px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_servico_lista()">
										<option value=''>Todos</option>
										<?
											$sql = "select 
														IdServicoGrupo, 
														DescricaoServicoGrupo 
													from 
														ServicoGrupo 
													where 
														IdLoja = $local_IdLoja 
													order by 
														DescricaoServicoGrupo";
											$res = @mysql_query($sql,$con);
											while($lin = @mysql_fetch_array($res)){
												echo"<option value='$lin[IdServicoGrupo]'>$lin[DescricaoServicoGrupo]</option>";
											}
										?>
									</select>
								</td>								
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 210px;'>
						<div id='listaDadosQuadroServico'>&nbsp;</div>
					</div>
					<form name='BuscaServico' method='post' onSubmit='return validar_busca_servico()'>
						<input type='hidden' name='IdServico' value=''>
						<input type='hidden' name='Local' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaServico', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>	
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaServico');
						
						var valorCampoServico = '';
						function validar_busca_servico(){
							if(document.BuscaServico.Local.value != ""){
								var Local	= document.BuscaServico.Local.value;	
							}else{
								var Local	= document.formulario.Local.value;
							}
							if(valorCampoServico !=''){
								busca_servico(valorCampoServico,true,Local,'busca');
							}
							return false;
						}
						function busca_servico_lista(){
							var DescricaoServico	 	= document.formularioServico.DescricaoServico.value;
							var IdTipoServico	 		= 1;
							var IdServicoGrupo	 		= document.formularioServico.IdServicoGrupo.value;
							
							if(document.BuscaServico.Local.value != ""){
								var Local	= document.BuscaServico.Local.value;	
							}else{
								var Local	= document.formulario.Local.value;
							}
							var Limit	  				= <?=getCodigoInterno(7,4)?>;
							var nameNode, nameTextNode, url;
							
							var xmlhttp   = false;
							if (window.XMLHttpRequest) { // Mozilla, Safari,...
						    	xmlhttp = new XMLHttpRequest();
						        if(xmlhttp.overrideMimeType){
						        	xmlhttp.overrideMimeType('text/xml');
								}
							}else if (window.ActiveXObject){ // IE
								try{
									xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
								}catch(e){
									try{
										xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
						            } catch (e) {}
						        }
						    }
						    
						    if(DescricaoServico == '' && IdServicoGrupo == ''){
								if(Local == 'Contrato'){
							    	url = "xml/servico_contrato.php?Limit="+Limit;
								}else{
							    	url = "xml/servico.php?Limit="+Limit;
								}
							}else{
								if(Local == 'Contrato'){
									url = "xml/servico_contrato.php?DescricaoServico="+DescricaoServico+"&IdTipoServico="+IdTipoServico+"&IdServicoGrupo="+IdServicoGrupo;
								}else{
									url = "xml/servico.php?DescricaoServico="+DescricaoServico+"&Local="+Local+"&IdTipoServico="+IdTipoServico+"&IdServicoGrupo="+IdServicoGrupo;
								}
							}
							
							if(Local == 'ContratoServico'){
								url	+=	"&IdServicoAlterar="+document.formulario.IdServicoAnterior.value+"&IdStatus=1";
							}
							if((Local == 'Contrato' && document.formulario.Acao.value == 'inserir') || Local == 'ContratoServico'){
								url	+=	"&IdPessoaF="+document.formulario.IdPessoaF.value+"&IdPessoa="+document.formulario.IdPessoa.value+"&IdTipoServico=1&IdStatus=1";									
							}
							if(Local == 'Contrato' && document.formulario.Acao.value == 'inserir'){
								if(document.formulario.Filtro_IdPaisEstadoCidade.value != ""){
									url += "&Filtro_IdPaisEstadoCidade="+document.formulario.Filtro_IdPaisEstadoCidade.value;									
								}
							}
							if(Local == 'Servico' && (document.formulario.IdTipoServico.value == '3' || document.formulario.IdTipoServico.value == '4')){
								url	+=	"&IdTipoServico=1";
							}
							if(Local == 'OrdemServico'){								
								url	+=	"&IdPessoaF="+document.formulario.IdPessoaF.value+"&IdPessoa="+document.formulario.IdPessoa.value;
								if(document.formulario.IdContrato.value != ""){
									url	+=	"&IdContrato="+document.formulario.IdContrato.value;	
									url	+=	"&IdTipoServico=3"; /*Agrupado*/
								}else{
									if(IdTipoServico==""){
										url	+=	"&IdTipoServico=2"; /*Eventual*/
									}
								}
							}
							
							xmlhttp.open("GET", url,true);
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroServico').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											
											var dados = "<table id='listaDados' style='width: 560px'>\n<tr>\n<td class='listaDados_titulo' style='width: 70px'>Serviço</td>\n<td class='listaDados_titulo'>Nome Serviço</td>\n<td class='listaDados_titulo'>Valor (<?=getParametroSistema(5,1)?>)</td>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdServico").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdServico = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescricaoServico = verifica_dado(nameTextNode.nodeValue);
												
												//nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoServico")[i]; 
												//nameTextNode = nameNode.childNodes[0];
												//var DescTipoServico = verifica_dado(nameTextNode.nodeValue);
												
												DescricaoServico	=	DescricaoServico.substr(0,50);
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var Valor = nameTextNode.nodeValue;
												
												if(Valor == '')	Valor = 0;
												Valor	=	formata_float(Valor).replace(".",",");
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdServico+"' onClick='aciona_busca_servico(this,"+IdServico+")'>";
												dados += 	"\n<td>"+IdServico+"</td><td>"+DescricaoServico+"</td><td class='valor'>"+Valor+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroServico').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_servico(campo,valor){
							if(valorCampoServico!=''){
								document.getElementById('listaDados_td_'+valorCampoServico).style.backgroundColor = "#FFFFFF";
							}
							if(document.BuscaServico.Local.value != ""){
								var Local	= document.BuscaServico.Local.value;	
							}else{
								var Local	= document.formulario.Local.value;
							}
							if(valorCampoServico == valor){
								busca_servico(valor,true,Local,'busca');
							}
							valorCampoServico = valor;
							document.BuscaServico.IdServico.value = valorCampoServico;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						function limpa_form_servico(){
							document.formularioServico.DescricaoServico.value	=	"";
							//document.formularioServico.IdTipoServico.value		=	"";
							document.formularioServico.IdServicoGrupo.value		=	"";
							
							valorCampoServico = "";
						}
						enterAsTab(document.forms.formularioServico);
					</script>
				</div>
			</div>
