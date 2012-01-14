<?php
require_once "model.php";

class Encomenda extends Model
{
   /** 
    * @access private
    * @var array
    */	
	protected $object = array(
							/***
                             * O preenchimento desses atributos são brigatórios:
							 */  							
								"formato"=>array("value"=>null, 
												 "name"=>"nCdFormato",
												 "rule"=>"/[1-2]{1}/",
												 "message"=>"O formato da encomenda só pode ser 1 ou 2.",
												 "url"=>true
								),
								"peso"=>array("value"=>null, 
											  "name"=>"nVlPeso",
											  "rule"=>"/\d/",
											  "message"=>"O peso da encomenda, não um peso valido.",
											  "url"=>true
								),
								"comprimento"=>array("value"=>null, 
													 "name"=>"nVlComprimento",
													 "rule"=>"/\d/",
													 "message"=>"O comprimento da encomenda, não um comprimento valido.",
												 	 "url"=>true
								),
								"altura"=>array("value"=>null, 
												"name"=>"nVlAltura",
												"rule"=>"/\d/",
												"message"=>"A altura da encomenda, não uma altura valida.",
												"url"=>true
												
								),
								"largura"=>array("value"=>null, 
												 "name"=>"nVlLargura",
												 "rule"=>"/\d/",
											     "message"=>"A largura da encomenda, não uma largura valida.",
												 "url"=>true
								),
								"diametro"=>array("value"=>null, 
												  "name"=>"nVlDiametro",
												  "rule"=>"/\d/",
												  "message"=>"O diâmetro da encomenda, não um diâmetro valido.",
												  "url"=>true
								),
								"codigo"=>array("value"=>null,
												"name"=>"nCdServico",
												"rule"=>"/\d{5}/",
												"message"=>"O código da encomenda, não um código valido.",
												"url"=>true
												
								),
							    
                          /***
						   * O preenchimento desses atributos são livres:						
                           */
								"valor"=>array("value"=>0,
											   "name"=>"Valor",
											   "url"=>false,
											   "rule"=>"/\d/"
								),
								"valor_mao_propria"=>array("value"=>0,
											   			   "name"=>"ValorMaoPropria",
														   "url"=>false,
														   "rule"=>"/\d/"
								),
								"valor_aviso_recebimento"=>array("value"=>0,
																 "name"=>"ValorAvisoRecebimento",
																 "url"=>false,
																 "rule"=>"/\d/"
								),
								"prazo_entrega"=>array("value"=>0,
													   "name"=>"PrazoEntrega",
												 	   "url"=>false,
													   "rule"=>"/\d/"
								),
								"mao_propria"=>array("value"=>false,
													 "name"=>"sCdMaoPropria",
												 	 "url"=>true,
													 "rule"=>"is_bool"
								),
								"aviso_recebimento"=>array("value"=>false,
														   "name"=>"sCdAvisoRecebimento",
														   "url"=>true,
														   "rule"=>"is_bool"
								),
								"valor_declarado"=>array("value"=>0,
														 "name"=>"nVlValorDeclarado",
														 "url"=>true,
														 "rule"=>"/\d/"
								),
								"entrega_domiciliar"=>array("value"=>false,
															"name"=>"EntregaDomiciliar",
														    "url"=>false,
															"rule"=>"is_bool"

								),
								"entrega_sabado"=>array("value"=>false,
														"name"=>"EntregaSabado",
												 		"url"=>false,
													    "rule"=>"is_bool"
								),
								"url"=>array("value"=>"",
											 "name"=>"url",
											 "url"=>false#,
									         #"rule"=>"|^http(s)?://[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i"
								),
								"erro"=>array("value"=>0,
											  "name"=>"Erro",
											  "url"=>false,
											  "rule"=>"/\d/"
								),
							    "msg_erro"=>array("value"=>"",
												  "name"=>"MsgErro",
												  "url"=>false,
												  "rule"=>"/(\s*|\w)/"
								)
		);
	
	/**
     * Método que faz o parse dos atributos da encomenda para,
     * query string.
	 *
	 * @access public
     * @param void void
	 * @return string
	 */
	public function getParam()
	{
		$data = array();		
		foreach( $this->object as $key => $parameter )
		{
			$this->validation($key, 
						      $parameter['value']);
						
			/**
			 * Monta url.
             */
			if( $parameter['url'] )
			{
				$value = $parameter['value'];
				
				/**
                 * Caso o value seja do tipo bool, ele convertido para String:
                 * true => s
                 * false => n
                 */
				if( is_bool( $value ) )
					$value = $value ? "s" : "n";
		
				$data[ $parameter['name'] ] = $value;
			}//if

		}//foreach
		
		return (string) http_build_query( $data );
	}//function
	
	/***
	 * Método que atribui n códigos a uma mesma encomenda.
	 *
	 * @access public
	 * @param int $value
	 * @return object
	 */
	public function setNCodigos( $value )
	{
		$codigo = $this->object[ "codigo" ][ "value" ];
		
		if( !is_null( $codigo ) )
			$codigo = (array) explode(",", $codigo);
		
		$this->validation("codigo", $value);	
		$codigo[] = $value;
				
		$this->object[ "codigo" ][ "value" ] = implode(",", $codigo);
		return $this;
	}//function
	
	/**
	 * Método que verifica se a encomenda é do tipo multi-codigo.
	 *
	 * @access public
	 * @param void void
	 * @return bool true||false
	 */
	public function isMultCodigo()
	{
		return strlen( $this->codigo ) > 5;
	}//function
}//class