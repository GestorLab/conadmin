			<div id='quadroBuscaAgente' style='width:533px;' class='quadroFlutuante'>
				<!--div class='tit'>Busca Agente Autorizado<div class='fecha' onClick="vi_id('quadroBuscaAgente', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Agente Autorizado</td>
						<td class='fecha' onClick="vi_id('quadroBuscaAgente', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioAgente' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Pessoa/Razão Social</td>
								<td class='descCampo'>CPF/CNPJ</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='Nome' autocomplete="off" value='<?=$local_Nome?>' style='width:365px' maxlength='100' onkeyup="busca_agente_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
								<td class='campo'>
									<input type='text' name='CPF_CNPJ' value='<?=$local_CPF_CNPJ?>' autocomplete="off" style='width:125px' maxlength='18' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeyup="busca_agente_lista()">
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='descCampo'>País</td>
								<td class='descCampo'>Estado</td>
								<td class='descCampo'>Nome Cidade</td>
							</tr>
							<tr>
								<td>
									<select name='IdPais'  style='width:113px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_agente_lista(); verifica_estado(this.value,document.formularioAgente.IdEstado)">
										<option value=''>Todos</option>
										<?
											$sql = "select IdPais, NomePais from Pais order by NomePais";
											$res = mysql_query($sql,$con);
											while($lin = mysql_fetch_array($res)){
												echo "<option value='$lin[IdPais]' ".compara($localIdPais,$lin[IdPais],"selected='selected'","").">$lin[NomePais]</option>";
											}
										?>
									</select>
								</td>
								<td>
									<select name='IdEstado' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:161px'  onChange="busca_agente_lista()">
										<option value=''>Todos</option>
										<?
											$sql	=	"select IdEstado, NomeEstado from Pais,Estado where Estado.IdPais = Pais.IdPais and Estado.IdPais=$localIdPais order by NomeEstado";
											$res	=	mysql_query($sql,$con);
											while($lin = mysql_fetch_array($res)){
												echo "<option value='$lin[IdEstado]' ".compara($localEstado,$lin[IdEstado],"selected='selected'","").">$lin[NomeEstado]</option>\n";
											}
										?>
									</select>
								</td>
								<td class='campo'>
									<input type='text' name='NomeCidade' value='<?=$local_NomeCidade?>' style='width:220px' maxlength='100' onkeyup="busca_agente_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 200px;'>
						<div id='listaDadosQuadroAgente'>&nbsp;</div>
					</div>
					<form name='BuscaAgente' method='post' onSubmit='return validar_busca_agente()'>
						<input type='hidden' name='IdAgenteAutorizado' value=''>
						<table class='listaDados'>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaAgente', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaAgente');
						
						var valorCampoAgente = '';
						function validar_busca_agente(){
							if(valorCampoAgente !=''){
								busca_agente(valorCampoAgente,false);
							}
							return false;
						}
						function busca_agente_lista(){
							var Nome 		= document.formularioAgente.Nome.value;
							var IdPais 		= document.formularioAgente.IdPais.value;
							var IdEstado	= document.formularioAgente.IdEstado.value;
							var NomeCidade	= document.formularioAgente.NomeCidade.value;
							var CPF_CNPJ	= document.formularioAgente.CPF_CNPJ.value;
							var Local		= document.formulario.Local.value;
							var Limit	  	= <?=getCodigoInterno(7,4)?>;
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
						    
						    if(Nome == '' && IdPais == '' && (IdEstado == '' || IdEstado == 0) && NomeCidade == '' && CPF_CNPJ==''){
						    	url = "../administrativo/xml/agente.php?Limit="+Limit;
							}else{
								url = "../administrativo/xml/agente.php?Nome="+Nome+"&IdPais="+IdPais+"&IdEstado="+IdEstado+"&NomeCidade="+NomeCidade+"&CPF_CNPJ="+CPF_CNPJ;
							}
							
							if(Local == 'Contrato' || Local == 'ContratoServico'){
								url	+=	'&IdStatus=1';
							}
							
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroAgente').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 511px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Agente</td>\n<td class='listaDados_titulo'>Nome Agente Autorizado</td>\n<td class='listaDados_titulo'>CNPJ</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdAgenteAutorizado").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdAgenteAutorizado")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdAgenteAutorizado = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("CPF_CNPJ")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var CPF_CNPJ = verifica_dado(nameTextNode.nodeValue);
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var RazaoSocial = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var Nome = nameTextNode.nodeValue;
												
												Nome 		= Nome.substring(0,30);
												RazaoSocial = RazaoSocial.substring(0,30);
												Nome 		= Nome.substring(0,30);
												
												if(RazaoSocial == ''){
													RazaoSocial	=	Nome;
												}
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdAgenteAutorizado+"' onClick='aciona_busca_agente(this,"+IdAgenteAutorizado+")'>";
												dados += 	"\n<td>"+IdAgenteAutorizado+"</td><td>"+RazaoSocial+"</td><td>"+CPF_CNPJ+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroAgente').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_agente(campo,valor){
							if(valorCampoAgente!=''){
								document.getElementById('listaDados_td_'+valorCampoAgente).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoAgente == valor){
								busca_agente(valor);
							}
							valorCampoAgente = valor;
							document.formulario.IdAgenteAutorizado.value = valorCampoAgente;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						function limpa_form_agente(){
							document.formularioAgente.Nome.value			=	"";
							document.formularioAgente.NomeCidade.value		=	"";
							document.formularioAgente.CPF_CNPJ.value		=	"";
							document.formularioAgente.IdPais[0].selected	=	true;
							
							while(document.formularioAgente.IdEstado.options.length > 0){
								document.formularioAgente.IdEstado.options[0] = null;
							}
							valorCampoAgente = '';
							document.formularioAgente.Nome.focus();
						}
						enterAsTab(document.forms.formularioAgente);		
					</script>
				</div>
			</div>
