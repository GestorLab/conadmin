			<div id='quadroBuscaCidade' style='width:400px;' class='quadroFlutuante'>	
				<!--div class='tit'>Busca Cidade<div class='fecha' onClick="vi_id('quadroBuscaCidade', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Busca Cidade</td>
						<td class='fecha' onClick="vi_id('quadroBuscaCidade', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='formularioCidade' method='post'>
						<table>
							<tr>
								<td class='descCampo'>País</td>
								<td class='descCampo'>Estado</td>
							</tr>
							<tr>
								<td>
									<select name='IdPais'  style='width:150px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="busca_cidade_lista(); verifica_estado(this.value,document.formularioCidade.IdEstado)">
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
									<select name='IdEstado' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:224px'  onChange="busca_cidade_lista()">
										<option value=''>Todos</option>
									</select>
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td class='descCampo'>Nome Cidade</td>
							</tr>
							<tr>
								<td class='campo'>
									<input type='text' name='NomeCidade' value='' style='width:368px' maxlength='100' onkeyup="busca_cidade_lista()" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
							</tr>
						</table>
					</form>
					<div id='carregando'>carregando...</div>
					<div class='listaDados' style='height: 200px;'>
						<div id='listaDadosQuadroCidade'>&nbsp;</div>
					</div>
					<form name='BuscaCidade' method='post' onSubmit='return validar_busca_cidade()'>
						<input type='hidden' name='IdCidade' value=''>
						<table>
							<tr>
								<td>
									<input type='submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaCidade', false);" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaCidade');
					
						var valorCampoCidade = '';
						function validar_busca_cidade(){
							if(valorCampoCidade !=''){
								busca_cidade(document.formularioCidade.IdPais.value,document.formularioCidade.IdEstado.value,valorCampoCidade,'false',document.formulario.Local.value,'listar');
							}
							return false;
						}
						function busca_cidade_lista(){
							var IdPais 		= document.formularioCidade.IdPais.value;
							var IdEstado	= document.formularioCidade.IdEstado.value;
							var NomeCidade	= document.formularioCidade.NomeCidade.value;
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
						    
						    if(IdEstado == 0) 	IdEstado = '';
						    
						    if(IdPais == '' && IdEstado == '' && NomeCidade == ''){
						    	url = "../administrativo/xml/cidade.php?Limit="+Limit;
							}else{
								url = "../administrativo/xml/cidade.php?IdPais="+IdPais+"&IdEstado="+IdEstado+"&NomeCidade="+NomeCidade;
								if(NomeCidade == ''){
									url	+=	"&Limit="+Limit;
								}
							}
							
							xmlhttp.open("GET", url,true);
							
							xmlhttp.onreadystatechange = function(){ 
					
								// Carregando...
								carregando(true);
					
								if(xmlhttp.readyState == 4){ 
									if(xmlhttp.status == 200){
										if(xmlhttp.responseText == 'false'){
											document.getElementById('listaDadosQuadroCidade').innerHTML = "";
											// Fim de Carregando
											carregando(false);
										}else{
											var dados = "<table id='listaDados' style='width: 378px'>\n<tr>\n<td class='listaDados_titulo' style='width: 60px'>&nbsp;</td>\n<td class='listaDados_titulo'>Nome País</td>\n<td class='listaDados_titulo'>Nome Estado</td>\n<td class='listaDados_titulo'>Nome Cidade</td>\n</tr>";
											for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPais").length; i++){
													
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdPais")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdPais = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("NomePais")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var NomePais = verifica_dado(nameTextNode.nodeValue);
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdEstado = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("NomeEstado")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var NomeEstado = verifica_dado(nameTextNode.nodeValue);
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var IdCidade = nameTextNode.nodeValue;
												
												nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[i]; 
												nameTextNode = nameNode.childNodes[0];
												var NomeCidade = verifica_dado(nameTextNode.nodeValue);
												
												dados += "\n<tr style='background-color:#FFF' id='listaDados_td_"+IdPais+"_"+IdEstado+"_"+IdCidade+"' onClick='aciona_busca_cidade(this,"+IdPais+","+IdEstado+","+IdCidade+")'>";
												dados += 	"\n<td>"+(i+1)+"</td><td>"+NomePais+"</td><td>"+NomeEstado+"</td><td>"+NomeCidade+"</td>";
												dados += "\n</tr>";
											}
											dados += "\n</table>";
											document.getElementById('listaDadosQuadroCidade').innerHTML = dados;
										}
									}
								} 
								// Fim de Carregando
								carregando(false);
							}
							xmlhttp.send(null);
						}
						function aciona_busca_cidade(campo,valor,valor2,valor3){
							if(valorCampoCidade!=''){
								document.getElementById('listaDados_td_'+valor+'_'+valor2+'_'+valorCampoCidade).style.backgroundColor = "#FFFFFF";
							}
							if(valorCampoCidade == valor3){
								busca_cidade(valor,valor2,valor3,false,document.formulario.Local.value,'listar');
							}
							valorCampoCidade = valor3;
							document.BuscaCidade.IdCidade.value = valorCampoCidade;
							campo.style.backgroundColor = '<?=getParametroSistema(15,4)?>';
						}
						function limpa_form_cidade(){
							document.formularioCidade.IdPais[0].selected	=	true;
							document.formularioCidade.NomeCidade.value		=	"";
							
							while(document.formularioCidade.IdEstado.options.length > 1){
								document.formularioCidade.IdEstado.options[1] = null;
							}
							valorCampoCidade = '';
						}
						enterAsTab(document.forms.formularioCidade);	
					</script>
				</div>
			</div>
