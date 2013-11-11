			<div id='quadroBuscaTerceiro' style='width:533px;' class='quadroFlutuante'>	
				<!--div class='tit'>Busca Terceiro<div class='fecha' onClick="vi_id('quadroBuscaTerceiro', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Terceiro</td>
						<td class='fecha' onClick="vi_id('quadroBuscaTerceiro', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioTerceiro' method='post'>
						<table>
							<tr>
								<td class='descCampo'>Nome Pessoa/Razão Social</td>
								<td class='descCampo'>CPF/CNPJ</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='Nome' autocomplete="off" value='' style='width:365px' maxlength='100' onkeyup="busca_terceiro_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
								<td class='campo'>
									<input type='text' name='CPF_CNPJ' value='' autocomplete="off" style='width:128px' maxlength='18' onFocus="Foco(this,'in',true)"  onBlur="Foco(this,'out')" onkeyup="busca_terceiro_lista()">
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
									<select name='IdPais'  style='width:113px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_terceiro_lista(); verifica_estado(this.value,document.formularioTerceiro.IdEstado)">
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
									<select name='IdEstado' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:161px'  onChange="busca_terceiro_lista()">
										<option value=''>Todos</option>
										<?
											$sql	=	"select IdEstado, NomeEstado from Pais,Estado where Estado.IdPais = Pais.IdPais and Estado.IdPais=$localIdPais order by NomeEstado";
											$res	=	mysql_query($sql,$con);
											while($lin = mysql_fetch_array($res)){
												echo "<option value='$lin[IdEstado]'>$lin[NomeEstado]</option>\n";
											}
										?>
									</select>
								</td>
								<td class='campo'>
									<input type='text' name='NomeCidade' value='' style='width:223px' maxlength='100' onkeyup="busca_terceiro_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 200px;'>
						<div id='listaDadosQuadroTerceiro'>&nbsp;</div>
					</div>
					<form name='BuscaTerceiro' method='post' action='busca_terceiro.php' onSubmit='return validar_busca_terceiro()'>
						<input type='hidden' name='IdPessoa' value=''>
						<table>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaTerceiro', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaTerceiro');
								
						var valorCampoTerceiro = '';
						function validar_busca_terceiro(){
							if(valorCampoTerceiro !=''){
								busca_terceiro(valorCampoTerceiro,false);
							}
							return false;
						}
						function busca_terceiro_lista(){
							var Nome 		= document.formularioTerceiro.Nome.value;
							var IdPais 		= document.formularioTerceiro.IdPais.value;
							var IdEstado	= document.formularioTerceiro.IdEstado.value;
							var NomeCidade	= document.formularioTerceiro.NomeCidade.value;
							var CPF_CNPJ	= document.formularioTerceiro.CPF_CNPJ.value;
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
						    	url = "../administrativo/xml/terceiro.php?Limit="+Limit;
							}else{
								url = "../administrativo/xml/terceiro.php?Nome="+Nome+"&IdPais="+IdPais+"&IdEstado="+IdEstado+"&NomeCidade="+NomeCidade+"&CPF_CNPJ="+CPF_CNPJ;
							}
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroTerceiro').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 511px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>Pessoa</td>\n<td class='listaDados_titulo'>Nome Terceiro</td>\n<td class='listaDados_titulo'>Razão Social</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPessoa").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdPessoa = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var Nome = verifica_dado(nameTextNode.nodeValue);
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("RazaoSocial")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var RazaoSocial = verifica_dado(nameTextNode.nodeValue);
												
												Nome 		= Nome.substring(0,30);
												RazaoSocial = RazaoSocial.substring(0,30);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdPessoa+"' onClick='aciona_busca_terceiro(this,"+IdPessoa+")'>";
												dados += 	"\n<td>"+IdPessoa+"</td><td>"+Nome+"</td><td>"+RazaoSocial+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroTerceiro').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_terceiro(campo,valor){
							if(valorCampoTerceiro!=''){
								document.getElementById('listaDados_td_'+valorCampoTerceiro).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoTerceiro == valor){
								busca_terceiro(valor);
							}
							valorCampoTerceiro = valor;
							document.BuscaTerceiro.IdPessoa.value = valorCampoTerceiro;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						function limpa_form_terceiro(){
							document.formularioTerceiro.Nome.value			=	"";
							document.formularioTerceiro.NomeCidade.value	=	"";
							document.formularioTerceiro.CPF_CNPJ.value		=	"";
							document.formularioTerceiro.IdPais[0].selected	=	true;
									
							while(document.formularioTerceiro.IdEstado.options.length > 1){
								document.formularioTerceiro.IdEstado.options[1] = null;
							}
							valorCampoTerceiro = '';
						}
						enterAsTab(document.forms.formularioTerceiro);
					</script>
				</div>
			</div>
