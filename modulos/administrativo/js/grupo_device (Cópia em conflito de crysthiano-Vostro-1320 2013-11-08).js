jQuery(document).ready(function(){
	
	var $j = jQuery.noConflict();
	
	//Pega os valores passados pela url
	var url = window.location.search.replace("?", "");
	
	//alert(url);
	
	//Se a variavel url possui valor executa a condição logo abaixo
	if(url != ""){
		
		//var tipoMensagem = url.substr(5, 1);
		
		url = decodeURI(url);
		//alert(url);
		
		//url = JSON.parse(url);
		url = $j.parseJSON(url);
		//alert(url.Tipo);
		if(url.Tipo == 3 || url.Tipo == 4 || url.Tipo == 6 || typeof url.Tipo == 'undefined'){
			$j("input[name=bt_inserir]").attr("disabled", "disabled");
			$j("input[name=bt_alterar]").removeAttr("disabled");
			$j("input[name=bt_excluir]").removeAttr("disabled");
		}else if(url.Tipo == 7){
			mensagens(7);
		}
			
			if(url.Tipo != "D"){
				//alert(decodeURI(url));
				$j.ajax({
					dataType: "json",
					type: "POST",
					url: "xml/grupo_device.php",
					data: {dadosWhere: url},
					beforeSend: function(){
						carregando(true);
					},
					success: function(data){
							//alert(data);
							for(var i in data){
								if(data[i] != null){
									if($j("#"+i).attr("id") == i){
										$j("#"+i+" option").each(function(index){
											if($j(this).val() == data[i]){
												$j(this).attr("selected", "selected");
											}
										});
									}
									$j("#"+i).val(data[i]);
								}
							}
						
					},
					complete: function(data){
						carregando(false);
						mensagens(url.Tipo);
					}
				});
		}
		}
	
	$j("#IdGrupoDevice").change(function(){
		var id = $j(this).val();
		var IdLoja = $j("#IdLoja").val();
		if(id != ""){
			var url = {
						IdGrupoDevice: id,
						IdLoja: IdLoja
			};
			
			location.href = "cadastro_grupo_device.php?" + JSON.stringify(url);
		}
	});
	
	$j("#form").submit(function(e){
		var cont = 0;
		
		mensagens(0);
		$j(".obrig").each(function(index){
			if($j(this).is(":input")){
				if($j(this).val() == ""){
					$j(this).focus();
					mensagens(1);
					cont = 1;
					e.preventDefault();
					return false;
				}
			}
		});
	});
	
	$j(".ordenar").click(function(){
		
		order = $j(this).attr('id');
		filtro = $j("#filtro").serialize();
		if($j("#tipoOrdenacao").val() == "DESC"){
			tipoOrder = "ASC";
		}else if($j("#tipoOrdenacao").val() == "ASC"){
			tipoOrder = "DESC"
		}
		
		location.href = "listar_grupo_device.php?order="+order+"&tipoOrder="+tipoOrder+"&"+filtro;
	});
	
	$j(".excluir").click(function(){
		var codigo = $j(this).attr("id").split("_");
		//alert(codigo[1]);
		var arrayId = {
				IdGrupoDevice: codigo[1]
		};
		var tipoOperacao = "Excluir";
		if(confirm("ATENCAO!\n\nVoce esta prestes a excluir um endereco.\nDeseja continuar?","SIM","NAO")){
			$j.ajax({
				dataType: "html",
				type: "POST",
				data: {dados: arrayId, bt_excluir: tipoOperacao, pageTipo: 'listar'},
				url: "files/inserir/inserir_grupo_device.php",
				success: function(data){
					//alert(data);
					location.href = "listar_grupo_device.php";
				}
			});
		}
	});
	
	$j('body').addTabindex();
});

(function($){
	$.fn.addTabindex = function(){
		$(':input').each(function(index){
			$(this).prop('tabindex', index);
		})
	}
})(jQuery);