<?
require_once "PHPUnit/Framework.php";
require_once "correios_webservice.php";
require_once "encomenda.php";

class TestCorreiosWebService extends PHPUnit_Framework_TestCase
{
	private $correios = null;

	public function setUp()
	{
		$this->correios = new CorreiosWebService(array("origem"=>71939360, "destino"=>72151613, "retorno"=>"xml"));
	}//function
	
	public function test_cria_web_service()
	{
		$this->assertTrue( $this->correios instanceof CorreiosWebService );	
	}//function
	
	public function test_caso_informacoes_basicas_nao_seja_passadas_deve_haver_um_exception()
	{
		$this->setExpectedException( "Exception" );			
		$correios = new CorreiosWebService();

	}//function
	
	public function test_parametros_passados_para_webservice_deve_gerar_uma_query_string()
	{
		$this->assertEquals( $this->correios->getParam(), "?nCdEmpresa=&sDsSenha=&sCepOrigem=71939360&sCepDestino=72151613&StrRetorno=xml");
	}//function
	
	public function test_caso_atributo_obrigatorio_seja_apagado_deve_haver_exception()
	{
		$this->setExpectedException( "Exception" );
		$this->correios->origem = null;
		
		$this->correios->getParam();
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
		$this->assertEquals( CorreiosWebService::E_SEDEX_COM_CONTRATO_GRUPO_TRES, 81850);
		
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
					   ->__set("valor_declarado", 200)
				  	   ->__set("aviso_recebimento", true)
		);
		
		$this->assertEquals( $this->correios->count(), 2, "Quantidade de encomendas.");
	    $encomenda1 = $this->correios->filter('encomenda1');
		
		$this->assertEquals($encomenda1->formato, 2, "Formato da primeira encomenda.");
		$this->assertEquals($encomenda1->codigo, 40045);
		$this->assertEquals($encomenda1->url, "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&sCepOrigem=71939360&sCepDestino=72151613&StrRetorno=xml&nCdFormato=2&nVlPeso=30&nVlComprimento=30&nVlAltura=10&nVlLargura=40&nVlDiametro=60&nCdServico=40045&sCdMaoPropria=n&sCdAvisoRecebimento=n&nVlValorDeclarado=0");

		$encomenda2 = $this->correios->filter('encomenda2');
		$this->assertEquals($encomenda2->formato, 1, "Formato do segunda encomenda.");
		$this->assertEquals($encomenda2->codigo, 81850, "Codigo atendimento encomenda2.");
		$this->assertEquals($encomenda2->url, "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&sCepOrigem=71939360&sCepDestino=72151613&StrRetorno=xml&nCdFormato=1&nVlPeso=100&nVlComprimento=50&nVlAltura=70&nVlLargura=40&nVlDiametro=60&nCdServico=81850&sCdMaoPropria=n&sCdAvisoRecebimento=s&nVlValorDeclarado=200");
		
		unset( $encomenda1, $encomenda2 );
	}//function
	
	public function test_limpar_lista_de_encomendas()
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
		$this->assertTrue( $this->correios->delete( "encomenda1" ) );
		$this->assertEquals( $this->correios->count(), 1, "Quantidade de encomendas.");
		
		$this->assertTrue( $this->correios->delete());
		$this->assertEquals( $this->correios->count(), 0);

		unset($encomenda1, $encomenda2);
	}//function
	
	public function test_converte_1050_para_us_moeda()
	{
		$this->assertEquals( $this->correios->usMoney('10,50'), 10.50);	
	}//function
	
	public function test_converte_1570_para_us_moeda()
	{
		$this->assertEquals( $this->correios->usMoney('15,70'), 15.70);	
	}//function
	
	public function test_caso_valor_convertido_nao_seja_valor_deve_haver_exception()
	{
		$this->setExpectedException( "Exception" );
		$this->correios->usMoney('15,aa');
	}//function

	public function test_process_encomendas_deve_retornar_true_apos_processamento()
	{
		$this->assertEquals( $this->correios->count(), 0);
		$encomenda1 = new Encomenda();
		$this->correios
		->add(
			$encomenda1->__set("formato", 1)
					   ->__set("peso", 1)
					   ->__set("comprimento", 20)
					   ->__set("altura", 5)
					   ->__set("largura", 15)
					   ->__set("mao_propria", true)
					   ->__set("valor_declarado", 200)
					   ->__set("aviso_recebimento", false)
					   ->__set("diametro", 0)
					   ->__set("codigo", CorreiosWebService::PAC_SEM_CONTRATO )
		);
		$this->assertEquals( $this->correios->count(), 1, "Quantidade de encomendas.");
		$this->assertTrue( $this->correios->processEncomendas() );
		$this->assertEquals( $this->correios->filter("encomenda1")->valor,  15.7 );
		
	}//function
	
}//class
