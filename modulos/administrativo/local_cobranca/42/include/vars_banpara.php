<?
	if($dadosboleto["agenciaVisual"] == ''){			$dadosboleto["agenciaVisual"] = $dadosboleto["agencia"];					} 
	if($dadosboleto["digito_agenciaVisual"] == ''){		$dadosboleto["digito_agenciaVisual"] = $dadosboleto["digito_agencia"];		}
	if($dadosboleto["contaVisual"] == ''){				$dadosboleto["contaVisual"] = $dadosboleto["conta"];						}
	if($dadosboleto["digito_contaVisual"] == ''){		$dadosboleto["digito_contaVisual"] = $dadosboleto["digito_conta"];			}

	$b = new boleto();		
	$b->banco_bradesco($dadosboleto);
?>