			<div id='quadroBuscaCarteira' style='width:533px;' class='quadroFlutuante'>	
				<!--div class='tit'>Busca Vendedor<div class='fecha' onClick="vi_id('quadroBuscaCarteira', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Vendedor</td>
						<td class='fecha' onClick="vi_id('quadroBuscaCarteira', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioCarteira' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Carteira/Razão Social</td>
								<td class='descCampo'>CPF/CNPJ</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='Nome' autocomplete="off" value='' style='width:365px' maxlength='100' onkeyup="busca_carteira_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
								<td class='campo'>
									<input type='text' name='CPF_CNPJ' value='' autocomplete="off" style='width:128px' maxlength='18' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeyup="busca_carteira_lista()">
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
									<select name='IdPais'  style='width:113px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_carteira_lista(); verifica_estado(this.value,document.formularioCarteira.IdEstado)">
										<option value=''>Todos</option>
										<?
											$sql = "select IdPais, NomePais from Pais order by NomePais";
											$res = mysql_query($sql,$con);
											while($lin = mysql_fetch_array($res)){
												echo "<option value='$lin[IdPais]'>$lin[NomePais]</option>";
											}
										?>
									</select>
								</td>
								<td>
									<select name='IdEstado' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:161px'  onChange="busca_carteira_lista()">
										<option value=''>Todos</option>
									</select>
								</td>
								<td class='campo'>
									<input type='text' name='NomeCidade' value='' style='width:223px' maxlength='100' onkeyup="busca_carteira_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 200px;'>
						<div id='listaDadosQuadroCarteira'>&nbsp;</div>
					</div>
					<form name='BuscaCarteira' method='post' onSubmit='return validar_busca_carteira()'>
						<input type='hidden' name='IdCarteira' value=''>
						<table>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaCarteira', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaCarteira');
					
						var valorCampoCarteira = '';
						function validar_busca_carteira(){
							if(valorCampoCarteira !=''){
								busca_carteira(document.formulario.IdAgenteAutorizado.value,valorCampoCarteira,'false',document.formulario.Local.value);
							}
							return false;
						}
						function busca_carteira_lista(){
							var Nome 		= document.formularioCarteira.Nome.value;
							var IdPais 		= document.formularioCarteira.IdPais.value;
							var IdEstado	= document.formularioCarteira.IdEstado.value;
							var NomeCidade	= document.formularioCarteira.NomeCidade.value;
							var CPF_CNPJ	= document.formularioCarteira.CPF_CNPJ.value;
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
						    	url = "../administrativo/xml/carteira.php?Limit="+Limit;
							}else{
								url = "../administrativo/xml/carteira.php?Nome="+Nome+"&IdPais="+IdPais+"&IdEstado="+IdEstado+"&NomeCidade="+NomeCidade+"&CPF_CNPJ="+CPF_CNPJ;
							}
														
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroCarteira').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 511px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Carteira</td>\n<td class='listaDados_titulo'>Nome</td>\n<td class='listaDados_titulo'>Razão Social</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCarteira").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdCarteira")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdCarteira = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var Nome = verifica_dado(nameTextNode.nodeValue);
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var RazaoSocial = verifica_dado(nameTextNode.nodeValue);
												
												Nome 		= Nome.substring(0,30);
												RazaoSocial = RazaoSocial.substring(0,30);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdCarteira+"' onClick='aciona_busca_carteira(this,"+IdCarteira+")'>";
												dados += 	"\n<td>"+IdCarteira+"</td><td>"+Nome+"</td><td>"+RazaoSocial+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroCarteira').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_carteira(campo,valor){
							if(valorCampoCarteira!=''){
								document.getElementById('listaDados_td_'+valorCampoCarteira).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoCarteira == valor){
								busca_carteira(document.formulario.IdAgenteAutorizado.value,valor,true,document.formulario.Local.value);
							}
							valorCampoCarteira = valor;
							document.BuscaCarteira.IdCarteira.value = valorCampoCarteira;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						function limpa_form_carteira(){
							document.formularioCarteira.Nome.value			=	"";
							document.formularioCarteira.NomeCidade.value		=	"";
							document.formularioCarteira.CPF_CNPJ.value		=	"";
							document.formularioCarteira.IdPais[0].selected	=	true;
							
							while(document.formularioCarteira.IdEstado.options.length > 1){
								document.formularioCarteira.IdEstado.options[1] = null;
							}
							valorCampoCarteira = '';
						}
						enterAsTab(document.forms.formularioCarteira);	
					</script>
				</div>
			</div>
