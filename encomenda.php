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
												"name"=>"CdServico",
												"required"=>true
								),
							    
                          /***
						   * O preenchimento desses atributos são livres:						
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
