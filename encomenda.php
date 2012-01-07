<?
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
												 "required"=>true,
												 "url"=>true,
												 "rule"=>"/\d{1}/"
								),
								"peso"=>array("value"=>null, 
											  "name"=>"nVlPeso",
											  "required"=>true,
											  "url"=>true,
											  "rule"=>"/\d/"
								),
								"comprimento"=>array("value"=>null, 
													 "name"=>"nVlComprimento",
													 "required"=>true,
												 	 "url"=>true,
													 "rule"=>""
								),
								"altura"=>array("value"=>null, 
												"name"=>"nVlAltura",
												"required"=>true,
												"url"=>true,
												"rule"=>""
								),
								"largura"=>array("value"=>null, 
												 "name"=>"nVlLargura",
												 "required"=>true,
												 "url"=>true,
											     "rule"=>""
								),
								"diametro"=>array("value"=>null, 
												  "name"=>"nVlDiametro",
												  "required"=>true,
												  "url"=>true,
												  "rule"=>""
								),
								"codigo"=>array("value"=>null,
												"name"=>"nCdServico",
												"required"=>true,
												"url"=>true,
												"rule"=>"/\d{5}/"
								),
							    
                          /***
						   * O preenchimento desses atributos são livres:						
                           */
								"valor"=>array("value"=>0,
											   "name"=>"Valor",
											   "required"=>false,
											   "url"=>false,
											   "rule"=>""
								),
								"valor_mao_propria"=>array("value"=>0,
											   			   "name"=>"ValorMaoPropria",
														   "required"=>false,
														   "url"=>false,
														   "rule"=>""
								),
								"valor_aviso_recebimento"=>array("value"=>0,
																 "name"=>"ValorAvisoRecebimento",
																 "required"=>false,
																 "url"=>false,
																 "rule"=>""
								),
								"prazo_entrega"=>array("value"=>0,
													   "name"=>"PrazoEntrega",
													   "required"=>false,
												 	   "url"=>false,
													   "rule"=>""
								),
								"mao_propria"=>array("value"=>false,
													 "name"=>"sCdMaoPropria",
													 "required"=>false,
												 	 "url"=>true,
													 "rule"=>""
								),
								"aviso_recebimento"=>array("value"=>false,
														   "name"=>"sCdAvisoRecebimento",
														   "required"=>false,
														   "url"=>true,
														   "rule"=>""
								),
								"valor_declarado"=>array("value"=>0,
														 "name"=>"nVlValorDeclarado",
														 "required"=>false,
														 "url"=>true,
														 "rule"=>""
								),
								"entrega_domiciliar"=>array("value"=>false,
															"name"=>"EntregaDomiciliar",
															"required"=>false,
														    "url"=>false,
															"rule"=>""

								),
								"entrega_sabado"=>array("value"=>false,
														"name"=>"EntregaSabado",
														"required"=>false,
												 		"url"=>false,
													    "rule"=>""
								),
								"url"=>array("value"=>"",
											 "name"=>"url",
											 "required"=>false,
											 "url"=>false,
											 "rule"=>""
								),
								"erro"=>array("value"=>0,
											  "name"=>"Erro",
											  "required"=>false,
											  "url"=>false,
											  "rule"=>""
								),
							    "msg_erro"=>array("value"=>"",
												  "name"=>"MsgErro",
												  "required"=>false,
												  "url"=>false,
												  "rule"=>""
								)
		);
	
	/**
     * Método que recupera valor do atributo $param.
	 *
	 * @access public
     * @param void void
	 * @return string
	 */
	public function getParam()
	{
		$data = array();		
		foreach( $this->object as  $key => $parameter )
		{
			if( $parameter['required'] && is_null( $parameter['value'] ) )
				throw new Exception( "Erro em ". __FUNCTION__ .", linha ". __LINE__ .": O parâmetro ".$key.", deve ser informado." );

			/**
			 * Monta url.
             */
			elseif( $parameter['url'] )
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
	 * Método que atributo n códigos a uma mesma encomenda.
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
		
		if( !preg_match( $this->object['codigo']['rule'], $value ))
			throw new Exception( "Erro em ".__FUNCTION__.", linha ".__LINE__.": Código informado não é valido." );
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
