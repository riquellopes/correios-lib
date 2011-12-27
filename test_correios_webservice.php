<?
require_once "PHPUnit/Framework.php";
require_once "correios_webservice.php";
require_once "encomenda.php";

class TestCorreiosWebService extends PHPUnit_Framework_TestCase
{
	private $correios = null;

	public function setUp()
	{
		$this->correios = new CorreiosWebService();
	}//function
	
	public function test_cria_web_service()
	{
		$this->assertTrue( $this->correios instanceof CorreiosWebService );	
	}//function

	public function test_verifica_url_base_existe()
	{
		$this->assertEquals( CorreiosWebService::URLBASE, "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx" );
	}//function
	
	public function test_codigo_de_servico_validos()
	{
		$this->assertEquals( CorreiosWebService::PAC_SEM_CONTRATO, 41106);		
		$this->assertEquals( CorreiosWebService::PAC_COM_CONTRATO, 41068);
		$this->assertEquals( CorreiosWebService::SEDEX_SEM_CONTRATO, 40010);
	    $this->assertEquals( CorreiosWebService::SEDEX_A_COBRAR_SEM_CONTRATO, 40045);		
		$this->assertEquals( CorreiosWebService::SEDEX_A_COBRAR_COM_CONTRATO, 40126);
		$this->assertEquals( CorreiosWebService::SEDEX_DEZ_SEM_CONTRATO, 40215);
		$this->assertEquals( CorreiosWebService::SEDEX_HOJE_SEM_CONTRATO, 40290);
		$this->assertEquals( CorreiosWebService::SEDEX_COM_CONTRATO_A, 40096);
		$this->assertEquals( CorreiosWebService::SEDEX_COM_CONTRATO_B, 40436);
		$this->assertEquals( CorreiosWebService::SEDEX_COM_CONTRATO_C, 40444);
		$this->assertEquals( CorreiosWebService::SEDEX_COM_CONTRATO_D, 40568);
		$this->assertEquals( CorreiosWebService::SEDEX_COM_CONTRATO_E, 40606);
		$this->assertEquals( CorreiosWebService::E_SEDEX_COM_CONTRATO, 81019);
		$this->assertEquals( CorreiosWebService::E_SEDEX_COM_CONTRATO_GRUPO_UM, 81868);
		$this->assertEquals( CorreiosWebService::E_SEDEX_COM_CONTRATO_GRUPO_DOIS, 81833);
		$this->assertEquals( CorreiosWebService:: E_SEDEX_COM_CONTRATO_GRUPO_TRES, 81850);
		
	}//function
	
	public function test_adicionar_encomenda_para_consulta()
	{
		$encomenda = new Encomenda();
		$this->correios->add(
			$encomenda->__set("formato", 2)
					  ->__set("peso", 30)
					  ->__set("comprimento", 30)
					  ->__set("altura", 10)
					  ->__set("largura", 40)
					  ->__set("diametro", 60)
					  ->__set("codigo", CorreiosWebService::SEDEX_A_COBRAR_SEM_CONTRATO )
		);
		
		$this->assertEquals( $this->correios->count(), 1);
	}//function
	
	public function test_caso_um_encomenda_que_nao_exista_seja_recupera_deve_haver_um_exception()
	{
		$this->setExpectedException('Exception');
		$this->correios->encomenda1;
	}//function

	public function test_monta_url_consulta_pedido()
	{	
		$encomenda1 = new Encomenda();
		$encomenda2 = new Encomenda();
		$this->correios
		->add(
			$encomenda1->__set("formato", 2)
					   ->__set("peso", 30)
					   ->__set("comprimento", 30)
					   ->__set("altura", 10)
					   ->__set("largura", 40)
					   ->__set("diametro", 60)
					   ->__set("codigo", CorreiosWebService::SEDEX_A_COBRAR_SEM_CONTRATO )
		)		
		->add(
			$encomenda2->__set("formato", 1)
					   ->__set("peso", 100)
					   ->__set("comprimento", 50)
					   ->__set("altura", 70)
					   ->__set("largura", 40)
					   ->__set("diametro", 60)
					   ->__set("codigo", CorreiosWebService::E_SEDEX_COM_CONTRATO_GRUPO_TRES )
		);
		
		$this->assertEquals( $this->correios->count(), 2, "Quantidade de encomendas.");
	    $encomenda1 = $this->correios->encomenda1;
		
		$this->assertEquals($encomenda1->formato, 2, "Formato da primeira encomenda.");
		$this->assertEquals($encomenda1->codigo, 40045);
		$this->assertEquals($encomenda1->url, "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdFormato=2&nVlPeso=30&VlComprimento=30&nVlAltura=10&nVlLargura=40&nVlDiametro=60&Codigo=40045");

		$encomenda2 = $this->correios->encomenda2;
		$this->assertEquals($encomenda2->formato, 1, "Formato do segunda encomenda.");
		$this->assertEquals($encomenda2->codigo, 81850, "Codigo atendimento encomenda2.");
		$this->assertEquals($encomenda2->url, "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdFormato=1&nVlPeso=100&VlComprimento=50&nVlAltura=70&nVlLargura=40&nVlDiametro=60&Codigo=81850");
		unset( $encomenda1, $encomenda2 );
	}//function
	
	public function test_limpar_toda_lista()
	{
		$this->assertEquals( $this->correios->count(), 0);
		$encomenda1 = new Encomenda();
		$encomenda2 = new Encomenda();
		$this->correios
		->add(
			$encomenda1->__set("formato", 2)
					   ->__set("peso", 30)
					   ->__set("comprimento", 30)
					   ->__set("altura", 10)
					   ->__set("largura", 40)
					   ->__set("diametro", 60)
					   ->__set("codigo", CorreiosWebService::SEDEX_A_COBRAR_SEM_CONTRATO )
		)		
		->add(
			$encomenda2->__set("formato", 1)
					   ->__set("peso", 100)
					   ->__set("comprimento", 50)
					   ->__set("altura", 70)
					   ->__set("largura", 40)
					   ->__set("diametro", 60)
					   ->__set("codigo", CorreiosWebService::E_SEDEX_COM_CONTRATO_GRUPO_TRES )
		);
		
		$this->assertEquals( $this->correios->count(), 2, "Quantidade de encomendas.");
		$this->assertTrue( $this->correios->apagarEncomendas());
		$this->assertEquals( $this->correios->count(), 0);

		unset($encomenda1, $encomenda2);
	}//function
		
}//class
