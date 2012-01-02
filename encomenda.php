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
												 "url"=>true
								),
								"peso"=>array("value"=>null, 
											  "name"=>"nVlPeso",
											  "required"=>true,
											  "url"=>true
								),
								"comprimento"=>array("value"=>null, 
													 "name"=>"nVlComprimento",
													 "required"=>true,
												 	 "url"=>true
								),
								"altura"=>array("value"=>null, 
												"name"=>"nVlAltura",
												"required"=>true,
												"url"=>true
								),
								"largura"=>array("value"=>null, 
												 "name"=>"nVlLargura",
												 "required"=>true,
												 "url"=>true
								),
								"diametro"=>array("value"=>null, 
												  "name"=>"nVlDiametro",
												  "required"=>true,
												  "url"=>true
								),
								"codigo"=>array("value"=>null,
												"name"=>"nCdServico",
												"required"=>true,
												"url"=>true
								),
							    
                          /***
						   * O preenchimento desses atributos são livres:						
                           */
								"valor"=>array("value"=>0,
											   "name"=>"Valor",
											   "required"=>false,
											   "url"=>false
								),
								"valor_mao_propria"=>array("value"=>0,
											   			   "name"=>"ValorMaoPropria",
														   "required"=>false,
														   "url"=>false
								),
								"valor_aviso_recebimento"=>array("value"=>0,
																 "name"=>"ValorAvisoRecebimento",
																 "required"=>false,
																 "url"=>false
								),
								"prazo_entrega"=>array("value"=>0,
													   "name"=>"PrazoEntrega",
													   "required"=>false,
												 	   "url"=>false
								),
								"mao_propria"=>array("value"=>false,
													 "name"=>"sCdMaoPropria",
													 "required"=>false,
												 	 "url"=>true
								),
								"aviso_recebimento"=>array("value"=>false,
														   "name"=>"sCdAvisoRecebimento",
														   "required"=>false,
														   "url"=>true
								),
								"valor_declarado"=>array("value"=>0,
														 "name"=>"nVlValorDeclarado",
														 "required"=>false,
														 "url"=>true
								),
								"entrega_domiciliar"=>array("value"=>false,
															"name"=>"EntregaDomiciliar",
															"required"=>false,
														    "url"=>false

								),
								"entrega_sabado"=>array("value"=>false,
														"name"=>"EntregaSabado",
														"required"=>false,
												 		"url"=>false
								),
								"url"=>array("value"=>"",
											 "name"=>"url",
											 "required"=>false,
											 "url"=>false
								),
								"erro"=>array("value"=>0,
											  "name"=>"Erro",
											  "required"=>false,
											  "url"=>false
								),
							    "msg_erro"=>array("value"=>"",
												  "name"=>"MsgErro",
												  "required"=>false,
												  "url"=>false
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
	
}//class
