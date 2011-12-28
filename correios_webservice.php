<?
require_once "model.php";
/*
http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&sCepOrigem=719393
60&sCepDestino=72151613&nVlPeso=1&nCdFormato=1&nVlComprimento=20&nVlAltura=5&nVlLargura=15
&sCdMaoPropria=s&nVlValorDeclarado=200&sCdAvisoRecebimento=n&nCdServico=41106&nVlDiametro=0
&StrRetorno=xml

http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&sCepOrigem=71939360
&sCepDestino=72151613&StrRetorno=xml&nCdFormato=1&nVlPeso=30&VlComprimento=30&nVlAltura=10
&nVlLargura=40&nVlDiametro=60&CdServico=40045


*/
class CorreiosWebService extends Model
{
	/**
     * Url base para acesso ao webservice dos Correios:
     */	
	const URLBASE = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx";
	
	/**
     * Códigos de serviços dos Correios:
     */
	const PAC_SEM_CONTRATO = 41106,
		  PAC_COM_CONTRATO = 41068,
		  SEDEX_SEM_CONTRATO = 40010,
		  SEDEX_A_COBRAR_SEM_CONTRATO = 40045,
		  SEDEX_A_COBRAR_COM_CONTRATO = 40126,
		  SEDEX_DEZ_SEM_CONTRATO = 40215,
		  SEDEX_HOJE_SEM_CONTRATO = 40290,
		  SEDEX_COM_CONTRATO_A = 40096,
		  SEDEX_COM_CONTRATO_B = 40436,
		  SEDEX_COM_CONTRATO_C = 40444,
		  SEDEX_COM_CONTRATO_D = 40568,
		  SEDEX_COM_CONTRATO_E = 40606,
		  E_SEDEX_COM_CONTRATO = 81019,
		  E_SEDEX_COM_CONTRATO_GRUPO_UM = 81868,
		  E_SEDEX_COM_CONTRATO_GRUPO_DOIS = 81833,
		  E_SEDEX_COM_CONTRATO_GRUPO_TRES = 81850;

  /**
   * @access private
   * @var array
   */
  private $encomendas = array();
 
  /**
   * @access private
   * @var int
   */ 
  private $qtd_encomendas = 0;
  
  /** 
   * @access protected
   * @var array
   */
  protected $object = array(
		/***
         * O preenchimento desses atributos são brigatórios:
         */
		"retorno"=>array("value"=>"xml",
						 "name"=>"StrRetorno",
						 "required"=>true
		),
		
		"destino"=>array("value"=>null,
						 "name"=>"sCepDestino",
						 "required"=>true
		),

		"origem"=>array("value"=>null,
						"name"=>"sCepOrigem",
						"required"=>true
		 ),
		
		/***
         * O preenchimento desses atributos são livres:
         */
		"senha"=>array("value"=>"",
					   "name"=>"sDsSenha",
					   "required"=>false
		),
		"cod_empresa"=>array("value"=>"",
							 "name"=>"nCdEmpresa",
							 "required"=>false
		)
  );
  
  /**
   * Método construtor da classe.
   *
   * @access public
   * @param void void
   * @return void
   */
  public function CorreiosWebService( $param = null )
  {
		$param = (array) $param;
			
		foreach( $this->object as $key => $params )
		{
			if( !isset($param[ $key ]) && $params['required'] )
				throw new Exception("Erro ". __FUNCTION__ ."linha:". __LINE__ .", o atributo ". $key ." deve ser informado.");
			
			if( array_key_exists( $key, $param ) && isset($param[ $key ]))
				$this->object[ $key ]["value"] = $param[ $key ];
		}//foreach

  }//function

  /**
   * Método que adiciona novas encomendas ao webservice.
   *
   * @access public
   * @param Encomenda $encomenda
   * @return CorreiosWebService object
   */
  public function add( Encomenda $encomenda )
  { 
	 $this->encomendas[ $this->getIndex() ] = $this->processUrl( $encomenda );
	 $this->qtd_encomendas++;
	 return $this;
  }//function
  
  /**
   * Método que recupera quantidade de encomendas existem no webservice.
   *
   * @access public
   * @return int
   */
  public function count()
  {		
	return $this->qtd_encomendas; 
  }//function
 
  /**
   * Método que verifica se uma encomenda existe.
   *
   * @access private
   * @param string $name
   * @param string $msg_error
   * @throws Exception 
   * @return void
   */ 
  protected function validation($name, $msg_error)
  {
  	if( !array_key_exists($name, $this->encomendas ) )
			throw new Exception( $msg_error );
  }//function

  /**
   * Método que recupera valor do atributo $encomendas
   *
   * @access public
   * @param string $name
   * @return Encomenda
   */ 
  public function filter( $name )
  {
	try
	{		
		$this->validation( $name, "Erro em ". __FUNCTION__.", linha ".__LINE__.": A encomenda ". $name . "não deu entrada em nosso sistema.");
		return $this->encomendas[ $name ];
	}
	catch(Exception $error)
    {
		throw new $error;
	}//try

  }//function

  /**
   * Método que recupera uma posição na fila de encomendas.
   *
   * @access private
   * @param void void
   * @return string
   */
  private function getIndex()
  {
	return (string) str_replace("XXX", ( $this->count() + 1 ), "encomendaXXX");
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
			
			$data[ $parameter['name'] ] = $parameter['value'];

		}//foreach
		
		$data = array_reverse( $data );
		return (string) "?".http_build_query( $data );
  }//function

  /**
   * Método que recupera url de acesso ao webservice dos Correios.
   *
   * @access private
   * @param Encomenda $encomenda
   * @return Encomenda
   */
  private function processUrl( Encomenda &$encomenda )
  {
	  $encomenda->url = trim( CorreiosWebService::URLBASE.$this->getParam()."&".$encomenda->getParam() );
	  return $encomenda;
  }//function
  
  /**
   * Método que apaga todas as encomendas da fila.
   *
   * @access public
   * @param string $encomenda
   * @return bool true||false
   */
  public function delete( $encomenda = "" )
  {
	$encomenda = trim( $encomenda );
	$qtd_encomendas = 0;	
	
	if( !$encomenda )
		$this->encomendas = array();
	else
	{
		try
		{
			$this->validation( $encomenda, "Erro em ". __FUNCTION__ .", linha " .__LINE__ .": Encomenda não deu entrada no webservice." );			
			$this->encomendas[ $encomenda ] = null;
			$qtd_encomendas = $this->qtd_encomendas;
			$qtd_encomendas--;
		}
		catch( Exception $error )
		{
			throw $error;
			return false;
		}
	}//if
	
	$this->qtd_encomendas = (int) $qtd_encomendas;
	return true;
  }//function
  
    
  /**
   * Método que processa encomenda.
   * 
   * @access public
   * @param void void
   * @return bool true||false
   */
  public function processEncomendas()
  {
		foreach( $this->encomendas as $encomenda )
		{
			$xml = $this->accessServer( $encomenda->url );
			
			echo $xml;
			break;	
		}//function
	 
		#return true;
  }//function
 
  private function accessServer( $url = "" )
  {
	 /*$handle = curl_init( $url );
			   curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);*/
			   
				echo $url;
				echo "\n";
				exit;
			  // try
			   //{
					include_once "local_settings.php";

					//if( !include_once "local_settings.php" )
						//throw new Exception( "" );
					
					/*curl_setopt($handle, CURLOPT_PROXY, "http://".PROXY);
			  		curl_setopt($handle, CURLOPT_PROXYPORT, PORT);
			   		curl_setopt($handle, CURLOPT_PROXYUSERPWD, USER.":".PASSWORD);
				//}
				//catch( Exception $error ){}

	$xml = curl_exec( $handle ); 
  		   curl_close( $handle ); 
	
	return $xml;*/
	
  }//function

}//class
