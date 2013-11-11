			<div id='quadroBuscaServicoAgrupador' style='width:533px;' class='quadroFlutuante'>	
				<!--div class='tit'>Busca Serviço<div class='fecha' onClick="vi_id('quadroBuscaServicoAgrupador', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Serviço</td>
						<td class='fecha' onClick="vi_id('quadroBuscaServicoAgrupador', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioServicoAgrupador' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Serviço</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='DescricaoServico' autocomplete="off" value='' style='width:501px' maxlength='100' onkeyup="busca_servico_agrupador_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" tabindex='1'>
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 180px;'>
						<div id='listaDadosQuadroServicoAgrupador'>&nbsp;</div>
					</div>
					<form name='BuscaServicoAgrupador' method='post' onSubmit='return validar_busca_servico_agrupador()'>
						<input type='hidden' name='IdServico' value=''>
						<input type='hidden' name='Local' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaServicoAgrupador', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>	
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaServicoAgrupador');
						
						var valorCampoServicoAgrupador = '';
						function validar_busca_servico_agrupador(){
							if(document.BuscaServicoAgrupador.Local.value != ""){
								var Local	= document.BuscaServicoAgrupador.Local.value;	
							}else{
								var Local	= document.formulario.Local.value;
							}
							if(valorCampoServicoAgrupador !=''){
								busca_servico(valorCampoServicoAgrupador,true,Local);
							}
							return false;
						}
						function busca_servico_agrupador_lista(){
							var DescricaoServico	 	= document.formularioServicoAgrupador.DescricaoServico.value;
							
							if(document.BuscaServicoAgrupador.Local.value != ""){
								var Local	= document.BuscaServicoAgrupador.Local.value;	
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
						    
						    if(DescricaoServico == ''){
						    	url = "xml/servico.php?Limit="+Limit+"&Local="+Local;
							}else{
								url = "xml/servico.php?DescricaoServico="+DescricaoServico+"&Local="+Local;
							}
							
							if(Local == 'ContratoServico'){
								url	+=	"&IdServicoAlterar="+document.formulario.IdServicoAnterior.value;
							}
							if((Local == 'Contrato' && document.formulario.Acao.value == 'inserir') || Local == 'ContratoServico'){
								url	+=	"&IdPessoaF="+document.formulario.IdPessoaF.value+"&IdPessoa="+document.formulario.IdPessoa.value+"&IdTipoServico=1";
							}
							if((Local == 'ServicoAgrupador' || Local == 'Servico') && (document.formulario.IdTipoServico.value == '3' || document.formulario.IdTipoServico.value == '4')){
								url	+=	"&IdTipoServico=1";
							}
							xmlhttp.open("GET", url,true);
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroServicoAgrupador').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 510px'>\n<tr>\n<td class='listaDados_titulo' style='width: 70px'>Serviço</td>\n<td class='listaDados_titulo'>Nome Serviço</td>\n<td class='listaDados_titulo'>Tipo</td>\n<td class='listaDados_titulo'>Valor (<?=getParametroSistema(5,1)?>)</td>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdServico").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdServico = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescricaoServico = verifica_dado(nameTextNode.nodeValue);
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoServico")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var DescTipoServico = verifica_dado(nameTextNode.nodeValue);
												
												DescricaoServico	=	DescricaoServico.substr(0,35);
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var Valor = nameTextNode.nodeValue;
												
												if(Valor == '')	Valor = 0;
												Valor	=	formata_float(Valor).replace(".",",");
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdServico+"' onClick='aciona_busca_servico_agrupador(this,"+IdServico+")'>";
												dados += 	"\n<td>"+IdServico+"</td><td>"+DescricaoServico+"</td><td>"+DescTipoServico+"</td><td class='valor'>"+Valor+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroServicoAgrupador').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_servico_agrupador(campo,valor){
							if(valorCampoServicoAgrupador!=''){
								document.getElementById('listaDados_td_'+valorCampoServicoAgrupador).style.backgroundColor = "#FFFFFF";
							}
							if(document.BuscaServicoAgrupador.Local.value != ""){
								var Local	= document.BuscaServicoAgrupador.Local.value;	
							}else{
								var Local	= document.formulario.Local.value;
							}
							if(valorCampoServicoAgrupador == valor){
								busca_servico(valor,true,Local);
							}
							valorCampoServicoAgrupador = valor;
							document.BuscaServicoAgrupador.IdServico.value = valorCampoServicoAgrupador;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						enterAsTab(document.forms.formularioServicoAgrupador);
					</script>
				</div>
			</div>
