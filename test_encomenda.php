<?
require_once "PHPUnit/Framework.php";
require_once "encomenda.php";

class TestEncomenda extends PHPUnit_Framework_TestCase
{
	private $encomenda = null;

	public function setUp()
	{
		$this->encomenda = new Encomenda();
		$this->encomenda->__set("formato", 1)
						->__set("peso", 100)
						->__set("comprimento", 60)
						->__set("altura", 80)
						->__set("largura", 80)
						->__set("diametro", 100)
						->__set("codigo", 40045);
	}//function
	
	public function tearDown()
	{
		$this->encomenda = null;	
	}//function

	public function test_cria_encomenda()
	{
		$this->assertTrue( $this->encomenda instanceof Encomenda );
	}//function
	
	public function test_parametros_nao_usados_devem_gerar_exception()
	{
		$this->setExpectedException('Exception');
		
		$encomenda = new Encomenda();
		$encomenda->espessura = 100;

		unset( $encomenda );
	}//function
	
	public function test_caso_sistema_nao_recupere_atributo_sistema_deve_gerar_exception()
	{
		$this->setExpectedException('Exception');
		
		$espessura = $this->encomenda->espessura;
	}//function

	public function test_caso_argumentos_obrigatorios_nao_sejam_passados_deve_haver_um_exception()
	{
		$this->setExpectedException('Exception');
		
		$encomenda = new Encomenda();
		$encomenda->getParam();
		
		unset( $encomenda );
	}//function
	
	public function test_os_parametros_passados_para_encomenda_1_deve_gerar_uma_url()
	{
		$param = $this->encomenda->getParam();
		
		$this->assertTrue( is_string( $param ) );
		$this->assertEquals( $param,  "nCdFormato=1&nVlPeso=100&VlComprimento=60&nVlAltura=80&nVlLargura=80&nVlDiametro=100&CdServico=40045" );
	}//function
	
	public function test_os_parametros_passados_para_encomenda_2_deve_gerar_uma_url()
	{
		$encomenda = new Encomenda();
		$encomenda->__set("formato", 2)
				  ->__set("peso", 30)
				  ->__set("comprimento", 30)
				  ->__set("altura", 10)
				  ->__set("largura", 40)
				  ->__set("diametro", 60)
				  ->__set("codigo", 40045);
		
		$param = $encomenda->getParam();
		$this->assertTrue( is_string( $param ) );
		$this->assertEquals( $param,  "nCdFormato=2&nVlPeso=30&VlComprimento=30&nVlAltura=10&nVlLargura=40&nVlDiametro=60&CdServico=40045" );

		unset( $encomenda );
	}//function
	
	public function test_to_json_encomenda1()
	{
		$encomenda = new Encomenda();
		$encomenda->__set("formato", 2)
				  ->__set("peso", 30)
				  ->__set("comprimento", 30)
				  ->__set("altura", 10)
				  ->__set("largura", 40)
				  ->__set("diametro", 60)
				  ->__set("codigo", 40045);
		
		$json = $encomenda->toJson();
		$this->assertEquals( $json, '{"formato":2,"peso":30,"comprimento":30,"altura":10,"largura":40,"diametro":60,"codigo":40045,"valor":0,"prazo_entrega":0,"valor_mao_propria":0,"valor_aviso_recebimento":0,"valor_declarado":0,"entrega_domiciliar":false,"entrega_sabado":false,"url":"","erro":0}');
	}//function
	
	public function test_to_json_encomenda2()
	{
		$encomenda = new Encomenda();
		$encomenda->__set("formato", 1)
				  ->__set("peso", 100)
				  ->__set("comprimento", 50)
				  ->__set("altura", 100)
				  ->__set("largura", 50)
				  ->__set("diametro", 100)
				  ->__set("codigo", 40045);
		
		$json = $encomenda->toJson();
		$this->assertEquals( $json, '{"formato":1,"peso":100,"comprimento":50,"altura":100,"largura":50,"diametro":100,"codigo":40045,"valor":0,"prazo_entrega":0,"valor_mao_propria":0,"valor_aviso_recebimento":0,"valor_declarado":0,"entrega_domiciliar":false,"entrega_sabado":false,"url":"","erro":0}');
	}//function
}//class
