<?
class CorreiosWebService
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
   * Método que recupera valor do atributo $encomendas
   *
   * @access public
   * @param string $name
   * @return Encomenda
   */
  public function __get( $name )
  {
	try
	{
		$this->validation( $name, "Erro em ". __FUNCTION__.", linha ".__LINE__.": A encomenda ". $name . "não deu entrada em nosso sistema.");		
		return $this->encomendas[ $name ];
	}
	catch(Exception $error )
	{
		throw new $error;
	}//catch
	
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
   * Método que recupera url de acesso ao webservice dos Correios.
   *
   * @access private
   * @param Encomenda $encomenda
   * @return Encomenda
   */
  private function processUrl( Encomenda &$encomenda )
  {
	  $encomenda->url = ( CorreiosWebService::URLBASE."?".$encomenda->getParam() );
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
   * Método que verifica se uma encomenda existe.
   *
   * @access private
   * @param string $name
   * @param string $msg_error
   * @throws Exception 
   * @return void
   */
  private function validation( $name, $msg_error )
  {
	if( !array_key_exists( $name, $this->encomendas ) )
		 throw new Exception( $msg_error );

  }//function 
  
}//class
