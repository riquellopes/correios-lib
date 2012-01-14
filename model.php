<?php
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
			$name = strtolower( $name );
			
			$this->validation( $name, 
							   $value );
			
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
			$name = strtolower( $name );
						
			$this->objectExist( $name, 
								$this->object );
			
			return $this->object[ $name ]['value'];
		}
		catch(Exception $error )
		{
			throw $error;		
		}//try
	}//function

	abstract protected function getParam();
	
	/**
     * Método que verifica se $value respeita as regras do $object.
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
		$this->objectExist( $name, $this->object );
		
		/***
		 * Recupera objeto::
		 */
		$object = $this->object[ $name ];
		
		/***
		 * Define variavel $exception como false;
		 */
		$exception = false;
		
		/***
		 * Caso um $msg_error não seja passada por parâmetro,
		 * o sistema verifica se objeto possui uma mensagem
		 * configurada, se não exister ele passa um valor em
		 * branco para o Exception::
         */
		$msg_error = ( empty( $msg_error ) ? isset($object['message']) ? $object['message'] : "" : $msg_error );

		/***
		 * Se uma regra não tiver sido definida para o objeto,
		 * o sistema vai ignorar o $value passado::
		 */
		$rule = ( isset( $object['rule'] ) ? $object['rule'] : null );
		
		/***
		 * Se o regra para validação a ser usada para o value seja
		 * uma função do php, funçao deve ignorar a função preg_math::
		 */
		if( !is_null( $rule ) )
		{
			if( function_exists( $rule ) && in_array($rule, array("is_bool") ) )
			{
				if( !call_user_func( $rule, $value ) )
					$exception=true;
			}
			elseif( !preg_match( $rule,  $value) )
				$exception=true;
		}//if
		
		if( $exception )
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
		/***
		 * Caso nenhuma mensagem de erro seja passada por parâmetro,
		 * objectExist deve usar sua mensagem default::
		 */		
		$msg_error = $msg_error ? $msg_error : "Erro em ". __FUNCTION__ .", linha ". __LINE__ .": O atributo '". $key ."', não existe no sistema.";		
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