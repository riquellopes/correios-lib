<?
abstract class Model
{
	protected $object = array();
	
	
	public function __construct()
	{
	
	}//function
	
	/**
     * Método que define valor do atributo $object.
	 * 
     * @access public
	 * @param string $name
     * @param mix $value
     * @return object
	 */
	public function __set( $name, $value)
	{
		try
		{
			$this->objectExist( $name, $this->object, "Erro em ". __FUNCTION__ .", linha ". __LINE__ .": O atributo '". $name ."', não existe no sistema." );
			#$this->validation( $name, $value, "Erro em ". __FUNCTION__ .", linha ". __LINE__ .": O atributo '". $name ."', não atende as regras do sistema." );
			$this->object[ $name ]['value'] = $value;
			return $this;			
		}
		catch( Exception $error )
		{
			throw $error;
		}//try
		
	}//function
	
	/**
     * Método que recupera o valor do atributo $object.
     *
     * @access public
     * @param string $name
     * @return mix
     */
	public function __get( $name )
	{
		try
		{		
			$this->objectExist( $name, $this->object, "Erro em ". __FUNCTION__ .", linha ". __LINE__ .": O atributo '". $name ."', não é padrão do sistema." );
			return $this->object[ $name ]['value'];
		}
		catch(Exception $error )
		{
			throw $error;		
		}//try
	}//function

	abstract protected function getParam();
	
	/**
     * Método que verifica se $value esta dentro das regras do $object.
	 *
	 * @access protected
	 * @param string $name
	 * @param mix $value
	 * @param string $msg_error
     * @throws Exception
	 * @return void
     */
	protected function validation( $name, $value=null, $msg_error="")
	{
		$object = $this->object[ $name ];
		if( !is_null( $object['rule'] ) && !preg_match( $object['rule'],  $value) )
			throw new Exception( $msg_error );
	}//function
	
	/**
     * Método que verifica se $key existe em $search.
	 *
     * @param string $key
     * @param array $search
	 * @param string $msg_error
	 * @throws Exception
	 * @return void
     */
	protected function objectExist( $key, $search, $msg_error="" )
	{
		if( !array_key_exists( $key, $search ) )
			throw new Exception( $msg_error );
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
	
	/**
	 * Método que cria uma mascara para o método __get.
	 * 
     * @access public
	 * @param string $name
	 * @return mix
     */
	public function get($name)
	{
		try
		{	
			return $this->__get( $name );
		}
		catch( Exception $error ){ throw $error; }
	}//function
	
	/**
	 * Método que cria uma mascara para o método __set.
	 * 
     * @access public
	 * @param string $name
     * @param mix $value
	 * @return object
     */
	public function set($name, $value)
	{
		try
		{ 
			$this->__set( $name, $value);
			return $this;
		}		
		catch( Exception $error ){ throw $error; }
	}//function
	
}//class
