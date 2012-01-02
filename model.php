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
			$this->validation( $name, "Erro em ". __FUNCTION__ .", linha ". __LINE__ .": O atributo '". $name ."', não é padrão do sistema." );
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
			$this->validation( $name, "Erro em ". __FUNCTION__ .", linha ". __LINE__ .": O atributo '". $name ."', não é padrão do sistema." );
			return $this->object[ $name ]['value'];
		}
		catch(Exception $error )
		{
			throw $error;		
		}//try
	}//function

	abstract protected function getParam();
	
	/**
     * Método que verifica se $name do atributo existe em $object.
	 *
	 * @access private
	 * @param string $msg_error
     * @throws Exception
	 * @return void
     */
	protected function validation( $name, $msg_error)
	{
		if( !array_key_exists($name, $this->object ) )
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

}//class
