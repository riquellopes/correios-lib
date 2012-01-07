<?
require_once "PHPUnit/Framework.php";
require_once "encomenda.php";

class TestEncomenda extends PHPUnit_Framework_TestCase
{
	private $encomenda = null;

	public function setUp()
	{
		$this->encomenda = new Encomenda();
		$this->encomenda->set("formato", 1)
						->set("peso", 100)
						->set("comprimento", 60)
						->set("altura", 80)
						->set("largura", 80)
						->set("diametro", 100)
						->set("codigo", 40045);
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
		$this->assertEquals( $param,  "nCdFormato=1&nVlPeso=100&nVlComprimento=60&nVlAltura=80&nVlLargura=80&nVlDiametro=100&nCdServico=40045&sCdMaoPropria=n&sCdAvisoRecebimento=n&nVlValorDeclarado=0" );
	}//function
	
	public function test_os_parametros_passados_para_encomenda_2_deve_gerar_uma_url()
	{
		$encomenda = new Encomenda();
		$encomenda->set("formato", 2)
				  ->set("peso", 30)
				  ->set("comprimento", 30)
				  ->set("altura", 10)
				  ->set("largura", 40)
				  ->set("diametro", 60)
				  ->set("codigo", 40045)
				  ->set("valor_declarado", 200)
				  ->set("aviso_recebimento", true);
		
		$param = $encomenda->getParam();
		$this->assertTrue( is_string( $param ) );
		$this->assertEquals( $param,  "nCdFormato=2&nVlPeso=30&nVlComprimento=30&nVlAltura=10&nVlLargura=40&nVlDiametro=60&nCdServico=40045&sCdMaoPropria=n&sCdAvisoRecebimento=s&nVlValorDeclarado=200" );

		unset( $encomenda );
	}//function
	
	public function test_to_json_encomenda1()
	{
		$encomenda = new Encomenda();
		$encomenda->set("formato", 2)
				  ->set("peso", 30)
				  ->set("comprimento", 30)
				  ->set("altura", 10)
				  ->set("largura", 40)
				  ->set("diametro", 60)
				  ->set("codigo", 40045);
		
		$json = $encomenda->toJson();
		$this->assertEquals( $json, '{"formato":2,"peso":30,"comprimento":30,"altura":10,"largura":40,"diametro":60,"codigo":40045,"valor":0,"valor_mao_propria":0,"valor_aviso_recebimento":0,"prazo_entrega":0,"mao_propria":false,"aviso_recebimento":false,"valor_declarado":0,"entrega_domiciliar":false,"entrega_sabado":false,"url":"","erro":0,"msg_erro":""}');
	}//function
	
	public function test_to_json_encomenda2()
	{
		$encomenda = new Encomenda();
		$encomenda->set("formato", 1)
				  ->set("peso", 100)
				  ->set("comprimento", 50)
				  ->set("altura", 100)
				  ->set("largura", 50)
				  ->set("diametro", 100)
				  ->set("codigo", 40045)
				  ->set("aviso_recebimento", true)
				  ->set("mao_propria", true);
		
		$json = $encomenda->toJson();
		$this->assertEquals( $json, '{"formato":1,"peso":100,"comprimento":50,"altura":100,"largura":50,"diametro":100,"codigo":40045,"valor":0,"valor_mao_propria":0,"valor_aviso_recebimento":0,"prazo_entrega":0,"mao_propria":true,"aviso_recebimento":true,"valor_declarado":0,"entrega_domiciliar":false,"entrega_sabado":false,"url":"","erro":0,"msg_erro":""}');
	}//function
	
	public function test_caso_seja_necessario_usuario_pode_setar_mais_de_um_codigo()
	{
		$encomenda = new Encomenda();
		$encomenda->set("formato", 1)
				  ->set("peso", 100)
				  ->set("comprimento", 50)
				  ->set("altura", 100)
				  ->set("largura", 50)
				  ->set("diametro", 100)
				  ->set("aviso_recebimento", true)
				  ->set("mao_propria", true)
				  ->setNCodigos(40045)
				  ->setNCodigos(40436);
		
		$this->assertEquals( $encomenda->get("codigo"), "40045,40436");
		$this->assertTrue( $encomenda->isMultCodigo() );
	}//function

	public function test_caso_multiplos_codigos_de_encomenda_nao_sejam_validos_deve_haver_exception()
	{
		$this->setExpectedException( "Exception" );
		$encomenda = new Encomenda();
		$encomenda->set("formato", 1)
				  ->set("peso", 100)
				  ->set("comprimento", 50)
				  ->set("altura", 100)
				  ->set("largura", 50)
				  ->set("diametro", 100)
				  ->set("aviso_recebimento", true)
				  ->set("mao_propria", true)
				  ->setNCodigos(40)
				  ->setNCodigos("dgkfjkj");
	}//function
}//class
