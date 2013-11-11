			<div id='quadroBuscaCep' style='width:347px;' class='quadroFlutuante'>
				<!--div class='tit'>Consulta de CEP<div class='fecha' onClick="vi_id('quadroBuscaCep', false);">X</div></div-->
				<table class='titMenu' cellspacing='0' cellpading='0'>
					<tr>
						<td class='tit'>Consulta de CEP</td>
						<td class='fecha' onClick="vi_id('quadroBuscaCep', false);">X</div></td>
					</tr>
				</table>
				<div class='filtro_busca'>
					<form name='BuscaCep' method="post" onSubmit="return CriticaCampos();" action="http://www.buscacep.correios.com.br/servicos/dnec/consultaLogradouroAction.do" target="_blank">
						<input type="Hidden" name="cfm" value="1">
						<input type="hidden" name="Metodo" value="listaLogradouro">
						<input type="hidden" name="TipoConsulta" value="logradouro">
						<input type="hidden" name="StartRow" value="1">
						<input type="hidden" name="EndRow" value="10">
						<table style='margin-left:5px'>
							<tr>
								<td class='descCampo'>Estado</td>
								<td class='descCampo'>Cidade</td>
							</tr>
							<tr>
								<td class='campo'>
									<select name='UF' style='width:50px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
										<?
											$sql = "select SiglaEstado from Estado where IdEstado = ".getCodigoInterno(3,2);
											$res = mysql_query($sql,$con);
											$lin = mysql_fetch_array($res);
																						
											$sql2 = "select SiglaEstado from Estado order by SiglaEstado";
											$res2 = mysql_query($sql2,$con);
											while($lin2 = mysql_fetch_array($res2)){
												echo "<option value='$lin2[SiglaEstado]'".compara($lin[SiglaEstado],$lin2[SiglaEstado]," selected","").">$lin2[SiglaEstado]</option>\n";
											}
										?>
									</select>
								</td>
								<td>
									<input align=left maxLength=40 name='Localidade' style='width:270px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
								</td>
							</tr>
						</table>
						<table style='margin-left:5px'> 
							<tr>
								<td class='descCampo'>Tipo</td>
								<td class='descCampo'>Logradouro</td>
							</tr>														
							<tr> 
								<td class='campo'>
									<select name='Tipo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
										<option value=""></option>
										<option value="">Outros</option>										
										<option value = "Aeroporto">Aeroporto</option>
										<option value = "Alameda">Alameda</option>
										<option value = "Área">Área</option>
										<option value = "Avenida">Avenida</option>														
										<option value = "Campo">Campo</option>
										<option value = "Chácara">Chácara</option>
										<option value = "Colônia">Colônia</option>
										<option value = "Condomínio">Condomínio</option>
										<option value = "Conjunto">Conjunto</option>
										<option value = "Distrito">Distrito</option>
										<option value = "Esplanada">Esplanada</option>
										<option value = "Estação">Estação</option>
										<option value = "Estrada">Estrada</option>
										<option value = "Favela">Favela</option>
										<option value = "Fazenda">Fazenda</option>
										<option value = "Feira">Feira</option>
										<option value = "Jardim">Jardim</option>
										<option value = "Ladeira">Ladeira</option>
										<option value = "Lago">Lago</option>
										<option value = "Lagoa">Lagoa</option>
										<option value = "Largo">Largo</option>
										<option value = "Loteamento">Loteamento</option>
										<option value = "Morro">Morro</option>
										<option value = "Núcleo">Núcleo</option>
										<option value = "Parque">Parque</option>
										<option value = "Passarela">Passarela</option>
										<option value = "Pátio">Pátio</option>
										<option value = "Praça">Praça</option>
										<option value = "Quadra">Quadra</option>
										<option value = "Recanto">Recanto</option>
										<option value = "Residencial">Residencial</option>
										<option value = "Rodovia">Rodovia</option>
										<option value = "Rua">Rua</option>
										<option value = "Setor">Setor</option>
										<option value = "Sítio">Sítio</option>
										<option value = "Travessa">Travessa</option>
										<option value = "Trecho">Trecho</option>
										<option value = "Trevo">Trevo</option>
										<option value = "Vale">Vale</option>
										<option value = "Vereda">Vereda</option>
										<option value = "Via">Via</option>
										<option value = "Viaduto">Viaduto</option>
										<option value = "Viela">Viela</option>
										<option value = "Vila">Vila</option>
									</select>
								</td>
								<td>
									<input align=left maxLength=60 name='Logradouro' style='width:222px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeypress="if ((event.keyCode > 32 && event.keyCode < 40) || (event.keyCode > 41 && event.keyCode < 48) || (event.keyCode > 57 && event.keyCode < 65) || (event.keyCode > 90 && event.keyCode < 97)) event.returnValue = false;">
								</td>
							</tr>
						</table>
						<table style='margin-left:5px'>
							<tr>
								<td class='descCampo'>Nº/Lote/Apto/Casa</td>
							</tr>
							<tr>
								<td><input align=left maxlength=5 name='Numero' size=5 onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"></td>
							</tr>
						</table>
						<table style='width:100%; text-align:right;'>
							<tr>	
								<td style='padding-right:4px'>
									<input type='Submit' value='Ok' class='botao'>
									<input type='button' value='Cancelar' onClick="vi_id('quadroBuscaCep', false)" class='botao'>
								</td>
							</tr>
						</table>
					</form>
					<script language='JavaScript' type='text/javascript'>
						// Para que os quadros flutuem
						new Draggable('quadroBuscaCep');						
						
						function CriticaCampos(){
					    	if (document.BuscaCep.Localidade.value == ""){
					    		alert("Informe o nome completo da Cidade/Município/Distrito/Povoado. Para o DF poderá ser informado o nome da Região Administrativa (Lago Sul, Lago Norte, Cruzeiro, Taguatinga, etc) !!");
					   			 document.BuscaCep.Localidade.focus();
					   			 return (false);
					   		 }else{ 
							    var Branco = " ";
							    var Posic, Carac;
						   		var Temp = document.BuscaCep.Localidade.value.length;    
						    	var Cont = 0;
						    	for (var i=0; i < Temp; i++){  
								   Carac =  document.BuscaCep.Localidade.value.charAt (i);
						 		   Posic  = Branco.indexOf (Carac);   
						 		   if (Posic == -1)   
						 			   Cont++;      
						  		   }   
						  		   if (Cont <= 0){
						   				alert("Informe o nome completo da Cidade/Município/Distrito/Povoado. Para o DF poderá ser informado o nome da Região Administrativa (Lago Sul, Lago Norte, Cruzeiro, Taguatinga, etc) !!");
						    			document.BuscaCep.Localidade.focus();
						   				return (false);
						           }   
						    }
						    if (document.BuscaCep.Logradouro.value == ""){
							    alert("Informe o nome do logradouro");
							    document.BuscaCep.Logradouro.focus();
						    	return (false);
						    }else{ 
							    var Branco = " ";
							    var Posic, Carac;
						    	var Temp = document.BuscaCep.Logradouro.value.length;    
							    var Cont = 0;
							    for (var i=0; i < Temp; i++){  
								    Carac =  document.BuscaCep.Logradouro.value.charAt (i);
						    		Posic  = Branco.indexOf (Carac);   
								    if (Posic == -1){   
						    			Cont++;      
						    		}
						    	}   
						    	if (Cont <= 0){
								    alert("Informe o nome do logradouro");
						   			document.BuscaCep.Logradouro.focus();
						   			 return (false);
						    	}  
						    }
						    vi_id('quadroBuscaCep', false);
							enterAsTab(document.forms.BuscaCep);	
						} 
					</script>
				</div>
			</div>
