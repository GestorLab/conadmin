jQuery(document).ready(function(){
	
	var $ = jQuery.noConflict();
	
	$("body").addTabindex();
	
	$("#IdDevicePerfil").on("change", function(event, IdDevice, IdDevicePerfil){
		var perfil = $(this);
		
		if(IdDevicePerfil != null){
			var dados = new Array();
			dados = {
					'IdDevice': IdDevice,
					'IdDevicePerfil': IdDevicePerfil
			};
		}else /*if($("#IdDevice").val().trim() == "")*/{
			dados = perfil.val();
		}
		if($(this).val() == ""){
			$(".atributos #cp_parametrosSistemas").remove();
		}else{
			var perfil = $(this);
			$.ajax({
				dataType: "html",
				type: "POST",
				url: "xml/atributos_device.php",
				data: {dadosWhere: dados},
				success: function(data){
					//alert(data);
					/*if(data.trim() != "" && data != undefined){
						$(".atributos #cp_parametrosSistemas").remove();
						$(".atributos").append(data);
						if($("#tabelaParametro tr:last").find("td:last").find('input:first').val().trim() == ""){
							$("#tabelaParametro tr:last").find("td:last").find('input:first').parent().remove();
						}
					}*/
					$(".atributos").append(data);
				},
				complete: function(){
					perfil.controlePerfil();
					$('body').addTabindex();
				}
			});
		}
	});
	
	if($("img[name=modal]").attr("name") == "modal")
	new Draggable('dialog');
	
	var url = window.location.search.replace("?", "");
	
	if(url != ""){
		
		addParmUrl("marTerceiroMonitor", "", url);
		
		$("#atualizaHistorico").attr("href", 'cadastro_device.php?' + url);
		
		url = decodeURI(url); 
		url = JSON.parse(url);
		
		/*if(url.IdDevicePerfil != undefined){
				$(".esconde").show();
				$("#IdDevicePerfil").trigger("change", [url.IdDevice, url.IdDevicePerfil]);
		}*/
		
		var tipoMensagem = url.Tipo;
		
		if(url.Tipo == 3 || url.Tipo == 4 || url.Tipo == undefined){
			$("#bt_inserir").attr("disabled", "disabled");
			$("#bt_alterar").removeAttr("disabled");
			$("#bt_excluir").removeAttr("disabled");
		}else if(url.Tipo == 7){
			mensagens(7);
		}
		
		if(url.Tipo != "D"){
			
			$.ajax({
				dataType: "json",
				type: "POST",
				url: "xml/device.php",
				data: {dadosWhere: url},
				beforeSend: function(){
					carregando(true);
				},
				success: function(data){
					//alert(data);
					for(var i in data){
						if(data[i] != null){
							if($("#"+i).attr("id") == i){
								$("#"+i+" option").each(function(index){
									if($(this).val() == data[i]){
										$(this).prop("selected", "selected");
									}
								});
							}
							$("#"+i).val(data[i]);
						}
					}
					//alert(data.IdDevicePerfil);
					if(data.IdDevice && data.IdDevicePerfil != ""){
						$(".esconde").show();
						$("#IdDevicePerfil").trigger("change", [url.IdDevice, data.IdDevicePerfil]);
					}
				},
				complete: function(data){
					carregando(false);
					
					$.ajax({
						dataType: "html",
						type: "POST",
						url: "xml/device.php",
					    data: {dadosWherePorta: url},
					    success: function(data){
					    	$(".portas").append(data);
					    }
					});
					
					mensagens(tipoMensagem);
				}
			});
		}
		
	}
	
	$("#QuantidadePortas").change(function(){
		$(this).devicePortas();
		$('body').addTabindex();
	});
	
	$("#form").submit(function(e){
		var cont = 0;
		
		mensagens(0);
		$(".obrig").each(function(index){
			if($(this).is(":input")){
				if($(this).val() == ""){
					$(this).focus();
					mensagens(1);
					cont = 1;
					e.preventDefault();
					return false;
				}
			}
		});
	});
	
	$("#IdDevice").change(function(){
		var id = $(this).val();
		if(id.trim() != ""){
			IdDevice = {
					IdDevice: id
			};
			alert('akiii');
			location.href = "cadastro_device.php?" + JSON.stringify(IdDevice);
		}
	});
	
	$("IdGrupoDevice").change(function(){
		
	});
	
	$("#IdTipoDevice").change(function(){
		if($(this).find("option:selected").val() == 1){
			$(".esconde").show();
		}else{
			$("#IdDevicePerfil").find("option:first").prop("selected", true);
			$(".esconde").hide();
			$(".atributos #cp_parametrosSistemas").remove();
			/*if($(".portas cp_Vencimento").is(":visible")){
				$("#QuantidadePortas").change(function(){
					$(this).devicePortas();
				});
			}*/
				
			if($("#QuantidadePortasSelect").is(":visible"))
				$("#QuantidadePortasSelect").remove();
			else
				$("#QuantidadePortas").removeAttr("disabled").val("");
			$("#QuantidadePortas").show();
		}
	});
	
	$(".ordenar").click(function(){
		order = $(this).attr('id');
		filtro = $("#filtro").serialize();
		if($("#tipoOrdenacao").val() == "DESC"){
			tipoOrder = "ASC";
		}else if($("#tipoOrdenacao").val() == "ASC"){
			tipoOrder = "DESC"
		}
		
		location.href = "listar_device.php?order="+order+"&tipoOrder="+tipoOrder+"&"+filtro;
	});
	
	$(".excluir").click(function(){
		var codigo = $(this).attr("id").split("_");
		var arrayId = {
				IdDevice: codigo[1]
		};
		//var action_excluir = {action: tipoOperacao};
		var tipoOperacao = "Excluir";
		
		if(confirm("ATENCAO!\n\nVoce esta prestes a excluir um endereco.\nDeseja continuar?","SIM","NAO")){
			$.ajax({
				dataType: "html",
				type: "POST",
				data: {dados: arrayId, bt_excluir: tipoOperacao, pageTipo: 'listar', action: tipoOperacao },
				url: "files/inserir/inserir_device.php",
				beforeSend: function(){
					carregando(true);
				},
				complete: function(){
					carregando(false);
				},
				success: function(data){
					alert(data);
					location.href = "listar_device.php";
				}
			});
		}
	});
	
	$("#QuantidadePortas").keypress(function(event){
		var tipo = $("#IdTipoDevice option:selected").val();
		if(tipo == 0){
			$(this).val("");
			$(this).attr("readonly", "readonly");
		}else{
			$(this).removeAttr("readonly");
			mascara(this,event,'int');
		}
	});
	
	
	
	
	$("input[name=DescricaoGrupoDevice]").keyup(function(){
		//alert($(this).val());
		val = $(this).val();
		//var name = $(this).attr('name');
		var IdLoja = $("#IdLoja").val();
		//var IdLoja = url.IdLoja;
		//alert(IdLoja);
		val = ' IdLoja = '+ IdLoja +' AND DescricaoGrupoDevice LIKE \'%'+val+'%\'';
		
		//if($(this).val() != ""){
			$.ajax({
				type: "POST",
				dataType: "html",
				url: "xml/grupo_device.php",
				data: {dadosWhereQuadro: val},
				beforeSend: function(){
					carregando(true);
				},
				complete: function(){
					carregando(false);
				},
				success: function(data){
					//alert(data);
					if($(".teste").is(":visible")){
						$(".teste").remove();
					}
					
					
					$('#listaDadosQuadroGrupoDevice').append(data);
					
					$(".dadosTable").on("click", function(){
						if($(this).css("background-color") == "rgb(255, 255, 255)"){
							$(this).css('background-color', "rgb(160, 196, 234)");
						}else{
							var id = $(this).find("td:eq(0)").text();
							var nome = $(this).find("td:eq(1)").text();
							
							$("#IdGrupoDevice").val(id);
							$("#DescricaoGrupoDevice").val(nome);
							
							$('#mask').hide();
							$('.quadroFlutuante').hide();
							$("#IdGrupoDevice").focus();
						}
					});
				}
			});
		//}
	});
	
	$('img[name=modal]').click(function(e) {
		
		//url.LikeDados = "IdGrupoDevice > 0 LIMIT 5";
		data = "IdGrupoDevice > 0 LIMIT 10";
		
		e.preventDefault();
		
		
		$("input[name=DescricaoGrupoDevice]").val("");
		
		$.ajax({
			dataType: "html",
			type: "POST",
			url: "xml/grupo_device.php",
			data: {dadosWhereQuadro: data},
			beforeSend: function(){
				carregando(true);
			},
			complete: function(){
				carregando(false);
			},
			success: function(data){
				//alert(data);
				if($(".teste").is(":visible")){
					$(".teste").remove();
				}
				$('#listaDadosQuadroGrupoDevice').append(data);
				
				$(".dadosTable").on("click", function(){
					if($(this).css("background-color") == "rgb(255, 255, 255)"){
						$(this).css('background-color', "rgb(160, 196, 234)");
					}else{
						var id = $(this).find("td:eq(0)").text();
						var nome = $(this).find("td:eq(1)").text();
						
						$("#IdGrupoDevice").val(id);
						$("#DescricaoGrupoDevice").val(nome);
						
						$('#mask').hide();
						$('.quadroFlutuante').hide();
						$("#IdGrupoDevice").focus();
					}
				});
			}
		});
		
		var id = $(this).attr('id');
	    //Inicio mascara para escurecer a janela
		var maskHeight = $(document).height();
		var maskWidth = $(window).width();
	
		$('#mask').css({'width':maskWidth,'height':maskHeight});

		$('#mask').fadeIn(0);	
		$('#mask').fadeTo("slow",0);//Fim mascara escurecer janela	
	
		//Obter a altura da janela e largura
		var winH = $(window).height();
		var winW = $(window).width();
              
		$(id).css('top',  winH/2-$(id).height()/2);
		$(id).css('left', winW/2-$(id).width()/2);
	
		$(id).fadeIn(0);
		
		$("input[name=DescricaoGrupoDevice]").focus();
	
	});
	
	$('.quadroFlutuante .close').click(function (e) {
		e.preventDefault();
		
		$('#mask').hide();
		$('.quadroFlutuante').hide();
	});		
	
	$('#mask').click(function () {
		$(this).hide();
		$('.quadroFlutuante').hide();
	});	
});

(function($){
	$.fn.addTabindex = function(){
		var count = 1;
		$(':input:visible').each(function(index){
			//if($(this).is(':visible')){
				$(this).prop('tabindex', count);
				count++
			//}
		});
	}
	
	$.fn.controlePerfil = function(){
		var perfil = $(this).val();
		$.ajax({
			dataType: "html",
			type: "POST",
			url: "xml/device_perfil.php",
			data: {dadosWhere: perfil},
			success: function(data){
				//alert(data);
				if(data == 0){
					$("#QuantidadePortas").val(data).attr("disabled", true);
				}else{
					var tagTd = $("#QuantidadePortas").hide().parent();
					$("#QuantidadePortasSelect").remove();
					tagTd.append(data);
					$('body').addTabindex();
					$("#QuantidadePortasSelect").change(function(){
						$(this).devicePortas();
					});
				}
			}
		});
	};
	
	$.fn.devicePortas = function(){
		var QuantiPortas = $(this).val();
		if($("#quantPoras").not(":visible") && QuantiPortas != "" && QuantiPortas != 0 && QuantiPortas <= 999){
		
			if(!$(".portas #cp_Vencimento").is(":visible")){
				var html = "<div id='cp_Vencimento' style='margin-bottom: 0px; display: block;'>" +
				   "<div id='cp_tit' style='margin-bottom:0;margin-top:10px;'>Portas</div>" +
				    "<table class='tableListarCad' cellspacing='0'>" +
		                "<tr class='tableListarTitleCad'>" +
		                   "<td class='tableListarEspaco'></td>" +
		 	               "<td class='tableListarEspaco'>Descrição</td>" +
		 	               "<td class='tableListarEspaco'>Observação</td>" +
		 	               "<td class='tableListarEspaco'>Disponivel</td>" +
		 	            "</tr>";
		 	       for(var i = 1; i <= QuantiPortas; i++){
		 	    	   if(i%2 == 0){
		 	    		   var cor = "style='background-color: rgb(226, 231, 237);'";
		 	    	   }else{
		 	    		   var cor = '';
		 	    	   }
		 	          html +=  "<tr "+cor+">" +
		 	          		"<td style='width: 70px; text-align: center;'>" + i +"<input type='hidden' name='portas["+i+"][IdDevicePorta]' value='' /></td>" +
			 	            	"<td style='width: 349px;'><input class='observe' id='DescricaoDevicePorta_"+i+"' name='portas["+i+"][DescricaoDevicePorta]' value='' onFocus='Foco(this,\"in\")'  onBlur='Foco(this,\"out\");' style='margin: 0px; width: 327px; outline: medium none; border-color: rgb(164, 164, 164); background-color: rgb(255, 255, 255);'/></td>" +
				 	            	"<td style='width: 349px;'>" +
				 	            		"<input class='observe' id='Observacao_"+i+"' name='portas["+i+"][Observacao]' value='' onFocus='Foco(this,\"in\")'  onBlur='Foco(this,\"out\");' style='margin: 0px; width: 327px; outline: medium none; border-color: rgb(164, 164, 164); background-color: rgb(255, 255, 255);' />" +
				 	            	"</td>" +
				 	            	"<td>" +
				 	            		"<select class='observe' id='Disponivel_"+i+"' name='portas["+i+"][Disponivel]' onFocus='Foco(this,\"in\")'  onBlur='Foco(this,\"out\");' style='margin: 0px; width: 80px; outline: medium none; border-color: rgb(164, 164, 164); background-color: rgb(255, 255, 255);'>" +
					 	            	 	"<option value='1'>Sim</option>" +
				 	            			"<option value='2'>Não</option>" +
			 	            			"</select>" +
				 	            	"</td>" +
			 	               "</tr>";
		 	       }
		 	       html +=    "<tr class='tableListarTitleCad'>" +
		 	     		"<td id='totalVencimentos' class='tableListarEspaco'>Total: "+(i - 1)+"</td>" +
		 	     				"<td></td>" +
		 	     				"<td></td>" +
		 	     				"<td></td>" +
		 	     		"</tr>" +
		 	     		"</table>" +
		 	     		"</div>";
		 	       $(".portas").append(html);
			}else{
				    var html = "";
				    var totalLinhas = $(".tableListarCad tr").length - 2;
				    var ultinaLina = $(".tableListarCad tr:last").prev().index();
				    if($(".tableListarCad tr[class^=esc]").length){
				    	ultinaLina = ultinaLina - $(".tableListarCad tr[class^=esc]").length;
				    	totalLinhas = totalLinhas - $(".tableListarCad tr[class^=esc]").length;
				    }
				    
				    if(QuantiPortas > totalLinhas){
				    	var quant = QuantiPortas - totalLinhas;
					    ultinaLina++;
				    	for(ultinaLina; ultinaLina <= QuantiPortas; ultinaLina++){
				 	    	   if(ultinaLina%2 == 0){
				 	    		   var cor = "style='background-color: rgb(226, 231, 237);'";
				 	    	   }else{
				 	    		   var cor = '';
				 	    	   }
				 	    	  html +=  "<tr "+cor+">" +
			 	          		"<td style='width: 70px; text-align: center;'>" + ultinaLina +"<input type='hidden' name='portas["+ultinaLina+"][IdDevicePorta]' value='' /></td>" +
				 	            	"<td style='width: 349px;'><input class='observe' id='DescricaoDevicePorta_"+ultinaLina+"' name='portas["+ultinaLina+"][DescricaoDevicePorta]' value='' onFocus='Foco(this,\"in\")'  onBlur='Foco(this,\"out\");' style='margin: 0px; width: 327px; outline: medium none; border-color: rgb(164, 164, 164); background-color: rgb(255, 255, 255);'/></td>" +
					 	            	"<td style='width: 349px;'>" +
					 	            		"<input class='observe' id='Observacao_"+ultinaLina+"' name='portas["+ultinaLina+"][Observacao]' value='' onFocus='Foco(this,\"in\")'  onBlur='Foco(this,\"out\");' style='margin: 0px; width: 327px; outline: medium none; border-color: rgb(164, 164, 164); background-color: rgb(255, 255, 255);' />" +
					 	            	"</td>" +
					 	            	"<td>" +
					 	            		"<select class='observe' id='Disponivel_"+ultinaLina+"' name='portas["+ultinaLina+"][Disponivel]' onFocus='Foco(this,\"in\")'  onBlur='Foco(this,\"out\");' style='margin: 0px; width: 80px; outline: medium none; border-color: rgb(164, 164, 164); background-color: rgb(255, 255, 255);'>" +
						 	            	 	"<option value='1'>Sim</option>" +
					 	            			"<option value='2'>Não</option>" +
				 	            			"</select>" +
					 	            	"</td>" +
				 	               "</tr>";
				 	       }
						$("#totalVencimentos").text("Total: "+QuantiPortas);
						$(".tableListarCad tr:last").prev().after(html);
				    }else{
				    	teste = totalLinhas - QuantiPortas;
				    	var contRemove = 0;
				    	for(var i = ultinaLina; i > QuantiPortas; i--){
				    		if($(".tableListarCad tr:eq("+i+")").find("td:first").find(":input").val().trim() == ""){
				    			$(".tableListarCad tr:eq("+i+")").find("td:first").next().find(":input").parent().parent().remove();
				    		}else{
				    			$(".tableListarCad tr:eq("+i+")").attr("class", "esconde");
				    			$(".tableListarCad tr:eq("+i+")").find("td").children(":input").each(function(index){
				    				var name = $(this).attr("name").replace("portas", "remove");
				    				$(this).removeAttr("name");
				    				$(this).attr("name", name);
				    				
				    			});
				    		}
				    	}
				    }
				    
			}
		}
	}
})(jQuery);


/*(function($){
  $.fn.extend({
	  FormObserve: function(){
		  fs = $(this);
		  fs.each(function(){
			  node = $(this);
			  v = node.find(":input");
			  node.FormObserve_Save();
			  
			  setInterval(function(){
				  v.each(function(){
					  campo = $(this);
					  if(campo.attr("class") != null){
						  if(campo.val() != this[campo.attr("id")] && campo.attr("class").indexOf("observe") != -1){
							  campo.attr("name", "portas["+campo.attr("id")+"]");
						  }
						  else if(campo.val() == this[campo.attr("id")] && campo.attr("class") == "observe")
							  campo.removeAttr("name");
					  }
					});
			  },1);
		  });
	  },
	  FormObserve_Save: function(){
		 node = $(this);
		 if(node.is("form")){
			 node.find(":input").each(function(){
				 if($(this).attr("id") != null){
					 this[$(this).attr("id")] = $(this).val();
				 }
			 });
		 }
	  }
  });
})(jQuery);*/