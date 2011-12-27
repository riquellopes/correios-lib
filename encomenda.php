<?
class Encomenda
{
	private $object = array(
							/***
                             * Obrigatórios:
							 */  							
								"formato"=>array("value"=>null, 
												 "name"=>"nCdFormato",
												 "required"=>true
								),
								"peso"=>array("value"=>null, 
											  "name"=>"nVlPeso",
											  "required"=>true
								),
								"comprimento"=>array("value"=>null, 
													 "name"=>"VlComprimento",
													 "required"=>true
								),
								"altura"=>array("value"=>null, 
												"name"=>"nVlAltura",
												"required"=>true,
								),
								"largura"=>array("value"=>null, 
												 "name"=>"nVlLargura",
												 "required"=>true
								),
								"diametro"=>array("value"=>null, 
												  "name"=>"nVlDiametro",
												  "required"=>true
								),
								"codigo"=>array("value"=>null,
												"name"=>"Codigo",
												"required"=>true
								),
							    
                          /***
						   * Livres / Preenchidos pelo sistema:								
                           */
								"valor"=>array("value"=>0,
											   "name"=>"Valor",
											   "required"=>false
								),
								"prazo_entrega"=>array("value"=>0,
													   "name"=>"PrazoEntrega",
													   "required"=>false
								),
								"valor_mao_propria"=>array("value"=>0,
														   "name"=>"ValorMaoPropria",
														   "required"=>false
								),
								"valor_aviso_recebimento"=>array("value"=>0,
																 "name"=>"ValorAvisoRecebimento",
																 "required"=>false
								),
								"valor_declarado"=>array("value"=>0,
														 "name"=>"ValorValorDeclarado",
														 "required"=>false
								),
								"entrega_domiciliar"=>array("value"=>false,
															"name"=>"EntregaDomiciliar",
															"required"=>false,
								),
								"entrega_sabado"=>array("value"=>false,
														"name"=>"EntregaSabado",
														"required"=>false
								),
								"url"=>array("value"=>"",
											 "name"=>"url",
											 "required"=>false
								),
								"erro"=>array("value"=>0,
											  "name"=>"Erro",
											  "required"=>false)
		);
	
	/**
     * Método que verifica se $name do atributo existe.
	 *
	 * @access private
	 * @param string $msg_error
     * @throws Exception
	 * @return void
     */	
	private function validation( $name, $msg_error )
	{
		if( !array_key_exists($name, $this->object ) )
			throw new Exception( $msg_error );	
	}//function
		
	/**
     * Método que define valor do atributo $object.
	 * 
     * @access public
	 * @param string $name
     * @param mix $value
     * @return object
	 */
	public function __set($name, $value)
	{
		try
		{
			$this->validation( $name, "Erro em ". __FUNCTION__ .", linha ". __LINE__ .": O atributo '". $name ."', não é padrão da encomenda." );
			$this->object[ $name ]['value'] = $value;
			return $this;			
		}
		catch( Exception $error )
		{
			throw $error;
		}//try

		
	}//function
	
	/**
     * Método que recupera valor do atributo $object
	 * 
	 * @access public
	 * @param string $name
	 * @return mix
     */
	public function __get( $name )
	{
		try
		{		
			$this->validation( $name, "Erro em ". __FUNCTION__ .", linha ". __LINE__ .": O atributo '". $name ."', não é padrão da encomenda." );
			return $this->object[ $name ]['value'];
		}
		catch(Exception $error )
		{
			throw $error;		
		}//try

	}//function
	
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
			elseif( $parameter['required'] )
			{
				$data[ $parameter['name'] ] = $parameter['value'];
			}//if

		}//foreach
		
		return (string) http_build_query( $data );
	}//function
	
    /**
     * Método que faz o parse dos atributos da class para o formato JSON.
	 *
     * @access public
     * @param void void
     * @return json
     */
	public function toJson()
	{
		$data = array();		
		foreach( $this->object as $key => $parameter )
			$data[ $key ] = $parameter[ "value" ];
		
		return json_encode( $data );
	}//function
	
}//class
