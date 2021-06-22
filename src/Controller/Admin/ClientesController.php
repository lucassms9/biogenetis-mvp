<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Clientes Controller
 *
 * @property \App\Model\Table\ClientesTable $Clientes
 *
 * @method \App\Model\Entity\Cliente[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ClientesController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->cobranca_tipos = [
            'Particular' => 'Particular',
            'Convênio' => 'Convênio'
        ];
        $this->loadModel('ExtratoSaldo');
        $this->loadModel('NetSuiteCidades');
        $this->loadComponent('NetSuite');
    }

    public function netsuitecidades()
    {
        $data = $this->getCidades();

        $rows = explode("\n", $data);

        foreach ($rows as $row) {
            $row = trim($row);
            $row = explode(";", $row);

            $save_data = [
                'internal_id' => $row[0],
                'nome' => $row[1],
                'code_municipio' => $row[2],
                'municipio_nome' => $row[4],
                'uf' => $row[5],
            ];

            $item_save = $this->NetSuiteCidades->newEntity();
            $item_save = $this->NetSuiteCidades->patchEntity($item_save, $save_data);
            $item_save = $this->NetSuiteCidades->save($item_save);
        }
        exit('foi');
    }

    public function netsuite()
    {
        // $this->NetSuite->createClientePF();
        // $this->NetSuite->createClientePJ();

        $clientes = $this->Clientes->find('all', [
            'conditions' => [
                'cnpj_cpf <>' => 'null',
                'id' => 16
            ]
        ])->toArray();

        foreach ($clientes as $cliente) {
            $cnpj_cpf = preg_replace("/[^0-9]/", "", $cliente->cnpj_cpf);

            $getCidade = $this->NetSuiteCidades->find('all', [
                'conditions' => [
                    'municipio_nome' => $cliente->cidade,
                    'uf' => $cliente->uf,
                ]
            ])->first();

            $data = [
                'externalId' => $cliente->cnpj_cpf,
                'nome' => $cliente->nome_fantasia,
                'sobrenome' => $cliente->razao_social,
                'email' => $cliente->responsavel_email,
                'endereco' => $cliente->endereco,
                'estado' => $cliente->uf,
                'bairro' => $cliente->bairro,
                'municipio' => $getCidade->internal_id,
                'cep' => preg_replace("/[^0-9]/", "", $cliente->cep),
                'phone1' => preg_replace("/[^0-9]/", "", @$cliente->responsavel_telefone),
                'phone2' => preg_replace("/[^0-9]/", "", @$cliente->responsavel_financeiro_telefone),
            ];

            $mail = explode(';', $data['email']);
            $data['email'] = $mail[0];

            if (!empty($cnpj_cpf) && strlen($cnpj_cpf) >= 14) {
                $external_id = $this->NetSuite->createClientePJ($data);
            } else {
                $external_id = $this->NetSuite->createClientePF($data);
            }

            $cliente->net_suite_id = $external_id;
            $this->Clientes->save($cliente);
            exit('completed');
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $action = 'Ver Todos';
        $title = 'Clientes';

        $conditions = [];

        if ($this->Auth->user('user_type_id') == 2) {
            $conditions['id'] = $this->Auth->user('cliente_id');
        }

        $clientes = $this->paginate($this->Clientes, [
            'conditions' => $conditions
        ]);

        if ($this->Auth->user('user_type_id') == 1) {
            $showActions = true;
        } else {
            $showActions = false;
        }
        $this->set(compact('clientes', 'action', 'title', 'showActions'));
    }

    /**
     * View method
     *
     * @param string|null $id Cliente id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $cliente = $this->Clientes->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set('cliente', $cliente);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $action = 'Cadastrar';
        $title = 'Clientes';

        $cliente = $this->Clientes->newEntity();
        if ($this->request->is('post')) {
            $cliente = $this->Clientes->patchEntity($cliente, $this->request->getData());
            $cliente = $this->Clientes->save($cliente);
            if ($cliente) {
                $cnpj_cpf = $cliente['cnpj_cpf'];

                $data = [
                    'externalId' => $cliente->id,
                    'nome' => $cliente->nome_fantasia,
                    'sobrenome' => $cliente->razao_social,
                    'email' => $cliente->responsavel_email,
                    'endereco' => $cliente->endereco,
                    'estado' => $cliente->uf,
                    'cep' => preg_replace("/[^0-9]/", "", $cliente->cep),
                    'municipio' => $cliente->cidade . ' - ' . $cliente->uf,
                ];

                if (strlen($cnpj_cpf) >= 14) {
                    $external_id = $this->NetSuite->createClientePJ($data);
                } else {
                    $external_id = $this->NetSuite->createClientePF($data);
                }

                $cliente->net_suite_id = $external_id;
                $this->Clientes->save($cliente);

                $this->Flash->success(__('The cliente has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The cliente could not be saved. Please, try again.'));
        }

        $cobranca_tipos = $this->cobranca_tipos;

        $this->set(compact('cliente', 'action', 'title', 'cobranca_tipos'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Cliente id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {

        $action = 'Editar';
        $title = 'Clientes';

        $cliente = $this->Clientes->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $req = $this->request->getData();


            if ($req['img_header_url']['size'] > 0) {
                $ext = explode('/', $req['img_header_url']['type']);
                $name = md5($req['img_header_url']['name']) . '.' . $ext[1];

                $url = 'clientes_imgs/' . $name;

                move_uploaded_file($req['img_header_url']['tmp_name'], CLIENTES_IMGS . $name);
                $req['img_header_url'] = $url;
            }

            if ($req['img_footer_url']['size'] > 0) {
                $ext = explode('/', $req['img_footer_url']['type']);
                $name = md5($req['img_footer_url']['name']) . '.' . $ext[1];

                $url = 'clientes_imgs/' . $name;

                move_uploaded_file($req['img_footer_url']['tmp_name'], CLIENTES_IMGS . $name);
                $req['img_footer_url'] = $url;
            }

            $cliente = $this->Clientes->patchEntity($cliente, $req);
            if ($this->Clientes->save($cliente)) {
                $this->Flash->success(__('The cliente has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The cliente could not be saved. Please, try again.'));
        }

        $clientes = $this->paginate($this->Clientes);

        $cobranca_tipos = $this->cobranca_tipos;

        $this->set(compact('cliente', 'action', 'title', 'cobranca_tipos'));
    }


    public function saldos($id = null)
    {
        $action = 'Saldo';
        $title = 'Clientes';


        if ($this->request->is(['patch', 'post', 'put'])) {
            $req = $this->request->getData();

            if (!empty($req['novo_saldo']) && $req['novo_saldo'] > 0) {

                $dataSave = [
                    'cliente_id' => $id,
                    'type' => 'C',
                    'valor' => $req['novo_saldo'],
                    'created_by' => $this->Auth->user('id')
                ];

                $extratoSaldo = $this->ExtratoSaldo->newEntity();
                $extratoSaldo = $this->ExtratoSaldo->patchEntity($extratoSaldo, $dataSave);
                $extratoSaldo = $this->ExtratoSaldo->save($extratoSaldo);

                return $this->redirect(['action' => 'saldos/' . $id]);
                $this->Flash->success(__('Dados salvos com sucesso'));
            }
        }

        $cliente = $this->Clientes->get($id, [
            'contain' => [],
        ]);

        $saldo = $cliente->getSaldo();

        $this->set(compact('action', 'title', 'saldo'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Cliente id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $cliente = $this->Clientes->get($id);
        if ($this->Clientes->delete($cliente)) {
            $this->Flash->success(__('The cliente has been deleted.'));
        } else {
            $this->Flash->error(__('The cliente could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    private function getCidades()
    {
        return "2416;Abadia de Goiás - GO;5200050;1050;Abadia de Goiás;GO
        5014;Abadia dos Dourados - MG;3100104;4001;Abadia dos Dourados;MG
        2417;Abadiânia - GO;5200100;9201;Abadiânia;GO
        5015;Abaeté - MG;3100203;4003;Abaeté;MG
        875;Abaetetuba - PA;1500107;401;Abaetetuba;PA
        1285;Abaiara - CE;2300101;1301;Abaiara;CE
        3455;Abaíra - BA;2900108;3301;Abaíra;BA
        3456;Abaré - BA;2900207;3303;Abaré;BA
        2957;Abatiá - PR;4100103;7401;Abatiá;PR
        2662;Abdon Batista - SC;4200051;9939;Abdon Batista;SC
        876;Abel Figueiredo - PA;1500131;375;Abel Figueiredo;PA
        2663;Abelardo Luz - SC;4200101;8001;Abelardo Luz;SC
        5016;Abre Campo - MG;3100302;4005;Abre Campo;MG
        1469;Abreu e Lima - PE;2600054;2631;Abreu e Lima;PE
        595;Abreulândia - TO;1700251;337;Abreulândia;TO
        5017;Acaiaca - MG;3100401;4007;Acaiaca;MG
        1654;Açailândia - MA;2100055;961;Açailândia;MA
        3457;Acajutiba - BA;2900306;3305;Acajutiba;BA
        877;Acará - PA;1500206;403;Acará;PA
        1286;Acarape - CE;2300150;1231;Acarape;CE
        1287;Acaraú - CE;2300200;1303;Acaraú;CE
        1118;Acari - RN;2400109;1601;Acari;RN
        1871;Acauã - PI;2200053;266;Acauã;PI
        3872;Aceguá - RS;4300034;1118;Aceguá;RS
        1288;Acopiara - CE;2300309;1305;Acopiara;CE
        734;Acorizal - MT;5100102;9001;Acorizal;MT
        33;Acrelândia - AC;1200013;643;Acrelândia;AC
        2418;Acreúna - GO;5200134;9645;Acreúna;GO
        1119;Açu - RN;2400208;1603;Açu;RN
        5018;Açucena - MG;3100500;4009;Açucena;MG
        1069;Adamantina - SP;3500105;6101;Adamantina;SP
        2419;Adelândia - GO;5200159;9769;Adelândia;GO
        1070;Adolfo - SP;3500204;6103;Adolfo;SP
        2958;Adrianópolis - PR;4100202;7403;Adrianópolis;PR
        3458;Adustina - BA;2900355;3253;Adustina;BA
        1470;Afogados da Ingazeira - PE;2600104;2301;Afogados da Ingazeira;PE
        1120;Afonso Bezerra - RN;2400307;1605;Afonso Bezerra;RN
        244;Afonso Cláudio - ES;3200102;5601;Afonso Cláudio;ES
        1655;Afonso Cunha - MA;2100105;701;Afonso Cunha;MA
        1471;Afrânio - PE;2600203;2303;Afrânio;PE
        878;Afuá - PA;1500305;405;Afuá;PA
        1472;Agrestina - PE;2600302;2305;Agrestina;PE
        1872;Agricolândia - PI;2200103;1001;Agricolândia;PI
        2664;Agrolândia - SC;4200200;8003;Agrolândia;SC
        2665;Agronômica - SC;4200309;8005;Agronômica;SC
        879;Água Azul do Norte - PA;1500347;383;Água Azul do Norte;PA
        5019;Água Boa - MG;3100609;4011;Água Boa;MG
        735;Água Boa - MT;5100201;9191;Água Boa;MT
        493;Água Branca - AL;2700102;2701;Água Branca;AL
        2094;Água Branca - PB;2500106;1901;Água Branca;PB
        1873;Água Branca - PI;2200202;1003;Água Branca;PI
        322;Água Clara - MS;5000203;9003;Água Clara;MS
        5020;Água Comprida - MG;3100708;4013;Água Comprida;MG
        2666;Água Doce - SC;4200408;8007;Água Doce;SC
        1656;Água Doce do Maranhão - MA;2100154;104;Água Doce do Maranhão;MA
        245;Água Doce do Norte - ES;3200169;5717;Água Doce do Norte;ES
        3459;Água Fria - BA;2900405;3307;Água Fria;BA
        2420;Água Fria de Goiás - GO;5200175;9771;Água Fria de Goiás;GO
        2421;Água Limpa - GO;5200209;9203;Água Limpa;GO
        1121;Água Nova - RN;2400406;1607;Água Nova;RN
        1473;Água Preta - PE;2600401;2307;Água Preta;PE
        3873;Água Santa - RS;4300059;8499;Água Santa;RS
        1071;Aguaí - SP;3500303;6105;Aguaí;SP
        5021;Aguanil - MG;3100807;4015;Aguanil;MG
        1474;Águas Belas - PE;2600500;2309;Águas Belas;PE
        1072;Águas da Prata - SP;3500402;6107;Águas da Prata;SP
        2667;Águas de Chapecó - SC;4200507;8009;Águas de Chapecó;SC
        1073;Águas de Lindóia - SP;3500501;6109;Águas de Lindóia;SP
        1074;Águas de Santa Bárbara - SP;3500550;7019;Águas de Santa Bárbara;SP
        1075;Águas de São Pedro - SP;3500600;6111;Águas de São Pedro;SP
        5022;Águas Formosas - MG;3100906;4017;Águas Formosas;MG
        2668;Águas Frias - SC;4200556;5577;Águas Frias;SC
        2422;Águas Lindas de Goiás - GO;5200258;1052;Águas Lindas de Goiás;GO
        2669;Águas Mornas - SC;4200606;8011;Águas Mornas;SC
        5023;Águas Vermelhas - MG;3101003;4019;Águas Vermelhas;MG
        3874;Agudo - RS;4300109;8501;Agudo;RS
        1076;Agudos - SP;3500709;6113;Agudos;SP
        2959;Agudos do Sul - PR;4100301;7405;Agudos do Sul;PR
        246;Águia Branca - ES;3200136;5733;Águia Branca;ES
        2095;Aguiar - PB;2500205;1903;Aguiar;PB
        596;Aguiarnópolis - TO;1700301;72;Aguiarnópolis;TO
        5024;Aimorés - MG;3101102;4021;Aimorés;MG
        3460;Aiquara - BA;2900603;3311;Aiquara;BA
        1289;Aiuaba - CE;2300408;1307;Aiuaba;CE
        5025;Aiuruoca - MG;3101201;4023;Aiuruoca;MG
        3875;Ajuricaba - RS;4300208;8503;Ajuricaba;RS
        5026;Alagoa - MG;3101300;4025;Alagoa;MG
        2096;Alagoa Grande - PB;2500304;1905;Alagoa Grande;PB
        2097;Alagoa Nova - PB;2500403;1907;Alagoa Nova;PB
        2098;Alagoinha - PB;2500502;1909;Alagoinha;PB
        1475;Alagoinha - PE;2600609;2311;Alagoinha;PE
        1874;Alagoinha do Piauí - PI;2200251;9767;Alagoinha do Piauí;PI
        3461;Alagoinhas - BA;2900702;3313;Alagoinhas;BA
        1077;Alambari - SP;3500758;2995;Alambari;SP
        5027;Albertina - MG;3101409;4027;Albertina;MG
        1657;Alcântara - MA;2100204;703;Alcântara;MA
        1290;Alcântaras - CE;2300507;1309;Alcântaras;CE
        2099;Alcantil - PB;2500536;440;Alcantil;PB
        323;Alcinópolis - MS;5000252;141;Alcinópolis;MS
        3462;Alcobaça - BA;2900801;3315;Alcobaça;BA
        1658;Aldeias Altas - MA;2100303;705;Aldeias Altas;MA
        3876;Alecrim - RS;4300307;8505;Alecrim;RS
        247;Alegre - ES;3200201;5603;Alegre;ES
        3877;Alegrete - RS;4300406;8507;Alegrete;RS
        1875;Alegrete do Piauí - PI;2200277;2269;Alegrete do Piauí;PI
        3878;Alegria - RS;4300455;8497;Alegria;RS
        5028;Além Paraíba - MG;3101508;4029;Além Paraíba;MG
        880;Alenquer - PA;1500404;407;Alenquer;PA
        1122;Alexandria - RN;2400505;1609;Alexandria;RN
        2423;Alexânia - GO;5200308;9205;Alexânia;GO
        5029;Alfenas - MG;3101607;4031;Alfenas;MG
        248;Alfredo Chaves - ES;3200300;5605;Alfredo Chaves;ES
        1078;Alfredo Marcondes - SP;3500808;6115;Alfredo Marcondes;SP
        5030;Alfredo Vasconcelos - MG;3101631;2681;Alfredo Vasconcelos;MG
        2670;Alfredo Wagner - SC;4200705;8013;Alfredo Wagner;SC
        2100;Algodão de Jandaíra - PB;2500577;442;Algodão de Jandaíra;PB
        2101;Alhandra - PB;2500601;1911;Alhandra;PB
        1476;Aliança - PE;2600708;2313;Aliança;PE
        597;Aliança do Tocantins - TO;1700350;9441;Aliança do Tocantins;TO
        3463;Almadina - BA;2900900;3317;Almadina;BA
        598;Almas - TO;1700400;9207;Almas;TO
        881;Almeirim - PA;1500503;409;Almeirim;PA
        5031;Almenara - MG;3101706;4033;Almenara;MG
        1123;Almino Afonso - RN;2400604;1611;Almino Afonso;RN
        2960;Almirante Tamandaré - PR;4100400;7407;Almirante Tamandaré;PR
        3879;Almirante Tamandaré do Sul - RS;4300471;1120;Almirante Tamandaré do Sul;RS
        2424;Aloândia - GO;5200506;9209;Aloândia;GO
        5032;Alpercata - MG;3101805;4035;Alpercata;MG
        3880;Alpestre - RS;4300505;8509;Alpestre;RS
        5033;Alpinópolis - MG;3101904;4037;Alpinópolis;MG
        736;Alta Floresta - MT;5100250;8987;Alta Floresta;MT
        55;Alta Floresta D'Oeste - RO;1100015;33;Alta Floresta D'Oeste;RO
        1079;Altair - SP;3500907;6117;Altair;SP
        882;Altamira - PA;1500602;411;Altamira;PA
        1659;Altamira do Maranhão - MA;2100402;707;Altamira do Maranhão;MA
        2961;Altamira do Paraná - PR;4100459;8455;Altamira do Paraná;PR
        1291;Altaneira - CE;2300606;1311;Altaneira;CE
        5034;Alterosa - MG;3102001;4039;Alterosa;MG
        1477;Altinho - PE;2600807;2315;Altinho;PE
        1080;Altinópolis - SP;3501004;6119;Altinópolis;SP
        2;Alto Alegre - RR;1400050;305;Alto Alegre;RR
        3881;Alto Alegre - RS;4300554;8495;Alto Alegre;RS
        1081;Alto Alegre - SP;3501103;6121;Alto Alegre;SP
        1660;Alto Alegre do Maranhão - MA;2100436;106;Alto Alegre do Maranhão;MA
        1661;Alto Alegre do Pindaré - MA;2100477;108;Alto Alegre do Pindaré;MA
        56;Alto Alegre dos Parecis - RO;1100379;2;Alto Alegre dos Parecis;RO
        737;Alto Araguaia - MT;5100300;9005;Alto Araguaia;MT
        2671;Alto Bela Vista - SC;4200754;886;Alto Bela Vista;SC
        738;Alto Boa Vista - MT;5100359;127;Alto Boa Vista;MT
        5035;Alto Caparaó - MG;3102050;564;Alto Caparaó;MG
        1124;Alto do Rodrigues - RN;2400703;1613;Alto do Rodrigues;RN
        3882;Alto Feliz - RS;4300570;6045;Alto Feliz;RS
        739;Alto Garças - MT;5100409;9007;Alto Garças;MT
        2425;Alto Horizonte - GO;5200555;85;Alto Horizonte;GO
        5036;Alto Jequitibá - MG;3153509;5069;Alto Jequitibá;MG
        1876;Alto Longá - PI;2200301;1005;Alto Longá;PI
        740;Alto Paraguai - MT;5100508;9009;Alto Paraguai;MT
        2962;Alto Paraíso - PR;4128625;5523;Alto Paraíso;PR
        57;Alto Paraíso - RO;1100403;675;Alto Paraíso;RO
        2426;Alto Paraíso de Goiás - GO;5200605;9211;Alto Paraíso de Goiás;GO
        2963;Alto Paraná - PR;4100608;7409;Alto Paraná;PR
        1662;Alto Parnaíba - MA;2100501;709;Alto Parnaíba;MA
        2964;Alto Piquiri - PR;4100707;7411;Alto Piquiri;PR
        5037;Alto Rio Doce - MG;3102100;4041;Alto Rio Doce;MG
        249;Alto Rio Novo - ES;3200359;5719;Alto Rio Novo;ES
        1292;Alto Santo - CE;2300705;1313;Alto Santo;CE
        741;Alto Taquari - MT;5100607;9911;Alto Taquari;MT
        2965;Altônia - PR;4100509;7951;Altônia;PR
        1877;Altos - PI;2200400;1007;Altos;PI
        1082;Alumínio - SP;3501152;3065;Alumínio;SP
        107;Alvarães - AM;1300029;289;Alvarães;AM
        5038;Alvarenga - MG;3102209;4043;Alvarenga;MG
        1083;Álvares Florence - SP;3501202;6123;Álvares Florence;SP
        1084;Álvares Machado - SP;3501301;6125;Álvares Machado;SP
        1085;Álvaro de Carvalho - SP;3501400;6127;Álvaro de Carvalho;SP
        1086;Alvinlândia - SP;3501509;6129;Alvinlândia;SP
        5039;Alvinópolis - MG;3102308;4045;Alvinópolis;MG
        3883;Alvorada - RS;4300604;8511;Alvorada;RS
        599;Alvorada - TO;1700707;9213;Alvorada;TO
        5040;Alvorada de Minas - MG;3102407;4047;Alvorada de Minas;MG
        1878;Alvorada do Gurguéia - PI;2200459;268;Alvorada do Gurguéia;PI
        2427;Alvorada do Norte - GO;5200803;9215;Alvorada do Norte;GO
        2966;Alvorada do Sul - PR;4100806;7413;Alvorada do Sul;PR
        58;Alvorada D'Oeste - RO;1100346;35;Alvorada D'Oeste;RO
        3;Amajari - RR;1400027;26;Amajari;RR
        324;Amambai - MS;5000609;9011;Amambai;MS
        17;Amapá - AP;1600105;601;Amapá;AP
        1663;Amapá do Maranhão - MA;2100550;110;Amapá do Maranhão;MA
        2967;Amaporã - PR;4100905;7415;Amaporã;PR
        1478;Amaraji - PE;2600906;2317;Amaraji;PE
        3884;Amaral Ferrador - RS;4300638;8493;Amaral Ferrador;RS
        2428;Amaralina - GO;5200829;1054;Amaralina;GO
        1879;Amarante - PI;2200509;1009;Amarante;PI
        1664;Amarante do Maranhão - MA;2100600;711;Amarante do Maranhão;MA
        3464;Amargosa - BA;2901007;3319;Amargosa;BA
        108;Amaturá - AM;1300060;291;Amaturá;AM
        3465;Amélia Rodrigues - BA;2901106;3321;Amélia Rodrigues;BA
        3466;América Dourada - BA;2901155;3071;América Dourada;BA
        1087;Americana - SP;3501608;6131;Americana;SP
        2429;Americano do Brasil - GO;5200852;9661;Americano do Brasil;GO
        1088;Américo Brasiliense - SP;3501707;6133;Américo Brasiliense;SP
        1089;Américo de Campos - SP;3501806;6135;Américo de Campos;SP
        3885;Ametista do Sul - RS;4300646;5969;Ametista do Sul;RS
        1293;Amontada - CE;2300754;1587;Amontada;CE
        2430;Amorinópolis - GO;5200902;9217;Amorinópolis;GO
        2201;Amparo - PB;2500734;444;Amparo;PB
        1090;Amparo - SP;3501905;6137;Amparo;SP
        169;Amparo de São Francisco - SE;2800100;3101;Amparo de São Francisco;SE
        5041;Amparo do Serra - MG;3102506;4049;Amparo do Serra;MG
        2968;Ampére - PR;4101002;7417;Ampére;PR
        494;Anadia - AL;2700201;2703;Anadia;AL
        3467;Anagé - BA;2901205;3323;Anagé;BA
        2969;Anahy - PR;4101051;5463;Anahy;PR
        883;Anajás - PA;1500701;413;Anajás;PA
        1665;Anajatuba - MA;2100709;713;Anajatuba;MA
        1091;Analândia - SP;3502002;6139;Analândia;SP
        109;Anamã - AM;1300086;293;Anamã;AM
        600;Ananás - TO;1701002;9219;Ananás;TO
        884;Ananindeua - PA;1500800;415;Ananindeua;PA
        2431;Anápolis - GO;5201108;9221;Anápolis;GO
        885;Anapu - PA;1500859;40;Anapu;PA
        1666;Anapurus - MA;2100808;715;Anapurus;MA
        325;Anastácio - MS;5000708;9013;Anastácio;MS
        326;Anaurilândia - MS;5000807;9015;Anaurilândia;MS
        250;Anchieta - ES;3200409;5607;Anchieta;ES
        2672;Anchieta - SC;4200804;8015;Anchieta;SC
        3468;Andaraí - BA;2901304;3325;Andaraí;BA
        2970;Andirá - PR;4101101;7419;Andirá;PR
        3469;Andorinha - BA;2901353;3255;Andorinha;BA
        5042;Andradas - MG;3102605;4051;Andradas;MG
        1092;Andradina - SP;3502101;6141;Andradina;SP
        3886;André da Rocha - RS;4300661;8491;André da Rocha;RS
        5043;Andrelândia - MG;3102803;4055;Andrelândia;MG
        1093;Angatuba - SP;3502200;6143;Angatuba;SP
        5044;Angelândia - MG;3102852;566;Angelândia;MG
        327;Angélica - MS;5000856;9169;Angélica;MS
        1479;Angelim - PE;2601003;2319;Angelim;PE
        2673;Angelina - SC;4200903;8017;Angelina;SC
        3470;Angical - BA;2901403;3327;Angical;BA
        1880;Angical do Piauí - PI;2200608;1011;Angical do Piauí;PI
        601;Angico - TO;1701051;165;Angico;TO
        1125;Angicos - RN;2400802;1615;Angicos;RN
        401;Angra dos Reis - RJ;3300100;5801;Angra dos Reis;RJ
        3471;Anguera - BA;2901502;3329;Anguera;BA
        2971;Ângulo - PR;4101150;5509;Ângulo;PR
        2432;Anhanguera - GO;5201207;9223;Anhanguera;GO
        1094;Anhembi - SP;3502309;6145;Anhembi;SP
        1095;Anhumas - SP;3502408;6147;Anhumas;SP
        2433;Anicuns - GO;5201306;9225;Anicuns;GO
        1881;Anísio de Abreu - PI;2200707;1013;Anísio de Abreu;PI
        2674;Anita Garibaldi - SC;4201000;8019;Anita Garibaldi;SC
        2675;Anitápolis - SC;4201109;8021;Anitápolis;SC
        110;Anori - AM;1300102;203;Anori;AM
        3887;Anta Gorda - RS;4300703;8513;Anta Gorda;RS
        3472;Antas - BA;2901601;3331;Antas;BA
        2972;Antonina - PR;4101200;7421;Antonina;PR
        1294;Antonina do Norte - CE;2300804;1315;Antonina do Norte;CE
        1882;Antônio Almeida - PI;2200806;1015;Antônio Almeida;PI
        3473;Antônio Cardoso - BA;2901700;3333;Antônio Cardoso;BA
        5045;Antônio Carlos - MG;3102902;4057;Antônio Carlos;MG
        2676;Antônio Carlos - SC;4201208;8023;Antônio Carlos;SC
        5046;Antônio Dias - MG;3103009;4059;Antônio Dias;MG
        3474;Antônio Gonçalves - BA;2901809;3335;Antônio Gonçalves;BA
        328;Antônio João - MS;5000906;9017;Antônio João;MS
        1126;Antônio Martins - RN;2400901;1617;Antônio Martins;RN
        2973;Antônio Olinto - PR;4101309;7423;Antônio Olinto;PR
        3888;Antônio Prado - RS;4300802;8515;Antônio Prado;RS
        5047;Antônio Prado de Minas - MG;3103108;4061;Antônio Prado de Minas;MG
        2202;Aparecida - PB;2500775;446;Aparecida;PB
        1096;Aparecida - SP;3502507;6149;Aparecida;SP
        2434;Aparecida de Goiânia - GO;5201405;9227;Aparecida de Goiânia;GO
        2435;Aparecida do Rio Doce - GO;5201454;71;Aparecida do Rio Doce;GO
        602;Aparecida do Rio Negro - TO;1701101;9713;Aparecida do Rio Negro;TO
        329;Aparecida do Taboado - MS;5001003;9019;Aparecida do Taboado;MS
        1097;Aparecida d'Oeste - SP;3502606;6151;Aparecida d'Oeste;SP
        402;Aperibé - RJ;3300159;2919;Aperibé;RJ
        251;Apiacá - ES;3200508;5609;Apiacá;ES
        742;Apiacás - MT;5100805;9773;Apiacás;MT
        1098;Apiaí - SP;3502705;6153;Apiaí;SP
        1667;Apicum-Açu - MA;2100832;112;Apicum-Açu;MA
        2677;Apiúna - SC;4201257;9941;Apiúna;SC
        1127;Apodi - RN;2401008;1619;Apodi;RN
        3475;Aporá - BA;2901908;3337;Aporá;BA
        2436;Aporé - GO;5201504;9229;Aporé;GO
        3476;Apuarema - BA;2901957;3257;Apuarema;BA
        2974;Apucarana - PR;4101408;7425;Apucarana;PR
        111;Apuí - AM;1300144;969;Apuí;AM
        1295;Apuiarés - CE;2300903;1317;Apuiarés;CE
        170;Aquidabã - SE;2800209;3103;Aquidabã;SE
        330;Aquidauana - MS;5001102;9021;Aquidauana;MS
        1296;Aquiraz - CE;2301000;1319;Aquiraz;CE
        2678;Arabutã - SC;4201273;5597;Arabutã;SC
        2203;Araçagi - PB;2500809;1915;Araçagi;PB
        5048;Araçaí - MG;3103207;4063;Araçaí;MG
        171;Aracaju - SE;2800308;3105;Aracaju;SE
        1099;Araçariguama - SP;3502754;3067;Araçariguama;SP
        3477;Araçás - BA;2902054;3259;Araçás;BA
        1297;Aracati - CE;2301109;1321;Aracati;CE
        3478;Aracatu - BA;2902005;3339;Aracatu;BA
        1100;Araçatuba - SP;3502804;6155;Araçatuba;SP
        3479;Araci - BA;2902104;3341;Araci;BA
        5049;Aracitaba - MG;3103306;4065;Aracitaba;MG
        1298;Aracoiaba - CE;2301208;1323;Aracoiaba;CE
        1480;Araçoiaba - PE;2601052;544;Araçoiaba;PE
        4401;Araçoiaba da Serra - SP;3502903;6157;Araçoiaba da Serra;SP
        252;Aracruz - ES;3200607;5611;Aracruz;ES
        2437;Araçu - GO;5201603;9231;Araçu;GO
        5050;Araçuaí - MG;3103405;4067;Araçuaí;MG
        2438;Aragarças - GO;5201702;9233;Aragarças;GO
        2439;Aragoiânia - GO;5201801;9235;Aragoiânia;GO
        603;Aragominas - TO;1701309;167;Aragominas;TO
        604;Araguacema - TO;1701903;9237;Araguacema;TO
        605;Araguaçu - TO;1702000;9239;Araguaçu;TO
        743;Araguaiana - MT;5101001;9869;Araguaiana;MT
        606;Araguaína - TO;1702109;9241;Araguaína;TO
        744;Araguainha - MT;5101209;9023;Araguainha;MT
        1668;Araguanã - MA;2100873;114;Araguanã;MA
        607;Araguanã - TO;1702158;169;Araguanã;TO
        2440;Araguapaz - GO;5202155;9669;Araguapaz;GO
        5051;Araguari - MG;3103504;4069;Araguari;MG
        608;Araguatins - TO;1702208;9243;Araguatins;TO
        1669;Araioses - MA;2100907;717;Araioses;MA
        331;Aral Moreira - MS;5001243;9171;Aral Moreira;MS
        3480;Aramari - BA;2902203;3343;Aramari;BA
        3889;Arambaré - RS;4300851;5779;Arambaré;RS
        1670;Arame - MA;2100956;1281;Arame;MA
        4402;Aramina - SP;3503000;6159;Aramina;SP
        4403;Arandu - SP;3503109;6161;Arandu;SP
        5052;Arantina - MG;3103603;4071;Arantina;MG
        4404;Arapeí - SP;3503158;2991;Arapeí;SP
        495;Arapiraca - AL;2700300;2705;Arapiraca;AL
        609;Arapoema - TO;1702307;9245;Arapoema;TO
        5053;Araponga - MG;3103702;4073;Araponga;MG
        2975;Arapongas - PR;4101507;7427;Arapongas;PR
        5054;Araporã - MG;3103751;2903;Araporã;MG
        2976;Arapoti - PR;4101606;7429;Arapoti;PR
        5055;Arapuá - MG;3103801;4075;Arapuá;MG
        2977;Arapuã - PR;4101655;830;Arapuã;PR
        745;Araputanga - MT;5101258;8989;Araputanga;MT
        2679;Araquari - SC;4201307;8025;Araquari;SC
        2204;Arara - PB;2500908;1917;Arara;PB
        2680;Araranguá - SC;4201406;8027;Araranguá;SC
        4405;Araraquara - SP;3503208;6163;Araraquara;SP
        4406;Araras - SP;3503307;6165;Araras;SP
        1299;Ararendá - CE;2301257;989;Ararendá;CE
        1671;Arari - MA;2101004;719;Arari;MA
        3890;Araricá - RS;4300877;952;Araricá;RS
        1300;Araripe - CE;2301307;1325;Araripe;CE
        1481;Araripina - PE;2601102;2321;Araripina;PE
        403;Araruama - RJ;3300209;5803;Araruama;RJ
        2205;Araruna - PB;2501005;1919;Araruna;PB
        2978;Araruna - PR;4101705;7431;Araruna;PR
        3481;Arataca - BA;2902252;3073;Arataca;BA
        3891;Aratiba - RS;4300901;8517;Aratiba;RS
        1301;Aratuba - CE;2301406;1327;Aratuba;CE
        3482;Aratuípe - BA;2902302;3345;Aratuípe;BA
        172;Arauá - SE;2800407;3107;Arauá;SE
        2979;Araucária - PR;4101804;7435;Araucária;PR
        5056;Araújos - MG;3103900;4077;Araújos;MG
        5057;Araxá - MG;3104007;4079;Araxá;MG
        5058;Arceburgo - MG;3104106;4081;Arceburgo;MG
        4407;Arco-Íris - SP;3503356;790;Arco-Íris;SP
        5059;Arcos - MG;3104205;4083;Arcos;MG
        1482;Arcoverde - PE;2601201;2323;Arcoverde;PE
        5060;Areado - MG;3104304;4085;Areado;MG
        404;Areal - RJ;3300225;2925;Areal;RJ
        4408;Arealva - SP;3503406;6167;Arealva;SP
        2206;Areia - PB;2501104;1921;Areia;PB
        1128;Areia Branca - RN;2401107;1621;Areia Branca;RN
        173;Areia Branca - SE;2800506;3109;Areia Branca;SE
        2207;Areia de Baraúnas - PB;2501153;448;Areia de Baraúnas;PB
        2208;Areial - PB;2501203;1923;Areial;PB
        4409;Areias - SP;3503505;6169;Areias;SP
        4410;Areiópolis - SP;3503604;6171;Areiópolis;SP
        746;Arenápolis - MT;5101308;9025;Arenápolis;MT
        2441;Arenópolis - GO;5202353;9671;Arenópolis;GO
        1129;Arês - RN;2401206;1623;Arês;RN
        5061;Argirita - MG;3104403;4087;Argirita;MG
        5062;Aricanduva - MG;3104452;568;Aricanduva;MG
        5063;Arinos - MG;3104502;4089;Arinos;MG
        747;Aripuanã - MT;5101407;9027;Aripuanã;MT
        59;Ariquemes - RO;1100023;7;Ariquemes;RO
        4411;Ariranha - SP;3503703;6173;Ariranha;SP
        2980;Ariranha do Ivaí - PR;4101853;832;Ariranha do Ivaí;PR
        405;Armação dos Búzios - RJ;3300233;770;Armação dos Búzios;RJ
        2681;Armazém - SC;4201505;8029;Armazém;SC
        1302;Arneiroz - CE;2301505;1329;Arneiroz;CE
        1883;Aroazes - PI;2200905;1017;Aroazes;PI
        2209;Aroeiras - PB;2501302;1925;Aroeiras;PB
        1884;Aroeiras do Itaim - PI;2200954;1188;Aroeiras do Itaim;PI
        1885;Arraial - PI;2201002;1019;Arraial;PI
        406;Arraial do Cabo - RJ;3300258;5927;Arraial do Cabo;RJ
        610;Arraias - TO;1702406;9247;Arraias;TO
        3892;Arroio do Meio - RS;4301008;8519;Arroio do Meio;RS
        3893;Arroio do Padre - RS;4301073;1122;Arroio do Padre;RS
        3894;Arroio do Sal - RS;4301057;8489;Arroio do Sal;RS
        3895;Arroio do Tigre - RS;4301206;8523;Arroio do Tigre;RS
        3896;Arroio dos Ratos - RS;4301107;8521;Arroio dos Ratos;RS
        3897;Arroio Grande - RS;4301305;8525;Arroio Grande;RS
        2682;Arroio Trinta - SC;4201604;8031;Arroio Trinta;SC
        4412;Artur Nogueira - SP;3503802;6175;Artur Nogueira;SP
        2442;Aruanã - GO;5202502;9249;Aruanã;GO
        4413;Arujá - SP;3503901;6177;Arujá;SP
        2683;Arvoredo - SC;4201653;5599;Arvoredo;SC
        3898;Arvorezinha - RS;4301404;8527;Arvorezinha;RS
        2684;Ascurra - SC;4201703;8033;Ascurra;SC
        4414;Aspásia - SP;3503950;2981;Aspásia;SP
        2981;Assaí - PR;4101903;7437;Assaí;PR
        1303;Assaré - CE;2301604;1331;Assaré;CE
        4415;Assis - SP;3504008;6179;Assis;SP
        34;Assis Brasil - AC;1200054;157;Assis Brasil;AC
        2982;Assis Chateaubriand - PR;4102000;7953;Assis Chateaubriand;PR
        2210;Assunção - PB;2501351;450;Assunção;PB
        1886;Assunção do Piauí - PI;2201051;270;Assunção do Piauí;PI
        5064;Astolfo Dutra - MG;3104601;4091;Astolfo Dutra;MG
        2983;Astorga - PR;4102109;7439;Astorga;PR
        496;Atalaia - AL;2700409;2707;Atalaia;AL
        2984;Atalaia - PR;4102208;7441;Atalaia;PR
        112;Atalaia do Norte - AM;1300201;205;Atalaia do Norte;AM
        2685;Atalanta - SC;4201802;8035;Atalanta;SC
        5065;Ataléia - MG;3104700;4093;Ataléia;MG
        4416;Atibaia - SP;3504107;6181;Atibaia;SP
        253;Atílio Vivacqua - ES;3200706;5613;Atílio Vivacqua;ES
        611;Augustinópolis - TO;1702554;9685;Augustinópolis;TO
        886;Augusto Corrêa - PA;1500909;417;Augusto Corrêa;PA
        5066;Augusto de Lima - MG;3104809;4095;Augusto de Lima;MG
        3899;Augusto Pestana - RS;4301503;8529;Augusto Pestana;RS
        3900;Áurea - RS;4301552;8487;Áurea;RS
        3483;Aurelino Leal - BA;2902401;3347;Aurelino Leal;BA
        4417;Auriflama - SP;3504206;6183;Auriflama;SP
        2443;Aurilândia - GO;5202601;9251;Aurilândia;GO
        1304;Aurora - CE;2301703;1333;Aurora;CE
        2686;Aurora - SC;4201901;8037;Aurora;SC
        887;Aurora do Pará - PA;1500958;389;Aurora do Pará;PA
        612;Aurora do Tocantins - TO;1702703;9253;Aurora do Tocantins;TO
        113;Autazes - AM;1300300;207;Autazes;AM
        4418;Avaí - SP;3504305;6185;Avaí;SP
        4419;Avanhandava - SP;3504404;6187;Avanhandava;SP
        4420;Avaré - SP;3504503;6189;Avaré;SP
        888;Aveiro - PA;1501006;419;Aveiro;PA
        1887;Avelino Lopes - PI;2201101;1021;Avelino Lopes;PI
        2444;Avelinópolis - GO;5202809;9255;Avelinópolis;GO
        1672;Axixá - MA;2101103;721;Axixá;MA
        613;Axixá do Tocantins - TO;1702901;9257;Axixá do Tocantins;TO
        614;Babaçulândia - TO;1703008;9259;Babaçulândia;TO
        1673;Bacabal - MA;2101202;723;Bacabal;MA
        1674;Bacabeira - MA;2101251;116;Bacabeira;MA
        1675;Bacuri - MA;2101301;725;Bacuri;MA
        1676;Bacurituba - MA;2101350;118;Bacurituba;MA
        4421;Bady Bassitt - SP;3504602;6191;Bady Bassitt;SP
        5067;Baependi - MG;3104908;4097;Baependi;MG
        3901;Bagé - RS;4301602;8531;Bagé;RS
        889;Bagre - PA;1501105;421;Bagre;PA
        2211;Baía da Traição - PB;2501401;1929;Baía da Traição;PB
        1131;Baía Formosa - RN;2401404;1627;Baía Formosa;RN
        3484;Baianópolis - BA;2902500;3349;Baianópolis;BA
        890;Baião - PA;1501204;423;Baião;PA
        3485;Baixa Grande - BA;2902609;3351;Baixa Grande;BA
        1888;Baixa Grande do Ribeiro - PI;2201150;2245;Baixa Grande do Ribeiro;PI
        1305;Baixio - CE;2301802;1335;Baixio;CE
        254;Baixo Guandu - ES;3200805;5615;Baixo Guandu;ES
        4422;Balbinos - SP;3504701;6193;Balbinos;SP
        5068;Baldim - MG;3105004;4099;Baldim;MG
        2445;Baliza - GO;5203104;9261;Baliza;GO
        2687;Balneário Arroio do Silva - SC;4201950;888;Balneário Arroio do Silva;SC
        2688;Balneário Barra do Sul - SC;4202057;5549;Balneário Barra do Sul;SC
        2689;Balneário Camboriú - SC;4202008;8039;Balneário Camboriú;SC
        2690;Balneário Gaivota - SC;4202073;890;Balneário Gaivota;SC
        2691;Balneário Piçarras - SC;4212809;8251;Balneário Piçarras;SC
        3902;Balneário Pinhal - RS;4301636;954;Balneário Pinhal;RS
        2956;Balneário Rincão - SC;4220000;1192;Balneário Rincão;SC
        2985;Balsa Nova - PR;4102307;7443;Balsa Nova;PR
        4423;Bálsamo - SP;3504800;6195;Bálsamo;SP
        1677;Balsas - MA;2101400;727;Balsas;MA
        5069;Bambuí - MG;3105103;4101;Bambuí;MG
        1306;Banabuiú - CE;2301851;1233;Banabuiú;CE
        4424;Bananal - SP;3504909;6197;Bananal;SP
        2212;Bananeiras - PB;2501500;1931;Bananeiras;PB
        5070;Bandeira - MG;3105202;4103;Bandeira;MG
        5071;Bandeira do Sul - MG;3105301;4105;Bandeira do Sul;MG
        2692;Bandeirante - SC;4202081;892;Bandeirante;SC
        332;Bandeirantes - MS;5001508;9029;Bandeirantes;MS
        2986;Bandeirantes - PR;4102406;7445;Bandeirantes;PR
        615;Bandeirantes do Tocantins - TO;1703057;74;Bandeirantes do Tocantins;TO
        891;Bannach - PA;1501253;42;Bannach;PA
        3486;Banzaê - BA;2902658;3261;Banzaê;BA
        3903;Barão - RS;4301651;8485;Barão;RS
        4425;Barão de Antonina - SP;3505005;6201;Barão de Antonina;SP
        5072;Barão de Cocais - MG;3105400;4107;Barão de Cocais;MG
        3904;Barão de Cotegipe - RS;4301701;8533;Barão de Cotegipe;RS
        1678;Barão de Grajaú - MA;2101509;729;Barão de Grajaú;MA
        748;Barão de Melgaço - MT;5101605;9031;Barão de Melgaço;MT
        5073;Barão de Monte Alto - MG;3105509;4109;Barão de Monte Alto;MG
        3905;Barão do Triunfo - RS;4301750;5771;Barão do Triunfo;RS
        2213;Baraúna - PB;2501534;452;Baraúna;PB
        1132;Baraúna - RN;2401453;3003;Baraúna;RN
        5074;Barbacena - MG;3105608;4111;Barbacena;MG
        1307;Barbalha - CE;2301901;1337;Barbalha;CE
        4426;Barbosa - SP;3505104;6199;Barbosa;SP
        2987;Barbosa Ferraz - PR;4102505;7447;Barbosa Ferraz;PR
        892;Barcarena - PA;1501303;425;Barcarena;PA
        1133;Barcelona - RN;2401503;1629;Barcelona;RN
        114;Barcelos - AM;1300409;209;Barcelos;AM
        4427;Bariri - SP;3505203;6203;Bariri;SP
        3487;Barra - BA;2902708;3353;Barra;BA
        2693;Barra Bonita - SC;4202099;894;Barra Bonita;SC
        4428;Barra Bonita - SP;3505302;6205;Barra Bonita;SP
        3488;Barra da Estiva - BA;2902807;3355;Barra da Estiva;BA
        1889;Barra D'Alcântara - PI;2201176;272;Barra D'Alcântara;PI
        1483;Barra de Guabiraba - PE;2601300;2325;Barra de Guabiraba;PE
        2214;Barra de Santa Rosa - PB;2501609;1933;Barra de Santa Rosa;PB
        2215;Barra de Santana - PB;2501575;454;Barra de Santana;PB
        497;Barra de Santo Antônio - AL;2700508;2709;Barra de Santo Antônio;AL
        255;Barra de São Francisco - ES;3200904;5617;Barra de São Francisco;ES
        498;Barra de São Miguel - AL;2700607;2711;Barra de São Miguel;AL
        2216;Barra de São Miguel - PB;2501708;1935;Barra de São Miguel;PB
        749;Barra do Bugres - MT;5101704;9033;Barra do Bugres;MT
        4429;Barra do Chapéu - SP;3505351;2997;Barra do Chapéu;SP
        3489;Barra do Choça - BA;2902906;3357;Barra do Choça;BA
        1679;Barra do Corda - MA;2101608;731;Barra do Corda;MA
        750;Barra do Garças - MT;5101803;9035;Barra do Garças;MT
        3906;Barra do Guarita - RS;4301859;6069;Barra do Guarita;RS
        2988;Barra do Jacaré - PR;4102703;7451;Barra do Jacaré;PR
        3490;Barra do Mendes - BA;2903003;3359;Barra do Mendes;BA
        616;Barra do Ouro - TO;1703073;76;Barra do Ouro;TO
        407;Barra do Piraí - RJ;3300308;5805;Barra do Piraí;RJ
        3907;Barra do Quaraí - RS;4301875;956;Barra do Quaraí;RS
        3908;Barra do Ribeiro - RS;4301909;8537;Barra do Ribeiro;RS
        3909;Barra do Rio Azul - RS;4301925;5959;Barra do Rio Azul;RS
        3491;Barra do Rocha - BA;2903102;3361;Barra do Rocha;BA
        4430;Barra do Turvo - SP;3505401;6207;Barra do Turvo;SP
        174;Barra dos Coqueiros - SE;2800605;3111;Barra dos Coqueiros;SE
        3910;Barra Funda - RS;4301958;5943;Barra Funda;RS
        5075;Barra Longa - MG;3105707;4113;Barra Longa;MG
        408;Barra Mansa - RJ;3300407;5807;Barra Mansa;RJ
        2694;Barra Velha - SC;4202107;8041;Barra Velha;SC
        2989;Barracão - PR;4102604;7449;Barracão;PR
        3911;Barracão - RS;4301800;8535;Barracão;RS
        1890;Barras - PI;2201200;1023;Barras;PI
        1308;Barreira - CE;2301950;1235;Barreira;CE
        3492;Barreiras - BA;2903201;3363;Barreiras;BA
        1891;Barreiras do Piauí - PI;2201309;1025;Barreiras do Piauí;PI
        115;Barreirinha - AM;1300508;211;Barreirinha;AM
        1680;Barreirinhas - MA;2101707;733;Barreirinhas;MA
        1484;Barreiros - PE;2601409;2327;Barreiros;PE
        4431;Barretos - SP;3505500;6209;Barretos;SP
        4432;Barrinha - SP;3505609;6211;Barrinha;SP
        1309;Barro - CE;2302008;1339;Barro;CE
        3493;Barro Alto - BA;2903235;3075;Barro Alto;BA
        2446;Barro Alto - GO;5203203;9263;Barro Alto;GO
        1892;Barro Duro - PI;2201408;1027;Barro Duro;PI
        3494;Barro Preto - BA;2903300;3365;Barro Preto;BA
        3495;Barrocas - BA;2903276;1110;Barrocas;BA
        617;Barrolândia - TO;1703107;9693;Barrolândia;TO
        1310;Barroquinha - CE;2302057;1237;Barroquinha;CE
        3912;Barros Cassal - RS;4302006;8539;Barros Cassal;RS
        5076;Barroso - MG;3105905;4117;Barroso;MG
        4433;Barueri - SP;3505708;6213;Barueri;SP
        4434;Bastos - SP;3505807;6215;Bastos;SP
        333;Bataguassu - MS;5001904;9037;Bataguassu;MS
        499;Batalha - AL;2700706;2713;Batalha;AL
        1893;Batalha - PI;2201507;1029;Batalha;PI
        4435;Batatais - SP;3505906;6217;Batatais;SP
        334;Batayporã - MS;5002001;9039;Batayporã;MS
        1311;Baturité - CE;2302107;1341;Baturité;CE
        4436;Bauru - SP;3506003;6219;Bauru;SP
        2217;Bayeux - PB;2501807;1937;Bayeux;PB
        4437;Bebedouro - SP;3506102;6221;Bebedouro;SP
        1312;Beberibe - CE;2302206;1343;Beberibe;CE
        1313;Bela Cruz - CE;2302305;1345;Bela Cruz;CE
        335;Bela Vista - MS;5002100;9041;Bela Vista;MS
        2990;Bela Vista da Caroba - PR;4102752;834;Bela Vista da Caroba;PR
        2447;Bela Vista de Goiás - GO;5203302;9265;Bela Vista de Goiás;GO
        5077;Bela Vista de Minas - MG;3106002;4119;Bela Vista de Minas;MG
        1681;Bela Vista do Maranhão - MA;2101772;122;Bela Vista do Maranhão;MA
        2991;Bela Vista do Paraíso - PR;4102802;7453;Bela Vista do Paraíso;PR
        1894;Bela Vista do Piauí - PI;2201556;274;Bela Vista do Piauí;PI
        2695;Bela Vista do Toldo - SC;4202131;896;Bela Vista do Toldo;SC
        1682;Belágua - MA;2101731;120;Belágua;MA
        500;Belém - AL;2700805;2715;Belém;AL
        893;Belém - PA;1501402;427;Belém;PA
        2218;Belém - PB;2501906;1939;Belém;PB
        1485;Belém de Maria - PE;2601508;2329;Belém de Maria;PE
        2219;Belém do Brejo do Cruz - PB;2502003;1941;Belém do Brejo do Cruz;PB
        1895;Belém do Piauí - PI;2201572;276;Belém do Piauí;PI
        1486;Belém do São Francisco - PE;2601607;2331;Belém do São Francisco;PE
        409;Belford Roxo - RJ;3300456;2909;Belford Roxo;RJ
        5078;Belmiro Braga - MG;3106101;4121;Belmiro Braga;MG
        3496;Belmonte - BA;2903409;3367;Belmonte;BA
        2696;Belmonte - SC;4202156;5745;Belmonte;SC
        3497;Belo Campo - BA;2903508;3369;Belo Campo;BA
        5079;Belo Horizonte - MG;3106200;4123;Belo Horizonte;MG
        1487;Belo Jardim - PE;2601706;2333;Belo Jardim;PE
        501;Belo Monte - AL;2700904;2717;Belo Monte;AL
        5080;Belo Oriente - MG;3106309;4125;Belo Oriente;MG
        5081;Belo Vale - MG;3106408;4127;Belo Vale;MG
        894;Belterra - PA;1501451;44;Belterra;PA
        1896;Beneditinos - PI;2201606;1031;Beneditinos;PI
        1683;Benedito Leite - MA;2101806;735;Benedito Leite;MA
        2697;Benedito Novo - SC;4202206;8043;Benedito Novo;SC
        895;Benevides - PA;1501501;429;Benevides;PA
        116;Benjamin Constant - AM;1300607;213;Benjamin Constant;AM
        3913;Benjamin Constant do Sul - RS;4302055;958;Benjamin Constant do Sul;RS
        4438;Bento de Abreu - SP;3506201;6223;Bento de Abreu;SP
        1134;Bento Fernandes - RN;2401602;1631;Bento Fernandes;RN
        3914;Bento Gonçalves - RS;4302105;8541;Bento Gonçalves;RS
        1684;Bequimão - MA;2101905;737;Bequimão;MA
        5082;Berilo - MG;3106507;4129;Berilo;MG
        5083;Berizal - MG;3106655;570;Berizal;MG
        2220;Bernardino Batista - PB;2502052;456;Bernardino Batista;PB
        4439;Bernardino de Campos - SP;3506300;6225;Bernardino de Campos;SP
        1685;Bernardo do Mearim - MA;2101939;124;Bernardo do Mearim;MA
        618;Bernardo Sayão - TO;1703206;9695;Bernardo Sayão;TO
        4440;Bertioga - SP;3506359;2965;Bertioga;SP
        1897;Bertolínia - PI;2201705;1033;Bertolínia;PI
        5084;Bertópolis - MG;3106606;4131;Bertópolis;MG
        117;Beruri - AM;1300631;295;Beruri;AM
        1488;Betânia - PE;2601805;2335;Betânia;PE
        1898;Betânia do Piauí - PI;2201739;278;Betânia do Piauí;PI
        5085;Betim - MG;3106705;4133;Betim;MG
        1489;Bezerros - PE;2601904;2337;Bezerros;PE
        5086;Bias Fortes - MG;3106804;4135;Bias Fortes;MG
        5087;Bicas - MG;3106903;4137;Bicas;MG
        2698;Biguaçu - SC;4202305;8045;Biguaçu;SC
        4441;Bilac - SP;3506409;6227;Bilac;SP
        5088;Biquinhas - MG;3107000;4139;Biquinhas;MG
        4442;Birigui - SP;3506508;6229;Birigui;SP
        4443;Biritiba Mirim - SP;3506607;6231;Biritiba Mirim;SP
        3498;Biritinga - BA;2903607;3371;Biritinga;BA
        2992;Bituruna - PR;4102901;7455;Bituruna;PR
        2699;Blumenau - SC;4202404;8047;Blumenau;SC
        256;Boa Esperança - ES;3201001;5619;Boa Esperança;ES
        5089;Boa Esperança - MG;3107109;4141;Boa Esperança;MG
        2993;Boa Esperança - PR;4103008;7457;Boa Esperança;PR
        2994;Boa Esperança do Iguaçu - PR;4103024;5471;Boa Esperança do Iguaçu;PR
        4444;Boa Esperança do Sul - SP;3506706;6233;Boa Esperança do Sul;SP
        1899;Boa Hora - PI;2201770;280;Boa Hora;PI
        3499;Boa Nova - BA;2903706;3373;Boa Nova;BA
        2221;Boa Ventura - PB;2502102;1943;Boa Ventura;PB
        2995;Boa Ventura de São Roque - PR;4103040;836;Boa Ventura de São Roque;PR
        1314;Boa Viagem - CE;2302404;1347;Boa Viagem;CE
        2222;Boa Vista - PB;2502151;458;Boa Vista;PB
        4;Boa Vista - RR;1400100;301;Boa Vista;RR
        2996;Boa Vista da Aparecida - PR;4103057;7981;Boa Vista da Aparecida;PR
        3915;Boa Vista das Missões - RS;4302154;5981;Boa Vista das Missões;RS
        3916;Boa Vista do Buricá - RS;4302204;8543;Boa Vista do Buricá;RS
        3917;Boa Vista do Cadeado - RS;4302220;1124;Boa Vista do Cadeado;RS
        1686;Boa Vista do Gurupi - MA;2101970;126;Boa Vista do Gurupi;MA
        3918;Boa Vista do Incra - RS;4302238;1126;Boa Vista do Incra;RS
        118;Boa Vista do Ramos - AM;1300680;297;Boa Vista do Ramos;AM
        3919;Boa Vista do Sul - RS;4302253;960;Boa Vista do Sul;RS
        3500;Boa Vista do Tupim - BA;2903805;3375;Boa Vista do Tupim;BA
        502;Boca da Mata - AL;2701001;2719;Boca da Mata;AL
        119;Boca do Acre - AM;1300706;215;Boca do Acre;AM
        1900;Bocaina - PI;2201804;1035;Bocaina;PI
        4445;Bocaina - SP;3506805;6235;Bocaina;SP
        5090;Bocaina de Minas - MG;3107208;4143;Bocaina de Minas;MG
        2700;Bocaina do Sul - SC;4202438;898;Bocaina do Sul;SC
        5091;Bocaiúva - MG;3107307;4145;Bocaiúva;MG
        2997;Bocaiúva do Sul - PR;4103107;7459;Bocaiúva do Sul;PR
        1135;Bodó - RN;2401651;412;Bodó;RN
        1490;Bodocó - PE;2602001;2339;Bodocó;PE
        336;Bodoquena - MS;5002159;9801;Bodoquena;MS
        4446;Bofete - SP;3506904;6237;Bofete;SP
        4447;Boituva - SP;3507001;6239;Boituva;SP
        1491;Bom Conselho - PE;2602100;2341;Bom Conselho;PE
        5092;Bom Despacho - MG;3107406;4147;Bom Despacho;MG
        1687;Bom Jardim - MA;2102002;955;Bom Jardim;MA
        1492;Bom Jardim - PE;2602209;2343;Bom Jardim;PE
        410;Bom Jardim - RJ;3300506;5809;Bom Jardim;RJ
        2701;Bom Jardim da Serra - SC;4202503;8389;Bom Jardim da Serra;SC
        2448;Bom Jardim de Goiás - GO;5203401;9267;Bom Jardim de Goiás;GO
        5093;Bom Jardim de Minas - MG;3107505;4149;Bom Jardim de Minas;MG
        2223;Bom Jesus - PB;2502201;1945;Bom Jesus;PB
        1901;Bom Jesus - PI;2201903;1037;Bom Jesus;PI
        1136;Bom Jesus - RN;2401701;1633;Bom Jesus;RN
        3920;Bom Jesus - RS;4302303;8545;Bom Jesus;RS
        2702;Bom Jesus - SC;4202537;900;Bom Jesus;SC
        3501;Bom Jesus da Lapa - BA;2903904;3377;Bom Jesus da Lapa;BA
        5094;Bom Jesus da Penha - MG;3107604;4151;Bom Jesus da Penha;MG
        3502;Bom Jesus da Serra - BA;2903953;3263;Bom Jesus da Serra;BA
        1688;Bom Jesus das Selvas - MA;2102036;128;Bom Jesus das Selvas;MA
        2449;Bom Jesus de Goiás - GO;5203500;9269;Bom Jesus de Goiás;GO
        5095;Bom Jesus do Amparo - MG;3107703;4153;Bom Jesus do Amparo;MG
        751;Bom Jesus do Araguaia - MT;5101852;1078;Bom Jesus do Araguaia;MT
        5096;Bom Jesus do Galho - MG;3107802;4155;Bom Jesus do Galho;MG
        411;Bom Jesus do Itabapoana - RJ;3300605;5811;Bom Jesus do Itabapoana;RJ
        257;Bom Jesus do Norte - ES;3201100;5621;Bom Jesus do Norte;ES
        2703;Bom Jesus do Oeste - SC;4202578;902;Bom Jesus do Oeste;SC
        2998;Bom Jesus do Sul - PR;4103156;838;Bom Jesus do Sul;PR
        896;Bom Jesus do Tocantins - PA;1501576;575;Bom Jesus do Tocantins;PA
        619;Bom Jesus do Tocantins - TO;1703305;341;Bom Jesus do Tocantins;TO
        4448;Bom Jesus dos Perdões - SP;3507100;6241;Bom Jesus dos Perdões;SP
        1689;Bom Lugar - MA;2102077;130;Bom Lugar;MA
        3921;Bom Princípio - RS;4302352;9823;Bom Princípio;RS
        1902;Bom Princípio do Piauí - PI;2201919;2287;Bom Princípio do Piauí;PI
        3922;Bom Progresso - RS;4302378;6071;Bom Progresso;RS
        5097;Bom Repouso - MG;3107901;4157;Bom Repouso;MG
        2704;Bom Retiro - SC;4202602;8049;Bom Retiro;SC
        3923;Bom Retiro do Sul - RS;4302402;8547;Bom Retiro do Sul;RS
        5098;Bom Sucesso - MG;3108008;4159;Bom Sucesso;MG
        2224;Bom Sucesso - PB;2502300;1947;Bom Sucesso;PB
        2999;Bom Sucesso - PR;4103206;7461;Bom Sucesso;PR
        4449;Bom Sucesso de Itararé - SP;3507159;3059;Bom Sucesso de Itararé;SP
        3000;Bom Sucesso do Sul - PR;4103222;9979;Bom Sucesso do Sul;PR
        2705;Bombinhas - SC;4202453;5537;Bombinhas;SC
        5099;Bonfim - MG;3108107;4161;Bonfim;MG
        5;Bonfim - RR;1400159;307;Bonfim;RR
        1903;Bonfim do Piauí - PI;2201929;2251;Bonfim do Piauí;PI
        2450;Bonfinópolis - GO;5203559;9775;Bonfinópolis;GO
        5100;Bonfinópolis de Minas - MG;3108206;4163;Bonfinópolis de Minas;MG
        3503;Boninal - BA;2904001;3379;Boninal;BA
        3504;Bonito - BA;2904050;3265;Bonito;BA
        337;Bonito - MS;5002209;9043;Bonito;MS
        897;Bonito - PA;1501600;431;Bonito;PA
        1493;Bonito - PE;2602308;2345;Bonito;PE
        5101;Bonito de Minas - MG;3108255;572;Bonito de Minas;MG
        2225;Bonito de Santa Fé - PB;2502409;1949;Bonito de Santa Fé;PB
        2451;Bonópolis - GO;5203575;1056;Bonópolis;GO
        2226;Boqueirão - PB;2502508;1951;Boqueirão;PB
        3924;Boqueirão do Leão - RS;4302451;8483;Boqueirão do Leão;RS
        1904;Boqueirão do Piauí - PI;2201945;282;Boqueirão do Piauí;PI
        175;Boquim - SE;2800670;3115;Boquim;SE
        3505;Boquira - BA;2904100;3381;Boquira;BA
        4450;Borá - SP;3507209;6243;Borá;SP
        4451;Boracéia - SP;3507308;6245;Boracéia;SP
        120;Borba - AM;1300805;217;Borba;AM
        2227;Borborema - PB;2502706;1955;Borborema;PB
        4452;Borborema - SP;3507407;6247;Borborema;SP
        5102;Borda da Mata - MG;3108305;4165;Borda da Mata;MG
        4453;Borebi - SP;3507456;7247;Borebi;SP
        3001;Borrazópolis - PR;4103305;7463;Borrazópolis;PR
        3925;Bossoroca - RS;4302501;8549;Bossoroca;RS
        5103;Botelhos - MG;3108404;4167;Botelhos;MG
        4454;Botucatu - SP;3507506;6249;Botucatu;SP
        5104;Botumirim - MG;3108503;4169;Botumirim;MG
        3506;Botuporã - BA;2904209;3383;Botuporã;BA
        2706;Botuverá - SC;4202701;8051;Botuverá;SC
        3926;Bozano - RS;4302584;1128;Bozano;RS
        2707;Braço do Norte - SC;4202800;8053;Braço do Norte;SC
        2708;Braço do Trombudo - SC;4202859;5557;Braço do Trombudo;SC
        3927;Braga - RS;4302600;8551;Braga;RS
        898;Bragança - PA;1501709;433;Bragança;PA
        4455;Bragança Paulista - SP;3507605;6251;Bragança Paulista;SP
        3002;Braganey - PR;4103354;7983;Braganey;PR
        503;Branquinha - AL;2701100;2721;Branquinha;AL
        5105;Brás Pires - MG;3108701;4173;Brás Pires;MG
        899;Brasil Novo - PA;1501725;639;Brasil Novo;PA
        338;Brasilândia - MS;5002308;9045;Brasilândia;MS
        5106;Brasilândia de Minas - MG;3108552;574;Brasilândia de Minas;MG
        3003;Brasilândia do Sul - PR;4103370;5521;Brasilândia do Sul;PR
        620;Brasilândia do Tocantins - TO;1703602;339;Brasilândia do Tocantins;TO
        35;Brasiléia - AC;1200104;105;Brasiléia;AC
        1905;Brasileira - PI;2201960;2283;Brasileira;PI
        1;Brasília - DF;5300108;9701;Brasília;DF
        5107;Brasília de Minas - MG;3108602;4171;Brasília de Minas;MG
        752;Brasnorte - MT;5101902;9873;Brasnorte;MT
        4456;Braúna - SP;3507704;6255;Braúna;SP
        5109;Braúnas - MG;3108800;4175;Braúnas;MG
        2452;Brazabrantes - GO;5203609;9271;Brazabrantes;GO
        5108;Brazópolis - MG;3108909;4177;Brazópolis;MG
        1494;Brejão - PE;2602407;2347;Brejão;PE
        258;Brejetuba - ES;3201159;758;Brejetuba;ES
        1495;Brejinho - PE;2602506;2349;Brejinho;PE
        1137;Brejinho - RN;2401800;1635;Brejinho;RN
        621;Brejinho de Nazaré - TO;1703701;9273;Brejinho de Nazaré;TO
        1690;Brejo - MA;2102101;739;Brejo;MA
        4457;Brejo Alegre - SP;3507753;792;Brejo Alegre;SP
        1496;Brejo da Madre de Deus - PE;2602605;2351;Brejo da Madre de Deus;PE
        1691;Brejo de Areia - MA;2102150;132;Brejo de Areia;MA
        2228;Brejo do Cruz - PB;2502805;1957;Brejo do Cruz;PB
        1906;Brejo do Piauí - PI;2201988;284;Brejo do Piauí;PI
        2229;Brejo dos Santos - PB;2502904;1959;Brejo dos Santos;PB
        176;Brejo Grande - SE;2800704;3113;Brejo Grande;SE
        900;Brejo Grande do Araguaia - PA;1501758;577;Brejo Grande do Araguaia;PA
        1315;Brejo Santo - CE;2302503;1349;Brejo Santo;CE
        3507;Brejões - BA;2904308;3385;Brejões;BA
        3508;Brejolândia - BA;2904407;3387;Brejolândia;BA
        901;Breu Branco - PA;1501782;625;Breu Branco;PA
        902;Breves - PA;1501808;435;Breves;PA
        2453;Britânia - GO;5203807;9275;Britânia;GO
        3928;Brochier - RS;4302659;8449;Brochier;RS
        4458;Brodowski - SP;3507803;6257;Brodowski;SP
        4459;Brotas - SP;3507902;6259;Brotas;SP
        3509;Brotas de Macaúbas - BA;2904506;3389;Brotas de Macaúbas;BA
        5110;Brumadinho - MG;3109006;4179;Brumadinho;MG
        3510;Brumado - BA;2904605;3391;Brumado;BA
        2709;Brunópolis - SC;4202875;904;Brunópolis;SC
        2710;Brusque - SC;4202909;8055;Brusque;SC
        5111;Bueno Brandão - MG;3109105;4181;Bueno Brandão;MG
        5112;Buenópolis - MG;3109204;4183;Buenópolis;MG
        1497;Buenos Aires - PE;2602704;2353;Buenos Aires;PE
        3511;Buerarema - BA;2904704;3393;Buerarema;BA
        5113;Bugre - MG;3109253;576;Bugre;MG
        1498;Buíque - PE;2602803;2355;Buíque;PE
        36;Bujari - AC;1200138;645;Bujari;AC
        903;Bujaru - PA;1501907;437;Bujaru;PA
        4460;Buri - SP;3508009;6261;Buri;SP
        4461;Buritama - SP;3508108;6263;Buritama;SP
        1692;Buriti - MA;2102200;741;Buriti;MA
        2454;Buriti Alegre - GO;5203906;9277;Buriti Alegre;GO
        1693;Buriti Bravo - MA;2102309;743;Buriti Bravo;MA
        2455;Buriti de Goiás - GO;5203939;63;Buriti de Goiás;GO
        622;Buriti do Tocantins - TO;1703800;9715;Buriti do Tocantins;TO
        1907;Buriti dos Lopes - PI;2202000;1039;Buriti dos Lopes;PI
        1908;Buriti dos Montes - PI;2202026;1297;Buriti dos Montes;PI
        1694;Buriticupu - MA;2102325;134;Buriticupu;MA
        2456;Buritinópolis - GO;5203962;61;Buritinópolis;GO
        3512;Buritirama - BA;2904753;3079;Buritirama;BA
        1695;Buritirana - MA;2102358;136;Buritirana;MA
        5114;Buritis - MG;3109303;4185;Buritis;MG
        60;Buritis - RO;1100452;4;Buritis;RO
        4462;Buritizal - SP;3508207;6265;Buritizal;SP
        5115;Buritizeiro - MG;3109402;4187;Buritizeiro;MG
        3929;Butiá - RS;4302709;8553;Butiá;RS
        121;Caapiranga - AM;1300839;299;Caapiranga;AM
        2230;Caaporã - PB;2503001;1961;Caaporã;PB
        339;Caarapó - MS;5002407;9055;Caarapó;MS
        3513;Caatiba - BA;2904803;3395;Caatiba;BA
        2231;Cabaceiras - PB;2503100;1963;Cabaceiras;PB
        3514;Cabaceiras do Paraguaçu - BA;2904852;3267;Cabaceiras do Paraguaçu;BA
        5116;Cabeceira Grande - MG;3109451;578;Cabeceira Grande;MG
        2457;Cabeceiras - GO;5204003;9279;Cabeceiras;GO
        1909;Cabeceiras do Piauí - PI;2202059;1299;Cabeceiras do Piauí;PI
        2232;Cabedelo - PB;2503209;1965;Cabedelo;PB
        61;Cabixi - RO;1100031;37;Cabixi;RO
        1499;Cabo de Santo Agostinho - PE;2602902;2357;Cabo de Santo Agostinho;PE
        412;Cabo Frio - RJ;3300704;5813;Cabo Frio;RJ
        5117;Cabo Verde - MG;3109501;4189;Cabo Verde;MG
        4463;Cabrália Paulista - SP;3508306;6267;Cabrália Paulista;SP
        4464;Cabreúva - SP;3508405;6269;Cabreúva;SP
        1500;Cabrobó - PE;2603009;2359;Cabrobó;PE
        2711;Caçador - SC;4203006;8057;Caçador;SC
        4465;Caçapava - SP;3508504;6271;Caçapava;SP
        3930;Caçapava do Sul - RS;4302808;8555;Caçapava do Sul;RS
        62;Cacaulândia - RO;1100601;677;Cacaulândia;RO
        3931;Cacequi - RS;4302907;8557;Cacequi;RS
        753;Cáceres - MT;5102504;9047;Cáceres;MT
        3515;Cachoeira - BA;2904902;3397;Cachoeira;BA
        2458;Cachoeira Alta - GO;5204102;9281;Cachoeira Alta;GO
        5118;Cachoeira da Prata - MG;3109600;4191;Cachoeira da Prata;MG
        2459;Cachoeira de Goiás - GO;5204201;9283;Cachoeira de Goiás;GO
        5119;Cachoeira de Minas - MG;3109709;4193;Cachoeira de Minas;MG
        5120;Cachoeira de Pajeú - MG;3102704;4053;Cachoeira de Pajeú;MG
        904;Cachoeira do Arari - PA;1502004;439;Cachoeira do Arari;PA
        905;Cachoeira do Piriá - PA;1501956;46;Cachoeira do Piriá;PA
        3932;Cachoeira do Sul - RS;4303004;8559;Cachoeira do Sul;RS
        2233;Cachoeira dos Índios - PB;2503308;1967;Cachoeira dos Índios;PB
        2460;Cachoeira Dourada - GO;5204250;9673;Cachoeira Dourada;GO
        5121;Cachoeira Dourada - MG;3109808;4195;Cachoeira Dourada;MG
        1696;Cachoeira Grande - MA;2102374;138;Cachoeira Grande;MA
        4466;Cachoeira Paulista - SP;3508603;6273;Cachoeira Paulista;SP
        413;Cachoeiras de Macacu - RJ;3300803;5815;Cachoeiras de Macacu;RJ
        1501;Cachoeirinha - PE;2603108;2361;Cachoeirinha;PE
        3933;Cachoeirinha - RS;4303103;8561;Cachoeirinha;RS
        623;Cachoeirinha - TO;1703826;171;Cachoeirinha;TO
        259;Cachoeiro de Itapemirim - ES;3201209;5623;Cachoeiro de Itapemirim;ES
        2234;Cacimba de Areia - PB;2503407;1969;Cacimba de Areia;PB
        2235;Cacimba de Dentro - PB;2503506;1971;Cacimba de Dentro;PB
        2236;Cacimbas - PB;2503555;460;Cacimbas;PB
        504;Cacimbinhas - AL;2701209;2723;Cacimbinhas;AL
        3934;Cacique Doble - RS;4303202;8563;Cacique Doble;RS
        63;Cacoal - RO;1100049;9;Cacoal;RO
        4467;Caconde - SP;3508702;6275;Caconde;SP
        2461;Caçu - GO;5204300;9285;Caçu;GO
        3516;Caculé - BA;2905008;3399;Caculé;BA
        3517;Caém - BA;2905107;3401;Caém;BA
        5122;Caetanópolis - MG;3109907;4197;Caetanópolis;MG
        3518;Caetanos - BA;2905156;3269;Caetanos;BA
        5123;Caeté - MG;3110004;4199;Caeté;MG
        1502;Caetés - PE;2603207;2363;Caetés;PE
        3519;Caetité - BA;2905206;3403;Caetité;BA
        3520;Cafarnaum - BA;2905305;3405;Cafarnaum;BA
        3004;Cafeara - PR;4103404;7465;Cafeara;PR
        3005;Cafelândia - PR;4103453;7985;Cafelândia;PR
        4468;Cafelândia - SP;3508801;6277;Cafelândia;SP
        3006;Cafezal do Sul - PR;4103479;5491;Cafezal do Sul;PR
        4469;Caiabu - SP;3508900;6279;Caiabu;SP
        5124;Caiana - MG;3110103;4201;Caiana;MG
        2462;Caiapônia - GO;5204409;9287;Caiapônia;GO
        3935;Caibaté - RS;4303301;8565;Caibaté;RS
        2712;Caibi - SC;4203105;8059;Caibi;SC
        2237;Caiçara - PB;2503605;1973;Caiçara;PB
        3936;Caiçara - RS;4303400;8567;Caiçara;RS
        1138;Caiçara do Norte - RN;2401859;414;Caiçara do Norte;RN
        1139;Caiçara do Rio do Vento - RN;2401909;1637;Caiçara do Rio do Vento;RN
        1140;Caicó - RN;2402006;1639;Caicó;RN
        4470;Caieiras - SP;3509007;6281;Caieiras;SP
        3521;Cairu - BA;2905404;3407;Cairu;BA
        4471;Caiuá - SP;3509106;6283;Caiuá;SP
        4472;Cajamar - SP;3509205;6285;Cajamar;SP
        1697;Cajapió - MA;2102408;745;Cajapió;MA
        1698;Cajari - MA;2102507;747;Cajari;MA
        4473;Cajati - SP;3509254;2967;Cajati;SP
        2238;Cajazeiras - PB;2503704;1975;Cajazeiras;PB
        1910;Cajazeiras do Piauí - PI;2202075;286;Cajazeiras do Piauí;PI
        2239;Cajazeirinhas - PB;2503753;462;Cajazeirinhas;PB
        4474;Cajobi - SP;3509304;6287;Cajobi;SP
        505;Cajueiro - AL;2701308;2725;Cajueiro;AL
        1911;Cajueiro da Praia - PI;2202083;288;Cajueiro da Praia;PI
        5125;Cajuri - MG;3110202;4203;Cajuri;MG
        4475;Cajuru - SP;3509403;6289;Cajuru;SP
        1503;Calçado - PE;2603306;2365;Calçado;PE
        18;Calçoene - AP;1600204;603;Calçoene;AP
        5126;Caldas - MG;3110301;4205;Caldas;MG
        2240;Caldas Brandão - PB;2503803;1977;Caldas Brandão;PB
        2463;Caldas Novas - GO;5204508;9289;Caldas Novas;GO
        2464;Caldazinha - GO;5204557;31;Caldazinha;GO
        3522;Caldeirão Grande - BA;2905503;3409;Caldeirão Grande;BA
        1912;Caldeirão Grande do Piauí - PI;2202091;2271;Caldeirão Grande do Piauí;PI
        3007;Califórnia - PR;4103503;7467;Califórnia;PR
        2713;Calmon - SC;4203154;5553;Calmon;SC
        1504;Calumbi - PE;2603405;2367;Calumbi;PE
        3523;Camacan - BA;2905602;3411;Camacan;BA
        3524;Camaçari - BA;2905701;3413;Camaçari;BA
        5127;Camacho - MG;3110400;4207;Camacho;MG
        2241;Camalaú - PB;2503902;1979;Camalaú;PB
        3525;Camamu - BA;2905800;3415;Camamu;BA
        5128;Camanducaia - MG;3110509;4209;Camanducaia;MG
        340;Camapuã - MS;5002605;9049;Camapuã;MS
        3937;Camaquã - RS;4303509;8569;Camaquã;RS
        1505;Camaragibe - PE;2603454;2629;Camaragibe;PE
        3938;Camargo - RS;4303558;8447;Camargo;RS
        3008;Cambará - PR;4103602;7469;Cambará;PR
        3939;Cambará do Sul - RS;4303608;8571;Cambará do Sul;RS
        3009;Cambé - PR;4103701;7471;Cambé;PR
        3010;Cambira - PR;4103800;7473;Cambira;PR
        2714;Camboriú - SC;4203204;8061;Camboriú;SC
        414;Cambuci - RJ;3300902;5817;Cambuci;RJ
        5129;Cambuí - MG;3110608;4211;Cambuí;MG
        5130;Cambuquira - MG;3110707;4213;Cambuquira;MG
        906;Cametá - PA;1502103;441;Cametá;PA
        1316;Camocim - CE;2302602;1351;Camocim;CE
        1506;Camocim de São Félix - PE;2603504;2369;Camocim de São Félix;PE
        5131;Campanário - MG;3110806;4215;Campanário;MG
        5132;Campanha - MG;3110905;4217;Campanha;MG
        506;Campestre - AL;2701357;560;Campestre;AL
        5133;Campestre - MG;3111002;4219;Campestre;MG
        3940;Campestre da Serra - RS;4303673;6013;Campestre da Serra;RS
        2465;Campestre de Goiás - GO;5204607;9291;Campestre de Goiás;GO
        1699;Campestre do Maranhão - MA;2102556;140;Campestre do Maranhão;MA
        3011;Campina da Lagoa - PR;4103909;7475;Campina da Lagoa;PR
        3941;Campina das Missões - RS;4303707;8573;Campina das Missões;RS
        4476;Campina do Monte Alegre - SP;3509452;2999;Campina do Monte Alegre;SP
        3012;Campina do Simão - PR;4103958;840;Campina do Simão;PR
        2242;Campina Grande - PB;2504009;1981;Campina Grande;PB
        3013;Campina Grande do Sul - PR;4104006;7477;Campina Grande do Sul;PR
        5134;Campina Verde - MG;3111101;4221;Campina Verde;MG
        2466;Campinaçu - GO;5204656;9687;Campinaçu;GO
        754;Campinápolis - MT;5102603;9863;Campinápolis;MT
        4477;Campinas - SP;3509502;6291;Campinas;SP
        1913;Campinas do Piauí - PI;2202109;1041;Campinas do Piauí;PI
        3942;Campinas do Sul - RS;4303806;8575;Campinas do Sul;RS
        2467;Campinorte - GO;5204706;9293;Campinorte;GO
        507;Campo Alegre - AL;2701407;2727;Campo Alegre;AL
        2715;Campo Alegre - SC;4203303;8063;Campo Alegre;SC
        2468;Campo Alegre de Goiás - GO;5204805;9295;Campo Alegre de Goiás;GO
        3526;Campo Alegre de Lourdes - BA;2905909;3417;Campo Alegre de Lourdes;BA
        1914;Campo Alegre do Fidalgo - PI;2202117;290;Campo Alegre do Fidalgo;PI
        5135;Campo Azul - MG;3111150;580;Campo Azul;MG
        5136;Campo Belo - MG;3111200;4223;Campo Belo;MG
        2716;Campo Belo do Sul - SC;4203402;8065;Campo Belo do Sul;SC
        3943;Campo Bom - RS;4303905;8577;Campo Bom;RS
        3014;Campo Bonito - PR;4104055;8475;Campo Bonito;PR
        177;Campo do Brito - SE;2801009;3119;Campo do Brito;SE
        5137;Campo do Meio - MG;3111309;4225;Campo do Meio;MG
        3015;Campo do Tenente - PR;4104105;7479;Campo do Tenente;PR
        2717;Campo Erê - SC;4203501;8067;Campo Erê;SC
        5138;Campo Florido - MG;3111408;4227;Campo Florido;MG
        3527;Campo Formoso - BA;2906006;3419;Campo Formoso;BA
        508;Campo Grande - AL;2701506;2729;Campo Grande;AL
        341;Campo Grande - MS;5002704;9051;Campo Grande;MS
        1130;Campo Grande - RN;2401305;1625;Campo Grande;RN
        1915;Campo Grande do Piauí - PI;2202133;292;Campo Grande do Piauí;PI
        3016;Campo Largo - PR;4104204;7481;Campo Largo;PR
        1916;Campo Largo do Piauí - PI;2202174;294;Campo Largo do Piauí;PI
        2469;Campo Limpo de Goiás - GO;5204854;1070;Campo Limpo de Goiás;GO
        4478;Campo Limpo Paulista - SP;3509601;6293;Campo Limpo Paulista;SP
        3017;Campo Magro - PR;4104253;842;Campo Magro;PR
        1917;Campo Maior - PI;2202208;1043;Campo Maior;PI
        3018;Campo Mourão - PR;4104303;7483;Campo Mourão;PR
        3944;Campo Novo - RS;4304002;8579;Campo Novo;RS
        64;Campo Novo de Rondônia - RO;1100700;679;Campo Novo de Rondônia;RO
        755;Campo Novo do Parecis - MT;5102637;9777;Campo Novo do Parecis;MT
        1141;Campo Redondo - RN;2402105;1641;Campo Redondo;RN
        756;Campo Verde - MT;5102678;9779;Campo Verde;MT
        5139;Campos Altos - MG;3111507;4229;Campos Altos;MG
        2470;Campos Belos - GO;5204904;9297;Campos Belos;GO
        3945;Campos Borges - RS;4304101;8445;Campos Borges;RS
        757;Campos de Júlio - MT;5102686;1032;Campos de Júlio;MT
        4479;Campos do Jordão - SP;3509700;6295;Campos do Jordão;SP
        415;Campos dos Goytacazes - RJ;3301009;5819;Campos dos Goytacazes;RJ
        5140;Campos Gerais - MG;3111606;4231;Campos Gerais;MG
        624;Campos Lindos - TO;1703842;173;Campos Lindos;TO
        2718;Campos Novos - SC;4203600;8069;Campos Novos;SC
        4480;Campos Novos Paulista - SP;3509809;6297;Campos Novos Paulista;SP
        1317;Campos Sales - CE;2302701;1353;Campos Sales;CE
        2471;Campos Verdes - GO;5204953;9781;Campos Verdes;GO
        1507;Camutanga - PE;2603603;2371;Camutanga;PE
        5141;Cana Verde - MG;3111903;4237;Cana Verde;MG
        5142;Canaã - MG;3111705;4233;Canaã;MG
        907;Canaã dos Carajás - PA;1502152;48;Canaã dos Carajás;PA
        758;Canabrava do Norte - MT;5102694;129;Canabrava do Norte;MT
        4481;Cananéia - SP;3509908;6299;Cananéia;SP
        509;Canapi - AL;2701605;2731;Canapi;AL
        3528;Canápolis - BA;2906105;3421;Canápolis;BA
        5143;Canápolis - MG;3111804;4235;Canápolis;MG
        3529;Canarana - BA;2906204;3423;Canarana;BA
        759;Canarana - MT;5102702;9193;Canarana;MT
        4482;Canas - SP;3509957;794;Canas;SP
        1918;Canavieira - PI;2202251;2247;Canavieira;PI
        3530;Canavieiras - BA;2906303;3425;Canavieiras;BA
        3531;Candeal - BA;2906402;3427;Candeal;BA
        3532;Candeias - BA;2906501;3429;Candeias;BA
        5144;Candeias - MG;3112000;4239;Candeias;MG
        65;Candeias do Jamari - RO;1100809;681;Candeias do Jamari;RO
        3946;Candelária - RS;4304200;8581;Candelária;RS
        3533;Candiba - BA;2906600;3431;Candiba;BA
        3019;Cândido de Abreu - PR;4104402;7485;Cândido de Abreu;PR
        3947;Cândido Godói - RS;4304309;8583;Cândido Godói;RS
        1700;Cândido Mendes - MA;2102606;749;Cândido Mendes;MA
        4483;Cândido Mota - SP;3510005;6301;Cândido Mota;SP
        4484;Cândido Rodrigues - SP;3510104;6303;Cândido Rodrigues;SP
        3534;Cândido Sales - BA;2906709;3433;Cândido Sales;BA
        3948;Candiota - RS;4304358;6083;Candiota;RS
        3020;Candói - PR;4104428;5499;Candói;PR
        3949;Canela - RS;4304408;8585;Canela;RS
        2719;Canelinha - SC;4203709;8071;Canelinha;SC
        1142;Canguaretama - RN;2402204;1643;Canguaretama;RN
        3950;Canguçu - RS;4304507;8587;Canguçu;RS
        178;Canhoba - SE;2801108;3121;Canhoba;SE
        1508;Canhotinho - PE;2603702;2373;Canhotinho;PE
        1318;Canindé - CE;2302800;1355;Canindé;CE
        179;Canindé de São Francisco - SE;2801207;3123;Canindé de São Francisco;SE
        4485;Canitar - SP;3510153;2947;Canitar;SP
        3951;Canoas - RS;4304606;8589;Canoas;RS
        2720;Canoinhas - SC;4203808;8073;Canoinhas;SC
        3535;Cansanção - BA;2906808;3435;Cansanção;BA
        6;Cantá - RR;1400175;28;Cantá;RR
        5145;Cantagalo - MG;3112059;582;Cantagalo;MG
        3021;Cantagalo - PR;4104451;8451;Cantagalo;PR
        416;Cantagalo - RJ;3301108;5821;Cantagalo;RJ
        1701;Cantanhede - MA;2102705;751;Cantanhede;MA
        1919;Canto do Buriti - PI;2202307;1045;Canto do Buriti;PI
        3536;Canudos - BA;2906824;3085;Canudos;BA
        3952;Canudos do Vale - RS;4304614;1130;Canudos do Vale;RS
        122;Canutama - AM;1300904;219;Canutama;AM
        908;Capanema - PA;1502202;443;Capanema;PA
        3022;Capanema - PR;4104501;7487;Capanema;PR
        2721;Capão Alto - SC;4203253;906;Capão Alto;SC
        4486;Capão Bonito - SP;3510203;6305;Capão Bonito;SP
        3953;Capão Bonito do Sul - RS;4304622;1132;Capão Bonito do Sul;RS
        3954;Capão da Canoa - RS;4304630;8915;Capão da Canoa;RS
        3955;Capão do Cipó - RS;4304655;1134;Capão do Cipó;RS
        3956;Capão do Leão - RS;4304663;8973;Capão do Leão;RS
        5146;Caparaó - MG;3112109;4241;Caparaó;MG
        510;Capela - AL;2701704;2733;Capela;AL
        180;Capela - SE;2801306;3125;Capela;SE
        3957;Capela de Santana - RS;4304689;8443;Capela de Santana;RS
        4487;Capela do Alto - SP;3510302;6307;Capela do Alto;SP
        3537;Capela do Alto Alegre - BA;2906857;3081;Capela do Alto Alegre;BA
        5147;Capela Nova - MG;3112208;4243;Capela Nova;MG
        5148;Capelinha - MG;3112307;4245;Capelinha;MG
        5149;Capetinga - MG;3112406;4247;Capetinga;MG
        2244;Capim - PB;2504033;464;Capim;PB
        5150;Capim Branco - MG;3112505;4249;Capim Branco;MG
        3538;Capim Grosso - BA;2906873;3083;Capim Grosso;BA
        5151;Capinópolis - MG;3112604;4251;Capinópolis;MG
        2722;Capinzal - SC;4203907;8075;Capinzal;SC
        1702;Capinzal do Norte - MA;2102754;142;Capinzal do Norte;MA
        1319;Capistrano - CE;2302909;1357;Capistrano;CE
        3958;Capitão - RS;4304697;6025;Capitão;RS
        5152;Capitão Andrade - MG;3112653;2651;Capitão Andrade;MG
        1920;Capitão de Campos - PI;2202406;1047;Capitão de Campos;PI
        5153;Capitão Enéas - MG;3112703;4253;Capitão Enéas;MG
        1921;Capitão Gervásio Oliveira - PI;2202455;296;Capitão Gervásio Oliveira;PI
        3023;Capitão Leônidas Marques - PR;4104600;7489;Capitão Leônidas Marques;PR
        909;Capitão Poço - PA;1502301;445;Capitão Poço;PA
        5154;Capitólio - MG;3112802;4255;Capitólio;MG
        4488;Capivari - SP;3510401;6309;Capivari;SP
        2723;Capivari de Baixo - SC;4203956;5545;Capivari de Baixo;SC
        3959;Capivari do Sul - RS;4304671;962;Capivari do Sul;RS
        37;Capixaba - AC;1200179;647;Capixaba;AC
        1509;Capoeiras - PE;2603801;2375;Capoeiras;PE
        5155;Caputira - MG;3112901;4257;Caputira;MG
        3960;Caraá - RS;4304713;964;Caraá;RS
        7;Caracaraí - RR;1400209;303;Caracaraí;RR
        342;Caracol - MS;5002803;9053;Caracol;MS
        1922;Caracol - PI;2202505;1049;Caracol;PI
        4489;Caraguatatuba - SP;3510500;6311;Caraguatatuba;SP
        5156;Caraí - MG;3113008;4259;Caraí;MG
        3539;Caraíbas - BA;2906899;3271;Caraíbas;BA
        3024;Carambeí - PR;4104659;844;Carambeí;PR
        5157;Caranaíba - MG;3113107;4261;Caranaíba;MG
        5158;Carandaí - MG;3113206;4263;Carandaí;MG
        5159;Carangola - MG;3113305;4265;Carangola;MG
        417;Carapebus - RJ;3300936;772;Carapebus;RJ
        4490;Carapicuíba - SP;3510609;6313;Carapicuíba;SP
        5160;Caratinga - MG;3113404;4267;Caratinga;MG
        123;Carauari - AM;1301001;221;Carauari;AM
        2245;Caraúbas - PB;2504074;466;Caraúbas;PB
        1143;Caraúbas - RN;2402303;1645;Caraúbas;RN
        1923;Caraúbas do Piauí - PI;2202539;298;Caraúbas do Piauí;PI
        3540;Caravelas - BA;2906907;3437;Caravelas;BA
        3961;Carazinho - RS;4304705;8591;Carazinho;RS
        5161;Carbonita - MG;3113503;4269;Carbonita;MG
        3541;Cardeal da Silva - BA;2907004;3439;Cardeal da Silva;BA
        4491;Cardoso - SP;3510708;6315;Cardoso;SP
        418;Cardoso Moreira - RJ;3301157;2915;Cardoso Moreira;RJ
        5162;Careaçu - MG;3113602;4271;Careaçu;MG
        124;Careiro - AM;1301100;223;Careiro;AM
        125;Careiro da Várzea - AM;1301159;965;Careiro da Várzea;AM
        260;Cariacica - ES;3201308;5625;Cariacica;ES
        1320;Caridade - CE;2303006;1359;Caridade;CE
        1924;Caridade do Piauí - PI;2202554;300;Caridade do Piauí;PI
        3542;Carinhanha - BA;2907103;3441;Carinhanha;BA
        181;Carira - SE;2801405;3127;Carira;SE
        1321;Cariré - CE;2303105;1361;Cariré;CE
        625;Cariri do Tocantins - TO;1703867;327;Cariri do Tocantins;TO
        1322;Caririaçu - CE;2303204;1363;Caririaçu;CE
        1323;Cariús - CE;2303303;1365;Cariús;CE
        760;Carlinda - MT;5102793;1034;Carlinda;MT
        3025;Carlópolis - PR;4104709;7491;Carlópolis;PR
        3962;Carlos Barbosa - RS;4304804;8593;Carlos Barbosa;RS
        5163;Carlos Chagas - MG;3113701;4273;Carlos Chagas;MG
        3963;Carlos Gomes - RS;4304853;5961;Carlos Gomes;RS
        5164;Carmésia - MG;3113800;4275;Carmésia;MG
        419;Carmo - RJ;3301207;5823;Carmo;RJ
        5165;Carmo da Cachoeira - MG;3113909;4277;Carmo da Cachoeira;MG
        5166;Carmo da Mata - MG;3114006;4279;Carmo da Mata;MG
        5167;Carmo de Minas - MG;3114105;4281;Carmo de Minas;MG
        5168;Carmo do Cajuru - MG;3114204;4283;Carmo do Cajuru;MG
        5169;Carmo do Paranaíba - MG;3114303;4285;Carmo do Paranaíba;MG
        5170;Carmo do Rio Claro - MG;3114402;4287;Carmo do Rio Claro;MG
        2472;Carmo do Rio Verde - GO;5205000;9299;Carmo do Rio Verde;GO
        626;Carmolândia - TO;1703883;175;Carmolândia;TO
        182;Carmópolis - SE;2801504;3129;Carmópolis;SE
        5171;Carmópolis de Minas - MG;3114501;4289;Carmópolis de Minas;MG
        1510;Carnaíba - PE;2603900;2377;Carnaíba;PE
        1144;Carnaúba dos Dantas - RN;2402402;1647;Carnaúba dos Dantas;RN
        1145;Carnaubais - RN;2402501;1649;Carnaubais;RN
        1324;Carnaubal - CE;2303402;1367;Carnaubal;CE
        1511;Carnaubeira da Penha - PE;2603926;2635;Carnaubeira da Penha;PE
        5172;Carneirinho - MG;3114550;2685;Carneirinho;MG
        511;Carneiros - AL;2701803;2735;Carneiros;AL
        8;Caroebe - RR;1400233;30;Caroebe;RR
        1703;Carolina - MA;2102804;753;Carolina;MA
        1512;Carpina - PE;2604007;2379;Carpina;PE
        5173;Carrancas - MG;3114600;4291;Carrancas;MG
        2246;Carrapateira - PB;2504108;1983;Carrapateira;PB
        627;Carrasco Bonito - TO;1703891;177;Carrasco Bonito;TO
        1513;Caruaru - PE;2604106;2381;Caruaru;PE
        1704;Carutapera - MA;2102903;755;Carutapera;MA
        5174;Carvalhópolis - MG;3114709;4293;Carvalhópolis;MG
        5175;Carvalhos - MG;3114808;4295;Carvalhos;MG
        4492;Casa Branca - SP;3510807;6317;Casa Branca;SP
        5176;Casa Grande - MG;3114907;4297;Casa Grande;MG
        3543;Casa Nova - BA;2907202;3443;Casa Nova;BA
        3964;Casca - RS;4304903;8595;Casca;RS
        5177;Cascalho Rico - MG;3115003;4299;Cascalho Rico;MG
        1325;Cascavel - CE;2303501;1369;Cascavel;CE
        3026;Cascavel - PR;4104808;7493;Cascavel;PR
        628;Caseara - TO;1703909;9717;Caseara;TO
        3965;Caseiros - RS;4304952;8441;Caseiros;RS
        420;Casimiro de Abreu - RJ;3301306;5825;Casimiro de Abreu;RJ
        1514;Casinhas - PE;2604155;546;Casinhas;PE
        2247;Casserengue - PB;2504157;468;Casserengue;PB
        5178;Cássia - MG;3115102;4301;Cássia;MG
        4493;Cássia dos Coqueiros - SP;3510906;6319;Cássia dos Coqueiros;SP
        343;Cassilândia - MS;5002902;9057;Cassilândia;MS
        910;Castanhal - PA;1502400;447;Castanhal;PA
        761;Castanheira - MT;5102850;9783;Castanheira;MT
        66;Castanheiras - RO;1100908;691;Castanheiras;RO
        2473;Castelândia - GO;5205059;81;Castelândia;GO
        261;Castelo - ES;3201407;5627;Castelo;ES
        1925;Castelo do Piauí - PI;2202604;1051;Castelo do Piauí;PI
        4494;Castilho - SP;3511003;6321;Castilho;SP
        3027;Castro - PR;4104907;7495;Castro;PR
        3544;Castro Alves - BA;2907301;3445;Castro Alves;BA
        5179;Cataguases - MG;3115300;4305;Cataguases;MG
        2474;Catalão - GO;5205109;9301;Catalão;GO
        4495;Catanduva - SP;3511102;6323;Catanduva;SP
        3028;Catanduvas - PR;4105003;7497;Catanduvas;PR
        2724;Catanduvas - SC;4204004;8077;Catanduvas;SC
        1326;Catarina - CE;2303600;1371;Catarina;CE
        5180;Catas Altas - MG;3115359;584;Catas Altas;MG
        5181;Catas Altas da Noruega - MG;3115409;4307;Catas Altas da Noruega;MG
        1515;Catende - PE;2604205;2383;Catende;PE
        4496;Catiguá - SP;3511201;6325;Catiguá;SP
        2248;Catingueira - PB;2504207;1985;Catingueira;PB
        3545;Catolândia - BA;2907400;3447;Catolândia;BA
        2249;Catolé do Rocha - PB;2504306;1987;Catolé do Rocha;PB
        3546;Catu - BA;2907509;3449;Catu;BA
        3966;Catuípe - RS;4305009;8597;Catuípe;RS
        5182;Catuji - MG;3115458;2653;Catuji;MG
        1327;Catunda - CE;2303659;983;Catunda;CE
        2475;Caturaí - GO;5205208;9303;Caturaí;GO
        3547;Caturama - BA;2907558;3273;Caturama;BA
        2250;Caturité - PB;2504355;470;Caturité;PB
        5183;Catuti - MG;3115474;586;Catuti;MG
        1328;Caucaia - CE;2303709;1373;Caucaia;CE
        2476;Cavalcante - GO;5205307;9305;Cavalcante;GO
        5184;Caxambu - MG;3115508;4309;Caxambu;MG
        2725;Caxambu do Sul - SC;4204103;8079;Caxambu do Sul;SC
        1705;Caxias - MA;2103000;757;Caxias;MA
        3967;Caxias do Sul - RS;4305108;8599;Caxias do Sul;RS
        1926;Caxingó - PI;2202653;302;Caxingó;PI
        1146;Ceará-Mirim - RN;2402600;1651;Ceará-Mirim;RN
        1706;Cedral - MA;2103109;759;Cedral;MA
        4497;Cedral - SP;3511300;6327;Cedral;SP
        1329;Cedro - CE;2303808;1375;Cedro;CE
        1516;Cedro - PE;2604304;2385;Cedro;PE
        183;Cedro de São João - SE;2801603;3131;Cedro de São João;SE
        5185;Cedro do Abaeté - MG;3115607;4311;Cedro do Abaeté;MG
        2726;Celso Ramos - SC;4204152;9943;Celso Ramos;SC
        3968;Centenário - RS;4305116;5963;Centenário;RS
        629;Centenário - TO;1704105;343;Centenário;TO
        3029;Centenário do Sul - PR;4105102;7499;Centenário do Sul;PR
        3548;Central - BA;2907608;3451;Central;BA
        5186;Central de Minas - MG;3115706;4313;Central de Minas;MG
        1707;Central do Maranhão - MA;2103125;144;Central do Maranhão;MA
        5187;Centralina - MG;3115805;4315;Centralina;MG
        1708;Centro do Guilherme - MA;2103158;146;Centro do Guilherme;MA
        1709;Centro Novo do Maranhão - MA;2103174;148;Centro Novo do Maranhão;MA
        67;Cerejeiras - RO;1100056;27;Cerejeiras;RO
        2477;Ceres - GO;5205406;9307;Ceres;GO
        4498;Cerqueira César - SP;3511409;6329;Cerqueira César;SP
        4499;Cerquilho - SP;3511508;6331;Cerquilho;SP
        3969;Cerrito - RS;4305124;966;Cerrito;RS
        3030;Cerro Azul - PR;4105201;7501;Cerro Azul;PR
        3970;Cerro Branco - RS;4305132;8439;Cerro Branco;RS
        1147;Cerro Corá - RN;2402709;1653;Cerro Corá;RN
        3971;Cerro Grande - RS;4305157;8437;Cerro Grande;RS
        3972;Cerro Grande do Sul - RS;4305173;8435;Cerro Grande do Sul;RS
        3973;Cerro Largo - RS;4305207;8601;Cerro Largo;RS
        2727;Cerro Negro - SC;4204178;5567;Cerro Negro;SC
        4500;Cesário Lange - SP;3511607;6333;Cesário Lange;SP
        3031;Céu Azul - PR;4105300;7957;Céu Azul;PR
        2478;Cezarina - GO;5205455;9785;Cezarina;GO
        1517;Chã de Alegria - PE;2604403;2387;Chã de Alegria;PE
        1518;Chã Grande - PE;2604502;2389;Chã Grande;PE
        512;Chã Preta - AL;2701902;2737;Chã Preta;AL
        5188;Chácara - MG;3115904;4317;Chácara;MG
        5189;Chalé - MG;3116001;4319;Chalé;MG
        3974;Chapada - RS;4305306;8603;Chapada;RS
        630;Chapada da Natividade - TO;1705102;80;Chapada da Natividade;TO
        631;Chapada de Areia - TO;1704600;78;Chapada de Areia;TO
        5190;Chapada do Norte - MG;3116100;4321;Chapada do Norte;MG
        762;Chapada dos Guimarães - MT;5103007;9059;Chapada dos Guimarães;MT
        5191;Chapada Gaúcha - MG;3116159;588;Chapada Gaúcha;MG
        2479;Chapadão do Céu - GO;5205471;73;Chapadão do Céu;GO
        2728;Chapadão do Lageado - SC;4204194;908;Chapadão do Lageado;SC
        344;Chapadão do Sul - MS;5002951;9787;Chapadão do Sul;MS
        1710;Chapadinha - MA;2103208;761;Chapadinha;MA
        2729;Chapecó - SC;4204202;8081;Chapecó;SC
        4501;Charqueada - SP;3511706;6335;Charqueada;SP
        3975;Charqueadas - RS;4305355;8693;Charqueadas;RS
        3976;Charrua - RS;4305371;5965;Charrua;RS
        1330;Chaval - CE;2303907;1377;Chaval;CE
        4502;Chavantes - SP;3557204;6337;Chavantes;SP
        911;Chaves - PA;1502509;449;Chaves;PA
        5192;Chiador - MG;3116209;4323;Chiador;MG
        3977;Chiapetta - RS;4305405;8605;Chiapetta;RS
        3032;Chopinzinho - PR;4105409;7503;Chopinzinho;PR
        1331;Choró - CE;2303931;993;Choró;CE
        1332;Chorozinho - CE;2303956;1239;Chorozinho;CE
        3549;Chorrochó - BA;2907707;3453;Chorrochó;BA
        3978;Chuí - RS;4305439;968;Chuí;RS
        68;Chupinguaia - RO;1100924;6;Chupinguaia;RO
        3979;Chuvisca - RS;4305447;970;Chuvisca;RS
        3033;Cianorte - PR;4105508;7505;Cianorte;PR
        3550;Cícero Dantas - BA;2907806;3455;Cícero Dantas;BA
        3034;Cidade Gaúcha - PR;4105607;7507;Cidade Gaúcha;PR
        2480;Cidade Ocidental - GO;5205497;77;Cidade Ocidental;GO
        1711;Cidelândia - MA;2103257;150;Cidelândia;MA
        3980;Cidreira - RS;4305454;8433;Cidreira;RS
        3551;Cipó - BA;2907905;3457;Cipó;BA
        5193;Cipotânea - MG;3116308;4325;Cipotânea;MG
        3981;Ciríaco - RS;4305504;8607;Ciríaco;RS
        5194;Claraval - MG;3116407;4327;Claraval;MG
        5195;Claro dos Poções - MG;3116506;4329;Claro dos Poções;MG
        763;Cláudia - MT;5103056;9789;Cláudia;MT
        5196;Cláudio - MG;3116605;4331;Cláudio;MG
        4503;Clementina - SP;3511904;6339;Clementina;SP
        3035;Clevelândia - PR;4105706;7509;Clevelândia;PR
        3552;Coaraci - BA;2908002;3459;Coaraci;BA
        126;Coari - AM;1301209;225;Coari;AM
        1927;Cocal - PI;2202703;1053;Cocal;PI
        1928;Cocal de Telha - PI;2202711;304;Cocal de Telha;PI
        2730;Cocal do Sul - SC;4204251;5543;Cocal do Sul;SC
        1929;Cocal dos Alves - PI;2202729;306;Cocal dos Alves;PI
        764;Cocalinho - MT;5103106;9865;Cocalinho;MT
        2481;Cocalzinho de Goiás - GO;5205513;55;Cocalzinho de Goiás;GO
        3553;Cocos - BA;2908101;3461;Cocos;BA
        127;Codajás - AM;1301308;227;Codajás;AM
        1712;Codó - MA;2103307;763;Codó;MA
        1713;Coelho Neto - MA;2103406;765;Coelho Neto;MA
        5197;Coimbra - MG;3116704;4333;Coimbra;MG
        513;Coité do Nóia - AL;2702009;2739;Coité do Nóia;AL
        1930;Coivaras - PI;2202737;995;Coivaras;PI
        912;Colares - PA;1502608;451;Colares;PA
        262;Colatina - ES;3201506;5629;Colatina;ES
        765;Colíder - MT;5103205;8979;Colíder;MT
        4504;Colina - SP;3512001;6341;Colina;SP
        1714;Colinas - MA;2103505;767;Colinas;MA
        3982;Colinas - RS;4305587;6029;Colinas;RS
        2482;Colinas do Sul - GO;5205521;9791;Colinas do Sul;GO
        632;Colinas do Tocantins - TO;1705508;9311;Colinas do Tocantins;TO
        633;Colméia - TO;1716703;9529;Colméia;TO
        766;Colniza - MT;5103254;1080;Colniza;MT
        4505;Colômbia - SP;3512100;6343;Colômbia;SP
        3036;Colombo - PR;4105805;7513;Colombo;PR
        1931;Colônia do Gurguéia - PI;2202752;2249;Colônia do Gurguéia;PI
        1932;Colônia do Piauí - PI;2202778;2253;Colônia do Piauí;PI
        514;Colônia Leopoldina - AL;2702108;2741;Colônia Leopoldina;AL
        3037;Colorado - PR;4105904;7515;Colorado;PR
        3983;Colorado - RS;4305603;8609;Colorado;RS
        69;Colorado do Oeste - RO;1100064;23;Colorado do Oeste;RO
        5198;Coluna - MG;3116803;4335;Coluna;MG
        634;Combinado - TO;1705557;9697;Combinado;TO
        5199;Comendador Gomes - MG;3116902;4337;Comendador Gomes;MG
        421;Comendador Levy Gasparian - RJ;3300951;2927;Comendador Levy Gasparian;RJ
        5200;Comercinho - MG;3117009;4339;Comercinho;MG
        767;Comodoro - MT;5103304;9883;Comodoro;MT
        2251;Conceição - PB;2504405;1989;Conceição;PB
        5201;Conceição da Aparecida - MG;3117108;4341;Conceição da Aparecida;MG
        263;Conceição da Barra - ES;3201605;5631;Conceição da Barra;ES
        5202;Conceição da Barra de Minas - MG;3115201;4303;Conceição da Barra de Minas;MG
        3554;Conceição da Feira - BA;2908200;3463;Conceição da Feira;BA
        5203;Conceição das Alagoas - MG;3117306;4345;Conceição das Alagoas;MG
        5204;Conceição das Pedras - MG;3117207;4343;Conceição das Pedras;MG
        5205;Conceição de Ipanema - MG;3117405;4347;Conceição de Ipanema;MG
        422;Conceição de Macabu - RJ;3301405;5827;Conceição de Macabu;RJ
        3555;Conceição do Almeida - BA;2908309;3465;Conceição do Almeida;BA
        913;Conceição do Araguaia - PA;1502707;453;Conceição do Araguaia;PA
        1933;Conceição do Canindé - PI;2202802;1055;Conceição do Canindé;PI
        264;Conceição do Castelo - ES;3201704;5633;Conceição do Castelo;ES
        3556;Conceição do Coité - BA;2908408;3467;Conceição do Coité;BA
        3557;Conceição do Jacuípe - BA;2908507;3469;Conceição do Jacuípe;BA
        1715;Conceição do Lago-Açu - MA;2103554;152;Conceição do Lago-Açu;MA
        5206;Conceição do Mato Dentro - MG;3117504;4349;Conceição do Mato Dentro;MG
        5207;Conceição do Pará - MG;3117603;4351;Conceição do Pará;MG
        5208;Conceição do Rio Verde - MG;3117702;4353;Conceição do Rio Verde;MG
        635;Conceição do Tocantins - TO;1705607;9313;Conceição do Tocantins;TO
        5209;Conceição dos Ouros - MG;3117801;4355;Conceição dos Ouros;MG
        4506;Conchal - SP;3512209;6345;Conchal;SP
        4507;Conchas - SP;3512308;6347;Conchas;SP
        2731;Concórdia - SC;4204301;8083;Concórdia;SC
        914;Concórdia do Pará - PA;1502756;579;Concórdia do Pará;PA
        2252;Condado - PB;2504504;1991;Condado;PB
        1519;Condado - PE;2604601;2391;Condado;PE
        3558;Conde - BA;2908606;3471;Conde;BA
        2253;Conde - PB;2504603;1993;Conde;PB
        3559;Condeúba - BA;2908705;3473;Condeúba;BA
        3984;Condor - RS;4305702;8611;Condor;RS
        5210;Cônego Marinho - MG;3117836;590;Cônego Marinho;MG
        5211;Confins - MG;3117876;592;Confins;MG
        768;Confresa - MT;5103353;131;Confresa;MT
        2254;Congo - PB;2504702;1995;Congo;PB
        5212;Congonhal - MG;3117900;4357;Congonhal;MG
        5213;Congonhas - MG;3118007;4359;Congonhas;MG
        5214;Congonhas do Norte - MG;3118106;4361;Congonhas do Norte;MG
        3038;Congonhinhas - PR;4106001;7517;Congonhinhas;PR
        5215;Conquista - MG;3118205;4363;Conquista;MG
        769;Conquista D'Oeste - MT;5103361;1082;Conquista D'Oeste;MT
        5216;Conselheiro Lafaiete - MG;3118304;4365;Conselheiro Lafaiete;MG
        3039;Conselheiro Mairinck - PR;4106100;7519;Conselheiro Mairinck;PR
        5217;Conselheiro Pena - MG;3118403;4367;Conselheiro Pena;MG
        5218;Consolação - MG;3118502;4369;Consolação;MG
        3985;Constantina - RS;4305801;8613;Constantina;RS
        5219;Contagem - MG;3118601;4371;Contagem;MG
        3040;Contenda - PR;4106209;7521;Contenda;PR
        3560;Contendas do Sincorá - BA;2908804;3475;Contendas do Sincorá;BA
        5220;Coqueiral - MG;3118700;4373;Coqueiral;MG
        3986;Coqueiro Baixo - RS;4305835;1136;Coqueiro Baixo;RS
        515;Coqueiro Seco - AL;2702207;2743;Coqueiro Seco;AL
        3987;Coqueiros do Sul - RS;4305850;5945;Coqueiros do Sul;RS
        5221;Coração de Jesus - MG;3118809;4375;Coração de Jesus;MG
        3561;Coração de Maria - BA;2908903;3477;Coração de Maria;BA
        3041;Corbélia - PR;4106308;7523;Corbélia;PR
        423;Cordeiro - RJ;3301504;5829;Cordeiro;RJ
        4508;Cordeirópolis - SP;3512407;6349;Cordeirópolis;SP
        3562;Cordeiros - BA;2909000;3479;Cordeiros;BA
        2732;Cordilheira Alta - SC;4204350;5579;Cordilheira Alta;SC
        5222;Cordisburgo - MG;3118908;4377;Cordisburgo;MG
        5223;Cordislândia - MG;3119005;4379;Cordislândia;MG
        1333;Coreaú - CE;2304004;1381;Coreaú;CE
        2255;Coremas - PB;2504801;1997;Coremas;PB
        345;Corguinho - MS;5003108;9061;Corguinho;MS
        3563;Coribe - BA;2909109;3481;Coribe;BA
        5224;Corinto - MG;3119104;4381;Corinto;MG
        3042;Cornélio Procópio - PR;4106407;7525;Cornélio Procópio;PR
        5225;Coroaci - MG;3119203;4383;Coroaci;MG
        4509;Coroados - SP;3512506;6351;Coroados;SP
        1716;Coroatá - MA;2103604;769;Coroatá;MA
        5226;Coromandel - MG;3119302;4385;Coromandel;MG
        3988;Coronel Barros - RS;4305871;6055;Coronel Barros;RS
        3989;Coronel Bicaco - RS;4305900;8615;Coronel Bicaco;RS
        3043;Coronel Domingos Soares - PR;4106456;846;Coronel Domingos Soares;PR
        1148;Coronel Ezequiel - RN;2402808;1655;Coronel Ezequiel;RN
        5227;Coronel Fabriciano - MG;3119401;4387;Coronel Fabriciano;MG
        2733;Coronel Freitas - SC;4204400;8085;Coronel Freitas;SC
        1149;Coronel João Pessoa - RN;2402907;1657;Coronel João Pessoa;RN
        3564;Coronel João Sá - BA;2909208;3483;Coronel João Sá;BA
        1934;Coronel José Dias - PI;2202851;2255;Coronel José Dias;PI
        4510;Coronel Macedo - SP;3512605;6353;Coronel Macedo;SP
        2734;Coronel Martins - SC;4204459;5735;Coronel Martins;SC
        5228;Coronel Murta - MG;3119500;4389;Coronel Murta;MG
        5229;Coronel Pacheco - MG;3119609;4391;Coronel Pacheco;MG
        3990;Coronel Pilar - RS;4305934;1138;Coronel Pilar;RS
        346;Coronel Sapucaia - MS;5003157;9997;Coronel Sapucaia;MS
        3044;Coronel Vivida - PR;4106506;7527;Coronel Vivida;PR
        5230;Coronel Xavier Chaves - MG;3119708;4393;Coronel Xavier Chaves;MG
        5231;Córrego Danta - MG;3119807;4395;Córrego Danta;MG
        5232;Córrego do Bom Jesus - MG;3119906;4397;Córrego do Bom Jesus;MG
        2483;Córrego do Ouro - GO;5205703;9315;Córrego do Ouro;GO
        5233;Córrego Fundo - MG;3119955;594;Córrego Fundo;MG
        5234;Córrego Novo - MG;3120003;4399;Córrego Novo;MG
        2735;Correia Pinto - SC;4204558;8395;Correia Pinto;SC
        1935;Corrente - PI;2202901;1057;Corrente;PI
        1520;Correntes - PE;2604700;2393;Correntes;PE
        3565;Correntina - BA;2909307;3485;Correntina;BA
        1521;Cortês - PE;2604809;2395;Cortês;PE
        347;Corumbá - MS;5003207;9063;Corumbá;MS
        2484;Corumbá de Goiás - GO;5205802;9317;Corumbá de Goiás;GO
        2485;Corumbaíba - GO;5205901;9319;Corumbaíba;GO
        4511;Corumbataí - SP;3512704;6355;Corumbataí;SP
        3045;Corumbataí do Sul - PR;4106555;8479;Corumbataí do Sul;PR
        70;Corumbiara - RO;1100072;981;Corumbiara;RO
        2736;Corupá - SC;4204509;8087;Corupá;SC
        516;Coruripe - AL;2702306;2745;Coruripe;AL
        4512;Cosmópolis - SP;3512803;6357;Cosmópolis;SP
        4513;Cosmorama - SP;3512902;6359;Cosmorama;SP
        71;Costa Marques - RO;1100080;21;Costa Marques;RO
        348;Costa Rica - MS;5003256;9803;Costa Rica;MS
        3566;Cotegipe - BA;2909406;3487;Cotegipe;BA
        4514;Cotia - SP;3513009;6361;Cotia;SP
        3991;Cotiporã - RS;4305959;8977;Cotiporã;RS
        770;Cotriguaçu - MT;5103379;89;Cotriguaçu;MT
        5235;Couto de Magalhães de Minas - MG;3120102;4401;Couto de Magalhães de Minas;MG
        636;Couto Magalhães - TO;1706001;9321;Couto Magalhães;TO
        3992;Coxilha - RS;4305975;5797;Coxilha;RS
        349;Coxim - MS;5003306;9065;Coxim;MS
        2256;Coxixola - PB;2504850;472;Coxixola;PB
        517;Craíbas - AL;2702355;2889;Craíbas;AL
        1334;Crateús - CE;2304103;1383;Crateús;CE
        1335;Crato - CE;2304202;1385;Crato;CE
        4515;Cravinhos - SP;3513108;6363;Cravinhos;SP
        3567;Cravolândia - BA;2909505;3489;Cravolândia;BA
        2737;Criciúma - SC;4204608;8089;Criciúma;SC
        5236;Crisólita - MG;3120151;596;Crisólita;MG
        3568;Crisópolis - BA;2909604;3491;Crisópolis;BA
        3993;Crissiumal - RS;4306007;8617;Crissiumal;RS
        5237;Cristais - MG;3120201;4403;Cristais;MG
        4516;Cristais Paulista - SP;3513207;6365;Cristais Paulista;SP
        3994;Cristal - RS;4306056;8431;Cristal;RS
        3995;Cristal do Sul - RS;4306072;972;Cristal do Sul;RS
        637;Cristalândia - TO;1706100;9323;Cristalândia;TO
        1936;Cristalândia do Piauí - PI;2203008;1059;Cristalândia do Piauí;PI
        5238;Cristália - MG;3120300;4405;Cristália;MG
        2486;Cristalina - GO;5206206;9325;Cristalina;GO
        5239;Cristiano Otoni - MG;3120409;4407;Cristiano Otoni;MG
        2487;Cristianópolis - GO;5206305;9327;Cristianópolis;GO
        5240;Cristina - MG;3120508;4409;Cristina;MG
        184;Cristinápolis - SE;2801702;3133;Cristinápolis;SE
        1937;Cristino Castro - PI;2203107;1061;Cristino Castro;PI
        3569;Cristópolis - BA;2909703;3493;Cristópolis;BA
        2488;Crixás - GO;5206404;9329;Crixás;GO
        638;Crixás do Tocantins - TO;1706258;82;Crixás do Tocantins;TO
        1336;Croatá - CE;2304236;1241;Croatá;CE
        2489;Cromínia - GO;5206503;9331;Cromínia;GO
        5241;Crucilândia - MG;3120607;4411;Crucilândia;MG
        1337;Cruz - CE;2304251;1589;Cruz;CE
        3996;Cruz Alta - RS;4306106;8619;Cruz Alta;RS
        3570;Cruz das Almas - BA;2909802;3495;Cruz das Almas;BA
        2257;Cruz do Espírito Santo - PB;2504900;1999;Cruz do Espírito Santo;PB
        3046;Cruz Machado - PR;4106803;7533;Cruz Machado;PR
        4517;Cruzália - SP;3513306;6367;Cruzália;SP
        3997;Cruzaltense - RS;4306130;1140;Cruzaltense;RS
        4518;Cruzeiro - SP;3513405;6369;Cruzeiro;SP
        5242;Cruzeiro da Fortaleza - MG;3120706;4413;Cruzeiro da Fortaleza;MG
        3047;Cruzeiro do Iguaçu - PR;4106571;5473;Cruzeiro do Iguaçu;PR
        3048;Cruzeiro do Oeste - PR;4106605;7529;Cruzeiro do Oeste;PR
        38;Cruzeiro do Sul - AC;1200203;107;Cruzeiro do Sul;AC
        3049;Cruzeiro do Sul - PR;4106704;7531;Cruzeiro do Sul;PR
        3998;Cruzeiro do Sul - RS;4306205;8621;Cruzeiro do Sul;RS
        1150;Cruzeta - RN;2403004;1659;Cruzeta;RN
        5243;Cruzília - MG;3120805;4415;Cruzília;MG
        3050;Cruzmaltina - PR;4106852;848;Cruzmaltina;PR
        4519;Cubatão - SP;3513504;6371;Cubatão;SP
        2258;Cubati - PB;2505006;2001;Cubati;PB
        771;Cuiabá - MT;5103403;9067;Cuiabá;MT
        2259;Cuité - PB;2505105;2003;Cuité;PB
        2260;Cuité de Mamanguape - PB;2505238;474;Cuité de Mamanguape;PB
        2261;Cuitegi - PB;2505204;2005;Cuitegi;PB
        72;Cujubim - RO;1100940;8;Cujubim;RO
        2490;Cumari - GO;5206602;9333;Cumari;GO
        1522;Cumaru - PE;2604908;2397;Cumaru;PE
        915;Cumaru do Norte - PA;1502764;385;Cumaru do Norte;PA
        185;Cumbe - SE;2801900;3137;Cumbe;SE
        4520;Cunha - SP;3513603;6373;Cunha;SP
        2738;Cunha Porã - SC;4204707;8091;Cunha Porã;SC
        2739;Cunhataí - SC;4204756;910;Cunhataí;SC
        5244;Cuparaque - MG;3120839;598;Cuparaque;MG
        1523;Cupira - PE;2605004;2399;Cupira;PE
        3571;Curaçá - BA;2909901;3497;Curaçá;BA
        1938;Curimatá - PI;2203206;1063;Curimatá;PI
        916;Curionópolis - PA;1502772;581;Curionópolis;PA
        3051;Curitiba - PR;4106902;7535;Curitiba;PR
        2740;Curitibanos - SC;4204806;8093;Curitibanos;SC
        3052;Curiúva - PR;4107009;7537;Curiúva;PR
        1939;Currais - PI;2203230;308;Currais;PI
        1151;Currais Novos - RN;2403103;1661;Currais Novos;RN
        2262;Curral de Cima - PB;2505279;476;Curral de Cima;PB
        5245;Curral de Dentro - MG;3120870;600;Curral de Dentro;MG
        1940;Curral Novo do Piauí - PI;2203271;312;Curral Novo do Piauí;PI
        2263;Curral Velho - PB;2505303;2007;Curral Velho;PB
        917;Curralinho - PA;1502806;455;Curralinho;PA
        1941;Curralinhos - PI;2203255;310;Curralinhos;PI
        918;Curuá - PA;1502855;50;Curuá;PA
        919;Curuçá - PA;1502905;457;Curuçá;PA
        1717;Cururupu - MA;2103703;771;Cururupu;MA
        772;Curvelândia - MT;5103437;1084;Curvelândia;MT
        5246;Curvelo - MG;3120904;4417;Curvelo;MG
        1524;Custódia - PE;2605103;2401;Custódia;PE
        19;Cutias - AP;1600212;667;Cutias;AP
        2491;Damianópolis - GO;5206701;9335;Damianópolis;GO
        2264;Damião - PB;2505352;478;Damião;PB
        2492;Damolândia - GO;5206800;9337;Damolândia;GO
        639;Darcinópolis - TO;1706506;179;Darcinópolis;TO
        3572;Dário Meira - BA;2910008;3499;Dário Meira;BA
        5247;Datas - MG;3121001;4419;Datas;MG
        3999;David Canabarro - RS;4306304;8623;David Canabarro;RS
        2493;Davinópolis - GO;5206909;9339;Davinópolis;GO
        1718;Davinópolis - MA;2103752;154;Davinópolis;MA
        5248;Delfim Moreira - MG;3121100;4421;Delfim Moreira;MG
        5249;Delfinópolis - MG;3121209;4423;Delfinópolis;MG
        518;Delmiro Gouveia - AL;2702405;2747;Delmiro Gouveia;AL
        5250;Delta - MG;3121258;602;Delta;MG
        1942;Demerval Lobão - PI;2203305;1065;Demerval Lobão;PI
        773;Denise - MT;5103452;9833;Denise;MT
        350;Deodápolis - MS;5003454;9175;Deodápolis;MS
        1338;Deputado Irapuan Pinheiro - CE;2304269;1243;Deputado Irapuan Pinheiro;CE
        4000;Derrubadas - RS;4306320;6073;Derrubadas;RS
        4521;Descalvado - SP;3513702;6375;Descalvado;SP
        2741;Descanso - SC;4204905;8095;Descanso;SC
        5251;Descoberto - MG;3121308;4425;Descoberto;MG
        2265;Desterro - PB;2505402;2009;Desterro;PB
        5252;Desterro de Entre Rios - MG;3121407;4427;Desterro de Entre Rios;MG
        5253;Desterro do Melo - MG;3121506;4429;Desterro do Melo;MG
        4001;Dezesseis de Novembro - RS;4306353;8429;Dezesseis de Novembro;RS
        4522;Diadema - SP;3513801;6377;Diadema;SP
        2266;Diamante - PB;2505600;2013;Diamante;PB
        3053;Diamante do Norte - PR;4107108;7539;Diamante do Norte;PR
        3054;Diamante do Sul - PR;4107124;5465;Diamante do Sul;PR
        3055;Diamante D'Oeste - PR;4107157;9915;Diamante D'Oeste;PR
        5254;Diamantina - MG;3121605;4431;Diamantina;MG
        774;Diamantino - MT;5103502;9069;Diamantino;MT
        640;Dianópolis - TO;1707009;9341;Dianópolis;TO
        3573;Dias d'Ávila - BA;2910057;3087;Dias d'Ávila;BA
        4002;Dilermando de Aguiar - RS;4306379;974;Dilermando de Aguiar;RS
        5255;Diogo de Vasconcelos - MG;3121704;4433;Diogo de Vasconcelos;MG
        5256;Dionísio - MG;3121803;4435;Dionísio;MG
        2742;Dionísio Cerqueira - SC;4205001;8097;Dionísio Cerqueira;SC
        2494;Diorama - GO;5207105;9343;Diorama;GO
        4523;Dirce Reis - SP;3513850;7249;Dirce Reis;SP
        1943;Dirceu Arcoverde - PI;2203354;1229;Dirceu Arcoverde;PI
        186;Divina Pastora - SE;2802007;3139;Divina Pastora;SE
        5257;Divinésia - MG;3121902;4437;Divinésia;MG
        5258;Divino - MG;3122009;4439;Divino;MG
        5259;Divino das Laranjeiras - MG;3122108;4441;Divino das Laranjeiras;MG
        265;Divino de São Lourenço - ES;3201803;5635;Divino de São Lourenço;ES
        4524;Divinolândia - SP;3513900;6379;Divinolândia;SP
        5260;Divinolândia de Minas - MG;3122207;4443;Divinolândia de Minas;MG
        5261;Divinópolis - MG;3122306;4445;Divinópolis;MG
        2495;Divinópolis de Goiás - GO;5208301;9309;Divinópolis de Goiás;GO
        641;Divinópolis do Tocantins - TO;1707108;9719;Divinópolis do Tocantins;TO
        5262;Divisa Alegre - MG;3122355;604;Divisa Alegre;MG
        5263;Divisa Nova - MG;3122405;4447;Divisa Nova;MG
        5264;Divisópolis - MG;3122454;2657;Divisópolis;MG
        4525;Dobrada - SP;3514007;6381;Dobrada;SP
        4526;Dois Córregos - SP;3514106;6383;Dois Córregos;SP
        4003;Dois Irmãos - RS;4306403;8625;Dois Irmãos;RS
        4004;Dois Irmãos das Missões - RS;4306429;5971;Dois Irmãos das Missões;RS
        351;Dois Irmãos do Buriti - MS;5003488;9793;Dois Irmãos do Buriti;MS
        642;Dois Irmãos do Tocantins - TO;1707207;9345;Dois Irmãos do Tocantins;TO
        4005;Dois Lajeados - RS;4306452;8427;Dois Lajeados;RS
        519;Dois Riachos - AL;2702504;2749;Dois Riachos;AL
        3056;Dois Vizinhos - PR;4107207;7541;Dois Vizinhos;PR
        4527;Dolcinópolis - SP;3514205;6385;Dolcinópolis;SP
        775;Dom Aquino - MT;5103601;9071;Dom Aquino;MT
        3574;Dom Basílio - BA;2910107;3501;Dom Basílio;BA
        5265;Dom Bosco - MG;3122470;606;Dom Bosco;MG
        5266;Dom Cavati - MG;3122504;4449;Dom Cavati;MG
        920;Dom Eliseu - PA;1502939;583;Dom Eliseu;PA
        1944;Dom Expedito Lopes - PI;2203404;1067;Dom Expedito Lopes;PI
        4006;Dom Feliciano - RS;4306502;8627;Dom Feliciano;RS
        1945;Dom Inocêncio - PI;2203453;1289;Dom Inocêncio;PI
        5267;Dom Joaquim - MG;3122603;4451;Dom Joaquim;MG
        3575;Dom Macedo Costa - BA;2910206;3503;Dom Macedo Costa;BA
        4007;Dom Pedrito - RS;4306601;8629;Dom Pedrito;RS
        1719;Dom Pedro - MA;2103802;773;Dom Pedro;MA
        4008;Dom Pedro de Alcântara - RS;4306551;976;Dom Pedro de Alcântara;RS
        5268;Dom Silvério - MG;3122702;4453;Dom Silvério;MG
        5269;Dom Viçoso - MG;3122801;4455;Dom Viçoso;MG
        266;Domingos Martins - ES;3201902;5637;Domingos Martins;ES
        1946;Domingos Mourão - PI;2203420;1141;Domingos Mourão;PI
        2743;Dona Emma - SC;4205100;8099;Dona Emma;SC
        5270;Dona Euzébia - MG;3122900;4457;Dona Euzébia;MG
        4009;Dona Francisca - RS;4306700;8631;Dona Francisca;RS
        2267;Dona Inês - PB;2505709;2015;Dona Inês;PB
        5271;Dores de Campos - MG;3123007;4459;Dores de Campos;MG
        5272;Dores de Guanhães - MG;3123106;4461;Dores de Guanhães;MG
        5273;Dores do Indaiá - MG;3123205;4463;Dores do Indaiá;MG
        267;Dores do Rio Preto - ES;3202009;5639;Dores do Rio Preto;ES
        5274;Dores do Turvo - MG;3123304;4465;Dores do Turvo;MG
        5275;Doresópolis - MG;3123403;4467;Doresópolis;MG
        1525;Dormentes - PE;2605152;2299;Dormentes;PE
        352;Douradina - MS;5003504;9805;Douradina;MS
        3057;Douradina - PR;4107256;8465;Douradina;PR
        4528;Dourado - SP;3514304;6387;Dourado;SP
        5276;Douradoquara - MG;3123502;4469;Douradoquara;MG
        353;Dourados - MS;5003702;9073;Dourados;MS
        3058;Doutor Camargo - PR;4107306;7543;Doutor Camargo;PR
        4010;Doutor Maurício Cardoso - RS;4306734;8425;Doutor Maurício Cardoso;RS
        2744;Doutor Pedrinho - SC;4205159;9945;Doutor Pedrinho;SC
        4011;Doutor Ricardo - RS;4306759;978;Doutor Ricardo;RS
        1152;Doutor Severiano - RN;2403202;1663;Doutor Severiano;RN
        3059;Doutor Ulysses - PR;4128633;5449;Doutor Ulysses;PR
        2496;Doverlândia - GO;5207253;9675;Doverlândia;GO
        4529;Dracena - SP;3514403;6389;Dracena;SP
        4530;Duartina - SP;3514502;6391;Duartina;SP
        424;Duas Barras - RJ;3301603;5831;Duas Barras;RJ
        2268;Duas Estradas - PB;2505808;2017;Duas Estradas;PB
        643;Dueré - TO;1707306;9347;Dueré;TO
        4531;Dumont - SP;3514601;6393;Dumont;SP
        1720;Duque Bacelar - MA;2103901;775;Duque Bacelar;MA
        425;Duque de Caxias - RJ;3301702;5833;Duque de Caxias;RJ
        5277;Durandé - MG;3123528;2675;Durandé;MG
        4532;Echaporã - SP;3514700;6395;Echaporã;SP
        268;Ecoporanga - ES;3202108;5641;Ecoporanga;ES
        2497;Edealina - GO;5207352;9795;Edealina;GO
        2498;Edéia - GO;5207402;9349;Edéia;GO
        128;Eirunepé - AM;1301407;229;Eirunepé;AM
        354;Eldorado - MS;5003751;9173;Eldorado;MS
        4533;Eldorado - SP;3514809;6397;Eldorado;SP
        921;Eldorado do Carajás - PA;1502954;377;Eldorado do Carajás;PA
        4012;Eldorado do Sul - RS;4306767;8423;Eldorado do Sul;RS
        1947;Elesbão Veloso - PI;2203503;1069;Elesbão Veloso;PI
        4534;Elias Fausto - SP;3514908;6399;Elias Fausto;SP
        1948;Eliseu Martins - PI;2203602;1071;Eliseu Martins;PI
        4535;Elisiário - SP;3514924;2975;Elisiário;SP
        3576;Elísio Medrado - BA;2910305;3505;Elísio Medrado;BA
        5278;Elói Mendes - MG;3123601;4471;Elói Mendes;MG
        2269;Emas - PB;2505907;2019;Emas;PB
        4536;Embaúba - SP;3514957;7251;Embaúba;SP
        4537;Embu das Artes - SP;3515004;6401;Embu das Artes;SP
        4538;Embu-Guaçu - SP;3515103;6403;Embu-Guaçu;SP
        4539;Emilianópolis - SP;3515129;2961;Emilianópolis;SP
        4013;Encantado - RS;4306809;8633;Encantado;RS
        1153;Encanto - RN;2403301;1665;Encanto;RN
        3577;Encruzilhada - BA;2910404;3507;Encruzilhada;BA
        4014;Encruzilhada do Sul - RS;4306908;8635;Encruzilhada do Sul;RS
        3060;Enéas Marques - PR;4107405;7545;Enéas Marques;PR
        3061;Engenheiro Beltrão - PR;4107504;7547;Engenheiro Beltrão;PR
        5279;Engenheiro Caldas - MG;3123700;4473;Engenheiro Caldas;MG
        4540;Engenheiro Coelho - SP;3515152;2949;Engenheiro Coelho;SP
        5280;Engenheiro Navarro - MG;3123809;4475;Engenheiro Navarro;MG
        426;Engenheiro Paulo de Frontin - RJ;3301801;5835;Engenheiro Paulo de Frontin;RJ
        4015;Engenho Velho - RS;4306924;5947;Engenho Velho;RS
        5281;Entre Folhas - MG;3123858;2663;Entre Folhas;MG
        3578;Entre Rios - BA;2910503;3509;Entre Rios;BA
        2745;Entre Rios - SC;4205175;912;Entre Rios;SC
        5282;Entre Rios de Minas - MG;3123908;4477;Entre Rios de Minas;MG
        3062;Entre Rios do Oeste - PR;4107538;5529;Entre Rios do Oeste;PR
        4016;Entre Rios do Sul - RS;4306957;8421;Entre Rios do Sul;RS
        4017;Entre-Ijuís - RS;4306932;8419;Entre-Ijuís;RS
        129;Envira - AM;1301506;231;Envira;AM
        39;Epitaciolândia - AC;1200252;651;Epitaciolândia;AC
        1154;Equador - RN;2403400;1667;Equador;RN
        4018;Erebango - RS;4306973;8417;Erebango;RS
        4019;Erechim - RS;4307005;8637;Erechim;RS
        1339;Ereré - CE;2304277;1245;Ereré;CE
        3579;Érico Cardoso - BA;2900504;3309;Érico Cardoso;BA
        2746;Ermo - SC;4205191;914;Ermo;SC
        4020;Ernestina - RS;4307054;8415;Ernestina;RS
        4021;Erval Grande - RS;4307203;8641;Erval Grande;RS
        4022;Erval Seco - RS;4307302;8643;Erval Seco;RS
        2747;Erval Velho - SC;4205209;8101;Erval Velho;SC
        5283;Ervália - MG;3124005;4479;Ervália;MG
        1526;Escada - PE;2605202;2403;Escada;PE
        4023;Esmeralda - RS;4307401;8645;Esmeralda;RS
        5284;Esmeraldas - MG;3124104;4481;Esmeraldas;MG
        5285;Espera Feliz - MG;3124203;4483;Espera Feliz;MG
        2270;Esperança - PB;2506004;2021;Esperança;PB
        4024;Esperança do Sul - RS;4307450;980;Esperança do Sul;RS
        3063;Esperança Nova - PR;4107520;850;Esperança Nova;PR
        1949;Esperantina - PI;2203701;1073;Esperantina;PI
        644;Esperantina - TO;1707405;181;Esperantina;TO
        1721;Esperantinópolis - MA;2104008;777;Esperantinópolis;MA
        3064;Espigão Alto do Iguaçu - PR;4107546;852;Espigão Alto do Iguaçu;PR
        73;Espigão D'Oeste - RO;1100098;25;Espigão D'Oeste;RO
        5286;Espinosa - MG;3124302;4485;Espinosa;MG
        1155;Espírito Santo - RN;2403509;1669;Espírito Santo;RN
        5287;Espírito Santo do Dourado - MG;3124401;4487;Espírito Santo do Dourado;MG
        4541;Espírito Santo do Pinhal - SP;3515186;6865;Espírito Santo do Pinhal;SP
        4542;Espírito Santo do Turvo - SP;3515194;7253;Espírito Santo do Turvo;SP
        3580;Esplanada - BA;2910602;3511;Esplanada;BA
        4025;Espumoso - RS;4307500;8647;Espumoso;RS
        4026;Estação - RS;4307559;7301;Estação;RS
        187;Estância - SE;2802106;3141;Estância;SE
        4027;Estância Velha - RS;4307609;8649;Estância Velha;RS
        4028;Esteio - RS;4307708;8651;Esteio;RS
        5288;Estiva - MG;3124500;4489;Estiva;MG
        4543;Estiva Gerbi - SP;3557303;2959;Estiva Gerbi;SP
        1722;Estreito - MA;2104057;963;Estreito;MA
        4029;Estrela - RS;4307807;8653;Estrela;RS
        5289;Estrela Dalva - MG;3124609;4491;Estrela Dalva;MG
        520;Estrela de Alagoas - AL;2702553;2643;Estrela de Alagoas;AL
        5290;Estrela do Indaiá - MG;3124708;4493;Estrela do Indaiá;MG
        2499;Estrela do Norte - GO;5207501;9351;Estrela do Norte;GO
        4544;Estrela do Norte - SP;3515301;6407;Estrela do Norte;SP
        5291;Estrela do Sul - MG;3124807;4495;Estrela do Sul;MG
        4545;Estrela d'Oeste - SP;3515202;6405;Estrela d'Oeste;SP
        4030;Estrela Velha - RS;4307815;982;Estrela Velha;RS
        3581;Euclides da Cunha - BA;2910701;3513;Euclides da Cunha;BA
        4546;Euclides da Cunha Paulista - SP;3515350;7255;Euclides da Cunha Paulista;SP
        4031;Eugênio de Castro - RS;4307831;8413;Eugênio de Castro;RS
        5292;Eugenópolis - MG;3124906;4497;Eugenópolis;MG
        3582;Eunápolis - BA;2910727;3117;Eunápolis;BA
        1340;Eusébio - CE;2304285;1247;Eusébio;CE
        5293;Ewbank da Câmara - MG;3125002;4499;Ewbank da Câmara;MG
        5966;Exterior - EX;9999999;;Exterior;EX
        5294;Extrema - MG;3125101;4501;Extrema;MG
        1156;Extremoz - RN;2403608;1671;Extremoz;RN
        1527;Exu - PE;2605301;2405;Exu;PE
        2271;Fagundes - PB;2506103;2023;Fagundes;PB
        4032;Fagundes Varela - RS;4307864;8411;Fagundes Varela;RS
        2500;Faina - GO;5207535;9797;Faina;GO
        5295;Fama - MG;3125200;4503;Fama;MG
        5296;Faria Lemos - MG;3125309;4505;Faria Lemos;MG
        1341;Farias Brito - CE;2304301;1387;Farias Brito;CE
        922;Faro - PA;1503002;459;Faro;PA
        3065;Farol - PR;4107553;5511;Farol;PR
        4033;Farroupilha - RS;4307906;8655;Farroupilha;RS
        4547;Fartura - SP;3515400;6409;Fartura;SP
        1950;Fartura do Piauí - PI;2203750;2257;Fartura do Piauí;PI
        3583;Fátima - BA;2910750;3089;Fátima;BA
        645;Fátima - TO;1707553;9683;Fátima;TO
        355;Fátima do Sul - MS;5003801;9075;Fátima do Sul;MS
        3066;Faxinal - PR;4107603;7549;Faxinal;PR
        4034;Faxinal do Soturno - RS;4308003;8657;Faxinal do Soturno;RS
        2748;Faxinal dos Guedes - SC;4205308;8103;Faxinal dos Guedes;SC
        4035;Faxinalzinho - RS;4308052;8409;Faxinalzinho;RS
        2501;Fazenda Nova - GO;5207600;9353;Fazenda Nova;GO
        3067;Fazenda Rio Grande - PR;4107652;9983;Fazenda Rio Grande;PR
        4036;Fazenda Vilanova - RS;4308078;984;Fazenda Vilanova;RS
        40;Feijó - AC;1200302;113;Feijó;AC
        3584;Feira da Mata - BA;2910776;3275;Feira da Mata;BA
        3585;Feira de Santana - BA;2910800;3515;Feira de Santana;BA
        521;Feira Grande - AL;2702603;2751;Feira Grande;AL
        1528;Feira Nova - PE;2605400;2407;Feira Nova;PE
        188;Feira Nova - SE;2802205;3143;Feira Nova;SE
        1723;Feira Nova do Maranhão - MA;2104073;156;Feira Nova do Maranhão;MA
        5297;Felício dos Santos - MG;3125408;4507;Felício dos Santos;MG
        1157;Felipe Guerra - RN;2403707;1673;Felipe Guerra;RN
        5298;Felisburgo - MG;3125606;4511;Felisburgo;MG
        5299;Felixlândia - MG;3125705;4513;Felixlândia;MG
        4037;Feliz - RS;4308102;8659;Feliz;RS
        522;Feliz Deserto - AL;2702702;2753;Feliz Deserto;AL
        776;Feliz Natal - MT;5103700;1036;Feliz Natal;MT
        3068;Fênix - PR;4107702;7551;Fênix;PR
        3069;Fernandes Pinheiro - PR;4107736;854;Fernandes Pinheiro;PR
        5300;Fernandes Tourinho - MG;3125804;4515;Fernandes Tourinho;MG
        1529;Fernando de Noronha - PE;2605459;2409;Fernando de Noronha;PE
        1724;Fernando Falcão - MA;2104081;158;Fernando Falcão;MA
        1158;Fernando Pedroza - RN;2403756;416;Fernando Pedroza;RN
        4548;Fernando Prestes - SP;3515608;6413;Fernando Prestes;SP
        4549;Fernandópolis - SP;3515509;6411;Fernandópolis;SP
        4550;Fernão - SP;3515657;796;Fernão;SP
        4551;Ferraz de Vasconcelos - SP;3515707;6415;Ferraz de Vasconcelos;SP
        20;Ferreira Gomes - AP;1600238;611;Ferreira Gomes;AP
        1530;Ferreiros - PE;2605509;2409;Ferreiros;PE
        5301;Ferros - MG;3125903;4517;Ferros;MG
        5302;Fervedouro - MG;3125952;2683;Fervedouro;MG
        3070;Figueira - PR;4107751;8457;Figueira;PR
        356;Figueirão - MS;5003900;1178;Figueirão;MS
        646;Figueirópolis - TO;1707652;9667;Figueirópolis;TO
        777;Figueirópolis D'Oeste - MT;5103809;9881;Figueirópolis D'Oeste;MT
        3586;Filadélfia - BA;2910859;3091;Filadélfia;BA
        647;Filadélfia - TO;1707702;9355;Filadélfia;TO
        3587;Firmino Alves - BA;2910909;3517;Firmino Alves;BA
        2502;Firminópolis - GO;5207808;9357;Firminópolis;GO
        523;Flexeiras - AL;2702801;2755;Flexeiras;AL
        3071;Flor da Serra do Sul - PR;4107850;5475;Flor da Serra do Sul;PR
        2749;Flor do Sertão - SC;4205357;916;Flor do Sertão;SC
        4552;Flora Rica - SP;3515806;6417;Flora Rica;SP
        3072;Floraí - PR;4107801;7553;Floraí;PR
        1159;Florânia - RN;2403806;1675;Florânia;RN
        4553;Floreal - SP;3515905;6419;Floreal;SP
        1531;Flores - PE;2605608;2411;Flores;PE
        4038;Flores da Cunha - RS;4308201;8661;Flores da Cunha;RS
        2503;Flores de Goiás - GO;5207907;9359;Flores de Goiás;GO
        1951;Flores do Piauí - PI;2203800;1075;Flores do Piauí;PI
        1532;Floresta - PE;2605707;2413;Floresta;PE
        3073;Floresta - PR;4107900;7555;Floresta;PR
        3588;Floresta Azul - BA;2911006;3519;Floresta Azul;BA
        923;Floresta do Araguaia - PA;1503044;52;Floresta do Araguaia;PA
        1952;Floresta do Piauí - PI;2203859;314;Floresta do Piauí;PI
        5401;Florestal - MG;3126000;4519;Florestal;MG
        3074;Florestópolis - PR;4108007;7557;Florestópolis;PR
        1953;Floriano - PI;2203909;1077;Floriano;PI
        4039;Floriano Peixoto - RS;4308250;986;Floriano Peixoto;RS
        2750;Florianópolis - SC;4205407;8105;Florianópolis;SC
        3075;Flórida - PR;4108106;7559;Flórida;PR
        4554;Flórida Paulista - SP;3516002;6421;Flórida Paulista;SP
        4555;Florínea - SP;3516101;6423;Florínea;SP
        130;Fonte Boa - AM;1301605;233;Fonte Boa;AM
        4040;Fontoura Xavier - RS;4308300;8663;Fontoura Xavier;RS
        5402;Formiga - MG;3126109;4521;Formiga;MG
        4041;Formigueiro - RS;4308409;8665;Formigueiro;RS
        2504;Formosa - GO;5208004;9361;Formosa;GO
        1725;Formosa da Serra Negra - MA;2104099;160;Formosa da Serra Negra;MA
        3076;Formosa do Oeste - PR;4108205;7561;Formosa do Oeste;PR
        3589;Formosa do Rio Preto - BA;2911105;3521;Formosa do Rio Preto;BA
        2751;Formosa do Sul - SC;4205431;5581;Formosa do Sul;SC
        2505;Formoso - GO;5208103;9363;Formoso;GO
        5403;Formoso - MG;3126208;4523;Formoso;MG
        648;Formoso do Araguaia - TO;1708205;9365;Formoso do Araguaia;TO
        4042;Forquetinha - RS;4308433;1142;Forquetinha;RS
        1342;Forquilha - CE;2304350;1591;Forquilha;CE
        2752;Forquilhinha - SC;4205456;973;Forquilhinha;SC
        1343;Fortaleza - CE;2304400;1389;Fortaleza;CE
        5404;Fortaleza de Minas - MG;3126307;4525;Fortaleza de Minas;MG
        1726;Fortaleza dos Nogueiras - MA;2104107;779;Fortaleza dos Nogueiras;MA
        4043;Fortaleza dos Valos - RS;4308458;9827;Fortaleza dos Valos;RS
        1344;Fortim - CE;2304459;987;Fortim;CE
        1727;Fortuna - MA;2104206;781;Fortuna;MA
        5405;Fortuna de Minas - MG;3126406;4527;Fortuna de Minas;MG
        3077;Foz do Iguaçu - PR;4108304;7563;Foz do Iguaçu;PR
        3078;Foz do Jordão - PR;4108452;856;Foz do Jordão;PR
        2753;Fraiburgo - SC;4205506;8107;Fraiburgo;SC
        4556;Franca - SP;3516200;6425;Franca;SP
        1954;Francinópolis - PI;2204006;1079;Francinópolis;PI
        3079;Francisco Alves - PR;4108320;7977;Francisco Alves;PR
        1955;Francisco Ayres - PI;2204105;1081;Francisco Ayres;PI
        5406;Francisco Badaró - MG;3126505;4529;Francisco Badaró;MG
        3080;Francisco Beltrão - PR;4108403;7565;Francisco Beltrão;PR
        1160;Francisco Dantas - RN;2403905;1677;Francisco Dantas;RN
        5407;Francisco Dumont - MG;3126604;4531;Francisco Dumont;MG
        1956;Francisco Macedo - PI;2204154;316;Francisco Macedo;PI
        4557;Francisco Morato - SP;3516309;6427;Francisco Morato;SP
        5408;Francisco Sá - MG;3126703;4533;Francisco Sá;MG
        1957;Francisco Santos - PI;2204204;1083;Francisco Santos;PI
        5409;Franciscópolis - MG;3126752;608;Franciscópolis;MG
        4558;Franco da Rocha - SP;3516408;6429;Franco da Rocha;SP
        1345;Frecheirinha - CE;2304509;1391;Frecheirinha;CE
        4044;Frederico Westphalen - RS;4308508;8667;Frederico Westphalen;RS
        5410;Frei Gaspar - MG;3126802;4535;Frei Gaspar;MG
        5411;Frei Inocêncio - MG;3126901;4537;Frei Inocêncio;MG
        5412;Frei Lagonegro - MG;3126950;610;Frei Lagonegro;MG
        2272;Frei Martinho - PB;2506202;2025;Frei Martinho;PB
        1533;Frei Miguelinho - PE;2605806;2415;Frei Miguelinho;PE
        189;Frei Paulo - SE;2802304;3145;Frei Paulo;SE
        2754;Frei Rogério - SC;4205555;918;Frei Rogério;SC
        5413;Fronteira - MG;3127008;4539;Fronteira;MG
        5414;Fronteira dos Vales - MG;3127057;4935;Fronteira dos Vales;MG
        1958;Fronteiras - PI;2204303;1085;Fronteiras;PI
        5415;Fruta de Leite - MG;3127073;612;Fruta de Leite;MG
        5416;Frutal - MG;3127107;4541;Frutal;MG
        1161;Frutuoso Gomes - RN;2404002;1751;Frutuoso Gomes;RN
        269;Fundão - ES;3202207;5643;Fundão;ES
        5417;Funilândia - MG;3127206;4543;Funilândia;MG
        4559;Gabriel Monteiro - SP;3516507;6431;Gabriel Monteiro;SP
        2273;Gado Bravo - PB;2506251;480;Gado Bravo;PB
        4560;Gália - SP;3516606;6433;Gália;SP
        5418;Galiléia - MG;3127305;4545;Galiléia;MG
        1162;Galinhos - RN;2404101;1679;Galinhos;RN
        2755;Galvão - SC;4205605;8109;Galvão;SC
        1534;Gameleira - PE;2605905;2417;Gameleira;PE
        2506;Gameleira de Goiás - GO;5208152;1072;Gameleira de Goiás;GO
        5419;Gameleiras - MG;3127339;614;Gameleiras;MG
        3590;Gandu - BA;2911204;3523;Gandu;BA
        1535;Garanhuns - PE;2606002;2419;Garanhuns;PE
        190;Gararu - SE;2802403;3149;Gararu;SE
        4561;Garça - SP;3516705;6435;Garça;SP
        4045;Garibaldi - RS;4308607;8669;Garibaldi;RS
        2756;Garopaba - SC;4205704;8113;Garopaba;SC
        924;Garrafão do Norte - PA;1503077;585;Garrafão do Norte;PA
        4046;Garruchos - RS;4308656;6081;Garruchos;RS
        2757;Garuva - SC;4205803;8115;Garuva;SC
        2758;Gaspar - SC;4205902;8117;Gaspar;SC
        4562;Gastão Vidigal - SP;3516804;6437;Gastão Vidigal;SP
        778;Gaúcha do Norte - MT;5103858;1038;Gaúcha do Norte;MT
        4047;Gaurama - RS;4308706;8671;Gaurama;RS
        3591;Gavião - BA;2911253;3093;Gavião;BA
        4563;Gavião Peixoto - SP;3516853;798;Gavião Peixoto;SP
        1959;Geminiano - PI;2204352;318;Geminiano;PI
        4048;General Câmara - RS;4308805;8673;General Câmara;RS
        779;General Carneiro - MT;5103908;9077;General Carneiro;MT
        3081;General Carneiro - PR;4108502;7567;General Carneiro;PR
        191;General Maynard - SE;2802502;3147;General Maynard;SE
        4564;General Salgado - SP;3516903;6439;General Salgado;SP
        1346;General Sampaio - CE;2304608;1393;General Sampaio;CE
        4049;Gentil - RS;4308854;5799;Gentil;RS
        3592;Gentio do Ouro - BA;2911303;3525;Gentio do Ouro;BA
        4565;Getulina - SP;3517000;6441;Getulina;SP
        4050;Getúlio Vargas - RS;4308904;8677;Getúlio Vargas;RS
        1960;Gilbués - PI;2204402;1087;Gilbués;PI
        524;Girau do Ponciano - AL;2702900;2757;Girau do Ponciano;AL
        4051;Giruá - RS;4309001;8679;Giruá;RS
        5420;Glaucilândia - MG;3127354;616;Glaucilândia;MG
        4566;Glicério - SP;3517109;6443;Glicério;SP
        3593;Glória - BA;2911402;3527;Glória;BA
        357;Glória de Dourados - MS;5004007;9079;Glória de Dourados;MS
        1536;Glória do Goitá - PE;2606101;2421;Glória do Goitá;PE
        780;Glória D'Oeste - MT;5103957;135;Glória D'Oeste;MT
        4052;Glorinha - RS;4309050;8407;Glorinha;RS
        1728;Godofredo Viana - MA;2104305;783;Godofredo Viana;MA
        3082;Godoy Moreira - PR;4108551;9947;Godoy Moreira;PR
        5421;Goiabeira - MG;3127370;618;Goiabeira;MG
        5422;Goianá - MG;3127388;620;Goianá;MG
        1537;Goiana - PE;2606200;2423;Goiana;PE
        2507;Goianápolis - GO;5208400;9367;Goianápolis;GO
        2508;Goiandira - GO;5208509;9369;Goiandira;GO
        2509;Goianésia - GO;5208608;9371;Goianésia;GO
        925;Goianésia do Pará - PA;1503093;627;Goianésia do Pará;PA
        2510;Goiânia - GO;5208707;9373;Goiânia;GO
        1163;Goianinha - RN;2404200;1681;Goianinha;RN
        2511;Goianira - GO;5208806;9375;Goianira;GO
        650;Goianorte - TO;1708304;9699;Goianorte;TO
        2512;Goiás - GO;5208905;9377;Goiás;GO
        651;Goiatins - TO;1709005;9533;Goiatins;TO
        2513;Goiatuba - GO;5209101;9379;Goiatuba;GO
        3083;Goioerê - PR;4108601;7569;Goioerê;PR
        3084;Goioxim - PR;4108650;858;Goioxim;PR
        5423;Gonçalves - MG;3127404;4547;Gonçalves;MG
        1729;Gonçalves Dias - MA;2104404;785;Gonçalves Dias;MA
        3594;Gongogi - BA;2911501;3529;Gongogi;BA
        5424;Gonzaga - MG;3127503;4549;Gonzaga;MG
        5425;Gouveia - MG;3127602;4551;Gouveia;MG
        2514;Gouvelândia - GO;5209150;9799;Gouvelândia;GO
        1730;Governador Archer - MA;2104503;787;Governador Archer;MA
        2759;Governador Celso Ramos - SC;4206009;8111;Governador Celso Ramos;SC
        1164;Governador Dix-Sept Rosado - RN;2404309;1683;Governador Dix-Sept Rosado;RN
        1731;Governador Edison Lobão - MA;2104552;162;Governador Edison Lobão;MA
        1732;Governador Eugênio Barros - MA;2104602;789;Governador Eugênio Barros;MA
        74;Governador Jorge Teixeira - RO;1101005;693;Governador Jorge Teixeira;RO
        270;Governador Lindenberg - ES;3202256;1114;Governador Lindenberg;ES
        1733;Governador Luiz Rocha - MA;2104628;164;Governador Luiz Rocha;MA
        3595;Governador Mangabeira - BA;2911600;3531;Governador Mangabeira;BA
        1734;Governador Newton Bello - MA;2104651;166;Governador Newton Bello;MA
        1735;Governador Nunes Freire - MA;2104677;168;Governador Nunes Freire;MA
        5426;Governador Valadares - MG;3127701;4553;Governador Valadares;MG
        1347;Graça - CE;2304657;1249;Graça;CE
        1736;Graça Aranha - MA;2104701;791;Graça Aranha;MA
        192;Gracho Cardoso - SE;2802601;3151;Gracho Cardoso;SE
        1737;Grajaú - MA;2104800;793;Grajaú;MA
        4053;Gramado - RS;4309100;8681;Gramado;RS
        4054;Gramado dos Loureiros - RS;4309126;5949;Gramado dos Loureiros;RS
        4055;Gramado Xavier - RS;4309159;5763;Gramado Xavier;RS
        3085;Grandes Rios - PR;4108700;7959;Grandes Rios;PR
        1538;Granito - PE;2606309;2425;Granito;PE
        1348;Granja - CE;2304707;1395;Granja;CE
        1349;Granjeiro - CE;2304806;1397;Granjeiro;CE
        5427;Grão Mogol - MG;3127800;4555;Grão Mogol;MG
        2760;Grão Pará - SC;4206108;8119;Grão Pará;SC
        1539;Gravatá - PE;2606408;2427;Gravatá;PE
        4056;Gravataí - RS;4309209;8683;Gravataí;RS
        2761;Gravatal - SC;4206207;8121;Gravatal;SC
        1350;Groaíras - CE;2304905;1399;Groaíras;CE
        1165;Grossos - RN;2404408;1685;Grossos;RN
        5428;Grupiara - MG;3127909;4557;Grupiara;MG
        4057;Guabiju - RS;4309258;8405;Guabiju;RS
        2762;Guabiruba - SC;4206306;8123;Guabiruba;SC
        271;Guaçuí - ES;3202306;5645;Guaçuí;ES
        1961;Guadalupe - PI;2204501;1089;Guadalupe;PI
        4058;Guaíba - RS;4309308;8685;Guaíba;RS
        4567;Guaiçara - SP;3517208;6445;Guaiçara;SP
        4568;Guaimbê - SP;3517307;6447;Guaimbê;SP
        3086;Guaíra - PR;4108809;7571;Guaíra;PR
        4569;Guaíra - SP;3517406;6449;Guaíra;SP
        3087;Guairaçá - PR;4108908;7573;Guairaçá;PR
        1351;Guaiúba - CE;2304954;1251;Guaiúba;CE
        131;Guajará - AM;1301654;967;Guajará;AM
        75;Guajará-Mirim - RO;1100106;1;Guajará-Mirim;RO
        3596;Guajeru - BA;2911659;3095;Guajeru;BA
        1166;Guamaré - RN;2404507;1687;Guamaré;RN
        3088;Guamiranga - PR;4108957;860;Guamiranga;PR
        3597;Guanambi - BA;2911709;3533;Guanambi;BA
        5429;Guanhães - MG;3128006;4559;Guanhães;MG
        5430;Guapé - MG;3128105;4561;Guapé;MG
        4570;Guapiaçu - SP;3517505;6451;Guapiaçu;SP
        4571;Guapiara - SP;3517604;6453;Guapiara;SP
        427;Guapimirim - RJ;3301850;2907;Guapimirim;RJ
        3089;Guapirama - PR;4109005;7575;Guapirama;PR
        2515;Guapó - GO;5209200;9381;Guapó;GO
        4059;Guaporé - RS;4309407;8687;Guaporé;RS
        3090;Guaporema - PR;4109104;7577;Guaporema;PR
        4572;Guará - SP;3517703;6455;Guará;SP
        2274;Guarabira - PB;2506301;2027;Guarabira;PB
        4573;Guaraçaí - SP;3517802;6457;Guaraçaí;SP
        3091;Guaraci - PR;4109203;7579;Guaraci;PR
        4574;Guaraci - SP;3517901;6459;Guaraci;SP
        5431;Guaraciaba - MG;3128204;4563;Guaraciaba;MG
        2763;Guaraciaba - SC;4206405;8125;Guaraciaba;SC
        1352;Guaraciaba do Norte - CE;2305001;1401;Guaraciaba do Norte;CE
        5432;Guaraciama - MG;3128253;622;Guaraciama;MG
        652;Guaraí - TO;1709302;9627;Guaraí;TO
        2516;Guaraíta - GO;5209291;65;Guaraíta;GO
        1353;Guaramiranga - CE;2305100;1403;Guaramiranga;CE
        2764;Guaramirim - SC;4206504;8127;Guaramirim;SC
        5433;Guaranésia - MG;3128303;4565;Guaranésia;MG
        5434;Guarani - MG;3128402;4567;Guarani;MG
        4060;Guarani das Missões - RS;4309506;8689;Guarani das Missões;RS
        2517;Guarani de Goiás - GO;5209408;9383;Guarani de Goiás;GO
        4575;Guarani d'Oeste - SP;3518008;6461;Guarani d'Oeste;SP
        3092;Guaraniaçu - PR;4109302;7581;Guaraniaçu;PR
        4576;Guarantã - SP;3518107;6463;Guarantã;SP
        781;Guarantã do Norte - MT;5104104;9887;Guarantã do Norte;MT
        272;Guarapari - ES;3202405;5647;Guarapari;ES
        3093;Guarapuava - PR;4109401;7583;Guarapuava;PR
        3094;Guaraqueçaba - PR;4109500;7585;Guaraqueçaba;PR
        5435;Guarará - MG;3128501;4569;Guarará;MG
        4577;Guararapes - SP;3518206;6465;Guararapes;SP
        4578;Guararema - SP;3518305;6467;Guararema;SP
        3598;Guaratinga - BA;2911808;3535;Guaratinga;BA
        4579;Guaratinguetá - SP;3518404;6469;Guaratinguetá;SP
        3095;Guaratuba - PR;4109609;7587;Guaratuba;PR
        5436;Guarda-Mor - MG;3128600;4571;Guarda-Mor;MG
        4580;Guareí - SP;3518503;6471;Guareí;SP
        4581;Guariba - SP;3518602;6473;Guariba;SP
        1962;Guaribas - PI;2204550;320;Guaribas;PI
        2518;Guarinos - GO;5209457;9993;Guarinos;GO
        4582;Guarujá - SP;3518701;6475;Guarujá;SP
        2765;Guarujá do Sul - SC;4206603;8129;Guarujá do Sul;SC
        4583;Guarulhos - SP;3518800;6477;Guarulhos;SP
        2766;Guatambú - SC;4206652;5583;Guatambú;SC
        4584;Guatapará - SP;3518859;7257;Guatapará;SP
        5437;Guaxupé - MG;3128709;4573;Guaxupé;MG
        358;Guia Lopes da Laguna - MS;5004106;9081;Guia Lopes da Laguna;MS
        5438;Guidoval - MG;3128808;4575;Guidoval;MG
        1738;Guimarães - MA;2104909;795;Guimarães;MA
        5439;Guimarânia - MG;3128907;4577;Guimarânia;MG
        782;Guiratinga - MT;5104203;9083;Guiratinga;MT
        5440;Guiricema - MG;3129004;4579;Guiricema;MG
        5441;Gurinhatã - MG;3129103;4581;Gurinhatã;MG
        2275;Gurinhém - PB;2506400;2029;Gurinhém;PB
        2276;Gurjão - PB;2506509;2031;Gurjão;PB
        926;Gurupá - PA;1503101;461;Gurupá;PA
        653;Gurupi - TO;1709500;9385;Gurupi;TO
        4585;Guzolândia - SP;3518909;6479;Guzolândia;SP
        4061;Harmonia - RS;4309555;8403;Harmonia;RS
        2519;Heitoraí - GO;5209606;9387;Heitoraí;GO
        5442;Heliodora - MG;3129202;4583;Heliodora;MG
        3599;Heliópolis - BA;2911857;3097;Heliópolis;BA
        4586;Herculândia - SP;3519006;6481;Herculândia;SP
        4062;Herval - RS;4307104;8639;Herval;RS
        2767;Herval d'Oeste - SC;4206702;8131;Herval d'Oeste;SC
        4063;Herveiras - RS;4309571;988;Herveiras;RS
        1354;Hidrolândia - CE;2305209;1405;Hidrolândia;CE
        2520;Hidrolândia - GO;5209705;9389;Hidrolândia;GO
        2521;Hidrolina - GO;5209804;9391;Hidrolina;GO
        4587;Holambra - SP;3519055;2953;Holambra;SP
        3096;Honório Serpa - PR;4109658;9981;Honório Serpa;PR
        1355;Horizonte - CE;2305233;1253;Horizonte;CE
        4064;Horizontina - RS;4309605;8691;Horizontina;RS
        4588;Hortolândia - SP;3519071;2951;Hortolândia;SP
        1963;Hugo Napoleão - PI;2204600;1091;Hugo Napoleão;PI
        4065;Hulha Negra - RS;4309654;6085;Hulha Negra;RS
        132;Humaitá - AM;1301704;235;Humaitá;AM
        4066;Humaitá - RS;4309704;8695;Humaitá;RS
        1739;Humberto de Campos - MA;2105005;797;Humberto de Campos;MA
        4589;Iacanga - SP;3519105;6483;Iacanga;SP
        2522;Iaciara - GO;5209903;9393;Iaciara;GO
        4590;Iacri - SP;3519204;6485;Iacri;SP
        3600;Iaçu - BA;2911907;3537;Iaçu;BA
        5443;Iapu - MG;3129301;4585;Iapu;MG
        4591;Iaras - SP;3519253;7259;Iaras;SP
        1540;Iati - PE;2606507;2429;Iati;PE
        3097;Ibaiti - PR;4109708;7589;Ibaiti;PR
        4067;Ibarama - RS;4309753;8401;Ibarama;RS
        1356;Ibaretama - CE;2305266;1255;Ibaretama;CE
        4592;Ibaté - SP;3519303;6487;Ibaté;SP
        525;Ibateguara - AL;2703007;2759;Ibateguara;AL
        273;Ibatiba - ES;3202454;5709;Ibatiba;ES
        3098;Ibema - PR;4109757;9949;Ibema;PR
        5444;Ibertioga - MG;3129400;4587;Ibertioga;MG
        5445;Ibiá - MG;3129509;4589;Ibiá;MG
        4068;Ibiaçá - RS;4309803;8697;Ibiaçá;RS
        5446;Ibiaí - MG;3129608;4591;Ibiaí;MG
        2768;Ibiam - SC;4206751;920;Ibiam;SC
        1357;Ibiapina - CE;2305308;1407;Ibiapina;CE
        2277;Ibiara - PB;2506608;2033;Ibiara;PB
        3601;Ibiassucê - BA;2912004;3539;Ibiassucê;BA
        3602;Ibicaraí - BA;2912103;3541;Ibicaraí;BA
        2769;Ibicaré - SC;4206801;8133;Ibicaré;SC
        3603;Ibicoara - BA;2912202;3543;Ibicoara;BA
        3604;Ibicuí - BA;2912301;3545;Ibicuí;BA
        1358;Ibicuitinga - CE;2305332;1257;Ibicuitinga;CE
        1541;Ibimirim - PE;2606606;2431;Ibimirim;PE
        3605;Ibipeba - BA;2912400;3547;Ibipeba;BA
        3606;Ibipitanga - BA;2912509;3551;Ibipitanga;BA
        3099;Ibiporã - PR;4109807;7591;Ibiporã;PR
        3607;Ibiquera - BA;2912608;3553;Ibiquera;BA
        4593;Ibirá - SP;3519402;6489;Ibirá;SP
        5447;Ibiracatu - MG;3129657;624;Ibiracatu;MG
        5448;Ibiraci - MG;3129707;4593;Ibiraci;MG
        274;Ibiraçu - ES;3202504;5649;Ibiraçu;ES
        4069;Ibiraiaras - RS;4309902;8699;Ibiraiaras;RS
        1542;Ibirajuba - PE;2606705;2433;Ibirajuba;PE
        2770;Ibirama - SC;4206900;8135;Ibirama;SC
        3608;Ibirapitanga - BA;2912707;3555;Ibirapitanga;BA
        3609;Ibirapuã - BA;2912806;3557;Ibirapuã;BA
        4070;Ibirapuitã - RS;4309951;7299;Ibirapuitã;RS
        4594;Ibirarema - SP;3519501;6491;Ibirarema;SP
        3610;Ibirataia - BA;2912905;3559;Ibirataia;BA
        5449;Ibirité - MG;3129806;4595;Ibirité;MG
        4071;Ibirubá - RS;4310009;8701;Ibirubá;RS
        3611;Ibitiara - BA;2913002;3561;Ibitiara;BA
        4595;Ibitinga - SP;3519600;6493;Ibitinga;SP
        275;Ibitirama - ES;3202553;6011;Ibitirama;ES
        3612;Ibititá - BA;2913101;3563;Ibititá;BA
        5450;Ibitiúra de Minas - MG;3129905;4597;Ibitiúra de Minas;MG
        5451;Ibituruna - MG;3130002;4599;Ibituruna;MG
        4596;Ibiúna - SP;3519709;6495;Ibiúna;SP
        3613;Ibotirama - BA;2913200;3565;Ibotirama;BA
        1359;Icapuí - CE;2305357;1593;Icapuí;CE
        2771;Içara - SC;4207007;8137;Içara;SC
        5452;Icaraí de Minas - MG;3130051;2693;Icaraí de Minas;MG
        3100;Icaraíma - PR;4109906;7593;Icaraíma;PR
        1740;Icatu - MA;2105104;799;Icatu;MA
        4597;Icém - SP;3519808;6497;Icém;SP
        3614;Ichu - BA;2913309;3567;Ichu;BA
        1360;Icó - CE;2305407;1409;Icó;CE
        276;Iconha - ES;3202603;5651;Iconha;ES
        1167;Ielmo Marinho - RN;2404606;1689;Ielmo Marinho;RN
        4598;Iepê - SP;3519907;6499;Iepê;SP
        526;Igaci - AL;2703106;2761;Igaci;AL
        3615;Igaporã - BA;2913408;3569;Igaporã;BA
        4599;Igaraçu do Tietê - SP;3520004;6501;Igaraçu do Tietê;SP
        2278;Igaracy - PB;2502607;1953;Igaracy;PB
        4600;Igarapava - SP;3520103;6503;Igarapava;SP
        5453;Igarapé - MG;3130101;4601;Igarapé;MG
        1741;Igarapé do Meio - MA;2105153;170;Igarapé do Meio;MA
        1742;Igarapé Grande - MA;2105203;801;Igarapé Grande;MA
        927;Igarapé-Açu - PA;1503200;463;Igarapé-Açu;PA
        928;Igarapé-Miri - PA;1503309;465;Igarapé-Miri;PA
        1543;Igarassu - PE;2606804;2435;Igarassu;PE
        4601;Igaratá - SP;3520202;6505;Igaratá;SP
        5454;Igaratinga - MG;3130200;4603;Igaratinga;MG
        3616;Igrapiúna - BA;2913457;3277;Igrapiúna;BA
        527;Igreja Nova - AL;2703205;2763;Igreja Nova;AL
        4072;Igrejinha - RS;4310108;8703;Igrejinha;RS
        428;Iguaba Grande - RJ;3301876;774;Iguaba Grande;RJ
        3617;Iguaí - BA;2913507;3571;Iguaí;BA
        4602;Iguape - SP;3520301;6507;Iguape;SP
        3101;Iguaraçu - PR;4110003;7595;Iguaraçu;PR
        1544;Iguaracy - PE;2606903;2437;Iguaracy;PE
        5455;Iguatama - MG;3130309;4605;Iguatama;MG
        359;Iguatemi - MS;5004304;9085;Iguatemi;MS
        1361;Iguatu - CE;2305506;1411;Iguatu;CE
        3102;Iguatu - PR;4110052;5467;Iguatu;PR
        5456;Ijaci - MG;3130408;4607;Ijaci;MG
        4073;Ijuí - RS;4310207;8705;Ijuí;RS
        4603;Ilha Comprida - SP;3520426;2969;Ilha Comprida;SP
        193;Ilha das Flores - SE;2802700;3153;Ilha das Flores;SE
        1545;Ilha de Itamaracá - PE;2607604;2451;Ilha de Itamaracá;PE
        1964;Ilha Grande - PI;2204659;322;Ilha Grande;PI
        4604;Ilha Solteira - SP;3520442;2943;Ilha Solteira;SP
        4605;Ilhabela - SP;3520400;6509;Ilhabela;SP
        3618;Ilhéus - BA;2913606;3573;Ilhéus;BA
        2772;Ilhota - SC;4207106;8139;Ilhota;SC
        5457;Ilicínea - MG;3130507;4609;Ilicínea;MG
        4074;Ilópolis - RS;4310306;8707;Ilópolis;RS
        2279;Imaculada - PB;2506707;2035;Imaculada;PB
        2773;Imaruí - SC;4207205;8141;Imaruí;SC
        3103;Imbaú - PR;4110078;862;Imbaú;PR
        4075;Imbé - RS;4310330;7297;Imbé;RS
        5458;Imbé de Minas - MG;3130556;626;Imbé de Minas;MG
        2774;Imbituba - SC;4207304;8143;Imbituba;SC
        3104;Imbituva - PR;4110102;7597;Imbituva;PR
        2775;Imbuia - SC;4207403;8145;Imbuia;SC
        4076;Imigrante - RS;4310363;7295;Imigrante;RS
        1743;Imperatriz - MA;2105302;803;Imperatriz;MA
        3105;Inácio Martins - PR;4110201;7599;Inácio Martins;PR
        2523;Inaciolândia - GO;5209937;69;Inaciolândia;GO
        1546;Inajá - PE;2607000;2439;Inajá;PE
        3106;Inajá - PR;4110300;7601;Inajá;PR
        5459;Inconfidentes - MG;3130606;4611;Inconfidentes;MG
        5460;Indaiabira - MG;3130655;628;Indaiabira;MG
        2776;Indaial - SC;4207502;8147;Indaial;SC
        4606;Indaiatuba - SP;3520509;6511;Indaiatuba;SP
        1362;Independência - CE;2305605;1413;Independência;CE
        4077;Independência - RS;4310405;8709;Independência;RS
        4607;Indiana - SP;3520608;6513;Indiana;SP
        5461;Indianópolis - MG;3130705;4613;Indianópolis;MG
        3107;Indianópolis - PR;4110409;7961;Indianópolis;PR
        4608;Indiaporã - SP;3520707;6515;Indiaporã;SP
        2524;Indiara - GO;5209952;9681;Indiara;GO
        194;Indiaroba - SE;2802809;3155;Indiaroba;SE
        783;Indiavaí - MT;5104500;9877;Indiavaí;MT
        2280;Ingá - PB;2506806;2037;Ingá;PB
        5462;Ingaí - MG;3130804;4615;Ingaí;MG
        1547;Ingazeira - PE;2607109;2441;Ingazeira;PE
        4078;Inhacorá - RS;4310413;6051;Inhacorá;RS
        3619;Inhambupe - BA;2913705;3575;Inhambupe;BA
        929;Inhangapi - PA;1503408;467;Inhangapi;PA
        528;Inhapi - AL;2703304;2765;Inhapi;AL
        5463;Inhapim - MG;3130903;4617;Inhapim;MG
        5464;Inhaúma - MG;3131000;4619;Inhaúma;MG
        1965;Inhuma - PI;2204709;1093;Inhuma;PI
        2525;Inhumas - GO;5210000;9395;Inhumas;GO
        5465;Inimutaba - MG;3131109;4621;Inimutaba;MG
        360;Inocência - MS;5004403;9087;Inocência;MS
        4609;Inúbia Paulista - SP;3520806;6517;Inúbia Paulista;SP
        2777;Iomerê - SC;4207577;922;Iomerê;SC
        5466;Ipaba - MG;3131158;2665;Ipaba;MG
        2526;Ipameri - GO;5210109;9397;Ipameri;GO
        5467;Ipanema - MG;3131208;4623;Ipanema;MG
        1168;Ipanguaçu - RN;2404705;1691;Ipanguaçu;RN
        1363;Ipaporanga - CE;2305654;1259;Ipaporanga;CE
        5468;Ipatinga - MG;3131307;4625;Ipatinga;MG
        1364;Ipaumirim - CE;2305704;1415;Ipaumirim;CE
        4610;Ipaussu - SP;3520905;6519;Ipaussu;SP
        4079;Ipê - RS;4310439;8399;Ipê;RS
        3620;Ipecaetá - BA;2913804;3577;Ipecaetá;BA
        4611;Iperó - SP;3521002;6521;Iperó;SP
        4612;Ipeúna - SP;3521101;6523;Ipeúna;SP
        5469;Ipiaçu - MG;3131406;4627;Ipiaçu;MG
        3621;Ipiaú - BA;2913903;3579;Ipiaú;BA
        4613;Ipiguá - SP;3521150;800;Ipiguá;SP
        3622;Ipirá - BA;2914000;3581;Ipirá;BA
        2778;Ipira - SC;4207601;8149;Ipira;SC
        3108;Ipiranga - PR;4110508;7603;Ipiranga;PR
        2527;Ipiranga de Goiás - GO;5210158;1074;Ipiranga de Goiás;GO
        784;Ipiranga do Norte - MT;5104526;1184;Ipiranga do Norte;MT
        1966;Ipiranga do Piauí - PI;2204808;1095;Ipiranga do Piauí;PI
        4080;Ipiranga do Sul - RS;4310462;7399;Ipiranga do Sul;RS
        133;Ipixuna - AM;1301803;239;Ipixuna;AM
        930;Ipixuna do Pará - PA;1503457;621;Ipixuna do Pará;PA
        1548;Ipojuca - PE;2607208;2443;Ipojuca;PE
        2528;Iporá - GO;5210208;9399;Iporá;GO
        3109;Iporã - PR;4110607;7605;Iporã;PR
        2779;Iporã do Oeste - SC;4207650;9951;Iporã do Oeste;SC
        4614;Iporanga - SP;3521200;6525;Iporanga;SP
        1365;Ipu - CE;2305803;1417;Ipu;CE
        4615;Ipuã - SP;3521309;6527;Ipuã;SP
        2780;Ipuaçu - SC;4207684;5737;Ipuaçu;SC
        1549;Ipubi - PE;2607307;2445;Ipubi;PE
        1169;Ipueira - RN;2404804;1693;Ipueira;RN
        1366;Ipueiras - CE;2305902;1419;Ipueiras;CE
        654;Ipueiras - TO;1709807;84;Ipueiras;TO
        5470;Ipuiúna - MG;3131505;4629;Ipuiúna;MG
        2781;Ipumirim - SC;4207700;8151;Ipumirim;SC
        3623;Ipupiara - BA;2914109;3583;Ipupiara;BA
        1367;Iracema - CE;2306009;1421;Iracema;CE
        9;Iracema - RR;1400282;32;Iracema;RR
        3110;Iracema do Oeste - PR;4110656;5485;Iracema do Oeste;PR
        4616;Iracemápolis - SP;3521408;6529;Iracemápolis;SP
        2782;Iraceminha - SC;4207759;9953;Iraceminha;SC
        4081;Iraí - RS;4310504;8711;Iraí;RS
        5471;Iraí de Minas - MG;3131604;4631;Iraí de Minas;MG
        3624;Irajuba - BA;2914208;3585;Irajuba;BA
        3625;Iramaia - BA;2914307;3587;Iramaia;BA
        134;Iranduba - AM;1301852;9835;Iranduba;AM
        2783;Irani - SC;4207809;8153;Irani;SC
        4617;Irapuã - SP;3521507;6531;Irapuã;SP
        4618;Irapuru - SP;3521606;6533;Irapuru;SP
        3626;Iraquara - BA;2914406;3589;Iraquara;BA
        3627;Irará - BA;2914505;3591;Irará;BA
        3111;Irati - PR;4110706;7607;Irati;PR
        2784;Irati - SC;4207858;5585;Irati;SC
        1368;Irauçuba - CE;2306108;1423;Irauçuba;CE
        3628;Irecê - BA;2914604;3593;Irecê;BA
        3112;Iretama - PR;4110805;7609;Iretama;PR
        2785;Irineópolis - SC;4207908;8155;Irineópolis;SC
        931;Irituia - PA;1503507;469;Irituia;PA
        277;Irupi - ES;3202652;2931;Irupi;ES
        1967;Isaías Coelho - PI;2204907;1097;Isaías Coelho;PI
        2529;Israelândia - GO;5210307;9401;Israelândia;GO
        2786;Itá - SC;4208005;8157;Itá;SC
        4082;Itaara - RS;4310538;990;Itaara;RS
        2281;Itabaiana - PB;2506905;2039;Itabaiana;PB
        195;Itabaiana - SE;2802908;3157;Itabaiana;SE
        196;Itabaianinha - SE;2803005;3159;Itabaianinha;SE
        3629;Itabela - BA;2914653;3279;Itabela;BA
        4619;Itaberá - SP;3521705;6535;Itaberá;SP
        3630;Itaberaba - BA;2914703;3595;Itaberaba;BA
        2530;Itaberaí - GO;5210406;9403;Itaberaí;GO
        197;Itabi - SE;2803104;3161;Itabi;SE
        5472;Itabira - MG;3131703;4633;Itabira;MG
        5473;Itabirinha - MG;3131802;4635;Itabirinha;MG
        5474;Itabirito - MG;3131901;4637;Itabirito;MG
        429;Itaboraí - RJ;3301900;5837;Itaboraí;RJ
        3631;Itabuna - BA;2914802;3597;Itabuna;BA
        655;Itacajá - TO;1710508;9405;Itacajá;TO
        5475;Itacambira - MG;3132008;4639;Itacambira;MG
        5476;Itacarambi - MG;3132107;4641;Itacarambi;MG
        3632;Itacaré - BA;2914901;3599;Itacaré;BA
        135;Itacoatiara - AM;1301902;241;Itacoatiara;AM
        1550;Itacuruba - PE;2607406;2447;Itacuruba;PE
        4083;Itacurubi - RS;4310553;7397;Itacurubi;RS
        3633;Itaeté - BA;2915007;3601;Itaeté;BA
        3634;Itagi - BA;2915106;3603;Itagi;BA
        3635;Itagibá - BA;2915205;3605;Itagibá;BA
        3636;Itagimirim - BA;2915304;3607;Itagimirim;BA
        278;Itaguaçu - ES;3202702;5653;Itaguaçu;ES
        3637;Itaguaçu da Bahia - BA;2915353;3281;Itaguaçu da Bahia;BA
        430;Itaguaí - RJ;3302007;5839;Itaguaí;RJ
        3113;Itaguajé - PR;4110904;7611;Itaguajé;PR
        5477;Itaguara - MG;3132206;4643;Itaguara;MG
        2531;Itaguari - GO;5210562;9919;Itaguari;GO
        2532;Itaguaru - GO;5210604;9407;Itaguaru;GO
        656;Itaguatins - TO;1710706;9409;Itaguatins;TO
        4620;Itaí - SP;3521804;6537;Itaí;SP
        1551;Itaíba - PE;2607505;2449;Itaíba;PE
        1369;Itaiçaba - CE;2306207;1425;Itaiçaba;CE
        1968;Itainópolis - PI;2205003;1099;Itainópolis;PI
        2787;Itaiópolis - SC;4208104;8159;Itaiópolis;SC
        1744;Itaipava do Grajaú - MA;2105351;172;Itaipava do Grajaú;MA
        5478;Itaipé - MG;3132305;4645;Itaipé;MG
        3114;Itaipulândia - PR;4110953;5525;Itaipulândia;PR
        1370;Itaitinga - CE;2306256;991;Itaitinga;CE
        932;Itaituba - PA;1503606;471;Itaituba;PA
        2533;Itajá - GO;5210802;9411;Itajá;GO
        1170;Itajá - RN;2404853;418;Itajá;RN
        2788;Itajaí - SC;4208203;8161;Itajaí;SC
        4621;Itajobi - SP;3521903;6539;Itajobi;SP
        4622;Itaju - SP;3522000;6541;Itaju;SP
        3638;Itaju do Colônia - BA;2915403;3609;Itaju do Colônia;BA
        5479;Itajubá - MG;3132404;4647;Itajubá;MG
        3639;Itajuípe - BA;2915502;3611;Itajuípe;BA
        431;Italva - RJ;3302056;5929;Italva;RJ
        3640;Itamaraju - BA;2915601;3613;Itamaraju;BA
        5480;Itamarandiba - MG;3132503;4649;Itamarandiba;MG
        136;Itamarati - AM;1301951;9837;Itamarati;AM
        5481;Itamarati de Minas - MG;3132602;4651;Itamarati de Minas;MG
        3641;Itamari - BA;2915700;3615;Itamari;BA
        5482;Itambacuri - MG;3132701;4653;Itambacuri;MG
        3115;Itambaracá - PR;4111001;7613;Itambaracá;PR
        3642;Itambé - BA;2915809;3617;Itambé;BA
        1552;Itambé - PE;2607653;2597;Itambé;PE
        3116;Itambé - PR;4111100;7615;Itambé;PR
        5483;Itambé do Mato Dentro - MG;3132800;4655;Itambé do Mato Dentro;MG
        5484;Itamogi - MG;3132909;4657;Itamogi;MG
        5485;Itamonte - MG;3133006;4659;Itamonte;MG
        3643;Itanagra - BA;2915908;3619;Itanagra;BA
        4623;Itanhaém - SP;3522109;6543;Itanhaém;SP
        5486;Itanhandu - MG;3133105;4661;Itanhandu;MG
        785;Itanhangá - MT;5104542;1186;Itanhangá;MT
        3644;Itanhém - BA;2916005;3621;Itanhém;BA
        5487;Itanhomi - MG;3133204;4663;Itanhomi;MG
        5488;Itaobim - MG;3133303;4665;Itaobim;MG
        4624;Itaoca - SP;3522158;3053;Itaoca;SP
        432;Itaocara - RJ;3302106;5841;Itaocara;RJ
        2534;Itapaci - GO;5210901;9413;Itapaci;GO
        5489;Itapagipe - MG;3133402;4667;Itapagipe;MG
        1371;Itapajé - CE;2306306;1427;Itapajé;CE
        3645;Itaparica - BA;2916104;3623;Itaparica;BA
        3646;Itapé - BA;2916203;3625;Itapé;BA
        3647;Itapebi - BA;2916302;3627;Itapebi;BA
        5490;Itapecerica - MG;3133501;4669;Itapecerica;MG
        4625;Itapecerica da Serra - SP;3522208;6545;Itapecerica da Serra;SP
        1745;Itapecuru Mirim - MA;2105401;807;Itapecuru Mirim;MA
        3117;Itapejara d'Oeste - PR;4111209;7617;Itapejara d'Oeste;PR
        2789;Itapema - SC;4208302;8163;Itapema;SC
        279;Itapemirim - ES;3202801;5655;Itapemirim;ES
        3118;Itaperuçu - PR;4111258;5451;Itaperuçu;PR
        433;Itaperuna - RJ;3302205;5843;Itaperuna;RJ
        1553;Itapetim - PE;2607703;2453;Itapetim;PE
        3648;Itapetinga - BA;2916401;3629;Itapetinga;BA
        4626;Itapetininga - SP;3522307;6547;Itapetininga;SP
        5491;Itapeva - MG;3133600;4671;Itapeva;MG
        4627;Itapeva - SP;3522406;6549;Itapeva;SP
        4628;Itapevi - SP;3522505;6551;Itapevi;SP
        3649;Itapicuru - BA;2916500;3631;Itapicuru;BA
        1372;Itapipoca - CE;2306405;1429;Itapipoca;CE
        4629;Itapira - SP;3522604;6553;Itapira;SP
        137;Itapiranga - AM;1302009;243;Itapiranga;AM
        2790;Itapiranga - SC;4208401;8165;Itapiranga;SC
        2535;Itapirapuã - GO;5211008;9415;Itapirapuã;GO
        4630;Itapirapuã Paulista - SP;3522653;3055;Itapirapuã Paulista;SP
        657;Itapiratins - TO;1710904;347;Itapiratins;TO
        1554;Itapissuma - PE;2607752;2633;Itapissuma;PE
        3650;Itapitanga - BA;2916609;3633;Itapitanga;BA
        1373;Itapiúna - CE;2306504;1431;Itapiúna;CE
        2791;Itapoá - SC;4208450;9985;Itapoá;SC
        4631;Itápolis - SP;3522703;6555;Itápolis;SP
        361;Itaporã - MS;5004502;9089;Itaporã;MS
        658;Itaporã do Tocantins - TO;1711100;9417;Itaporã do Tocantins;TO
        2282;Itaporanga - PB;2507002;2041;Itaporanga;PB
        4632;Itaporanga - SP;3522802;6557;Itaporanga;SP
        198;Itaporanga d'Ajuda - SE;2803203;3163;Itaporanga d'Ajuda;SE
        2283;Itapororoca - PB;2507101;2043;Itapororoca;PB
        76;Itapuã do Oeste - RO;1101104;683;Itapuã do Oeste;RO
        4084;Itapuca - RS;4310579;6027;Itapuca;RS
        4633;Itapuí - SP;3522901;6559;Itapuí;SP
        4634;Itapura - SP;3523008;6561;Itapura;SP
        2536;Itapuranga - GO;5211206;9419;Itapuranga;GO
        4635;Itaquaquecetuba - SP;3523107;6563;Itaquaquecetuba;SP
        3651;Itaquara - BA;2916708;3635;Itaquara;BA
        4085;Itaqui - RS;4310603;8713;Itaqui;RS
        362;Itaquiraí - MS;5004601;9807;Itaquiraí;MS
        1555;Itaquitinga - PE;2607802;2455;Itaquitinga;PE
        280;Itarana - ES;3202900;5657;Itarana;ES
        3652;Itarantim - BA;2916807;3637;Itarantim;BA
        4636;Itararé - SP;3523206;6565;Itararé;SP
        1374;Itarema - CE;2306553;1595;Itarema;CE
        4637;Itariri - SP;3523305;6567;Itariri;SP
        2537;Itarumã - GO;5211305;9421;Itarumã;GO
        4086;Itati - RS;4310652;1144;Itati;RS
        434;Itatiaia - RJ;3302254;6003;Itatiaia;RJ
        5492;Itatiaiuçu - MG;3133709;4673;Itatiaiuçu;MG
        4638;Itatiba - SP;3523404;6569;Itatiba;SP
        4087;Itatiba do Sul - RS;4310702;8715;Itatiba do Sul;RS
        3653;Itatim - BA;2916856;3283;Itatim;BA
        4639;Itatinga - SP;3523503;6571;Itatinga;SP
        1375;Itatira - CE;2306603;1433;Itatira;CE
        2284;Itatuba - PB;2507200;2045;Itatuba;PB
        1171;Itaú - RN;2404903;1695;Itaú;RN
        5493;Itaú de Minas - MG;3133758;5731;Itaú de Minas;MG
        786;Itaúba - MT;5104559;9901;Itaúba;MT
        21;Itaubal - AP;1600253;669;Itaubal;AP
        2538;Itauçu - GO;5211404;9423;Itauçu;GO
        1969;Itaueira - PI;2205102;1101;Itaueira;PI
        5494;Itaúna - MG;3133808;4675;Itaúna;MG
        3119;Itaúna do Sul - PR;4111308;7619;Itaúna do Sul;PR
        5495;Itaverava - MG;3133907;4677;Itaverava;MG
        5496;Itinga - MG;3134004;4679;Itinga;MG
        1746;Itinga do Maranhão - MA;2105427;174;Itinga do Maranhão;MA
        787;Itiquira - MT;5104609;9091;Itiquira;MT
        4640;Itirapina - SP;3523602;6573;Itirapina;SP
        4641;Itirapuã - SP;3523701;6575;Itirapuã;SP
        3654;Itiruçu - BA;2916906;3639;Itiruçu;BA
        3655;Itiúba - BA;2917003;3641;Itiúba;BA
        4642;Itobi - SP;3523800;6577;Itobi;SP
        3656;Itororó - BA;2917102;3643;Itororó;BA
        4643;Itu - SP;3523909;6579;Itu;SP
        3657;Ituaçu - BA;2917201;3645;Ituaçu;BA
        3658;Ituberá - BA;2917300;3647;Ituberá;BA
        5497;Itueta - MG;3134103;4681;Itueta;MG
        5498;Ituiutaba - MG;3134202;4683;Ituiutaba;MG
        2539;Itumbiara - GO;5211503;9425;Itumbiara;GO
        5499;Itumirim - MG;3134301;4685;Itumirim;MG
        4644;Itupeva - SP;3524006;6581;Itupeva;SP
        933;Itupiranga - PA;1503705;473;Itupiranga;PA
        2792;Ituporanga - SC;4208500;8167;Ituporanga;SC
        5500;Iturama - MG;3134400;4687;Iturama;MG
        5501;Itutinga - MG;3134509;4689;Itutinga;MG
        4645;Ituverava - SP;3524105;6583;Ituverava;SP
        3659;Iuiu - BA;2917334;3285;Iuiu;BA
        281;Iúna - ES;3203007;5659;Iúna;ES
        3120;Ivaí - PR;4111407;7621;Ivaí;PR
        3121;Ivaiporã - PR;4111506;7623;Ivaiporã;PR
        3122;Ivaté - PR;4111555;9955;Ivaté;PR
        3123;Ivatuba - PR;4111605;7625;Ivatuba;PR
        363;Ivinhema - MS;5004700;9093;Ivinhema;MS
        2540;Ivolândia - GO;5211602;9427;Ivolândia;GO
        4088;Ivorá - RS;4310751;7395;Ivorá;RS
        4089;Ivoti - RS;4310801;8717;Ivoti;RS
        1556;Jaboatão dos Guararapes - PE;2607901;2457;Jaboatão dos Guararapes;PE
        2793;Jaborá - SC;4208609;8169;Jaborá;SC
        3660;Jaborandi - BA;2917359;9859;Jaborandi;BA
        4646;Jaborandi - SP;3524204;6585;Jaborandi;SP
        3124;Jaboti - PR;4111704;7627;Jaboti;PR
        4090;Jaboticaba - RS;4310850;7393;Jaboticaba;RS
        4647;Jaboticabal - SP;3524303;6587;Jaboticabal;SP
        5502;Jaboticatubas - MG;3134608;4691;Jaboticatubas;MG
        1172;Jaçanã - RN;2405009;1697;Jaçanã;RN
        3661;Jacaraci - BA;2917409;3649;Jacaraci;BA
        2285;Jacaraú - PB;2507309;2047;Jacaraú;PB
        529;Jacaré dos Homens - AL;2703403;2767;Jacaré dos Homens;AL
        934;Jacareacanga - PA;1503754;631;Jacareacanga;PA
        4648;Jacareí - SP;3524402;6589;Jacareí;SP
        3125;Jacarezinho - PR;4111803;7629;Jacarezinho;PR
        4649;Jaci - SP;3524501;6591;Jaci;SP
        788;Jaciara - MT;5104807;9095;Jaciara;MT
        5503;Jacinto - MG;3134707;4693;Jacinto;MG
        2794;Jacinto Machado - SC;4208708;8171;Jacinto Machado;SC
        3662;Jacobina - BA;2917508;3651;Jacobina;BA
        1970;Jacobina do Piauí - PI;2205151;2273;Jacobina do Piauí;PI
        5504;Jacuí - MG;3134806;4695;Jacuí;MG
        530;Jacuípe - AL;2703502;2769;Jacuípe;AL
        4091;Jacuizinho - RS;4310876;1146;Jacuizinho;RS
        935;Jacundá - PA;1503804;475;Jacundá;PA
        4650;Jacupiranga - SP;3524600;6593;Jacupiranga;SP
        5505;Jacutinga - MG;3134905;4697;Jacutinga;MG
        4092;Jacutinga - RS;4310900;8719;Jacutinga;RS
        3126;Jaguapitã - PR;4111902;7631;Jaguapitã;PR
        3663;Jaguaquara - BA;2917607;3653;Jaguaquara;BA
        5506;Jaguaraçu - MG;3135001;4699;Jaguaraçu;MG
        4093;Jaguarão - RS;4311007;8721;Jaguarão;RS
        3664;Jaguarari - BA;2917706;3655;Jaguarari;BA
        282;Jaguaré - ES;3203056;5713;Jaguaré;ES
        1376;Jaguaretama - CE;2306702;1435;Jaguaretama;CE
        4094;Jaguari - RS;4311106;8723;Jaguari;RS
        3127;Jaguariaíva - PR;4112009;7633;Jaguariaíva;PR
        1377;Jaguaribara - CE;2306801;1437;Jaguaribara;CE
        1378;Jaguaribe - CE;2306900;1439;Jaguaribe;CE
        3665;Jaguaripe - BA;2917805;3657;Jaguaripe;BA
        4651;Jaguariúna - SP;3524709;6595;Jaguariúna;SP
        1379;Jaguaruana - CE;2307007;1441;Jaguaruana;CE
        2795;Jaguaruna - SC;4208807;8173;Jaguaruna;SC
        5507;Jaíba - MG;3135050;2893;Jaíba;MG
        1971;Jaicós - PI;2205201;1103;Jaicós;PI
        4652;Jales - SP;3524808;6597;Jales;SP
        4653;Jambeiro - SP;3524907;6599;Jambeiro;SP
        5508;Jampruca - MG;3135076;2655;Jampruca;MG
        5509;Janaúba - MG;3135100;4701;Janaúba;MG
        2541;Jandaia - GO;5211701;9429;Jandaia;GO
        3128;Jandaia do Sul - PR;4112108;7635;Jandaia do Sul;PR
        3666;Jandaíra - BA;2917904;3659;Jandaíra;BA
        1173;Jandaíra - RN;2405108;1699;Jandaíra;RN
        4654;Jandira - SP;3525003;6601;Jandira;SP
        1174;Janduís - RN;2405207;1701;Janduís;RN
        789;Jangada - MT;5104906;9861;Jangada;MT
        3129;Janiópolis - PR;4112207;7637;Janiópolis;PR
        5510;Januária - MG;3135209;4703;Januária;MG
        1175;Januário Cicco - RN;2405306;1703;Januário Cicco;RN
        5511;Japaraíba - MG;3135308;4705;Japaraíba;MG
        531;Japaratinga - AL;2703601;2771;Japaratinga;AL
        199;Japaratuba - SE;2803302;3165;Japaratuba;SE
        435;Japeri - RJ;3302270;2913;Japeri;RJ
        1176;Japi - RN;2405405;1705;Japi;RN
        3130;Japira - PR;4112306;7639;Japira;PR
        200;Japoatã - SE;2803401;3167;Japoatã;SE
        5512;Japonvar - MG;3135357;630;Japonvar;MG
        364;Japorã - MS;5004809;161;Japorã;MS
        138;Japurá - AM;1302108;245;Japurá;AM
        3131;Japurá - PR;4112405;7641;Japurá;PR
        1557;Jaqueira - PE;2607950;548;Jaqueira;PE
        4095;Jaquirana - RS;4311122;7391;Jaquirana;RS
        2542;Jaraguá - GO;5211800;9431;Jaraguá;GO
        2796;Jaraguá do Sul - SC;4208906;8175;Jaraguá do Sul;SC
        365;Jaraguari - MS;5004908;9097;Jaraguari;MS
        532;Jaramataia - AL;2703700;2773;Jaramataia;AL
        1380;Jardim - CE;2307106;1443;Jardim;CE
        366;Jardim - MS;5005004;9099;Jardim;MS
        3132;Jardim Alegre - PR;4112504;7643;Jardim Alegre;PR
        1177;Jardim de Angicos - RN;2405504;1707;Jardim de Angicos;RN
        1178;Jardim de Piranhas - RN;2405603;1709;Jardim de Piranhas;RN
        1972;Jardim do Mulato - PI;2205250;997;Jardim do Mulato;PI
        1179;Jardim do Seridó - RN;2405702;1711;Jardim do Seridó;RN
        3133;Jardim Olinda - PR;4112603;7645;Jardim Olinda;PR
        2797;Jardinópolis - SC;4208955;5587;Jardinópolis;SC
        4655;Jardinópolis - SP;3525102;6603;Jardinópolis;SP
        4096;Jari - RS;4311130;992;Jari;RS
        4656;Jarinu - SP;3525201;6605;Jarinu;SP
        77;Jaru - RO;1100114;15;Jaru;RO
        2543;Jataí - GO;5211909;9433;Jataí;GO
        3134;Jataizinho - PR;4112702;7647;Jataizinho;PR
        1558;Jataúba - PE;2608008;2459;Jataúba;PE
        367;Jateí - MS;5005103;9101;Jateí;MS
        1381;Jati - CE;2307205;1445;Jati;CE
        1747;Jatobá - MA;2105450;176;Jatobá;MA
        1559;Jatobá - PE;2608057;550;Jatobá;PE
        1973;Jatobá do Piauí - PI;2205276;324;Jatobá do Piauí;PI
        4657;Jaú - SP;3525300;6607;Jaú;SP
        659;Jaú do Tocantins - TO;1711506;329;Jaú do Tocantins;TO
        2544;Jaupaci - GO;5212006;9435;Jaupaci;GO
        790;Jauru - MT;5105002;8991;Jauru;MT
        5513;Jeceaba - MG;3135407;4707;Jeceaba;MG
        5514;Jenipapo de Minas - MG;3135456;632;Jenipapo de Minas;MG
        1748;Jenipapo dos Vieiras - MA;2105476;178;Jenipapo dos Vieiras;MA
        5515;Jequeri - MG;3135506;4709;Jequeri;MG
        533;Jequiá da Praia - AL;2703759;562;Jequiá da Praia;AL
        3667;Jequié - BA;2918001;3661;Jequié;BA
        5516;Jequitaí - MG;3135605;4711;Jequitaí;MG
        5517;Jequitibá - MG;3135704;4713;Jequitibá;MG
        5518;Jequitinhonha - MG;3135803;4715;Jequitinhonha;MG
        3668;Jeremoabo - BA;2918100;3663;Jeremoabo;BA
        2286;Jericó - PB;2507408;2049;Jericó;PB
        4658;Jeriquara - SP;3525409;6609;Jeriquara;SP
        283;Jerônimo Monteiro - ES;3203106;5661;Jerônimo Monteiro;ES
        1974;Jerumenha - PI;2205300;1105;Jerumenha;PI
        5519;Jesuânia - MG;3135902;4717;Jesuânia;MG
        3135;Jesuítas - PR;4112751;7997;Jesuítas;PR
        2545;Jesúpolis - GO;5212055;49;Jesúpolis;GO
        1382;Jijoca de Jericoacoara - CE;2307254;985;Jijoca de Jericoacoara;CE
        78;Ji-Paraná - RO;1100122;5;Ji-Paraná;RO
        3669;Jiquiriçá - BA;2918209;3665;Jiquiriçá;BA
        3670;Jitaúna - BA;2918308;3667;Jitaúna;BA
        2798;Joaçaba - SC;4209003;8177;Joaçaba;SC
        5520;Joaíma - MG;3136009;4719;Joaíma;MG
        5521;Joanésia - MG;3136108;4721;Joanésia;MG
        4659;Joanópolis - SP;3525508;6611;Joanópolis;SP
        1560;João Alfredo - PE;2608107;2461;João Alfredo;PE
        1180;João Câmara - RN;2405801;1713;João Câmara;RN
        1975;João Costa - PI;2205359;326;João Costa;PI
        1181;João Dias - RN;2405900;1715;João Dias;RN
        3671;João Dourado - BA;2918357;3099;João Dourado;BA
        1749;João Lisboa - MA;2105500;809;João Lisboa;MA
        5522;João Monlevade - MG;3136207;4723;João Monlevade;MG
        284;João Neiva - ES;3203130;5721;João Neiva;ES
        2287;João Pessoa - PB;2507507;2051;João Pessoa;PB
        5523;João Pinheiro - MG;3136306;4725;João Pinheiro;MG
        4660;João Ramalho - SP;3525607;6613;João Ramalho;SP
        5524;Joaquim Felício - MG;3136405;4727;Joaquim Felício;MG
        534;Joaquim Gomes - AL;2703809;2775;Joaquim Gomes;AL
        1561;Joaquim Nabuco - PE;2608206;2463;Joaquim Nabuco;PE
        1976;Joaquim Pires - PI;2205409;1107;Joaquim Pires;PI
        3136;Joaquim Távora - PR;4112801;7649;Joaquim Távora;PR
        2367;Joca Claudino - PB;2513653;514;Joca Claudino;PB
        1977;Joca Marques - PI;2205458;328;Joca Marques;PI
        4097;Jóia - RS;4311155;9829;Jóia;RS
        2799;Joinville - SC;4209102;8179;Joinville;SC
        5525;Jordânia - MG;3136504;4729;Jordânia;MG
        41;Jordão - AC;1200328;653;Jordão;AC
        2800;José Boiteux - SC;4209151;9957;José Boiteux;SC
        4661;José Bonifácio - SP;3525706;6615;José Bonifácio;SP
        1182;José da Penha - RN;2406007;1717;José da Penha;RN
        1978;José de Freitas - PI;2205508;1109;José de Freitas;PI
        5526;José Gonçalves de Minas - MG;3136520;634;José Gonçalves de Minas;MG
        5527;José Raydan - MG;3136553;636;José Raydan;MG
        1750;Joselândia - MA;2105609;811;Joselândia;MA
        5528;Josenópolis - MG;3136579;638;Josenópolis;MG
        2546;Joviânia - GO;5212105;9437;Joviânia;GO
        791;Juara - MT;5105101;9819;Juara;MT
        2288;Juarez Távora - PB;2507606;2053;Juarez Távora;PB
        660;Juarina - TO;1711803;349;Juarina;TO
        5529;Juatuba - MG;3136652;2691;Juatuba;MG
        2289;Juazeirinho - PB;2507705;2055;Juazeirinho;PB
        3672;Juazeiro - BA;2918407;3669;Juazeiro;BA
        1383;Juazeiro do Norte - CE;2307304;1447;Juazeiro do Norte;CE
        1979;Juazeiro do Piauí - PI;2205516;330;Juazeiro do Piauí;PI
        1384;Jucás - CE;2307403;1449;Jucás;CE
        1562;Jucati - PE;2608255;2295;Jucati;PE
        3673;Jucuruçu - BA;2918456;3287;Jucuruçu;BA
        1183;Jucurutu - RN;2406106;1719;Jucurutu;RN
        792;Juína - MT;5105150;9831;Juína;MT
        5530;Juiz de Fora - MG;3136702;4733;Juiz de Fora;MG
        1980;Júlio Borges - PI;2205524;332;Júlio Borges;PI
        4098;Júlio de Castilhos - RS;4311205;8725;Júlio de Castilhos;RS
        4662;Júlio Mesquita - SP;3525805;6617;Júlio Mesquita;SP
        4663;Jumirim - SP;3525854;802;Jumirim;SP
        1751;Junco do Maranhão - MA;2105658;180;Junco do Maranhão;MA
        2290;Junco do Seridó - PB;2507804;2057;Junco do Seridó;PB
        535;Jundiá - AL;2703908;2777;Jundiá;AL
        1184;Jundiá - RN;2406155;1108;Jundiá;RN
        4664;Jundiaí - SP;3525904;6619;Jundiaí;SP
        3137;Jundiaí do Sul - PR;4112900;7651;Jundiaí do Sul;PR
        536;Junqueiro - AL;2704005;2779;Junqueiro;AL
        4665;Junqueirópolis - SP;3526001;6621;Junqueirópolis;SP
        1563;Jupi - PE;2608305;2465;Jupi;PE
        2801;Jupiá - SC;4209177;924;Jupiá;SC
        4666;Juquiá - SP;3526100;6623;Juquiá;SP
        4667;Juquitiba - SP;3526209;6625;Juquitiba;SP
        5531;Juramento - MG;3136801;4735;Juramento;MG
        3138;Juranda - PR;4112959;8463;Juranda;PR
        1564;Jurema - PE;2608404;2467;Jurema;PE
        1981;Jurema - PI;2205532;334;Jurema;PI
        2291;Juripiranga - PB;2507903;2059;Juripiranga;PB
        2292;Juru - PB;2508000;2061;Juru;PB
        139;Juruá - AM;1302207;247;Juruá;AM
        5532;Juruaia - MG;3136900;4737;Juruaia;MG
        793;Juruena - MT;5105176;9921;Juruena;MT
        936;Juruti - PA;1503903;477;Juruti;PA
        794;Juscimeira - MT;5105200;9189;Juscimeira;MT
        3674;Jussara - BA;2918506;3671;Jussara;BA
        2547;Jussara - GO;5212204;9439;Jussara;GO
        3139;Jussara - PR;4113007;7653;Jussara;PR
        3675;Jussari - BA;2918555;3069;Jussari;BA
        3676;Jussiape - BA;2918605;3673;Jussiape;BA
        140;Jutaí - AM;1302306;249;Jutaí;AM
        368;Juti - MS;5005152;9923;Juti;MS
        5533;Juvenília - MG;3136959;640;Juvenília;MG
        3140;Kaloré - PR;4113106;7655;Kaloré;PR
        141;Lábrea - AM;1302405;251;Lábrea;AM
        2802;Lacerdópolis - SC;4209201;8181;Lacerdópolis;SC
        5534;Ladainha - MG;3137007;4739;Ladainha;MG
        369;Ladário - MS;5005202;9103;Ladário;MS
        3677;Lafaiete Coutinho - BA;2918704;3675;Lafaiete Coutinho;BA
        5535;Lagamar - MG;3137106;4741;Lagamar;MG
        201;Lagarto - SE;2803500;3169;Lagarto;SE
        2803;Lages - SC;4209300;8183;Lages;SC
        1752;Lago da Pedra - MA;2105708;813;Lago da Pedra;MA
        1753;Lago do Junco - MA;2105807;815;Lago do Junco;MA
        1754;Lago dos Rodrigues - MA;2105948;184;Lago dos Rodrigues;MA
        1755;Lago Verde - MA;2105906;817;Lago Verde;MA
        2293;Lagoa - PB;2508109;2063;Lagoa;PB
        1982;Lagoa Alegre - PI;2205557;999;Lagoa Alegre;PI
        4099;Lagoa Bonita do Sul - RS;4311239;1148;Lagoa Bonita do Sul;RS
        537;Lagoa da Canoa - AL;2704104;2781;Lagoa da Canoa;AL
        661;Lagoa da Confusão - TO;1711902;367;Lagoa da Confusão;TO
        5536;Lagoa da Prata - MG;3137205;4743;Lagoa da Prata;MG
        1185;Lagoa d'Anta - RN;2406205;1723;Lagoa d'Anta;RN
        2294;Lagoa de Dentro - PB;2508208;2065;Lagoa de Dentro;PB
        1566;Lagoa de Itaenga - PE;2608503;2469;Lagoa de Itaenga;PE
        1186;Lagoa de Pedras - RN;2406304;1725;Lagoa de Pedras;RN
        1983;Lagoa de São Francisco - PI;2205573;338;Lagoa de São Francisco;PI
        1187;Lagoa de Velhos - RN;2406403;1727;Lagoa de Velhos;RN
        1984;Lagoa do Barro do Piauí - PI;2205565;2259;Lagoa do Barro do Piauí;PI
        1565;Lagoa do Carro - PE;2608453;2289;Lagoa do Carro;PE
        1756;Lagoa do Mato - MA;2105922;182;Lagoa do Mato;MA
        1567;Lagoa do Ouro - PE;2608602;2471;Lagoa do Ouro;PE
        1985;Lagoa do Piauí - PI;2205581;340;Lagoa do Piauí;PI
        1986;Lagoa do Sítio - PI;2205599;342;Lagoa do Sítio;PI
        662;Lagoa do Tocantins - TO;1711951;353;Lagoa do Tocantins;TO
        1568;Lagoa dos Gatos - PE;2608701;2473;Lagoa dos Gatos;PE
        5537;Lagoa dos Patos - MG;3137304;4745;Lagoa dos Patos;MG
        4100;Lagoa dos Três Cantos - RS;4311270;5951;Lagoa dos Três Cantos;RS
        5538;Lagoa Dourada - MG;3137403;4747;Lagoa Dourada;MG
        5539;Lagoa Formosa - MG;3137502;4749;Lagoa Formosa;MG
        5540;Lagoa Grande - MG;3137536;2905;Lagoa Grande;MG
        1569;Lagoa Grande - PE;2608750;552;Lagoa Grande;PE
        1757;Lagoa Grande do Maranhão - MA;2105963;186;Lagoa Grande do Maranhão;MA
        1188;Lagoa Nova - RN;2406502;1729;Lagoa Nova;RN
        3678;Lagoa Real - BA;2918753;3289;Lagoa Real;BA
        1189;Lagoa Salgada - RN;2406601;1731;Lagoa Salgada;RN
        2548;Lagoa Santa - GO;5212253;1076;Lagoa Santa;GO
        5541;Lagoa Santa - MG;3137601;4751;Lagoa Santa;MG
        2295;Lagoa Seca - PB;2508307;2067;Lagoa Seca;PB
        4101;Lagoa Vermelha - RS;4311304;8727;Lagoa Vermelha;RS
        4102;Lagoão - RS;4311254;7389;Lagoão;RS
        4668;Lagoinha - SP;3526308;6627;Lagoinha;SP
        1987;Lagoinha do Piauí - PI;2205540;336;Lagoinha do Piauí;PI
        2804;Laguna - SC;4209409;8185;Laguna;SC
        370;Laguna Carapã - MS;5005251;163;Laguna Carapã;MS
        3679;Laje - BA;2918803;3677;Laje;BA
        436;Laje do Muriaé - RJ;3302304;5845;Laje do Muriaé;RJ
        4103;Lajeado - RS;4311403;8729;Lajeado;RS
        663;Lajeado - TO;1712009;351;Lajeado;TO
        4104;Lajeado do Bugre - RS;4311429;5983;Lajeado do Bugre;RS
        2805;Lajeado Grande - SC;4209458;5739;Lajeado Grande;SC
        1758;Lajeado Novo - MA;2105989;188;Lajeado Novo;MA
        3680;Lajedão - BA;2918902;3679;Lajedão;BA
        3681;Lajedinho - BA;2919009;3681;Lajedinho;BA
        1570;Lajedo - PE;2608800;2475;Lajedo;PE
        3682;Lajedo do Tabocal - BA;2919058;3291;Lajedo do Tabocal;BA
        1190;Lajes - RN;2406700;1733;Lajes;RN
        1191;Lajes Pintadas - RN;2406809;1735;Lajes Pintadas;RN
        5542;Lajinha - MG;3137700;4753;Lajinha;MG
        3683;Lamarão - BA;2919108;3683;Lamarão;BA
        5543;Lambari - MG;3137809;4755;Lambari;MG
        795;Lambari D'Oeste - MT;5105234;137;Lambari D'Oeste;MT
        5544;Lamim - MG;3137908;4757;Lamim;MG
        1988;Landri Sales - PI;2205607;1111;Landri Sales;PI
        3141;Lapa - PR;4113205;7657;Lapa;PR
        3684;Lapão - BA;2919157;3973;Lapão;BA
        285;Laranja da Terra - ES;3203163;5723;Laranja da Terra;ES
        5545;Laranjal - MG;3138005;4759;Laranjal;MG
        3142;Laranjal - PR;4113254;5501;Laranjal;PR
        22;Laranjal do Jari - AP;1600279;613;Laranjal do Jari;AP
        4669;Laranjal Paulista - SP;3526407;6629;Laranjal Paulista;SP
        202;Laranjeiras - SE;2803609;3171;Laranjeiras;SE
        3143;Laranjeiras do Sul - PR;4113304;7659;Laranjeiras do Sul;PR
        5546;Lassance - MG;3138104;4761;Lassance;MG
        2296;Lastro - PB;2508406;2069;Lastro;PB
        2806;Laurentino - SC;4209508;8187;Laurentino;SC
        3685;Lauro de Freitas - BA;2919207;3685;Lauro de Freitas;BA
        2807;Lauro Müller - SC;4209607;8189;Lauro Müller;SC
        664;Lavandeira - TO;1712157;86;Lavandeira;TO
        4670;Lavínia - SP;3526506;6631;Lavínia;SP
        5547;Lavras - MG;3138203;4763;Lavras;MG
        1385;Lavras da Mangabeira - CE;2307502;1451;Lavras da Mangabeira;CE
        4105;Lavras do Sul - RS;4311502;8731;Lavras do Sul;RS
        4671;Lavrinhas - SP;3526605;6633;Lavrinhas;SP
        5548;Leandro Ferreira - MG;3138302;4765;Leandro Ferreira;MG
        2808;Lebon Régis - SC;4209706;8191;Lebon Régis;SC
        4672;Leme - SP;3526704;6635;Leme;SP
        5549;Leme do Prado - MG;3138351;642;Leme do Prado;MG
        3686;Lençóis - BA;2919306;3687;Lençóis;BA
        4673;Lençóis Paulista - SP;3526803;6637;Lençóis Paulista;SP
        2809;Leoberto Leal - SC;4209805;8193;Leoberto Leal;SC
        5550;Leopoldina - MG;3138401;4767;Leopoldina;MG
        2549;Leopoldo de Bulhões - GO;5212303;9443;Leopoldo de Bulhões;GO
        3144;Leópolis - PR;4113403;7661;Leópolis;PR
        4106;Liberato Salzano - RS;4311601;8733;Liberato Salzano;RS
        5551;Liberdade - MG;3138500;4769;Liberdade;MG
        3687;Licínio de Almeida - BA;2919405;3689;Licínio de Almeida;BA
        3145;Lidianópolis - PR;4113429;5507;Lidianópolis;PR
        1759;Lima Campos - MA;2106003;819;Lima Campos;MA
        5552;Lima Duarte - MG;3138609;4771;Lima Duarte;MG
        4674;Limeira - SP;3526902;6639;Limeira;SP
        5553;Limeira do Oeste - MG;3138625;2687;Limeira do Oeste;MG
        1571;Limoeiro - PE;2608909;2477;Limoeiro;PE
        538;Limoeiro de Anadia - AL;2704203;2783;Limoeiro de Anadia;AL
        937;Limoeiro do Ajuru - PA;1504000;479;Limoeiro do Ajuru;PA
        1386;Limoeiro do Norte - CE;2307601;1453;Limoeiro do Norte;CE
        3146;Lindoeste - PR;4113452;9959;Lindoeste;PR
        4675;Lindóia - SP;3527009;6641;Lindóia;SP
        2810;Lindóia do Sul - SC;4209854;9961;Lindóia do Sul;SC
        4107;Lindolfo Collor - RS;4311627;6017;Lindolfo Collor;RS
        4108;Linha Nova - RS;4311643;6047;Linha Nova;RS
        286;Linhares - ES;3203205;5663;Linhares;ES
        4676;Lins - SP;3527108;6643;Lins;SP
        2297;Livramento - PB;2508505;2071;Livramento;PB
        3688;Livramento de Nossa Senhora - BA;2919504;3691;Livramento de Nossa Senhora;BA
        665;Lizarda - TO;1712405;9569;Lizarda;TO
        3147;Loanda - PR;4113502;7663;Loanda;PR
        3148;Lobato - PR;4113601;7665;Lobato;PR
        2298;Logradouro - PB;2508554;482;Logradouro;PB
        3149;Londrina - PR;4113700;7667;Londrina;PR
        5554;Lontra - MG;3138658;2695;Lontra;MG
        2811;Lontras - SC;4209904;8195;Lontras;SC
        4677;Lorena - SP;3527207;6645;Lorena;SP
        1760;Loreto - MA;2106102;821;Loreto;MA
        4678;Lourdes - SP;3527256;2937;Lourdes;SP
        4679;Louveira - SP;3527306;6647;Louveira;SP
        796;Lucas do Rio Verde - MT;5105259;9925;Lucas do Rio Verde;MT
        4680;Lucélia - SP;3527405;6649;Lucélia;SP
        2299;Lucena - PB;2508604;2073;Lucena;PB
        4681;Lucianópolis - SP;3527504;6651;Lucianópolis;SP
        797;Luciara - MT;5105309;9105;Luciara;MT
        1192;Lucrécia - RN;2406908;1737;Lucrécia;RN
        4682;Luís Antônio - SP;3527603;6653;Luís Antônio;SP
        1989;Luís Correia - PI;2205706;1113;Luís Correia;PI
        1761;Luís Domingues - MA;2106201;823;Luís Domingues;MA
        3689;Luís Eduardo Magalhães - BA;2919553;1112;Luís Eduardo Magalhães;BA
        1193;Luís Gomes - RN;2407005;1739;Luís Gomes;RN
        5555;Luisburgo - MG;3138674;644;Luisburgo;MG
        5556;Luislândia - MG;3138682;646;Luislândia;MG
        2812;Luiz Alves - SC;4210001;8197;Luiz Alves;SC
        3150;Luiziana - PR;4113734;8481;Luiziana;PR
        4683;Luiziânia - SP;3527702;6655;Luiziânia;SP
        5557;Luminárias - MG;3138708;4773;Luminárias;MG
        3151;Lunardelli - PR;4113759;8459;Lunardelli;PR
        4684;Lupércio - SP;3527801;6657;Lupércio;SP
        3152;Lupionópolis - PR;4113809;7669;Lupionópolis;PR
        4685;Lutécia - SP;3527900;6659;Lutécia;SP
        5558;Luz - MG;3138807;4775;Luz;MG
        2813;Luzerna - SC;4210035;926;Luzerna;SC
        2550;Luziânia - GO;5212501;9445;Luziânia;GO
        1990;Luzilândia - PI;2205805;1115;Luzilândia;PI
        666;Luzinópolis - TO;1712454;88;Luzinópolis;TO
        437;Macaé - RJ;3302403;5847;Macaé;RJ
        1194;Macaíba - RN;2407104;1741;Macaíba;RN
        3690;Macajuba - BA;2919603;3693;Macajuba;BA
        4109;Maçambará - RS;4311718;994;Maçambará;RS
        203;Macambira - SE;2803708;3173;Macambira;SE
        23;Macapá - AP;1600303;605;Macapá;AP
        1572;Macaparana - PE;2609006;2479;Macaparana;PE
        3691;Macarani - BA;2919702;3695;Macarani;BA
        4686;Macatuba - SP;3528007;6661;Macatuba;SP
        1195;Macau - RN;2407203;1743;Macau;RN
        4687;Macaubal - SP;3528106;6663;Macaubal;SP
        3692;Macaúbas - BA;2919801;3697;Macaúbas;BA
        4688;Macedônia - SP;3528205;6665;Macedônia;SP
        539;Maceió - AL;2704302;2785;Maceió;AL
        5559;Machacalis - MG;3138906;4777;Machacalis;MG
        4110;Machadinho - RS;4311700;8735;Machadinho;RS
        79;Machadinho D'Oeste - RO;1100130;39;Machadinho D'Oeste;RO
        5560;Machado - MG;3139003;4779;Machado;MG
        1573;Machados - PE;2609105;2481;Machados;PE
        2814;Macieira - SC;4210050;5575;Macieira;SC
        438;Macuco - RJ;3302452;776;Macuco;RJ
        3693;Macururé - BA;2919900;3699;Macururé;BA
        1387;Madalena - CE;2307635;1261;Madalena;CE
        1991;Madeiro - PI;2205854;344;Madeiro;PI
        3694;Madre de Deus - BA;2919926;3293;Madre de Deus;BA
        5561;Madre de Deus de Minas - MG;3139102;4781;Madre de Deus de Minas;MG
        2300;Mãe d'Água - PB;2508703;2075;Mãe d'Água;PB
        938;Mãe do Rio - PA;1504059;587;Mãe do Rio;PA
        3695;Maetinga - BA;2919959;3975;Maetinga;BA
        2815;Mafra - SC;4210100;8199;Mafra;SC
        939;Magalhães Barata - PA;1504109;481;Magalhães Barata;PA
        1762;Magalhães de Almeida - MA;2106300;825;Magalhães de Almeida;MA
        4689;Magda - SP;3528304;6667;Magda;SP
        439;Magé - RJ;3302502;5849;Magé;RJ
        3696;Maiquinique - BA;2920007;3701;Maiquinique;BA
        3697;Mairi - BA;2920106;3703;Mairi;BA
        4690;Mairinque - SP;3528403;6669;Mairinque;SP
        4691;Mairiporã - SP;3528502;6671;Mairiporã;SP
        2551;Mairipotaba - GO;5212600;9447;Mairipotaba;GO
        2816;Major Gercino - SC;4210209;8201;Major Gercino;SC
        540;Major Isidoro - AL;2704401;2787;Major Isidoro;AL
        1196;Major Sales - RN;2407252;420;Major Sales;RN
        2817;Major Vieira - SC;4210308;8203;Major Vieira;SC
        5562;Malacacheta - MG;3139201;4783;Malacacheta;MG
        3698;Malhada - BA;2920205;3705;Malhada;BA
        3699;Malhada de Pedras - BA;2920304;3707;Malhada de Pedras;BA
        204;Malhada dos Bois - SE;2803807;3175;Malhada dos Bois;SE
        205;Malhador - SE;2803906;3177;Malhador;SE
        3153;Mallet - PR;4113908;7671;Mallet;PR
        2301;Malta - PB;2508802;2077;Malta;PB
        2302;Mamanguape - PB;2508901;2079;Mamanguape;PB
        2552;Mambaí - GO;5212709;9449;Mambaí;GO
        3154;Mamborê - PR;4114005;7673;Mamborê;PR
        5563;Mamonas - MG;3139250;2895;Mamonas;MG
        4111;Mampituba - RS;4311734;996;Mampituba;RS
        142;Manacapuru - AM;1302504;253;Manacapuru;AM
        2303;Manaíra - PB;2509008;2081;Manaíra;PB
        143;Manaquiri - AM;1302553;9839;Manaquiri;AM
        1574;Manari - PE;2609154;554;Manari;PE
        144;Manaus - AM;1302603;255;Manaus;AM
        42;Mâncio Lima - AC;1200336;109;Mâncio Lima;AC
        3155;Mandaguaçu - PR;4114104;7675;Mandaguaçu;PR
        3156;Mandaguari - PR;4114203;7677;Mandaguari;PR
        3157;Mandirituba - PR;4114302;7679;Mandirituba;PR
        4692;Manduri - SP;3528601;6673;Manduri;SP
        3158;Manfrinópolis - PR;4114351;864;Manfrinópolis;PR
        5564;Manga - MG;3139300;4785;Manga;MG
        440;Mangaratiba - RJ;3302601;5851;Mangaratiba;RJ
        3159;Mangueirinha - PR;4114401;7511;Mangueirinha;PR
        5565;Manhuaçu - MG;3139409;4787;Manhuaçu;MG
        5566;Manhumirim - MG;3139508;4789;Manhumirim;MG
        145;Manicoré - AM;1302702;257;Manicoré;AM
        1992;Manoel Emídio - PI;2205904;1117;Manoel Emídio;PI
        3160;Manoel Ribas - PR;4114500;7681;Manoel Ribas;PR
        43;Manoel Urbano - AC;1200344;155;Manoel Urbano;AC
        4112;Manoel Viana - RS;4311759;6079;Manoel Viana;RS
        3700;Manoel Vitorino - BA;2920403;3709;Manoel Vitorino;BA
        3701;Mansidão - BA;2920452;3977;Mansidão;BA
        5567;Mantena - MG;3139607;4791;Mantena;MG
        287;Mantenópolis - ES;3203304;5665;Mantenópolis;ES
        4113;Maquiné - RS;4311775;5783;Maquiné;RS
        5568;Mar de Espanha - MG;3139805;4795;Mar de Espanha;MG
        541;Mar Vermelho - AL;2704906;2797;Mar Vermelho;AL
        2553;Mara Rosa - GO;5212808;9451;Mara Rosa;GO
        146;Maraã - AM;1302801;259;Maraã;AM
        940;Marabá - PA;1504208;483;Marabá;PA
        4693;Marabá Paulista - SP;3528700;6675;Marabá Paulista;SP
        1763;Maracaçumé - MA;2106326;190;Maracaçumé;MA
        4694;Maracaí - SP;3528809;6677;Maracaí;SP
        2818;Maracajá - SC;4210407;8391;Maracajá;SC
        371;Maracaju - MS;5005400;9107;Maracaju;MS
        941;Maracanã - PA;1504307;485;Maracanã;PA
        1388;Maracanaú - CE;2307650;1585;Maracanaú;CE
        3702;Maracás - BA;2920502;3711;Maracás;BA
        542;Maragogi - AL;2704500;2789;Maragogi;AL
        3703;Maragogipe - BA;2920601;3713;Maragogipe;BA
        1575;Maraial - PE;2609204;2483;Maraial;PE
        1764;Marajá do Sena - MA;2106359;192;Marajá do Sena;MA
        1389;Maranguape - CE;2307700;1455;Maranguape;CE
        1765;Maranhãozinho - MA;2106375;194;Maranhãozinho;MA
        942;Marapanim - PA;1504406;487;Marapanim;PA
        4695;Marapoama - SP;3528858;2977;Marapoama;SP
        4114;Maratá - RS;4311791;6039;Maratá;RS
        288;Marataízes - ES;3203320;760;Marataízes;ES
        3704;Maraú - BA;2920700;3715;Maraú;BA
        4115;Marau - RS;4311809;8737;Marau;RS
        543;Maravilha - AL;2704609;2791;Maravilha;AL
        2819;Maravilha - SC;4210506;8205;Maravilha;SC
        5569;Maravilhas - MG;3139706;4793;Maravilhas;MG
        2304;Marcação - PB;2509057;484;Marcação;PB
        798;Marcelândia - MT;5105580;9899;Marcelândia;MT
        4116;Marcelino Ramos - RS;4311908;8739;Marcelino Ramos;RS
        1197;Marcelino Vieira - RN;2407302;1745;Marcelino Vieira;RN
        3705;Marcionílio Souza - BA;2920809;3717;Marcionílio Souza;BA
        1390;Marco - CE;2307809;1457;Marco;CE
        1993;Marcolândia - PI;2205953;2275;Marcolândia;PI
        1994;Marcos Parente - PI;2206001;1119;Marcos Parente;PI
        3161;Marechal Cândido Rondon - PR;4114609;7683;Marechal Cândido Rondon;PR
        544;Marechal Deodoro - AL;2704708;2793;Marechal Deodoro;AL
        289;Marechal Floriano - ES;3203346;2929;Marechal Floriano;ES
        44;Marechal Thaumaturgo - AC;1200351;655;Marechal Thaumaturgo;AC
        2820;Marema - SC;4210555;9963;Marema;SC
        2305;Mari - PB;2509107;2083;Mari;PB
        5570;Maria da Fé - MG;3139904;4797;Maria da Fé;MG
        3162;Maria Helena - PR;4114708;7685;Maria Helena;PR
        3163;Marialva - PR;4114807;7687;Marialva;PR
        5571;Mariana - MG;3140001;4799;Mariana;MG
        4117;Mariana Pimentel - RS;4311981;5759;Mariana Pimentel;RS
        4118;Mariano Moro - RS;4312005;8741;Mariano Moro;RS
        667;Marianópolis do Tocantins - TO;1712504;9711;Marianópolis do Tocantins;TO
        4696;Mariápolis - SP;3528908;6679;Mariápolis;SP
        545;Maribondo - AL;2704807;2795;Maribondo;AL
        441;Maricá - RJ;3302700;5853;Maricá;RJ
        5572;Marilac - MG;3140100;4801;Marilac;MG
        290;Marilândia - ES;3203353;5707;Marilândia;ES
        3164;Marilândia do Sul - PR;4114906;7433;Marilândia do Sul;PR
        3165;Marilena - PR;4115002;7975;Marilena;PR
        4697;Marília - SP;3529005;6681;Marília;SP
        3166;Mariluz - PR;4115101;7689;Mariluz;PR
        3167;Maringá - PR;4115200;7691;Maringá;PR
        4698;Marinópolis - SP;3529104;6683;Marinópolis;SP
        5573;Mário Campos - MG;3140159;648;Mário Campos;MG
        3168;Mariópolis - PR;4115309;7693;Mariópolis;PR
        3169;Maripá - PR;4115358;5487;Maripá;PR
        5574;Maripá de Minas - MG;3140209;4803;Maripá de Minas;MG
        943;Marituba - PA;1504422;54;Marituba;PA
        2306;Marizópolis - PB;2509156;486;Marizópolis;PB
        5575;Marliéria - MG;3140308;4805;Marliéria;MG
        3170;Marmeleiro - PR;4115408;7695;Marmeleiro;PR
        5576;Marmelópolis - MG;3140407;4807;Marmelópolis;MG
        4119;Marques de Souza - RS;4312054;998;Marques de Souza;RS
        3171;Marquinho - PR;4115457;866;Marquinho;PR
        5577;Martinho Campos - MG;3140506;4809;Martinho Campos;MG
        1391;Martinópole - CE;2307908;1459;Martinópole;CE
        4699;Martinópolis - SP;3529203;6685;Martinópolis;SP
        1198;Martins - RN;2407401;1747;Martins;RN
        5578;Martins Soares - MG;3140530;650;Martins Soares;MG
        206;Maruim - SE;2804003;3179;Maruim;SE
        3172;Marumbi - PR;4115507;7697;Marumbi;PR
        2554;Marzagão - GO;5212907;9453;Marzagão;GO
        3706;Mascote - BA;2920908;3719;Mascote;BA
        1392;Massapê - CE;2308005;1461;Massapê;CE
        1995;Massapê do Piauí - PI;2206050;346;Massapê do Piauí;PI
        2307;Massaranduba - PB;2509206;2085;Massaranduba;PB
        2821;Massaranduba - SC;4210605;8207;Massaranduba;SC
        4120;Mata - RS;4312104;8743;Mata;RS
        3707;Mata de São João - BA;2921005;3721;Mata de São João;BA
        546;Mata Grande - AL;2705002;2799;Mata Grande;AL
        1766;Mata Roma - MA;2106409;827;Mata Roma;MA
        5579;Mata Verde - MG;3140555;2659;Mata Verde;MG
        4700;Matão - SP;3529302;6687;Matão;SP
        2308;Mataraca - PB;2509305;2087;Mataraca;PB
        668;Mateiros - TO;1712702;317;Mateiros;TO
        3173;Matelândia - PR;4115606;7699;Matelândia;PR
        5580;Materlândia - MG;3140605;4811;Materlândia;MG
        5581;Mateus Leme - MG;3140704;4813;Mateus Leme;MG
        5582;Mathias Lobato - MG;3171501;5431;Mathias Lobato;MG
        5583;Matias Barbosa - MG;3140803;4815;Matias Barbosa;MG
        5584;Matias Cardoso - MG;3140852;2897;Matias Cardoso;MG
        1996;Matias Olímpio - PI;2206100;1121;Matias Olímpio;PI
        3708;Matina - BA;2921054;3295;Matina;BA
        1767;Matinha - MA;2106508;829;Matinha;MA
        2309;Matinhas - PB;2509339;488;Matinhas;PB
        3174;Matinhos - PR;4115705;7963;Matinhos;PR
        5585;Matipó - MG;3140902;4817;Matipó;MG
        4121;Mato Castelhano - RS;4312138;5931;Mato Castelhano;RS
        2310;Mato Grosso - PB;2509370;490;Mato Grosso;PB
        4122;Mato Leitão - RS;4312153;6031;Mato Leitão;RS
        4123;Mato Queimado - RS;4312179;1150;Mato Queimado;RS
        3175;Mato Rico - PR;4115739;5503;Mato Rico;PR
        5586;Mato Verde - MG;3141009;4819;Mato Verde;MG
        1768;Matões - MA;2106607;831;Matões;MA
        1769;Matões do Norte - MA;2106631;196;Matões do Norte;MA
        2822;Matos Costa - SC;4210704;8209;Matos Costa;SC
        5587;Matozinhos - MG;3141108;4821;Matozinhos;MG
        2555;Matrinchã - GO;5212956;9927;Matrinchã;GO
        547;Matriz de Camaragibe - AL;2705101;2801;Matriz de Camaragibe;AL
        799;Matupá - MT;5105606;9929;Matupá;MT
        2311;Maturéia - PB;2509396;492;Maturéia;PB
        5588;Matutina - MG;3141207;4823;Matutina;MG
        4701;Mauá - SP;3529401;6689;Mauá;SP
        3176;Mauá da Serra - PR;4115754;5459;Mauá da Serra;PR
        147;Maués - AM;1302900;261;Maués;AM
        2556;Maurilândia - GO;5213004;9457;Maurilândia;GO
        669;Maurilândia do Tocantins - TO;1712801;183;Maurilândia do Tocantins;TO
        1393;Mauriti - CE;2308104;1463;Mauriti;CE
        1199;Maxaranguape - RN;2407500;1749;Maxaranguape;RN
        4124;Maximiliano de Almeida - RS;4312203;8745;Maximiliano de Almeida;RS
        24;Mazagão - AP;1600402;607;Mazagão;AP
        5589;Medeiros - MG;3141306;4825;Medeiros;MG
        3709;Medeiros Neto - BA;2921104;3723;Medeiros Neto;BA
        3177;Medianeira - PR;4115804;7701;Medianeira;PR
        944;Medicilândia - PA;1504455;589;Medicilândia;PA
        5590;Medina - MG;3141405;4827;Medina;MG
        2823;Meleiro - SC;4210803;8211;Meleiro;SC
        945;Melgaço - PA;1504505;489;Melgaço;PA
        442;Mendes - RJ;3302809;5855;Mendes;RJ
        5591;Mendes Pimentel - MG;3141504;4829;Mendes Pimentel;MG
        4702;Mendonça - SP;3529500;6691;Mendonça;SP
        3178;Mercedes - PR;4115853;5531;Mercedes;PR
        5592;Mercês - MG;3141603;4831;Mercês;MG
        4703;Meridiano - SP;3529609;6693;Meridiano;SP
        1394;Meruoca - CE;2308203;1465;Meruoca;CE
        4704;Mesópolis - SP;3529658;2983;Mesópolis;SP
        5593;Mesquita - MG;3141702;4833;Mesquita;MG
        443;Mesquita - RJ;3302858;1116;Mesquita;RJ
        548;Messias - AL;2705200;2803;Messias;AL
        1200;Messias Targino - RN;2407609;1721;Messias Targino;RN
        1997;Miguel Alves - PI;2206209;1123;Miguel Alves;PI
        3710;Miguel Calmon - BA;2921203;3725;Miguel Calmon;BA
        1998;Miguel Leão - PI;2206308;1125;Miguel Leão;PI
        444;Miguel Pereira - RJ;3302908;5857;Miguel Pereira;RJ
        4705;Miguelópolis - SP;3529708;6695;Miguelópolis;SP
        3711;Milagres - BA;2921302;3727;Milagres;BA
        1395;Milagres - CE;2308302;1467;Milagres;CE
        1770;Milagres do Maranhão - MA;2106672;198;Milagres do Maranhão;MA
        1396;Milhã - CE;2308351;1597;Milhã;CE
        1999;Milton Brandão - PI;2206357;348;Milton Brandão;PI
        2557;Mimoso de Goiás - GO;5213053;9931;Mimoso de Goiás;GO
        291;Mimoso do Sul - ES;3203403;5667;Mimoso do Sul;ES
        2558;Minaçu - GO;5213087;9647;Minaçu;GO
        549;Minador do Negrão - AL;2705309;2805;Minador do Negrão;AL
        4125;Minas do Leão - RS;4312252;5773;Minas do Leão;RS
        5594;Minas Novas - MG;3141801;4835;Minas Novas;MG
        5595;Minduri - MG;3141900;4837;Minduri;MG
        2559;Mineiros - GO;5213103;9459;Mineiros;GO
        4706;Mineiros do Tietê - SP;3529807;6697;Mineiros do Tietê;SP
        80;Ministro Andreazza - RO;1101203;695;Ministro Andreazza;RO
        4707;Mira Estrela - SP;3530003;6701;Mira Estrela;SP
        5596;Mirabela - MG;3142007;4839;Mirabela;MG
        4708;Miracatu - SP;3529906;6699;Miracatu;SP
        445;Miracema - RJ;3303005;5859;Miracema;RJ
        670;Miracema do Tocantins - TO;1713205;9461;Miracema do Tocantins;TO
        1771;Mirador - MA;2106706;833;Mirador;MA
        3179;Mirador - PR;4115903;7703;Mirador;PR
        5597;Miradouro - MG;3142106;4841;Miradouro;MG
        4126;Miraguaí - RS;4312302;8747;Miraguaí;RS
        5598;Miraí - MG;3142205;4843;Miraí;MG
        1397;Miraíma - CE;2308377;1263;Miraíma;CE
        372;Miranda - MS;5005608;9111;Miranda;MS
        1772;Miranda do Norte - MA;2106755;1283;Miranda do Norte;MA
        1576;Mirandiba - PE;2609303;2485;Mirandiba;PE
        4709;Mirandópolis - SP;3530102;6703;Mirandópolis;SP
        3712;Mirangaba - BA;2921401;3729;Mirangaba;BA
        671;Miranorte - TO;1713304;9463;Miranorte;TO
        3713;Mirante - BA;2921450;3297;Mirante;BA
        81;Mirante da Serra - RO;1101302;697;Mirante da Serra;RO
        4710;Mirante do Paranapanema - SP;3530201;6705;Mirante do Paranapanema;SP
        3180;Miraselva - PR;4116000;7705;Miraselva;PR
        4711;Mirassol - SP;3530300;6707;Mirassol;SP
        800;Mirassol d'Oeste - MT;5105622;9177;Mirassol d'Oeste;MT
        4712;Mirassolândia - SP;3530409;6709;Mirassolândia;SP
        5599;Miravânia - MG;3142254;652;Miravânia;MG
        2824;Mirim Doce - SC;4210852;5559;Mirim Doce;SC
        1773;Mirinzal - MA;2106805;835;Mirinzal;MA
        3181;Missal - PR;4116059;8469;Missal;PR
        1398;Missão Velha - CE;2308401;1469;Missão Velha;CE
        946;Mocajuba - PA;1504604;491;Mocajuba;PA
        4713;Mococa - SP;3530508;6711;Mococa;SP
        2825;Modelo - SC;4210902;8213;Modelo;SC
        5600;Moeda - MG;3142304;4845;Moeda;MG
        5601;Moema - MG;3142403;4847;Moema;MG
        2312;Mogeiro - PB;2509404;2089;Mogeiro;PB
        4714;Mogi das Cruzes - SP;3530607;6713;Mogi das Cruzes;SP
        4715;Mogi Guaçu - SP;3530706;6715;Mogi Guaçu;SP
        4716;Mogi Mirim - SP;3530805;6717;Mogi Mirim;SP
        2560;Moiporá - GO;5213400;9465;Moiporá;GO
        207;Moita Bonita - SE;2804102;3181;Moita Bonita;SE
        947;Moju - PA;1504703;493;Moju;PA
        948;Mojuí dos Campos - PA;1504752;1190;Mojuí dos Campos;PA
        1399;Mombaça - CE;2308500;1471;Mombaça;CE
        4717;Mombuca - SP;3530904;6719;Mombuca;SP
        1774;Monção - MA;2106904;837;Monção;MA
        4718;Monções - SP;3531001;6721;Monções;SP
        2826;Mondaí - SC;4211009;8215;Mondaí;SC
        4719;Mongaguá - SP;3531100;6723;Mongaguá;SP
        5602;Monjolos - MG;3142502;4849;Monjolos;MG
        2000;Monsenhor Gil - PI;2206407;1127;Monsenhor Gil;PI
        2001;Monsenhor Hipólito - PI;2206506;1129;Monsenhor Hipólito;PI
        5603;Monsenhor Paulo - MG;3142601;4851;Monsenhor Paulo;MG
        1400;Monsenhor Tabosa - CE;2308609;1473;Monsenhor Tabosa;CE
        2313;Montadas - PB;2509503;2091;Montadas;PB
        5604;Montalvânia - MG;3142700;4853;Montalvânia;MG
        292;Montanha - ES;3203502;5669;Montanha;ES
        1201;Montanhas - RN;2407708;1753;Montanhas;RN
        4127;Montauri - RS;4312351;7387;Montauri;RS
        949;Monte Alegre - PA;1504802;495;Monte Alegre;PA
        1202;Monte Alegre - RN;2407807;1755;Monte Alegre;RN
        2561;Monte Alegre de Goiás - GO;5213509;9467;Monte Alegre de Goiás;GO
        5605;Monte Alegre de Minas - MG;3142809;4855;Monte Alegre de Minas;MG
        208;Monte Alegre de Sergipe - SE;2804201;3183;Monte Alegre de Sergipe;SE
        2002;Monte Alegre do Piauí - PI;2206605;1131;Monte Alegre do Piauí;PI
        4720;Monte Alegre do Sul - SP;3531209;6725;Monte Alegre do Sul;SP
        4128;Monte Alegre dos Campos - RS;4312377;1000;Monte Alegre dos Campos;RS
        4721;Monte Alto - SP;3531308;6727;Monte Alto;SP
        4722;Monte Aprazível - SP;3531407;6729;Monte Aprazível;SP
        5606;Monte Azul - MG;3142908;4857;Monte Azul;MG
        4723;Monte Azul Paulista - SP;3531506;6731;Monte Azul Paulista;SP
        5607;Monte Belo - MG;3143005;4859;Monte Belo;MG
        4129;Monte Belo do Sul - RS;4312385;5993;Monte Belo do Sul;RS
        2827;Monte Carlo - SC;4211058;5561;Monte Carlo;SC
        5608;Monte Carmelo - MG;3143104;4861;Monte Carmelo;MG
        2828;Monte Castelo - SC;4211108;8217;Monte Castelo;SC
        4724;Monte Castelo - SP;3531605;6733;Monte Castelo;SP
        1203;Monte das Gameleiras - RN;2407906;1757;Monte das Gameleiras;RN
        672;Monte do Carmo - TO;1713601;9469;Monte do Carmo;TO
        5609;Monte Formoso - MG;3143153;654;Monte Formoso;MG
        2314;Monte Horebe - PB;2509602;2093;Monte Horebe;PB
        4725;Monte Mor - SP;3531803;6737;Monte Mor;SP
        82;Monte Negro - RO;1101401;685;Monte Negro;RO
        3714;Monte Santo - BA;2921500;3731;Monte Santo;BA
        5610;Monte Santo de Minas - MG;3143203;4863;Monte Santo de Minas;MG
        673;Monte Santo do Tocantins - TO;1713700;90;Monte Santo do Tocantins;TO
        5611;Monte Sião - MG;3143401;4867;Monte Sião;MG
        2315;Monteiro - PB;2509701;2095;Monteiro;PB
        4726;Monteiro Lobato - SP;3531704;6735;Monteiro Lobato;SP
        550;Monteirópolis - AL;2705408;2807;Monteirópolis;AL
        4130;Montenegro - RS;4312401;8749;Montenegro;RS
        1775;Montes Altos - MA;2107001;839;Montes Altos;MA
        5612;Montes Claros - MG;3143302;4865;Montes Claros;MG
        2562;Montes Claros de Goiás - GO;5213707;9471;Montes Claros de Goiás;GO
        5613;Montezuma - MG;3143450;2697;Montezuma;MG
        2563;Montividiu - GO;5213756;9933;Montividiu;GO
        2564;Montividiu do Norte - GO;5213772;79;Montividiu do Norte;GO
        1401;Morada Nova - CE;2308708;1475;Morada Nova;CE
        5614;Morada Nova de Minas - MG;3143500;4869;Morada Nova de Minas;MG
        1402;Moraújo - CE;2308807;1477;Moraújo;CE
        1577;Moreilândia - PE;2614303;2585;Moreilândia;PE
        3182;Moreira Sales - PR;4116109;7707;Moreira Sales;PR
        1578;Moreno - PE;2609402;2487;Moreno;PE
        4131;Mormaço - RS;4312427;5933;Mormaço;RS
        3715;Morpará - BA;2921609;3733;Morpará;BA
        3183;Morretes - PR;4116208;7709;Morretes;PR
        1403;Morrinhos - CE;2308906;1479;Morrinhos;CE
        2565;Morrinhos - GO;5213806;9473;Morrinhos;GO
        4132;Morrinhos do Sul - RS;4312443;5775;Morrinhos do Sul;RS
        4727;Morro Agudo - SP;3531902;6739;Morro Agudo;SP
        2566;Morro Agudo de Goiás - GO;5213855;9935;Morro Agudo de Goiás;GO
        2003;Morro Cabeça no Tempo - PI;2206654;350;Morro Cabeça no Tempo;PI
        2829;Morro da Fumaça - SC;4211207;8219;Morro da Fumaça;SC
        5615;Morro da Garça - MG;3143609;4871;Morro da Garça;MG
        3716;Morro do Chapéu - BA;2921708;3735;Morro do Chapéu;BA
        2004;Morro do Chapéu do Piauí - PI;2206670;352;Morro do Chapéu do Piauí;PI
        5616;Morro do Pilar - MG;3143708;4873;Morro do Pilar;MG
        2830;Morro Grande - SC;4211256;5539;Morro Grande;SC
        4133;Morro Redondo - RS;4312450;7385;Morro Redondo;RS
        4134;Morro Reuter - RS;4312476;6019;Morro Reuter;RS
        1776;Morros - MA;2107100;841;Morros;MA
        3717;Mortugaba - BA;2921807;3737;Mortugaba;BA
        4728;Morungaba - SP;3532009;6741;Morungaba;SP
        2567;Mossâmedes - GO;5213905;9475;Mossâmedes;GO
        1204;Mossoró - RN;2408003;1759;Mossoró;RN
        4135;Mostardas - RS;4312500;8751;Mostardas;RS
        4729;Motuca - SP;3532058;7263;Motuca;SP
        2568;Mozarlândia - GO;5214002;9477;Mozarlândia;GO
        950;Muaná - PA;1504901;497;Muaná;PA
        10;Mucajaí - RR;1400308;309;Mucajaí;RR
        1404;Mucambo - CE;2309003;1481;Mucambo;CE
        3718;Mucugê - BA;2921906;3739;Mucugê;BA
        4136;Muçum - RS;4312609;8753;Muçum;RS
        3719;Mucuri - BA;2922003;3741;Mucuri;BA
        293;Mucurici - ES;3203601;5671;Mucurici;ES
        4137;Muitos Capões - RS;4312617;1002;Muitos Capões;RS
        4138;Muliterno - RS;4312625;5935;Muliterno;RS
        1405;Mulungu - CE;2309102;1483;Mulungu;CE
        2316;Mulungu - PB;2509800;2097;Mulungu;PB
        3720;Mulungu do Morro - BA;2922052;3299;Mulungu do Morro;BA
        3721;Mundo Novo - BA;2922102;3743;Mundo Novo;BA
        2569;Mundo Novo - GO;5214051;9651;Mundo Novo;GO
        373;Mundo Novo - MS;5005681;9179;Mundo Novo;MS
        5617;Munhoz - MG;3143807;4875;Munhoz;MG
        3184;Munhoz de Melo - PR;4116307;7711;Munhoz de Melo;PR
        3722;Muniz Ferreira - BA;2922201;3745;Muniz Ferreira;BA
        294;Muniz Freire - ES;3203700;5673;Muniz Freire;ES
        3723;Muquém do São Francisco - BA;2922250;3005;Muquém do São Francisco;BA
        295;Muqui - ES;3203809;5675;Muqui;ES
        5618;Muriaé - MG;3143906;4877;Muriaé;MG
        209;Muribeca - SE;2804300;3185;Muribeca;SE
        551;Murici - AL;2705507;2809;Murici;AL
        2005;Murici dos Portelas - PI;2206696;354;Murici dos Portelas;PI
        674;Muricilândia - TO;1713957;187;Muricilândia;TO
        3724;Muritiba - BA;2922300;3747;Muritiba;BA
        4730;Murutinga do Sul - SP;3532108;6743;Murutinga do Sul;SP
        3725;Mutuípe - BA;2922409;3749;Mutuípe;BA
        5619;Mutum - MG;3144003;4879;Mutum;MG
        2570;Mutunópolis - GO;5214101;9479;Mutunópolis;GO
        5620;Muzambinho - MG;3144102;4881;Muzambinho;MG
        5621;Nacip Raydan - MG;3144201;4883;Nacip Raydan;MG
        4731;Nantes - SP;3532157;804;Nantes;SP
        5622;Nanuque - MG;3144300;4885;Nanuque;MG
        4139;Não-Me-Toque - RS;4312658;8755;Não-Me-Toque;RS
        5623;Naque - MG;3144359;656;Naque;MG
        4732;Narandiba - SP;3532207;6745;Narandiba;SP
        1205;Natal - RN;2408102;1761;Natal;RN
        5624;Natalândia - MG;3144375;658;Natalândia;MG
        5625;Natércia - MG;3144409;4887;Natércia;MG
        446;Natividade - RJ;3303104;5861;Natividade;RJ
        675;Natividade - TO;1714203;9481;Natividade;TO
        4733;Natividade da Serra - SP;3532306;6747;Natividade da Serra;SP
        2317;Natuba - PB;2509909;2099;Natuba;PB
        2831;Navegantes - SC;4211306;8221;Navegantes;SC
        374;Naviraí - MS;5005707;9113;Naviraí;MS
        3726;Nazaré - BA;2922508;3751;Nazaré;BA
        676;Nazaré - TO;1714302;9483;Nazaré;TO
        1579;Nazaré da Mata - PE;2609501;2489;Nazaré da Mata;PE
        2006;Nazaré do Piauí - PI;2206704;1133;Nazaré do Piauí;PI
        4734;Nazaré Paulista - SP;3532405;6749;Nazaré Paulista;SP
        5626;Nazareno - MG;3144508;4889;Nazareno;MG
        2318;Nazarezinho - PB;2510006;2101;Nazarezinho;PB
        5965;Nazária - PI;2206720;1180;Nazária;PI
        2571;Nazário - GO;5214408;9485;Nazário;GO
        210;Neópolis - SE;2804409;3187;Neópolis;SE
        5627;Nepomuceno - MG;3144607;4891;Nepomuceno;MG
        2572;Nerópolis - GO;5214507;9487;Nerópolis;GO
        4735;Neves Paulista - SP;3532504;6751;Neves Paulista;SP
        148;Nhamundá - AM;1303007;263;Nhamundá;AM
        4736;Nhandeara - SP;3532603;6753;Nhandeara;SP
        4140;Nicolau Vergueiro - RS;4312674;5937;Nicolau Vergueiro;RS
        3727;Nilo Peçanha - BA;2922607;3753;Nilo Peçanha;BA
        447;Nilópolis - RJ;3303203;5863;Nilópolis;RJ
        1777;Nina Rodrigues - MA;2107209;843;Nina Rodrigues;MA
        5628;Ninheira - MG;3144656;660;Ninheira;MG
        375;Nioaque - MS;5005806;9115;Nioaque;MS
        4737;Nipoã - SP;3532702;6755;Nipoã;SP
        2573;Niquelândia - GO;5214606;9489;Niquelândia;GO
        1206;Nísia Floresta - RN;2408201;1763;Nísia Floresta;RN
        448;Niterói - RJ;3303302;5865;Niterói;RJ
        801;Nobres - MT;5105903;9117;Nobres;MT
        4141;Nonoai - RS;4312708;8757;Nonoai;RS
        3728;Nordestina - BA;2922656;3979;Nordestina;BA
        11;Normandia - RR;1400407;311;Normandia;RR
        802;Nortelândia - MT;5106000;9119;Nortelândia;MT
        211;Nossa Senhora Aparecida - SE;2804458;3135;Nossa Senhora Aparecida;SE
        212;Nossa Senhora da Glória - SE;2804508;3189;Nossa Senhora da Glória;SE
        213;Nossa Senhora das Dores - SE;2804607;3191;Nossa Senhora das Dores;SE
        3185;Nossa Senhora das Graças - PR;4116406;7713;Nossa Senhora das Graças;PR
        214;Nossa Senhora de Lourdes - SE;2804706;3193;Nossa Senhora de Lourdes;SE
        2007;Nossa Senhora de Nazaré - PI;2206753;356;Nossa Senhora de Nazaré;PI
        803;Nossa Senhora do Livramento - MT;5106109;9121;Nossa Senhora do Livramento;MT
        215;Nossa Senhora do Socorro - SE;2804805;3195;Nossa Senhora do Socorro;SE
        2008;Nossa Senhora dos Remédios - PI;2206803;1135;Nossa Senhora dos Remédios;PI
        4738;Nova Aliança - SP;3532801;6757;Nova Aliança;SP
        3186;Nova Aliança do Ivaí - PR;4116505;7715;Nova Aliança do Ivaí;PR
        4142;Nova Alvorada - RS;4312757;7383;Nova Alvorada;RS
        376;Nova Alvorada do Sul - MS;5006002;143;Nova Alvorada do Sul;MS
        2574;Nova América - GO;5214705;9491;Nova América;GO
        3187;Nova América da Colina - PR;4116604;7717;Nova América da Colina;PR
        377;Nova Andradina - MS;5006200;9123;Nova Andradina;MS
        4143;Nova Araçá - RS;4312807;8759;Nova Araçá;RS
        2575;Nova Aurora - GO;5214804;9493;Nova Aurora;GO
        3188;Nova Aurora - PR;4116703;7965;Nova Aurora;PR
        804;Nova Bandeirantes - MT;5106158;117;Nova Bandeirantes;MT
        4144;Nova Bassano - RS;4312906;8761;Nova Bassano;RS
        5629;Nova Belém - MG;3144672;662;Nova Belém;MG
        4145;Nova Boa Vista - RS;4312955;5953;Nova Boa Vista;RS
        805;Nova Brasilândia - MT;5106208;8981;Nova Brasilândia;MT
        83;Nova Brasilândia D'Oeste - RO;1100148;41;Nova Brasilândia D'Oeste;RO
        4146;Nova Bréscia - RS;4313003;8763;Nova Bréscia;RS
        4739;Nova Campina - SP;3532827;3061;Nova Campina;SP
        3729;Nova Canaã - BA;2922706;3755;Nova Canaã;BA
        806;Nova Canaã do Norte - MT;5106216;9889;Nova Canaã do Norte;MT
        4740;Nova Canaã Paulista - SP;3532843;2985;Nova Canaã Paulista;SP
        4147;Nova Candelária - RS;4313011;1004;Nova Candelária;RS
        3189;Nova Cantu - PR;4116802;7719;Nova Cantu;PR
        4741;Nova Castilho - SP;3532868;806;Nova Castilho;SP
        1778;Nova Colinas - MA;2107258;200;Nova Colinas;MA
        2576;Nova Crixás - GO;5214838;9653;Nova Crixás;GO
        1207;Nova Cruz - RN;2408300;1765;Nova Cruz;RN
        5630;Nova Era - MG;3144706;4893;Nova Era;MG
        2832;Nova Erechim - SC;4211405;8223;Nova Erechim;SC
        3190;Nova Esperança - PR;4116901;7721;Nova Esperança;PR
        951;Nova Esperança do Piriá - PA;1504950;391;Nova Esperança do Piriá;PA
        3191;Nova Esperança do Sudoeste - PR;4116950;5477;Nova Esperança do Sudoeste;PR
        4148;Nova Esperança do Sul - RS;4313037;7381;Nova Esperança do Sul;RS
        4742;Nova Europa - SP;3532900;6759;Nova Europa;SP
        3730;Nova Fátima - BA;2922730;3007;Nova Fátima;BA
        3192;Nova Fátima - PR;4117008;7723;Nova Fátima;PR
        2319;Nova Floresta - PB;2510105;2103;Nova Floresta;PB
        449;Nova Friburgo - RJ;3303401;5867;Nova Friburgo;RJ
        2577;Nova Glória - GO;5214861;9655;Nova Glória;GO
        4743;Nova Granada - SP;3533007;6761;Nova Granada;SP
        807;Nova Guarita - MT;5108808;121;Nova Guarita;MT
        4744;Nova Guataporanga - SP;3533106;6763;Nova Guataporanga;SP
        4149;Nova Hartz - RS;4313060;7379;Nova Hartz;RS
        3731;Nova Ibiá - BA;2922755;3009;Nova Ibiá;BA
        450;Nova Iguaçu - RJ;3303500;5869;Nova Iguaçu;RJ
        2578;Nova Iguaçu de Goiás - GO;5214879;87;Nova Iguaçu de Goiás;GO
        4745;Nova Independência - SP;3533205;6765;Nova Independência;SP
        1779;Nova Iorque - MA;2107308;845;Nova Iorque;MA
        952;Nova Ipixuna - PA;1504976;56;Nova Ipixuna;PA
        2833;Nova Itaberaba - SC;4211454;5589;Nova Itaberaba;SC
        3732;Nova Itarana - BA;2922805;3757;Nova Itarana;BA
        808;Nova Lacerda - MT;5106182;1040;Nova Lacerda;MT
        3193;Nova Laranjeiras - PR;4117057;5479;Nova Laranjeiras;PR
        5631;Nova Lima - MG;3144805;4895;Nova Lima;MG
        3194;Nova Londrina - PR;4117107;7725;Nova Londrina;PR
        4746;Nova Luzitânia - SP;3533304;6767;Nova Luzitânia;SP
        84;Nova Mamoré - RO;1100338;47;Nova Mamoré;RO
        809;Nova Marilândia - MT;5108857;103;Nova Marilândia;MT
        810;Nova Maringá - MT;5108907;111;Nova Maringá;MT
        5632;Nova Módica - MG;3144904;4897;Nova Módica;MG
        811;Nova Monte Verde - MT;5108956;119;Nova Monte Verde;MT
        812;Nova Mutum - MT;5106224;9937;Nova Mutum;MT
        813;Nova Nazaré - MT;5106174;1086;Nova Nazaré;MT
        4747;Nova Odessa - SP;3533403;6769;Nova Odessa;SP
        814;Nova Olímpia - MT;5106232;9893;Nova Olímpia;MT
        3195;Nova Olímpia - PR;4117206;7967;Nova Olímpia;PR
        1406;Nova Olinda - CE;2309201;1485;Nova Olinda;CE
        2320;Nova Olinda - PB;2510204;2105;Nova Olinda;PB
        677;Nova Olinda - TO;1714880;9663;Nova Olinda;TO
        1780;Nova Olinda do Maranhão - MA;2107357;202;Nova Olinda do Maranhão;MA
        149;Nova Olinda do Norte - AM;1303106;265;Nova Olinda do Norte;AM
        4150;Nova Pádua - RS;4313086;5991;Nova Pádua;RS
        4151;Nova Palma - RS;4313102;8765;Nova Palma;RS
        2321;Nova Palmeira - PB;2510303;2107;Nova Palmeira;PB
        4152;Nova Petrópolis - RS;4313201;8767;Nova Petrópolis;RS
        5633;Nova Ponte - MG;3145000;4899;Nova Ponte;MG
        5634;Nova Porteirinha - MG;3145059;664;Nova Porteirinha;MG
        4153;Nova Prata - RS;4313300;8769;Nova Prata;RS
        3196;Nova Prata do Iguaçu - PR;4117255;7995;Nova Prata do Iguaçu;PR
        4154;Nova Ramada - RS;4313334;1006;Nova Ramada;RS
        3733;Nova Redenção - BA;2922854;3011;Nova Redenção;BA
        5635;Nova Resende - MG;3145109;4901;Nova Resende;MG
        2579;Nova Roma - GO;5214903;9495;Nova Roma;GO
        4155;Nova Roma do Sul - RS;4313359;7377;Nova Roma do Sul;RS
        678;Nova Rosalândia - TO;1715002;9721;Nova Rosalândia;TO
        1407;Nova Russas - CE;2309300;1487;Nova Russas;CE
        3197;Nova Santa Bárbara - PR;4117214;5457;Nova Santa Bárbara;PR
        815;Nova Santa Helena - MT;5106190;1088;Nova Santa Helena;MT
        2009;Nova Santa Rita - PI;2207959;370;Nova Santa Rita;PI
        4156;Nova Santa Rita - RS;4313375;5757;Nova Santa Rita;RS
        3198;Nova Santa Rosa - PR;4117222;7979;Nova Santa Rosa;PR
        5636;Nova Serrana - MG;3145208;4903;Nova Serrana;MG
        3734;Nova Soure - BA;2922904;3759;Nova Soure;BA
        3199;Nova Tebas - PR;4117271;9913;Nova Tebas;PR
        953;Nova Timboteua - PA;1505007;499;Nova Timboteua;PA
        2834;Nova Trento - SC;4211504;8225;Nova Trento;SC
        816;Nova Ubiratã - MT;5106240;1042;Nova Ubiratã;MT
        5637;Nova União - MG;3136603;4731;Nova União;MG
        85;Nova União - RO;1101435;10;Nova União;RO
        296;Nova Venécia - ES;3203908;5677;Nova Venécia;ES
        2580;Nova Veneza - GO;5215009;9497;Nova Veneza;GO
        2835;Nova Veneza - SC;4211603;8227;Nova Veneza;SC
        3735;Nova Viçosa - BA;2923001;3761;Nova Viçosa;BA
        817;Nova Xavantina - MT;5106257;9195;Nova Xavantina;MT
        4748;Novais - SP;3533254;2979;Novais;SP
        679;Novo Acordo - TO;1715101;9499;Novo Acordo;TO
        150;Novo Airão - AM;1303205;201;Novo Airão;AM
        680;Novo Alegre - TO;1715150;9703;Novo Alegre;TO
        151;Novo Aripuanã - AM;1303304;267;Novo Aripuanã;AM
        4157;Novo Barreiro - RS;4313490;5985;Novo Barreiro;RS
        2581;Novo Brasil - GO;5215207;9501;Novo Brasil;GO
        4158;Novo Cabrais - RS;4313391;1008;Novo Cabrais;RS
        5638;Novo Cruzeiro - MG;3145307;4905;Novo Cruzeiro;MG
        2582;Novo Gama - GO;5215231;1058;Novo Gama;GO
        4159;Novo Hamburgo - RS;4313409;8771;Novo Hamburgo;RS
        3736;Novo Horizonte - BA;2923035;3013;Novo Horizonte;BA
        2836;Novo Horizonte - SC;4211652;5591;Novo Horizonte;SC
        4749;Novo Horizonte - SP;3533502;6771;Novo Horizonte;SP
        818;Novo Horizonte do Norte - MT;5106273;9903;Novo Horizonte do Norte;MT
        86;Novo Horizonte do Oeste - RO;1100502;689;Novo Horizonte do Oeste;RO
        378;Novo Horizonte do Sul - MS;5006259;159;Novo Horizonte do Sul;MS
        3200;Novo Itacolomi - PR;4117297;5517;Novo Itacolomi;PR
        681;Novo Jardim - TO;1715259;321;Novo Jardim;TO
        552;Novo Lino - AL;2705606;2811;Novo Lino;AL
        4160;Novo Machado - RS;4313425;6057;Novo Machado;RS
        819;Novo Mundo - MT;5106265;1044;Novo Mundo;MT
        1408;Novo Oriente - CE;2309409;1489;Novo Oriente;CE
        5639;Novo Oriente de Minas - MG;3145356;666;Novo Oriente de Minas;MG
        2010;Novo Oriente do Piauí - PI;2206902;1137;Novo Oriente do Piauí;PI
        2583;Novo Planalto - GO;5215256;9735;Novo Planalto;GO
        954;Novo Progresso - PA;1505031;633;Novo Progresso;PA
        955;Novo Repartimento - PA;1505064;629;Novo Repartimento;PA
        820;Novo Santo Antônio - MT;5106315;1090;Novo Santo Antônio;MT
        2011;Novo Santo Antônio - PI;2206951;358;Novo Santo Antônio;PI
        821;Novo São Joaquim - MT;5106281;9867;Novo São Joaquim;MT
        4161;Novo Tiradentes - RS;4313441;5973;Novo Tiradentes;RS
        3737;Novo Triunfo - BA;2923050;3015;Novo Triunfo;BA
        4162;Novo Xingu - RS;4313466;1152;Novo Xingu;RS
        5640;Novorizonte - MG;3145372;668;Novorizonte;MG
        4750;Nuporanga - SP;3533601;6773;Nuporanga;SP
        956;Óbidos - PA;1505106;501;Óbidos;PA
        1409;Ocara - CE;2309458;1265;Ocara;CE
        4751;Ocauçu - SP;3533700;6775;Ocauçu;SP
        2012;Oeiras - PI;2207009;1139;Oeiras;PI
        957;Oeiras do Pará - PA;1505205;503;Oeiras do Pará;PA
        25;Oiapoque - AP;1600501;609;Oiapoque;AP
        5641;Olaria - MG;3145406;4907;Olaria;MG
        4752;Óleo - SP;3533809;6777;Óleo;SP
        2322;Olho d'Água - PB;2510402;2109;Olho d'Água;PB
        1781;Olho d'Água das Cunhãs - MA;2107407;847;Olho d'Água das Cunhãs;MA
        553;Olho d'Água das Flores - AL;2705705;2813;Olho d'Água das Flores;AL
        1208;Olho d'Água do Borges - RN;2408409;1767;Olho d'Água do Borges;RN
        554;Olho d'Água do Casado - AL;2705804;2815;Olho d'Água do Casado;AL
        2013;Olho D'Água do Piauí - PI;2207108;360;Olho D'Água do Piauí;PI
        555;Olho d'Água Grande - AL;2705903;2817;Olho d'Água Grande;AL
        5642;Olhos-d'Água - MG;3145455;670;Olhos-d'Água;MG
        4753;Olímpia - SP;3533908;6779;Olímpia;SP
        5643;Olímpio Noronha - MG;3145505;4909;Olímpio Noronha;MG
        1580;Olinda - PE;2609600;2491;Olinda;PE
        1782;Olinda Nova do Maranhão - MA;2107456;204;Olinda Nova do Maranhão;MA
        3738;Olindina - BA;2923100;3763;Olindina;BA
        2323;Olivedos - PB;2510501;2111;Olivedos;PB
        5644;Oliveira - MG;3145604;4911;Oliveira;MG
        682;Oliveira de Fátima - TO;1715507;92;Oliveira de Fátima;TO
        3739;Oliveira dos Brejinhos - BA;2923209;3765;Oliveira dos Brejinhos;BA
        5645;Oliveira Fortes - MG;3145703;4913;Oliveira Fortes;MG
        556;Olivença - AL;2706000;2819;Olivença;AL
        5646;Onça de Pitangui - MG;3145802;4915;Onça de Pitangui;MG
        4754;Onda Verde - SP;3534005;6781;Onda Verde;SP
        5647;Oratórios - MG;3145851;672;Oratórios;MG
        4755;Oriente - SP;3534104;6783;Oriente;SP
        4756;Orindiúva - SP;3534203;6785;Orindiúva;SP
        958;Oriximiná - PA;1505304;505;Oriximiná;PA
        5648;Orizânia - MG;3145877;674;Orizânia;MG
        2584;Orizona - GO;5215306;9503;Orizona;GO
        4757;Orlândia - SP;3534302;6787;Orlândia;SP
        2837;Orleans - SC;4211702;8229;Orleans;SC
        1581;Orobó - PE;2609709;2493;Orobó;PE
        1582;Orocó - PE;2609808;2495;Orocó;PE
        1410;Orós - CE;2309508;1491;Orós;CE
        3201;Ortigueira - PR;4117305;7727;Ortigueira;PR
        4758;Osasco - SP;3534401;6789;Osasco;SP
        4759;Oscar Bressane - SP;3534500;6791;Oscar Bressane;SP
        4163;Osório - RS;4313508;8773;Osório;RS
        4760;Osvaldo Cruz - SP;3534609;6793;Osvaldo Cruz;SP
        2838;Otacílio Costa - SC;4211751;8397;Otacílio Costa;SC
        959;Ourém - PA;1505403;507;Ourém;PA
        3740;Ouriçangas - BA;2923308;3767;Ouriçangas;BA
        1583;Ouricuri - PE;2609907;2497;Ouricuri;PE
        960;Ourilândia do Norte - PA;1505437;591;Ourilândia do Norte;PA
        4761;Ourinhos - SP;3534708;6795;Ourinhos;SP
        3301;Ourizona - PR;4117404;7729;Ourizona;PR
        2839;Ouro - SC;4211801;8231;Ouro;SC
        557;Ouro Branco - AL;2706109;2821;Ouro Branco;AL
        5649;Ouro Branco - MG;3145901;4917;Ouro Branco;MG
        1209;Ouro Branco - RN;2408508;1769;Ouro Branco;RN
        5650;Ouro Fino - MG;3146008;4919;Ouro Fino;MG
        5651;Ouro Preto - MG;3146107;4921;Ouro Preto;MG
        87;Ouro Preto do Oeste - RO;1100155;17;Ouro Preto do Oeste;RO
        2324;Ouro Velho - PB;2510600;2113;Ouro Velho;PB
        2840;Ouro Verde - SC;4211850;5741;Ouro Verde;SC
        4762;Ouro Verde - SP;3534807;6797;Ouro Verde;SP
        2585;Ouro Verde de Goiás - GO;5215405;9505;Ouro Verde de Goiás;GO
        5652;Ouro Verde de Minas - MG;3146206;4923;Ouro Verde de Minas;MG
        3302;Ouro Verde do Oeste - PR;4117453;9965;Ouro Verde do Oeste;PR
        4763;Ouroeste - SP;3534757;808;Ouroeste;SP
        3741;Ourolândia - BA;2923357;3017;Ourolândia;BA
        2586;Ouvidor - GO;5215504;9507;Ouvidor;GO
        4764;Pacaembu - SP;3534906;6799;Pacaembu;SP
        961;Pacajá - PA;1505486;593;Pacajá;PA
        1411;Pacajus - CE;2309607;1493;Pacajus;CE
        12;Pacaraima - RR;1400456;34;Pacaraima;RR
        1412;Pacatuba - CE;2309706;1495;Pacatuba;CE
        216;Pacatuba - SE;2804904;3197;Pacatuba;SE
        1783;Paço do Lumiar - MA;2107506;849;Paço do Lumiar;MA
        1413;Pacoti - CE;2309805;1497;Pacoti;CE
        1414;Pacujá - CE;2309904;1499;Pacujá;CE
        2587;Padre Bernardo - GO;5215603;9509;Padre Bernardo;GO
        5653;Padre Carvalho - MG;3146255;676;Padre Carvalho;MG
        2014;Padre Marcos - PI;2207207;1143;Padre Marcos;PI
        5654;Padre Paraíso - MG;3146305;4925;Padre Paraíso;MG
        2015;Paes Landim - PI;2207306;1145;Paes Landim;PI
        5655;Pai Pedro - MG;3146552;678;Pai Pedro;MG
        2841;Paial - SC;4211876;928;Paial;SC
        3303;Paiçandu - PR;4117503;7731;Paiçandu;PR
        4164;Paim Filho - RS;4313607;8775;Paim Filho;RS
        5656;Paineiras - MG;3146404;4927;Paineiras;MG
        2842;Painel - SC;4211892;930;Painel;SC
        5657;Pains - MG;3146503;4929;Pains;MG
        5658;Paiva - MG;3146602;4931;Paiva;MG
        2016;Pajeú do Piauí - PI;2207355;362;Pajeú do Piauí;PI
        558;Palestina - AL;2706208;2823;Palestina;AL
        4765;Palestina - SP;3535002;6801;Palestina;SP
        2588;Palestina de Goiás - GO;5215652;9737;Palestina de Goiás;GO
        962;Palestina do Pará - PA;1505494;379;Palestina do Pará;PA
        1415;Palhano - CE;2310001;1501;Palhano;CE
        2843;Palhoça - SC;4211900;8233;Palhoça;SC
        5659;Palma - MG;3146701;4933;Palma;MG
        2844;Palma Sola - SC;4212007;8235;Palma Sola;SC
        1416;Palmácia - CE;2310100;1503;Palmácia;CE
        1584;Palmares - PE;2610004;2499;Palmares;PE
        4165;Palmares do Sul - RS;4313656;8967;Palmares do Sul;RS
        4766;Palmares Paulista - SP;3535101;6803;Palmares Paulista;SP
        3304;Palmas - PR;4117602;7733;Palmas;PR
        683;Palmas - TO;1721000;9733;Palmas;TO
        3742;Palmas de Monte Alto - BA;2923407;3769;Palmas de Monte Alto;BA
        3305;Palmeira - PR;4117701;7735;Palmeira;PR
        2845;Palmeira - SC;4212056;932;Palmeira;SC
        4166;Palmeira das Missões - RS;4313706;8777;Palmeira das Missões;RS
        2017;Palmeira do Piauí - PI;2207405;1147;Palmeira do Piauí;PI
        4767;Palmeira d'Oeste - SP;3535200;6805;Palmeira d'Oeste;SP
        559;Palmeira dos Índios - AL;2706307;2825;Palmeira dos Índios;AL
        2018;Palmeirais - PI;2207504;1149;Palmeirais;PI
        1784;Palmeirândia - MA;2107605;851;Palmeirândia;MA
        684;Palmeirante - TO;1715705;189;Palmeirante;TO
        3743;Palmeiras - BA;2923506;3771;Palmeiras;BA
        2589;Palmeiras de Goiás - GO;5215702;9511;Palmeiras de Goiás;GO
        685;Palmeiras do Tocantins - TO;1713809;185;Palmeiras do Tocantins;TO
        1585;Palmeirina - PE;2610103;2501;Palmeirina;PE
        686;Palmeirópolis - TO;1715754;9649;Palmeirópolis;TO
        2590;Palmelo - GO;5215801;9513;Palmelo;GO
        2591;Palminópolis - GO;5215900;9515;Palminópolis;GO
        3306;Palmital - PR;4117800;7737;Palmital;PR
        4768;Palmital - SP;3535309;6807;Palmital;SP
        4167;Palmitinho - RS;4313805;8779;Palmitinho;RS
        2846;Palmitos - SC;4212106;8237;Palmitos;SC
        5660;Palmópolis - MG;3146750;2661;Palmópolis;MG
        3307;Palotina - PR;4117909;7739;Palotina;PR
        2592;Panamá - GO;5216007;9517;Panamá;GO
        4168;Panambi - RS;4313904;8781;Panambi;RS
        297;Pancas - ES;3204005;5679;Pancas;ES
        1586;Panelas - PE;2610202;2503;Panelas;PE
        4769;Panorama - SP;3535408;6809;Panorama;SP
        4169;Pantano Grande - RS;4313953;7375;Pantano Grande;RS
        560;Pão de Açúcar - AL;2706406;2827;Pão de Açúcar;AL
        5661;Papagaios - MG;3146909;4937;Papagaios;MG
        2847;Papanduva - SC;4212205;8239;Papanduva;SC
        2019;Paquetá - PI;2207553;364;Paquetá;PI
        5662;Pará de Minas - MG;3147105;4941;Pará de Minas;MG
        451;Paracambi - RJ;3303609;5871;Paracambi;RJ
        5663;Paracatu - MG;3147006;4939;Paracatu;MG
        1417;Paracuru - CE;2310209;1505;Paracuru;CE
        963;Paragominas - PA;1505502;509;Paragominas;PA
        5664;Paraguaçu - MG;3147204;4943;Paraguaçu;MG
        4770;Paraguaçu Paulista - SP;3535507;6811;Paraguaçu Paulista;SP
        4170;Paraí - RS;4314001;8783;Paraí;RS
        452;Paraíba do Sul - RJ;3303708;5873;Paraíba do Sul;RJ
        1785;Paraibano - MA;2107704;853;Paraibano;MA
        4771;Paraibuna - SP;3535606;6813;Paraibuna;SP
        1418;Paraipaba - CE;2310258;1599;Paraipaba;CE
        2848;Paraíso - SC;4212239;5747;Paraíso;SC
        4772;Paraíso - SP;3535705;6815;Paraíso;SP
        379;Paraíso das Águas - MS;5006275;1196;Paraíso das Águas;MS
        3308;Paraíso do Norte - PR;4118006;7741;Paraíso do Norte;PR
        4171;Paraíso do Sul - RS;4314027;7373;Paraíso do Sul;RS
        687;Paraíso do Tocantins - TO;1716109;9519;Paraíso do Tocantins;TO
        5665;Paraisópolis - MG;3147303;4945;Paraisópolis;MG
        1419;Parambu - CE;2310308;1507;Parambu;CE
        3744;Paramirim - BA;2923605;3773;Paramirim;BA
        1420;Paramoti - CE;2310407;1509;Paramoti;CE
        1210;Paraná - RN;2408607;1771;Paraná;RN
        688;Paranã - TO;1716208;9521;Paranã;TO
        3309;Paranacity - PR;4118105;7743;Paranacity;PR
        3310;Paranaguá - PR;4118204;7745;Paranaguá;PR
        380;Paranaíba - MS;5006309;9125;Paranaíba;MS
        2593;Paranaiguara - GO;5216304;9455;Paranaiguara;GO
        822;Paranaíta - MT;5106299;9885;Paranaíta;MT
        4773;Paranapanema - SP;3535804;6817;Paranapanema;SP
        3311;Paranapoema - PR;4118303;7747;Paranapoema;PR
        4774;Paranapuã - SP;3535903;6819;Paranapuã;SP
        1587;Paranatama - PE;2610301;2505;Paranatama;PE
        823;Paranatinga - MT;5106307;8983;Paranatinga;MT
        3312;Paranavaí - PR;4118402;7749;Paranavaí;PR
        381;Paranhos - MS;5006358;9739;Paranhos;MS
        5666;Paraopeba - MG;3147402;4947;Paraopeba;MG
        4775;Parapuã - SP;3536000;6821;Parapuã;SP
        2325;Parari - PB;2510659;494;Parari;PB
        3745;Paratinga - BA;2923704;3775;Paratinga;BA
        453;Paraty - RJ;3303807;5875;Paraty;RJ
        1211;Paraú - RN;2408706;1773;Paraú;RN
        964;Parauapebas - PA;1505536;595;Parauapebas;PA
        2594;Paraúna - GO;5216403;9523;Paraúna;GO
        1212;Parazinho - RN;2408805;1775;Parazinho;RN
        4776;Pardinho - SP;3536109;6823;Pardinho;SP
        4172;Pareci Novo - RS;4314035;6041;Pareci Novo;RS
        88;Parecis - RO;1101450;12;Parecis;RO
        1213;Parelhas - RN;2408904;1777;Parelhas;RN
        561;Pariconha - AL;2706422;2645;Pariconha;AL
        152;Parintins - AM;1303403;269;Parintins;AM
        3746;Paripiranga - BA;2923803;3777;Paripiranga;BA
        562;Paripueira - AL;2706448;2641;Paripueira;AL
        4777;Pariquera-Açu - SP;3536208;6825;Pariquera-Açu;SP
        4778;Parisi - SP;3536257;2989;Parisi;SP
        2020;Parnaguá - PI;2207603;1151;Parnaguá;PI
        2021;Parnaíba - PI;2207702;1153;Parnaíba;PI
        1588;Parnamirim - PE;2610400;2507;Parnamirim;PE
        1214;Parnamirim - RN;2403251;1779;Parnamirim;RN
        1786;Parnarama - MA;2107803;855;Parnarama;MA
        4173;Parobé - RS;4314050;9825;Parobé;RS
        1215;Passa e Fica - RN;2409100;1781;Passa e Fica;RN
        5667;Passa Quatro - MG;3147600;4951;Passa Quatro;MG
        4174;Passa Sete - RS;4314068;1010;Passa Sete;RS
        5668;Passa Tempo - MG;3147709;4953;Passa Tempo;MG
        5670;Passa Vinte - MG;3147808;4955;Passa Vinte;MG
        5669;Passabém - MG;3147501;4949;Passabém;MG
        2326;Passagem - PB;2510709;2115;Passagem;PB
        1216;Passagem - RN;2409209;1783;Passagem;RN
        1787;Passagem Franca - MA;2107902;857;Passagem Franca;MA
        2022;Passagem Franca do Piauí - PI;2207751;1293;Passagem Franca do Piauí;PI
        1589;Passira - PE;2610509;2509;Passira;PE
        563;Passo de Camaragibe - AL;2706505;2829;Passo de Camaragibe;AL
        2849;Passo de Torres - SC;4212254;5541;Passo de Torres;SC
        4175;Passo do Sobrado - RS;4314076;5765;Passo do Sobrado;RS
        4176;Passo Fundo - RS;4314100;8785;Passo Fundo;RS
        5671;Passos - MG;3147907;4957;Passos;MG
        2850;Passos Maia - SC;4212270;5743;Passos Maia;SC
        1788;Pastos Bons - MA;2108009;859;Pastos Bons;MA
        5672;Patis - MG;3147956;680;Patis;MG
        3313;Pato Bragado - PR;4118451;5533;Pato Bragado;PR
        3314;Pato Branco - PR;4118501;7751;Pato Branco;PR
        2327;Patos - PB;2510808;2117;Patos;PB
        5673;Patos de Minas - MG;3148004;4959;Patos de Minas;MG
        2023;Patos do Piauí - PI;2207777;2277;Patos do Piauí;PI
        5674;Patrocínio - MG;3148103;4961;Patrocínio;MG
        5675;Patrocínio do Muriaé - MG;3148202;4963;Patrocínio do Muriaé;MG
        4779;Patrocínio Paulista - SP;3536307;6827;Patrocínio Paulista;SP
        1217;Patu - RN;2409308;1785;Patu;RN
        454;Paty do Alferes - RJ;3303856;6005;Paty do Alferes;RJ
        3747;Pau Brasil - BA;2923902;3779;Pau Brasil;BA
        965;Pau D'Arco - PA;1505551;387;Pau D'Arco;PA
        689;Pau D'Arco - TO;1716307;191;Pau D'Arco;TO
        2024;Pau D'Arco do Piauí - PI;2207793;1104;Pau D'Arco do Piauí;PI
        1218;Pau dos Ferros - RN;2409407;1787;Pau dos Ferros;RN
        1590;Paudalho - PE;2610608;2511;Paudalho;PE
        153;Pauini - AM;1303502;271;Pauini;AM
        5676;Paula Cândido - MG;3148301;4965;Paula Cândido;MG
        3315;Paula Freitas - PR;4118600;7753;Paula Freitas;PR
        4780;Paulicéia - SP;3536406;6829;Paulicéia;SP
        4781;Paulínia - SP;3536505;6831;Paulínia;SP
        1789;Paulino Neves - MA;2108058;206;Paulino Neves;MA
        2328;Paulista - PB;2510907;2119;Paulista;PB
        1591;Paulista - PE;2610707;2513;Paulista;PE
        2025;Paulistana - PI;2207801;1155;Paulistana;PI
        4782;Paulistânia - SP;3536570;810;Paulistânia;SP
        5677;Paulistas - MG;3148400;4967;Paulistas;MG
        3748;Paulo Afonso - BA;2924009;3781;Paulo Afonso;BA
        4177;Paulo Bento - RS;4314134;1154;Paulo Bento;RS
        4783;Paulo de Faria - SP;3536604;6833;Paulo de Faria;SP
        3316;Paulo Frontin - PR;4118709;7755;Paulo Frontin;PR
        564;Paulo Jacinto - AL;2706604;2831;Paulo Jacinto;AL
        2851;Paulo Lopes - SC;4212304;8241;Paulo Lopes;SC
        1790;Paulo Ramos - MA;2108108;959;Paulo Ramos;MA
        5678;Pavão - MG;3148509;4969;Pavão;MG
        4178;Paverama - RS;4314159;7371;Paverama;RS
        2026;Pavussu - PI;2207850;366;Pavussu;PI
        3749;Pé de Serra - BA;2924058;3981;Pé de Serra;BA
        3317;Peabiru - PR;4118808;7757;Peabiru;PR
        5679;Peçanha - MG;3148608;4971;Peçanha;MG
        4784;Pederneiras - SP;3536703;6835;Pederneiras;SP
        1592;Pedra - PE;2610806;2515;Pedra;PE
        5680;Pedra Azul - MG;3148707;4973;Pedra Azul;MG
        4785;Pedra Bela - SP;3536802;6837;Pedra Bela;SP
        5681;Pedra Bonita - MG;3148756;682;Pedra Bonita;MG
        1421;Pedra Branca - CE;2310506;1511;Pedra Branca;CE
        2329;Pedra Branca - PB;2511004;2121;Pedra Branca;PB
        26;Pedra Branca do Amapari - AP;1600154;663;Pedra Branca do Amapari;AP
        5682;Pedra do Anta - MG;3148806;4975;Pedra do Anta;MG
        5683;Pedra do Indaiá - MG;3148905;4977;Pedra do Indaiá;MG
        5684;Pedra Dourada - MG;3149002;4979;Pedra Dourada;MG
        1219;Pedra Grande - RN;2409506;1789;Pedra Grande;RN
        2330;Pedra Lavrada - PB;2511103;2123;Pedra Lavrada;PB
        217;Pedra Mole - SE;2805000;3199;Pedra Mole;SE
        824;Pedra Preta - MT;5106372;9181;Pedra Preta;MT
        1220;Pedra Preta - RN;2409605;1791;Pedra Preta;RN
        5685;Pedralva - MG;3149101;4981;Pedralva;MG
        4786;Pedranópolis - SP;3536901;6839;Pedranópolis;SP
        3750;Pedrão - BA;2924108;3783;Pedrão;BA
        4179;Pedras Altas - RS;4314175;1156;Pedras Altas;RS
        2331;Pedras de Fogo - PB;2511202;2125;Pedras de Fogo;PB
        5686;Pedras de Maria da Cruz - MG;3149150;2899;Pedras de Maria da Cruz;MG
        2852;Pedras Grandes - SC;4212403;8243;Pedras Grandes;SC
        4787;Pedregulho - SP;3537008;6841;Pedregulho;SP
        4788;Pedreira - SP;3537107;6843;Pedreira;SP
        1791;Pedreiras - MA;2108207;861;Pedreiras;MA
        218;Pedrinhas - SE;2805109;3201;Pedrinhas;SE
        4789;Pedrinhas Paulista - SP;3537156;2963;Pedrinhas Paulista;SP
        5687;Pedrinópolis - MG;3149200;4983;Pedrinópolis;MG
        690;Pedro Afonso - TO;1716505;9525;Pedro Afonso;TO
        3751;Pedro Alexandre - BA;2924207;3785;Pedro Alexandre;BA
        1221;Pedro Avelino - RN;2409704;1793;Pedro Avelino;RN
        298;Pedro Canário - ES;3204054;5715;Pedro Canário;ES
        4790;Pedro de Toledo - SP;3537206;6845;Pedro de Toledo;SP
        1792;Pedro do Rosário - MA;2108256;208;Pedro do Rosário;MA
        382;Pedro Gomes - MS;5006408;9127;Pedro Gomes;MS
        2027;Pedro II - PI;2207900;1157;Pedro II;PI
        2028;Pedro Laurentino - PI;2207934;368;Pedro Laurentino;PI
        5688;Pedro Leopoldo - MG;3149309;4985;Pedro Leopoldo;MG
        4180;Pedro Osório - RS;4314209;8787;Pedro Osório;RS
        2332;Pedro Régis - PB;2512721;500;Pedro Régis;PB
        5689;Pedro Teixeira - MG;3149408;4987;Pedro Teixeira;MG
        1222;Pedro Velho - RN;2409803;1795;Pedro Velho;RN
        691;Peixe - TO;1716604;9527;Peixe;TO
        966;Peixe-Boi - PA;1505601;511;Peixe-Boi;PA
        825;Peixoto de Azevedo - MT;5106422;9891;Peixoto de Azevedo;MT
        4181;Pejuçara - RS;4314308;8789;Pejuçara;RS
        4182;Pelotas - RS;4314407;8791;Pelotas;RS
        1422;Penaforte - CE;2310605;1513;Penaforte;CE
        1793;Penalva - MA;2108306;863;Penalva;MA
        4791;Penápolis - SP;3537305;6847;Penápolis;SP
        1223;Pendências - RN;2409902;1797;Pendências;RN
        565;Penedo - AL;2706703;2833;Penedo;AL
        2853;Penha - SC;4212502;8245;Penha;SC
        1423;Pentecoste - CE;2310704;1515;Pentecoste;CE
        5690;Pequeri - MG;3149507;4989;Pequeri;MG
        5691;Pequi - MG;3149606;4991;Pequi;MG
        692;Pequizeiro - TO;1716653;9705;Pequizeiro;TO
        5692;Perdigão - MG;3149705;4993;Perdigão;MG
        5693;Perdizes - MG;3149804;4995;Perdizes;MG
        5694;Perdões - MG;3149903;4997;Perdões;MG
        4792;Pereira Barreto - SP;3537404;6849;Pereira Barreto;SP
        4793;Pereiras - SP;3537503;6851;Pereiras;SP
        1424;Pereiro - CE;2310803;1517;Pereiro;CE
        1794;Peri Mirim - MA;2108405;865;Peri Mirim;MA
        5695;Periquito - MG;3149952;684;Periquito;MG
        2854;Peritiba - SC;4212601;8247;Peritiba;SC
        1795;Peritoró - MA;2108454;210;Peritoró;MA
        3318;Perobal - PR;4118857;868;Perobal;PR
        3319;Pérola - PR;4118907;7969;Pérola;PR
        3320;Pérola d'Oeste - PR;4119004;7759;Pérola d'Oeste;PR
        2595;Perolândia - GO;5216452;75;Perolândia;GO
        4794;Peruíbe - SP;3537602;6853;Peruíbe;SP
        5696;Pescador - MG;3150000;4999;Pescador;MG
        2855;Pescaria Brava - SC;4212650;1194;Pescaria Brava;SC
        1593;Pesqueira - PE;2610905;2517;Pesqueira;PE
        1594;Petrolândia - PE;2611002;2519;Petrolândia;PE
        2856;Petrolândia - SC;4212700;8249;Petrolândia;SC
        1595;Petrolina - PE;2611101;2521;Petrolina;PE
        2596;Petrolina de Goiás - GO;5216809;9531;Petrolina de Goiás;GO
        455;Petrópolis - RJ;3303906;5877;Petrópolis;RJ
        566;Piaçabuçu - AL;2706802;2835;Piaçabuçu;AL
        4795;Piacatu - SP;3537701;6855;Piacatu;SP
        2333;Piancó - PB;2511301;2127;Piancó;PB
        3752;Piatã - BA;2924306;3787;Piatã;BA
        5697;Piau - MG;3150109;5001;Piau;MG
        4183;Picada Café - RS;4314423;6021;Picada Café;RS
        967;Piçarra - PA;1505635;58;Piçarra;PA
        2029;Picos - PI;2208007;1159;Picos;PI
        2334;Picuí - PB;2511400;2129;Picuí;PB
        4796;Piedade - SP;3537800;6857;Piedade;SP
        5698;Piedade de Caratinga - MG;3150158;686;Piedade de Caratinga;MG
        5699;Piedade de Ponte Nova - MG;3150208;5003;Piedade de Ponte Nova;MG
        5700;Piedade do Rio Grande - MG;3150307;5005;Piedade do Rio Grande;MG
        5701;Piedade dos Gerais - MG;3150406;5007;Piedade dos Gerais;MG
        3321;Piên - PR;4119103;7761;Piên;PR
        3753;Pilão Arcado - BA;2924405;3789;Pilão Arcado;BA
        567;Pilar - AL;2706901;2837;Pilar;AL
        2335;Pilar - PB;2511509;2131;Pilar;PB
        2597;Pilar de Goiás - GO;5216908;9535;Pilar de Goiás;GO
        4797;Pilar do Sul - SP;3537909;6859;Pilar do Sul;SP
        2336;Pilões - PB;2511608;2133;Pilões;PB
        1224;Pilões - RN;2410009;1799;Pilões;RN
        2337;Pilõezinhos - PB;2511707;2135;Pilõezinhos;PB
        5702;Pimenta - MG;3150505;5009;Pimenta;MG
        89;Pimenta Bueno - RO;1100189;11;Pimenta Bueno;RO
        2030;Pimenteiras - PI;2208106;1161;Pimenteiras;PI
        90;Pimenteiras do Oeste - RO;1101468;14;Pimenteiras do Oeste;RO
        3754;Pindaí - BA;2924504;3791;Pindaí;BA
        4798;Pindamonhangaba - SP;3538006;6861;Pindamonhangaba;SP
        1796;Pindaré-Mirim - MA;2108504;867;Pindaré-Mirim;MA
        568;Pindoba - AL;2707008;2839;Pindoba;AL
        3755;Pindobaçu - BA;2924603;3793;Pindobaçu;BA
        4799;Pindorama - SP;3538105;6863;Pindorama;SP
        693;Pindorama do Tocantins - TO;1717008;9537;Pindorama do Tocantins;TO
        1425;Pindoretama - CE;2310852;1267;Pindoretama;CE
        5703;Pingo d'Água - MG;3150539;688;Pingo d'Água;MG
        3322;Pinhais - PR;4119152;5453;Pinhais;PR
        4184;Pinhal - RS;4314456;7369;Pinhal;RS
        4185;Pinhal da Serra - RS;4314464;1158;Pinhal da Serra;RS
        3323;Pinhal de São Bento - PR;4119251;5495;Pinhal de São Bento;PR
        4186;Pinhal Grande - RS;4314472;5787;Pinhal Grande;RS
        3324;Pinhalão - PR;4119202;7763;Pinhalão;PR
        2857;Pinhalzinho - SC;4212908;8253;Pinhalzinho;SC
        4800;Pinhalzinho - SP;3538204;6867;Pinhalzinho;SP
        3325;Pinhão - PR;4119301;7765;Pinhão;PR
        219;Pinhão - SE;2805208;3203;Pinhão;SE
        456;Pinheiral - RJ;3303955;778;Pinheiral;RJ
        4187;Pinheirinho do Vale - RS;4314498;5975;Pinheirinho do Vale;RS
        1797;Pinheiro - MA;2108603;869;Pinheiro;MA
        4188;Pinheiro Machado - RS;4314506;8793;Pinheiro Machado;RS
        2858;Pinheiro Preto - SC;4213005;8255;Pinheiro Preto;SC
        299;Pinheiros - ES;3204104;5681;Pinheiros;ES
        3756;Pintadas - BA;2924652;3983;Pintadas;BA
        4189;Pinto Bandeira - RS;4314548;1160;Pinto Bandeira;RS
        5704;Pintópolis - MG;3150570;690;Pintópolis;MG
        2031;Pio IX - PI;2208205;1163;Pio IX;PI
        1798;Pio XII - MA;2108702;871;Pio XII;MA
        4801;Piquerobi - SP;3538303;6869;Piquerobi;SP
        1426;Piquet Carneiro - CE;2310902;1519;Piquet Carneiro;CE
        4802;Piquete - SP;3538501;6871;Piquete;SP
        4803;Piracaia - SP;3538600;6873;Piracaia;SP
        2598;Piracanjuba - GO;5217104;9539;Piracanjuba;GO
        5705;Piracema - MG;3150604;5011;Piracema;MG
        4804;Piracicaba - SP;3538709;6875;Piracicaba;SP
        2032;Piracuruca - PI;2208304;1165;Piracuruca;PI
        457;Piraí - RJ;3304003;5879;Piraí;RJ
        3757;Piraí do Norte - BA;2924678;3019;Piraí do Norte;BA
        3326;Piraí do Sul - PR;4119400;7767;Piraí do Sul;PR
        4805;Piraju - SP;3538808;6877;Piraju;SP
        5706;Pirajuba - MG;3150703;5013;Pirajuba;MG
        4806;Pirajuí - SP;3538907;6879;Pirajuí;SP
        220;Pirambu - SE;2805307;3205;Pirambu;SE
        5707;Piranga - MG;3150802;5015;Piranga;MG
        4807;Pirangi - SP;3539004;6881;Pirangi;SP
        5708;Piranguçu - MG;3150901;5017;Piranguçu;MG
        5709;Piranguinho - MG;3151008;5019;Piranguinho;MG
        569;Piranhas - AL;2707107;2841;Piranhas;AL
        2599;Piranhas - GO;5217203;9541;Piranhas;GO
        1799;Pirapemas - MA;2108801;873;Pirapemas;MA
        5710;Pirapetinga - MG;3151107;5021;Pirapetinga;MG
        4190;Pirapó - RS;4314555;7367;Pirapó;RS
        5711;Pirapora - MG;3151206;5023;Pirapora;MG
        4808;Pirapora do Bom Jesus - SP;3539103;6883;Pirapora do Bom Jesus;SP
        4809;Pirapozinho - SP;3539202;6885;Pirapozinho;SP
        3327;Piraquara - PR;4119509;7769;Piraquara;PR
        694;Piraquê - TO;1717206;355;Piraquê;TO
        4810;Pirassununga - SP;3539301;6887;Pirassununga;SP
        4191;Piratini - RS;4314605;8795;Piratini;RS
        4811;Piratininga - SP;3539400;6889;Piratininga;SP
        2859;Piratuba - SC;4213104;8257;Piratuba;SC
        5712;Piraúba - MG;3151305;5025;Piraúba;MG
        2600;Pirenópolis - GO;5217302;9543;Pirenópolis;GO
        2601;Pires do Rio - GO;5217401;9545;Pires do Rio;GO
        1427;Pires Ferreira - CE;2310951;1269;Pires Ferreira;CE
        3758;Piripá - BA;2924702;3795;Piripá;BA
        2033;Piripiri - PI;2208403;1167;Piripiri;PI
        3759;Piritiba - BA;2924801;3797;Piritiba;BA
        2338;Pirpirituba - PB;2511806;2137;Pirpirituba;PB
        3328;Pitanga - PR;4119608;7771;Pitanga;PR
        3329;Pitangueiras - PR;4119657;5461;Pitangueiras;PR
        4812;Pitangueiras - SP;3539509;6891;Pitangueiras;SP
        5713;Pitangui - MG;3151404;5027;Pitangui;MG
        2339;Pitimbu - PB;2511905;2139;Pitimbu;PB
        695;Pium - TO;1717503;9547;Pium;TO
        300;Piúma - ES;3204203;5683;Piúma;ES
        5714;Piumhi - MG;3151503;5029;Piumhi;MG
        968;Placas - PA;1505650;60;Placas;PA
        45;Plácido de Castro - AC;1200385;151;Plácido de Castro;AC
        2602;Planaltina - GO;5217609;9595;Planaltina;GO
        3330;Planaltina do Paraná - PR;4119707;7773;Planaltina do Paraná;PR
        3760;Planaltino - BA;2924900;3799;Planaltino;BA
        3761;Planalto - BA;2925006;3801;Planalto;BA
        3331;Planalto - PR;4119806;7775;Planalto;PR
        4192;Planalto - RS;4314704;8797;Planalto;RS
        4813;Planalto - SP;3539608;6893;Planalto;SP
        2860;Planalto Alegre - SC;4213153;5593;Planalto Alegre;SC
        826;Planalto da Serra - MT;5106455;91;Planalto da Serra;MT
        5715;Planura - MG;3151602;5031;Planura;MG
        4814;Platina - SP;3539707;6895;Platina;SP
        4815;Poá - SP;3539806;6897;Poá;SP
        1596;Poção - PE;2611200;2523;Poção;PE
        1800;Poção de Pedras - MA;2108900;875;Poção de Pedras;MA
        2340;Pocinhos - PB;2512002;2141;Pocinhos;PB
        1225;Poço Branco - RN;2410108;1801;Poço Branco;RN
        2341;Poço Dantas - PB;2512036;496;Poço Dantas;PB
        4193;Poço das Antas - RS;4314753;7365;Poço das Antas;RS
        570;Poço das Trincheiras - AL;2707206;2843;Poço das Trincheiras;AL
        2342;Poço de José de Moura - PB;2512077;498;Poço de José de Moura;PB
        5716;Poço Fundo - MG;3151701;5033;Poço Fundo;MG
        221;Poço Redondo - SE;2805406;3207;Poço Redondo;SE
        222;Poço Verde - SE;2805505;3209;Poço Verde;SE
        3762;Poções - BA;2925105;3803;Poções;BA
        827;Poconé - MT;5106505;9129;Poconé;MT
        5717;Poços de Caldas - MG;3151800;5035;Poços de Caldas;MG
        5718;Pocrane - MG;3151909;5037;Pocrane;MG
        3763;Pojuca - BA;2925204;3805;Pojuca;BA
        4816;Poloni - SP;3539905;6899;Poloni;SP
        2343;Pombal - PB;2512101;2143;Pombal;PB
        1597;Pombos - PE;2611309;2525;Pombos;PE
        2861;Pomerode - SC;4213203;8259;Pomerode;SC
        4817;Pompéia - SP;3540002;6901;Pompéia;SP
        5719;Pompéu - MG;3152006;5039;Pompéu;MG
        4818;Pongaí - SP;3540101;6903;Pongaí;SP
        969;Ponta de Pedras - PA;1505700;513;Ponta de Pedras;PA
        3332;Ponta Grossa - PR;4119905;7777;Ponta Grossa;PR
        383;Ponta Porã - MS;5006606;9131;Ponta Porã;MS
        4819;Pontal - SP;3540200;6905;Pontal;SP
        828;Pontal do Araguaia - MT;5106653;95;Pontal do Araguaia;MT
        3333;Pontal do Paraná - PR;4119954;870;Pontal do Paraná;PR
        2603;Pontalina - GO;5217708;9549;Pontalina;GO
        4820;Pontalinda - SP;3540259;2987;Pontalinda;SP
        4194;Pontão - RS;4314779;5939;Pontão;RS
        2862;Ponte Alta - SC;4213302;8261;Ponte Alta;SC
        696;Ponte Alta do Bom Jesus - TO;1717800;9551;Ponte Alta do Bom Jesus;TO
        2863;Ponte Alta do Norte - SC;4213351;5569;Ponte Alta do Norte;SC
        697;Ponte Alta do Tocantins - TO;1717909;9553;Ponte Alta do Tocantins;TO
        829;Ponte Branca - MT;5106703;9133;Ponte Branca;MT
        5720;Ponte Nova - MG;3152105;5041;Ponte Nova;MG
        4195;Ponte Preta - RS;4314787;5967;Ponte Preta;RS
        2864;Ponte Serrada - SC;4213401;8263;Ponte Serrada;SC
        830;Pontes e Lacerda - MT;5106752;8999;Pontes e Lacerda;MT
        4821;Pontes Gestal - SP;3540309;6907;Pontes Gestal;SP
        301;Ponto Belo - ES;3204252;762;Ponto Belo;ES
        5721;Ponto Chique - MG;3152131;692;Ponto Chique;MG
        5722;Ponto dos Volantes - MG;3152170;694;Ponto dos Volantes;MG
        3764;Ponto Novo - BA;2925253;3021;Ponto Novo;BA
        4822;Populina - SP;3540408;6909;Populina;SP
        1428;Poranga - CE;2311009;1521;Poranga;CE
        4823;Porangaba - SP;3540507;6911;Porangaba;SP
        2604;Porangatu - GO;5218003;9555;Porangatu;GO
        458;Porciúncula - RJ;3304102;5881;Porciúncula;RJ
        3334;Porecatu - PR;4120002;7779;Porecatu;PR
        1226;Portalegre - RN;2410207;1803;Portalegre;RN
        4196;Portão - RS;4314803;8799;Portão;RS
        2605;Porteirão - GO;5218052;1060;Porteirão;GO
        1429;Porteiras - CE;2311108;1523;Porteiras;CE
        5723;Porteirinha - MG;3152204;5043;Porteirinha;MG
        970;Portel - PA;1505809;515;Portel;PA
        2606;Portelândia - GO;5218102;9557;Portelândia;GO
        2034;Porto - PI;2208502;1169;Porto;PI
        46;Porto Acre - AC;1200807;649;Porto Acre;AC
        4197;Porto Alegre - RS;4314902;8801;Porto Alegre;RS
        831;Porto Alegre do Norte - MT;5106778;9895;Porto Alegre do Norte;MT
        2035;Porto Alegre do Piauí - PI;2208551;372;Porto Alegre do Piauí;PI
        698;Porto Alegre do Tocantins - TO;1718006;9723;Porto Alegre do Tocantins;TO
        3335;Porto Amazonas - PR;4120101;7781;Porto Amazonas;PR
        3336;Porto Barreiro - PR;4120150;872;Porto Barreiro;PR
        2865;Porto Belo - SC;4213500;8265;Porto Belo;SC
        571;Porto Calvo - AL;2707305;2845;Porto Calvo;AL
        223;Porto da Folha - SE;2805604;3211;Porto da Folha;SE
        971;Porto de Moz - PA;1505908;517;Porto de Moz;PA
        572;Porto de Pedras - AL;2707404;2847;Porto de Pedras;AL
        1227;Porto do Mangue - RN;2410256;426;Porto do Mangue;RN
        832;Porto dos Gaúchos - MT;5106802;9135;Porto dos Gaúchos;MT
        833;Porto Esperidião - MT;5106828;9875;Porto Esperidião;MT
        834;Porto Estrela - MT;5106851;101;Porto Estrela;MT
        4824;Porto Feliz - SP;3540606;6913;Porto Feliz;SP
        4825;Porto Ferreira - SP;3540705;6915;Porto Ferreira;SP
        5724;Porto Firme - MG;3152303;5045;Porto Firme;MG
        1801;Porto Franco - MA;2109007;877;Porto Franco;MA
        27;Porto Grande - AP;1600535;671;Porto Grande;AP
        4198;Porto Lucena - RS;4315008;8803;Porto Lucena;RS
        4199;Porto Mauá - RS;4315057;6065;Porto Mauá;RS
        384;Porto Murtinho - MS;5006903;9137;Porto Murtinho;MS
        699;Porto Nacional - TO;1718204;9559;Porto Nacional;TO
        459;Porto Real - RJ;3304110;780;Porto Real;RJ
        573;Porto Real do Colégio - AL;2707503;2849;Porto Real do Colégio;AL
        3337;Porto Rico - PR;4120200;7783;Porto Rico;PR
        1802;Porto Rico do Maranhão - MA;2109056;212;Porto Rico do Maranhão;MA
        3765;Porto Seguro - BA;2925303;3807;Porto Seguro;BA
        2866;Porto União - SC;4213609;8267;Porto União;SC
        91;Porto Velho - RO;1100205;3;Porto Velho;RO
        4200;Porto Vera Cruz - RS;4315073;6067;Porto Vera Cruz;RS
        3338;Porto Vitória - PR;4120309;7785;Porto Vitória;PR
        47;Porto Walter - AC;1200393;657;Porto Walter;AC
        4201;Porto Xavier - RS;4315107;8805;Porto Xavier;RS
        2607;Posse - GO;5218300;9561;Posse;GO
        5725;Poté - MG;3152402;5047;Poté;MG
        1430;Potengi - CE;2311207;1525;Potengi;CE
        4826;Potim - SP;3540754;2993;Potim;SP
        3766;Potiraguá - BA;2925402;3809;Potiraguá;BA
        4827;Potirendaba - SP;3540804;6917;Potirendaba;SP
        1431;Potiretama - CE;2311231;1271;Potiretama;CE
        5726;Pouso Alegre - MG;3152501;5049;Pouso Alegre;MG
        5727;Pouso Alto - MG;3152600;5051;Pouso Alto;MG
        4202;Pouso Novo - RS;4315131;7363;Pouso Novo;RS
        2867;Pouso Redondo - SC;4213708;8269;Pouso Redondo;SC
        835;Poxoréu - MT;5107008;9139;Poxoréu;MT
        4828;Pracinha - SP;3540853;812;Pracinha;SP
        28;Pracuúba - AP;1600550;673;Pracuúba;AP
        3767;Prado - BA;2925501;3811;Prado;BA
        3339;Prado Ferreira - PR;4120333;874;Prado Ferreira;PR
        4829;Pradópolis - SP;3540903;6919;Pradópolis;SP
        5728;Prados - MG;3152709;5053;Prados;MG
        2868;Praia Grande - SC;4213807;8271;Praia Grande;SC
        4830;Praia Grande - SP;3541000;6921;Praia Grande;SP
        700;Praia Norte - TO;1718303;9725;Praia Norte;TO
        972;Prainha - PA;1506005;519;Prainha;PA
        3340;Pranchita - PR;4120358;7991;Pranchita;PR
        5729;Prata - MG;3152808;5055;Prata;MG
        2344;Prata - PB;2512200;2145;Prata;PB
        2036;Prata do Piauí - PI;2208601;1171;Prata do Piauí;PI
        4831;Pratânia - SP;3541059;814;Pratânia;SP
        5730;Pratápolis - MG;3152907;5057;Pratápolis;MG
        5731;Pratinha - MG;3153004;5059;Pratinha;MG
        4832;Presidente Alves - SP;3541109;6923;Presidente Alves;SP
        5732;Presidente Bernardes - MG;3153103;5061;Presidente Bernardes;MG
        4833;Presidente Bernardes - SP;3541208;6925;Presidente Bernardes;SP
        2869;Presidente Castello Branco - SC;4213906;8273;Presidente Castello Branco;SC
        3341;Presidente Castelo Branco - PR;4120408;7787;Presidente Castelo Branco;PR
        3768;Presidente Dutra - BA;2925600;3813;Presidente Dutra;BA
        1803;Presidente Dutra - MA;2109106;879;Presidente Dutra;MA
        4834;Presidente Epitácio - SP;3541307;6927;Presidente Epitácio;SP
        154;Presidente Figueiredo - AM;1303536;9841;Presidente Figueiredo;AM
        2870;Presidente Getúlio - SC;4214003;8275;Presidente Getúlio;SC
        3769;Presidente Jânio Quadros - BA;2925709;3815;Presidente Jânio Quadros;BA
        1804;Presidente Juscelino - MA;2109205;881;Presidente Juscelino;MA
        5733;Presidente Juscelino - MG;3153202;5063;Presidente Juscelino;MG
        302;Presidente Kennedy - ES;3204302;5685;Presidente Kennedy;ES
        701;Presidente Kennedy - TO;1718402;9629;Presidente Kennedy;TO
        5734;Presidente Kubitschek - MG;3153301;5065;Presidente Kubitschek;MG
        4203;Presidente Lucena - RS;4315149;6023;Presidente Lucena;RS
        1805;Presidente Médici - MA;2109239;214;Presidente Médici;MA
        92;Presidente Médici - RO;1100254;19;Presidente Médici;RO
        2871;Presidente Nereu - SC;4214102;8277;Presidente Nereu;SC
        5735;Presidente Olegário - MG;3153400;5067;Presidente Olegário;MG
        4835;Presidente Prudente - SP;3541406;6929;Presidente Prudente;SP
        1806;Presidente Sarney - MA;2109270;216;Presidente Sarney;MA
        3770;Presidente Tancredo Neves - BA;2925758;3023;Presidente Tancredo Neves;BA
        1807;Presidente Vargas - MA;2109304;883;Presidente Vargas;MA
        4836;Presidente Venceslau - SP;3541505;6931;Presidente Venceslau;SP
        973;Primavera - PA;1506104;521;Primavera;PA
        1598;Primavera - PE;2611408;2527;Primavera;PE
        93;Primavera de Rondônia - RO;1101476;16;Primavera de Rondônia;RO
        836;Primavera do Leste - MT;5107040;9871;Primavera do Leste;MT
        1808;Primeira Cruz - MA;2109403;885;Primeira Cruz;MA
        3342;Primeiro de Maio - PR;4120507;7789;Primeiro de Maio;PR
        2872;Princesa - SC;4214151;934;Princesa;SC
        2345;Princesa Isabel - PB;2512309;2147;Princesa Isabel;PB
        2608;Professor Jamil - GO;5218391;51;Professor Jamil;GO
        4204;Progresso - RS;4315156;7361;Progresso;RS
        4837;Promissão - SP;3541604;6933;Promissão;SP
        224;Propriá - SE;2805703;3213;Propriá;SE
        4205;Protásio Alves - RS;4315172;7359;Protásio Alves;RS
        5736;Prudente de Morais - MG;3153608;5071;Prudente de Morais;MG
        3343;Prudentópolis - PR;4120606;7791;Prudentópolis;PR
        702;Pugmil - TO;1718451;94;Pugmil;TO
        1229;Pureza - RN;2410405;1807;Pureza;RN
        4206;Putinga - RS;4315206;8807;Putinga;RS
        2346;Puxinanã - PB;2512408;2149;Puxinanã;PB
        4838;Quadra - SP;3541653;816;Quadra;SP
        4207;Quaraí - RS;4315305;8809;Quaraí;RS
        5737;Quartel Geral - MG;3153707;5073;Quartel Geral;MG
        3344;Quarto Centenário - PR;4120655;876;Quarto Centenário;PR
        4839;Quatá - SP;3541703;6935;Quatá;SP
        3345;Quatiguá - PR;4120705;7793;Quatiguá;PR
        974;Quatipuru - PA;1506112;62;Quatipuru;PA
        460;Quatis - RJ;3304128;2923;Quatis;RJ
        3346;Quatro Barras - PR;4120804;7795;Quatro Barras;PR
        4208;Quatro Irmãos - RS;4315313;1162;Quatro Irmãos;RS
        3347;Quatro Pontes - PR;4120853;5535;Quatro Pontes;PR
        574;Quebrangulo - AL;2707602;2851;Quebrangulo;AL
        3348;Quedas do Iguaçu - PR;4120903;7955;Quedas do Iguaçu;PR
        2037;Queimada Nova - PI;2208650;2279;Queimada Nova;PI
        3771;Queimadas - BA;2925808;3817;Queimadas;BA
        2347;Queimadas - PB;2512507;2151;Queimadas;PB
        461;Queimados - RJ;3304144;2911;Queimados;RJ
        4840;Queiroz - SP;3541802;6937;Queiroz;SP
        4841;Queluz - SP;3541901;6939;Queluz;SP
        5738;Queluzito - MG;3153806;5075;Queluzito;MG
        837;Querência - MT;5107065;97;Querência;MT
        3349;Querência do Norte - PR;4121000;7797;Querência do Norte;PR
        4209;Quevedos - RS;4315321;5789;Quevedos;RS
        3772;Quijingue - BA;2925907;3819;Quijingue;BA
        2873;Quilombo - SC;4214201;8279;Quilombo;SC
        3350;Quinta do Sol - PR;4121109;7799;Quinta do Sol;PR
        4842;Quintana - SP;3542008;6941;Quintana;SP
        4210;Quinze de Novembro - RS;4315354;7357;Quinze de Novembro;RS
        1599;Quipapá - PE;2611507;2529;Quipapá;PE
        2609;Quirinópolis - GO;5218508;9563;Quirinópolis;GO
        462;Quissamã - RJ;3304151;6007;Quissamã;RJ
        3351;Quitandinha - PR;4121208;7801;Quitandinha;PR
        1432;Quiterianópolis - CE;2311264;9917;Quiterianópolis;CE
        2348;Quixaba - PB;2512606;2153;Quixaba;PB
        1600;Quixaba - PE;2611533;2637;Quixaba;PE
        3773;Quixabeira - BA;2925931;3025;Quixabeira;BA
        1433;Quixadá - CE;2311306;1527;Quixadá;CE
        1434;Quixelô - CE;2311355;9853;Quixelô;CE
        1435;Quixeramobim - CE;2311405;1529;Quixeramobim;CE
        1436;Quixeré - CE;2311504;1531;Quixeré;CE
        1230;Rafael Fernandes - RN;2410504;1809;Rafael Fernandes;RN
        1231;Rafael Godeiro - RN;2410603;1893;Rafael Godeiro;RN
        3774;Rafael Jambeiro - BA;2925956;3985;Rafael Jambeiro;BA
        4843;Rafard - SP;3542107;6943;Rafard;SP
        3352;Ramilândia - PR;4121257;5527;Ramilândia;PR
        4844;Rancharia - SP;3542206;6945;Rancharia;SP
        3353;Rancho Alegre - PR;4121307;7803;Rancho Alegre;PR
        3354;Rancho Alegre D'Oeste - PR;4121356;5513;Rancho Alegre D'Oeste;PR
        2874;Rancho Queimado - SC;4214300;8281;Rancho Queimado;SC
        1809;Raposa - MA;2109452;218;Raposa;MA
        5739;Raposos - MG;3153905;5077;Raposos;MG
        5740;Raul Soares - MG;3154002;5079;Raul Soares;MG
        3355;Realeza - PR;4121406;7805;Realeza;PR
        3356;Rebouças - PR;4121505;7807;Rebouças;PR
        1601;Recife - PE;2611606;2531;Recife;PE
        5741;Recreio - MG;3154101;5081;Recreio;MG
        703;Recursolândia - TO;1718501;357;Recursolândia;TO
        1437;Redenção - CE;2311603;1533;Redenção;CE
        975;Redenção - PA;1506138;567;Redenção;PA
        4845;Redenção da Serra - SP;3542305;6947;Redenção da Serra;SP
        2038;Redenção do Gurguéia - PI;2208700;1173;Redenção do Gurguéia;PI
        4211;Redentora - RS;4315404;8811;Redentora;RS
        5742;Reduto - MG;3154150;696;Reduto;MG
        2039;Regeneração - PI;2208809;1175;Regeneração;PI
        4846;Regente Feijó - SP;3542404;6949;Regente Feijó;SP
        4847;Reginópolis - SP;3542503;6951;Reginópolis;SP
        4848;Registro - SP;3542602;6953;Registro;SP
        4212;Relvado - RS;4315453;7355;Relvado;RS
        3775;Remanso - BA;2926004;3821;Remanso;BA
        2349;Remígio - PB;2512705;2155;Remígio;PB
        3357;Renascença - PR;4121604;7809;Renascença;PR
        1438;Reriutaba - CE;2311702;1535;Reriutaba;CE
        463;Resende - RJ;3304201;5883;Resende;RJ
        5743;Resende Costa - MG;3154200;5083;Resende Costa;MG
        3358;Reserva - PR;4121703;7811;Reserva;PR
        838;Reserva do Cabaçal - MT;5107156;9879;Reserva do Cabaçal;MT
        3359;Reserva do Iguaçu - PR;4121752;878;Reserva do Iguaçu;PR
        5744;Resplendor - MG;3154309;5085;Resplendor;MG
        5745;Ressaquinha - MG;3154408;5087;Ressaquinha;MG
        4849;Restinga - SP;3542701;6955;Restinga;SP
        4213;Restinga Sêca - RS;4315503;8813;Restinga Sêca;RS
        3776;Retirolândia - BA;2926103;3823;Retirolândia;BA
        1810;Riachão - MA;2109502;887;Riachão;MA
        2350;Riachão - PB;2512747;502;Riachão;PB
        3777;Riachão das Neves - BA;2926202;3825;Riachão das Neves;BA
        2351;Riachão do Bacamarte - PB;2512754;504;Riachão do Bacamarte;PB
        225;Riachão do Dantas - SE;2805802;3215;Riachão do Dantas;SE
        3778;Riachão do Jacuípe - BA;2926301;3827;Riachão do Jacuípe;BA
        2352;Riachão do Poço - PB;2512762;506;Riachão do Poço;PB
        5746;Riachinho - MG;3154457;2901;Riachinho;MG
        704;Riachinho - TO;1718550;193;Riachinho;TO
        1232;Riacho da Cruz - RN;2410702;1811;Riacho da Cruz;RN
        1602;Riacho das Almas - PE;2611705;2533;Riacho das Almas;PE
        3779;Riacho de Santana - BA;2926400;3829;Riacho de Santana;BA
        1233;Riacho de Santana - RN;2410801;1813;Riacho de Santana;RN
        2353;Riacho de Santo Antônio - PB;2512788;508;Riacho de Santo Antônio;PB
        2354;Riacho dos Cavalos - PB;2512804;2157;Riacho dos Cavalos;PB
        5747;Riacho dos Machados - MG;3154507;5089;Riacho dos Machados;MG
        2040;Riacho Frio - PI;2208858;374;Riacho Frio;PI
        1234;Riachuelo - RN;2410900;1815;Riachuelo;RN
        226;Riachuelo - SE;2805901;3217;Riachuelo;SE
        2610;Rialma - GO;5218607;9565;Rialma;GO
        2611;Rianápolis - GO;5218706;9567;Rianápolis;GO
        1811;Ribamar Fiquene - MA;2109551;220;Ribamar Fiquene;MA
        385;Ribas do Rio Pardo - MS;5007109;9141;Ribas do Rio Pardo;MS
        4850;Ribeira - SP;3542800;6957;Ribeira;SP
        3780;Ribeira do Amparo - BA;2926509;3831;Ribeira do Amparo;BA
        2041;Ribeira do Piauí - PI;2208874;376;Ribeira do Piauí;PI
        3781;Ribeira do Pombal - BA;2926608;3833;Ribeira do Pombal;BA
        1603;Ribeirão - PE;2611804;2535;Ribeirão;PE
        4851;Ribeirão Bonito - SP;3542909;6959;Ribeirão Bonito;SP
        4852;Ribeirão Branco - SP;3543006;6961;Ribeirão Branco;SP
        839;Ribeirão Cascalheira - MT;5107180;9741;Ribeirão Cascalheira;MT
        3360;Ribeirão Claro - PR;4121802;7813;Ribeirão Claro;PR
        4853;Ribeirão Corrente - SP;3543105;6963;Ribeirão Corrente;SP
        5748;Ribeirão das Neves - MG;3154606;5091;Ribeirão das Neves;MG
        3782;Ribeirão do Largo - BA;2926657;3027;Ribeirão do Largo;BA
        3361;Ribeirão do Pinhal - PR;4121901;7815;Ribeirão do Pinhal;PR
        4854;Ribeirão do Sul - SP;3543204;6965;Ribeirão do Sul;SP
        4855;Ribeirão dos Índios - SP;3543238;818;Ribeirão dos Índios;SP
        4856;Ribeirão Grande - SP;3543253;3057;Ribeirão Grande;SP
        4857;Ribeirão Pires - SP;3543303;6967;Ribeirão Pires;SP
        4858;Ribeirão Preto - SP;3543402;6969;Ribeirão Preto;SP
        5749;Ribeirão Vermelho - MG;3154705;5093;Ribeirão Vermelho;MG
        840;Ribeirãozinho - MT;5107198;99;Ribeirãozinho;MT
        2042;Ribeiro Gonçalves - PI;2208908;1177;Ribeiro Gonçalves;PI
        227;Ribeirópolis - SE;2806008;3219;Ribeirópolis;SE
        4859;Rifaina - SP;3543600;6973;Rifaina;SP
        4860;Rincão - SP;3543709;6975;Rincão;SP
        4861;Rinópolis - SP;3543808;6977;Rinópolis;SP
        5750;Rio Acima - MG;3154804;5095;Rio Acima;MG
        3362;Rio Azul - PR;4122008;7817;Rio Azul;PR
        303;Rio Bananal - ES;3204351;5711;Rio Bananal;ES
        3363;Rio Bom - PR;4122107;7819;Rio Bom;PR
        464;Rio Bonito - RJ;3304300;5885;Rio Bonito;RJ
        3364;Rio Bonito do Iguaçu - PR;4122156;5481;Rio Bonito do Iguaçu;PR
        48;Rio Branco - AC;1200401;139;Rio Branco;AC
        841;Rio Branco - MT;5107206;8995;Rio Branco;MT
        3365;Rio Branco do Ivaí - PR;4122172;880;Rio Branco do Ivaí;PR
        3366;Rio Branco do Sul - PR;4122206;7821;Rio Branco do Sul;PR
        386;Rio Brilhante - MS;5007208;9143;Rio Brilhante;MS
        5751;Rio Casca - MG;3154903;5097;Rio Casca;MG
        465;Rio Claro - RJ;3304409;5887;Rio Claro;RJ
        4862;Rio Claro - SP;3543907;6979;Rio Claro;SP
        94;Rio Crespo - RO;1100262;687;Rio Crespo;RO
        705;Rio da Conceição - TO;1718659;323;Rio da Conceição;TO
        2875;Rio das Antas - SC;4214409;8283;Rio das Antas;SC
        466;Rio das Flores - RJ;3304508;5889;Rio das Flores;RJ
        467;Rio das Ostras - RJ;3304524;2921;Rio das Ostras;RJ
        4863;Rio das Pedras - SP;3544004;6981;Rio das Pedras;SP
        3783;Rio de Contas - BA;2926707;3835;Rio de Contas;BA
        468;Rio de Janeiro - RJ;3304557;6001;Rio de Janeiro;RJ
        3784;Rio do Antônio - BA;2926806;3837;Rio do Antônio;BA
        2876;Rio do Campo - SC;4214508;8285;Rio do Campo;SC
        1235;Rio do Fogo - RN;2408953;422;Rio do Fogo;RN
        2877;Rio do Oeste - SC;4214607;8287;Rio do Oeste;SC
        3785;Rio do Pires - BA;2926905;3839;Rio do Pires;BA
        5752;Rio do Prado - MG;3155108;5101;Rio do Prado;MG
        2878;Rio do Sul - SC;4214805;8291;Rio do Sul;SC
        5753;Rio Doce - MG;3155009;5099;Rio Doce;MG
        706;Rio dos Bois - TO;1718709;359;Rio dos Bois;TO
        2879;Rio dos Cedros - SC;4214706;8289;Rio dos Cedros;SC
        4214;Rio dos Índios - RS;4315552;5955;Rio dos Índios;RS
        5754;Rio Espera - MG;3155207;5103;Rio Espera;MG
        1604;Rio Formoso - PE;2611903;2537;Rio Formoso;PE
        2880;Rio Fortuna - SC;4214904;8293;Rio Fortuna;SC
        4215;Rio Grande - RS;4315602;8815;Rio Grande;RS
        4864;Rio Grande da Serra - SP;3544103;6983;Rio Grande da Serra;SP
        2043;Rio Grande do Piauí - PI;2209005;1179;Rio Grande do Piauí;PI
        575;Rio Largo - AL;2707701;2853;Rio Largo;AL
        5755;Rio Manso - MG;3155306;5105;Rio Manso;MG
        976;Rio Maria - PA;1506161;569;Rio Maria;PA
        2881;Rio Negrinho - SC;4215000;8295;Rio Negrinho;SC
        387;Rio Negro - MS;5007307;9145;Rio Negro;MS
        3367;Rio Negro - PR;4122305;7823;Rio Negro;PR
        5756;Rio Novo - MG;3155405;5107;Rio Novo;MG
        304;Rio Novo do Sul - ES;3204401;5687;Rio Novo do Sul;ES
        5757;Rio Paranaíba - MG;3155504;5109;Rio Paranaíba;MG
        4216;Rio Pardo - RS;4315701;8817;Rio Pardo;RS
        5758;Rio Pardo de Minas - MG;3155603;5111;Rio Pardo de Minas;MG
        5759;Rio Piracicaba - MG;3155702;5113;Rio Piracicaba;MG
        5760;Rio Pomba - MG;3155801;5115;Rio Pomba;MG
        5761;Rio Preto - MG;3155900;5117;Rio Preto;MG
        155;Rio Preto da Eva - AM;1303569;9843;Rio Preto da Eva;AM
        2612;Rio Quente - GO;5218789;9995;Rio Quente;GO
        3786;Rio Real - BA;2927002;3841;Rio Real;BA
        2882;Rio Rufino - SC;4215059;5571;Rio Rufino;SC
        707;Rio Sono - TO;1718758;9679;Rio Sono;TO
        2355;Rio Tinto - PB;2512903;2159;Rio Tinto;PB
        2613;Rio Verde - GO;5218805;9571;Rio Verde;GO
        388;Rio Verde de Mato Grosso - MS;5007406;9147;Rio Verde de Mato Grosso;MS
        5762;Rio Vermelho - MG;3156007;5119;Rio Vermelho;MG
        4865;Riolândia - SP;3544202;6985;Riolândia;SP
        4217;Riozinho - RS;4315750;7353;Riozinho;RS
        2883;Riqueza - SC;4215075;5749;Riqueza;SC
        5763;Ritápolis - MG;3156106;5121;Ritápolis;MG
        4866;Riversul - SP;3543501;6971;Riversul;SP
        4218;Roca Sales - RS;4315800;8819;Roca Sales;RS
        389;Rochedo - MS;5007505;9149;Rochedo;MS
        5764;Rochedo de Minas - MG;3156205;5123;Rochedo de Minas;MG
        2884;Rodeio - SC;4215109;8297;Rodeio;SC
        4219;Rodeio Bonito - RS;4315909;8821;Rodeio Bonito;RS
        5765;Rodeiro - MG;3156304;5125;Rodeiro;MG
        3787;Rodelas - BA;2927101;3843;Rodelas;BA
        1236;Rodolfo Fernandes - RN;2411007;1817;Rodolfo Fernandes;RN
        49;Rodrigues Alves - AC;1200427;659;Rodrigues Alves;AC
        4220;Rolador - RS;4315958;1164;Rolador;RS
        3368;Rolândia - PR;4122404;7825;Rolândia;PR
        4221;Rolante - RS;4316006;8823;Rolante;RS
        95;Rolim de Moura - RO;1100288;29;Rolim de Moura;RO
        5766;Romaria - MG;3156403;5127;Romaria;MG
        2885;Romelândia - SC;4215208;8299;Romelândia;SC
        3369;Roncador - PR;4122503;7827;Roncador;PR
        4222;Ronda Alta - RS;4316105;8825;Ronda Alta;RS
        4223;Rondinha - RS;4316204;8827;Rondinha;RS
        842;Rondolândia - MT;5107578;1092;Rondolândia;MT
        3370;Rondon - PR;4122602;7829;Rondon;PR
        977;Rondon do Pará - PA;1506187;573;Rondon do Pará;PA
        843;Rondonópolis - MT;5107602;9151;Rondonópolis;MT
        4224;Roque Gonzales - RS;4316303;8829;Roque Gonzales;RS
        13;Rorainópolis - RR;1400472;36;Rorainópolis;RR
        4867;Rosana - SP;3544251;7265;Rosana;SP
        1812;Rosário - MA;2109601;891;Rosário;MA
        5767;Rosário da Limeira - MG;3156452;698;Rosário da Limeira;MG
        228;Rosário do Catete - SE;2806107;3221;Rosário do Catete;SE
        3371;Rosário do Ivaí - PR;4122651;8473;Rosário do Ivaí;PR
        4225;Rosário do Sul - RS;4316402;8831;Rosário do Sul;RS
        844;Rosário Oeste - MT;5107701;9153;Rosário Oeste;MT
        4868;Roseira - SP;3544301;6987;Roseira;SP
        576;Roteiro - AL;2707800;2855;Roteiro;AL
        5768;Rubelita - MG;3156502;5129;Rubelita;MG
        4869;Rubiácea - SP;3544400;6989;Rubiácea;SP
        2614;Rubiataba - GO;5218904;9573;Rubiataba;GO
        5769;Rubim - MG;3156601;5131;Rubim;MG
        4870;Rubinéia - SP;3544509;6991;Rubinéia;SP
        978;Rurópolis - PA;1506195;597;Rurópolis;PA
        1439;Russas - CE;2311801;1537;Russas;CE
        3788;Ruy Barbosa - BA;2927200;3845;Ruy Barbosa;BA
        1237;Ruy Barbosa - RN;2411106;1819;Ruy Barbosa;RN
        5770;Sabará - MG;3156700;5133;Sabará;MG
        3372;Sabáudia - PR;4122701;7831;Sabáudia;PR
        4871;Sabino - SP;3544608;6993;Sabino;SP
        5771;Sabinópolis - MG;3156809;5135;Sabinópolis;MG
        1440;Saboeiro - CE;2311900;1539;Saboeiro;CE
        5772;Sacramento - MG;3156908;5137;Sacramento;MG
        4226;Sagrada Família - RS;4316428;5987;Sagrada Família;RS
        4872;Sagres - SP;3544707;6995;Sagres;SP
        1605;Sairé - PE;2612000;2539;Sairé;PE
        4227;Saldanha Marinho - RS;4316436;7339;Saldanha Marinho;RS
        4873;Sales - SP;3544806;6997;Sales;SP
        4874;Sales Oliveira - SP;3544905;6999;Sales Oliveira;SP
        4875;Salesópolis - SP;3545001;7001;Salesópolis;SP
        2886;Salete - SC;4215307;8301;Salete;SC
        2356;Salgadinho - PB;2513000;2161;Salgadinho;PB
        1606;Salgadinho - PE;2612109;2541;Salgadinho;PE
        229;Salgado - SE;2806206;3223;Salgado;SE
        2357;Salgado de São Félix - PB;2513109;2163;Salgado de São Félix;PB
        3373;Salgado Filho - PR;4122800;7833;Salgado Filho;PR
        1607;Salgueiro - PE;2612208;2543;Salgueiro;PE
        5773;Salinas - MG;3157005;5139;Salinas;MG
        3789;Salinas da Margarida - BA;2927309;3847;Salinas da Margarida;BA
        979;Salinópolis - PA;1506203;523;Salinópolis;PA
        1441;Salitre - CE;2311959;1273;Salitre;CE
        4876;Salmourão - SP;3545100;7003;Salmourão;SP
        1608;Saloá - PE;2612307;2545;Saloá;PE
        2887;Saltinho - SC;4215356;936;Saltinho;SC
        4877;Saltinho - SP;3545159;5445;Saltinho;SP
        4878;Salto - SP;3545209;7005;Salto;SP
        5774;Salto da Divisa - MG;3157104;5141;Salto da Divisa;MG
        4879;Salto de Pirapora - SP;3545308;7007;Salto de Pirapora;SP
        845;Salto do Céu - MT;5107750;8997;Salto do Céu;MT
        3374;Salto do Itararé - PR;4122909;7835;Salto do Itararé;PR
        4228;Salto do Jacuí - RS;4316451;8975;Salto do Jacuí;RS
        3375;Salto do Lontra - PR;4123006;7837;Salto do Lontra;PR
        4880;Salto Grande - SP;3545407;7009;Salto Grande;SP
        2888;Salto Veloso - SC;4215406;8303;Salto Veloso;SC
        3790;Salvador - BA;2927408;3849;Salvador;BA
        4229;Salvador das Missões - RS;4316477;6061;Salvador das Missões;RS
        4230;Salvador do Sul - RS;4316501;8833;Salvador do Sul;RS
        980;Salvaterra - PA;1506302;525;Salvaterra;PA
        1813;Sambaíba - MA;2109700;893;Sambaíba;MA
        708;Sampaio - TO;1718808;9727;Sampaio;TO
        4231;Sananduva - RS;4316600;8835;Sananduva;RS
        2615;Sanclerlândia - GO;5219001;9575;Sanclerlândia;GO
        709;Sandolândia - TO;1718840;331;Sandolândia;TO
        4881;Sandovalina - SP;3545506;7011;Sandovalina;SP
        2889;Sangão - SC;4215455;5547;Sangão;SC
        1609;Sanharó - PE;2612406;2547;Sanharó;PE
        4882;Santa Adélia - SP;3545605;7013;Santa Adélia;SP
        4883;Santa Albertina - SP;3545704;7015;Santa Albertina;SP
        3376;Santa Amélia - PR;4123105;7839;Santa Amélia;PR
        3791;Santa Bárbara - BA;2927507;3851;Santa Bárbara;BA
        5775;Santa Bárbara - MG;3157203;5143;Santa Bárbara;MG
        2616;Santa Bárbara de Goiás - GO;5219100;9577;Santa Bárbara de Goiás;GO
        5776;Santa Bárbara do Leste - MG;3157252;2667;Santa Bárbara do Leste;MG
        5777;Santa Bárbara do Monte Verde - MG;3157278;700;Santa Bárbara do Monte Verde;MG
        981;Santa Bárbara do Pará - PA;1506351;369;Santa Bárbara do Pará;PA
        4232;Santa Bárbara do Sul - RS;4316709;8837;Santa Bárbara do Sul;RS
        5778;Santa Bárbara do Tugúrio - MG;3157302;5145;Santa Bárbara do Tugúrio;MG
        4884;Santa Bárbara d'Oeste - SP;3545803;7017;Santa Bárbara d'Oeste;SP
        4885;Santa Branca - SP;3546009;7021;Santa Branca;SP
        3792;Santa Brígida - BA;2927606;3853;Santa Brígida;BA
        846;Santa Carmem - MT;5107248;123;Santa Carmem;MT
        2358;Santa Cecília - PB;2513158;510;Santa Cecília;PB
        2890;Santa Cecília - SC;4215505;8305;Santa Cecília;SC
        3377;Santa Cecília do Pavão - PR;4123204;7841;Santa Cecília do Pavão;PR
        4233;Santa Cecília do Sul - RS;4316733;1166;Santa Cecília do Sul;RS
        4234;Santa Clara do Sul - RS;4316758;6033;Santa Clara do Sul;RS
        4886;Santa Clara d'Oeste - SP;3546108;7023;Santa Clara d'Oeste;SP
        2359;Santa Cruz - PB;2513208;2165;Santa Cruz;PB
        1610;Santa Cruz - PE;2612455;2297;Santa Cruz;PE
        1238;Santa Cruz - RN;2411205;1823;Santa Cruz;RN
        3793;Santa Cruz Cabrália - BA;2927705;3855;Santa Cruz Cabrália;BA
        1611;Santa Cruz da Baixa Verde - PE;2612471;2639;Santa Cruz da Baixa Verde;PE
        4887;Santa Cruz da Conceição - SP;3546207;7025;Santa Cruz da Conceição;SP
        4888;Santa Cruz da Esperança - SP;3546256;820;Santa Cruz da Esperança;SP
        3794;Santa Cruz da Vitória - BA;2927804;3857;Santa Cruz da Vitória;BA
        4889;Santa Cruz das Palmeiras - SP;3546306;7027;Santa Cruz das Palmeiras;SP
        2617;Santa Cruz de Goiás - GO;5219209;9579;Santa Cruz de Goiás;GO
        5779;Santa Cruz de Minas - MG;3157336;702;Santa Cruz de Minas;MG
        3378;Santa Cruz de Monte Castelo - PR;4123303;7843;Santa Cruz de Monte Castelo;PR
        5780;Santa Cruz de Salinas - MG;3157377;704;Santa Cruz de Salinas;MG
        982;Santa Cruz do Arari - PA;1506401;527;Santa Cruz do Arari;PA
        1612;Santa Cruz do Capibaribe - PE;2612505;2549;Santa Cruz do Capibaribe;PE
        5781;Santa Cruz do Escalvado - MG;3157401;5147;Santa Cruz do Escalvado;MG
        2044;Santa Cruz do Piauí - PI;2209104;1181;Santa Cruz do Piauí;PI
        4890;Santa Cruz do Rio Pardo - SP;3546405;7029;Santa Cruz do Rio Pardo;SP
        4235;Santa Cruz do Sul - RS;4316808;8839;Santa Cruz do Sul;RS
        847;Santa Cruz do Xingu - MT;5107743;1094;Santa Cruz do Xingu;MT
        2045;Santa Cruz dos Milagres - PI;2209153;1295;Santa Cruz dos Milagres;PI
        5782;Santa Efigênia de Minas - MG;3157500;5149;Santa Efigênia de Minas;MG
        4891;Santa Ernestina - SP;3546504;7031;Santa Ernestina;SP
        3379;Santa Fé - PR;4123402;7845;Santa Fé;PR
        2618;Santa Fé de Goiás - GO;5219258;9743;Santa Fé de Goiás;GO
        5783;Santa Fé de Minas - MG;3157609;5151;Santa Fé de Minas;MG
        710;Santa Fé do Araguaia - TO;1718865;195;Santa Fé do Araguaia;TO
        4892;Santa Fé do Sul - SP;3546603;7033;Santa Fé do Sul;SP
        1613;Santa Filomena - PE;2612554;556;Santa Filomena;PE
        2046;Santa Filomena - PI;2209203;1183;Santa Filomena;PI
        1814;Santa Filomena do Maranhão - MA;2109759;222;Santa Filomena do Maranhão;MA
        4893;Santa Gertrudes - SP;3546702;7035;Santa Gertrudes;SP
        1815;Santa Helena - MA;2109809;895;Santa Helena;MA
        2360;Santa Helena - PB;2513307;2167;Santa Helena;PB
        3380;Santa Helena - PR;4123501;7971;Santa Helena;PR
        2891;Santa Helena - SC;4215554;5751;Santa Helena;SC
        2619;Santa Helena de Goiás - GO;5219308;9581;Santa Helena de Goiás;GO
        5784;Santa Helena de Minas - MG;3157658;706;Santa Helena de Minas;MG
        3795;Santa Inês - BA;2927903;3859;Santa Inês;BA
        1816;Santa Inês - MA;2109908;957;Santa Inês;MA
        2361;Santa Inês - PB;2513356;512;Santa Inês;PB
        3381;Santa Inês - PR;4123600;7847;Santa Inês;PR
        2620;Santa Isabel - GO;5219357;9689;Santa Isabel;GO
        4894;Santa Isabel - SP;3546801;7037;Santa Isabel;SP
        3382;Santa Isabel do Ivaí - PR;4123709;7849;Santa Isabel do Ivaí;PR
        156;Santa Isabel do Rio Negro - AM;1303601;237;Santa Isabel do Rio Negro;AM
        3383;Santa Izabel do Oeste - PR;4123808;7851;Santa Izabel do Oeste;PR
        983;Santa Izabel do Pará - PA;1506500;529;Santa Izabel do Pará;PA
        5785;Santa Juliana - MG;3157708;5153;Santa Juliana;MG
        305;Santa Leopoldina - ES;3204500;5689;Santa Leopoldina;ES
        3384;Santa Lúcia - PR;4123824;5469;Santa Lúcia;PR
        4895;Santa Lúcia - SP;3546900;7039;Santa Lúcia;SP
        2047;Santa Luz - PI;2209302;1185;Santa Luz;PI
        3796;Santa Luzia - BA;2928059;3987;Santa Luzia;BA
        1817;Santa Luzia - MA;2110005;897;Santa Luzia;MA
        5786;Santa Luzia - MG;3157807;5155;Santa Luzia;MG
        2362;Santa Luzia - PB;2513406;2169;Santa Luzia;PB
        230;Santa Luzia do Itanhy - SE;2806305;3225;Santa Luzia do Itanhy;SE
        577;Santa Luzia do Norte - AL;2707909;2857;Santa Luzia do Norte;AL
        984;Santa Luzia do Pará - PA;1506559;371;Santa Luzia do Pará;PA
        1818;Santa Luzia do Paruá - MA;2110039;1285;Santa Luzia do Paruá;MA
        96;Santa Luzia D'Oeste - RO;1100296;43;Santa Luzia D'Oeste;RO
        5787;Santa Margarida - MG;3157906;5157;Santa Margarida;MG
        4236;Santa Margarida do Sul - RS;4316972;1168;Santa Margarida do Sul;RS
        1239;Santa Maria - RN;2409332;424;Santa Maria;RN
        4237;Santa Maria - RS;4316907;8841;Santa Maria;RS
        1614;Santa Maria da Boa Vista - PE;2612604;2551;Santa Maria da Boa Vista;PE
        4896;Santa Maria da Serra - SP;3547007;7041;Santa Maria da Serra;SP
        3797;Santa Maria da Vitória - BA;2928109;3863;Santa Maria da Vitória;BA
        985;Santa Maria das Barreiras - PA;1506583;599;Santa Maria das Barreiras;PA
        5788;Santa Maria de Itabira - MG;3158003;5159;Santa Maria de Itabira;MG
        306;Santa Maria de Jetibá - ES;3204559;5725;Santa Maria de Jetibá;ES
        1615;Santa Maria do Cambucá - PE;2612703;2553;Santa Maria do Cambucá;PE
        4238;Santa Maria do Herval - RS;4316956;7337;Santa Maria do Herval;RS
        3385;Santa Maria do Oeste - PR;4123857;5505;Santa Maria do Oeste;PR
        986;Santa Maria do Pará - PA;1506609;531;Santa Maria do Pará;PA
        5789;Santa Maria do Salto - MG;3158102;5161;Santa Maria do Salto;MG
        5790;Santa Maria do Suaçuí - MG;3158201;5163;Santa Maria do Suaçuí;MG
        711;Santa Maria do Tocantins - TO;1718881;361;Santa Maria do Tocantins;TO
        469;Santa Maria Madalena - RJ;3304607;5891;Santa Maria Madalena;RJ
        3386;Santa Mariana - PR;4123907;7853;Santa Mariana;PR
        4897;Santa Mercedes - SP;3547106;7043;Santa Mercedes;SP
        3387;Santa Mônica - PR;4123956;5519;Santa Mônica;PR
        1442;Santa Quitéria - CE;2312205;1545;Santa Quitéria;CE
        1819;Santa Quitéria do Maranhão - MA;2110104;899;Santa Quitéria do Maranhão;MA
        1820;Santa Rita - MA;2110203;901;Santa Rita;MA
        2363;Santa Rita - PB;2513703;2175;Santa Rita;PB
        5791;Santa Rita de Caldas - MG;3159209;5183;Santa Rita de Caldas;MG
        3798;Santa Rita de Cássia - BA;2928406;3549;Santa Rita de Cássia;BA
        5792;Santa Rita de Ibitipoca - MG;3159407;5187;Santa Rita de Ibitipoca;MG
        5793;Santa Rita de Jacutinga - MG;3159308;5185;Santa Rita de Jacutinga;MG
        5794;Santa Rita de Minas - MG;3159357;2669;Santa Rita de Minas;MG
        2621;Santa Rita do Araguaia - GO;5219407;9583;Santa Rita do Araguaia;GO
        5795;Santa Rita do Itueto - MG;3159506;5189;Santa Rita do Itueto;MG
        2622;Santa Rita do Novo Destino - GO;5219456;1062;Santa Rita do Novo Destino;GO
        390;Santa Rita do Pardo - MS;5007554;9745;Santa Rita do Pardo;MS
        4898;Santa Rita do Passa Quatro - SP;3547502;7051;Santa Rita do Passa Quatro;SP
        5796;Santa Rita do Sapucaí - MG;3159605;5191;Santa Rita do Sapucaí;MG
        712;Santa Rita do Tocantins - TO;1718899;96;Santa Rita do Tocantins;TO
        848;Santa Rita do Trivelato - MT;5107768;1096;Santa Rita do Trivelato;MT
        4899;Santa Rita d'Oeste - SP;3547403;7049;Santa Rita d'Oeste;SP
        4239;Santa Rosa - RS;4317202;8847;Santa Rosa;RS
        5797;Santa Rosa da Serra - MG;3159704;5193;Santa Rosa da Serra;MG
        2623;Santa Rosa de Goiás - GO;5219506;9585;Santa Rosa de Goiás;GO
        2892;Santa Rosa de Lima - SC;4215604;8307;Santa Rosa de Lima;SC
        231;Santa Rosa de Lima - SE;2806503;3229;Santa Rosa de Lima;SE
        4900;Santa Rosa de Viterbo - SP;3547601;7053;Santa Rosa de Viterbo;SP
        2048;Santa Rosa do Piauí - PI;2209377;2261;Santa Rosa do Piauí;PI
        50;Santa Rosa do Purus - AC;1200435;661;Santa Rosa do Purus;AC
        2893;Santa Rosa do Sul - SC;4215653;9967;Santa Rosa do Sul;SC
        713;Santa Rosa do Tocantins - TO;1718907;9729;Santa Rosa do Tocantins;TO
        4901;Santa Salete - SP;3547650;822;Santa Salete;SP
        307;Santa Teresa - ES;3204609;5691;Santa Teresa;ES
        2364;Santa Teresinha - PB;2513802;2177;Santa Teresinha;PB
        4240;Santa Tereza - RS;4317251;5995;Santa Tereza;RS
        2624;Santa Tereza de Goiás - GO;5219605;9587;Santa Tereza de Goiás;GO
        3388;Santa Tereza do Oeste - PR;4124020;9969;Santa Tereza do Oeste;PR
        714;Santa Tereza do Tocantins - TO;1719004;9731;Santa Tereza do Tocantins;TO
        3799;Santa Terezinha - BA;2928505;3869;Santa Terezinha;BA
        849;Santa Terezinha - MT;5107776;9197;Santa Terezinha;MT
        1616;Santa Terezinha - PE;2612802;2555;Santa Terezinha;PE
        2894;Santa Terezinha - SC;4215679;5555;Santa Terezinha;SC
        2625;Santa Terezinha de Goiás - GO;5219704;9589;Santa Terezinha de Goiás;GO
        3389;Santa Terezinha de Itaipu - PR;4124053;8467;Santa Terezinha de Itaipu;PR
        2895;Santa Terezinha do Progresso - SC;4215687;938;Santa Terezinha do Progresso;SC
        715;Santa Terezinha do Tocantins - TO;1720002;98;Santa Terezinha do Tocantins;TO
        5798;Santa Vitória - MG;3159803;5195;Santa Vitória;MG
        4241;Santa Vitória do Palmar - RS;4317301;8849;Santa Vitória do Palmar;RS
        3800;Santaluz - BA;2928000;3861;Santaluz;BA
        29;Santana - AP;1600600;615;Santana;AP
        3801;Santana - BA;2928208;3865;Santana;BA
        4242;Santana da Boa Vista - RS;4317004;8843;Santana da Boa Vista;RS
        4902;Santana da Ponte Pensa - SP;3547205;7045;Santana da Ponte Pensa;SP
        5799;Santana da Vargem - MG;3158300;5165;Santana da Vargem;MG
        5800;Santana de Cataguases - MG;3158409;5167;Santana de Cataguases;MG
        2365;Santana de Mangueira - PB;2513505;2171;Santana de Mangueira;PB
        4903;Santana de Parnaíba - SP;3547304;7047;Santana de Parnaíba;SP
        5801;Santana de Pirapama - MG;3158508;5169;Santana de Pirapama;MG
        1443;Santana do Acaraú - CE;2312007;1541;Santana do Acaraú;CE
        987;Santana do Araguaia - PA;1506708;533;Santana do Araguaia;PA
        1444;Santana do Cariri - CE;2312106;1543;Santana do Cariri;CE
        5802;Santana do Deserto - MG;3158607;5171;Santana do Deserto;MG
        5803;Santana do Garambéu - MG;3158706;5173;Santana do Garambéu;MG
        578;Santana do Ipanema - AL;2708006;2859;Santana do Ipanema;AL
        3390;Santana do Itararé - PR;4124004;7855;Santana do Itararé;PR
        5804;Santana do Jacaré - MG;3158805;5175;Santana do Jacaré;MG
        4243;Sant'Ana do Livramento - RS;4317103;8845;Sant'Ana do Livramento;RS
        5805;Santana do Manhuaçu - MG;3158904;5177;Santana do Manhuaçu;MG
        1821;Santana do Maranhão - MA;2110237;224;Santana do Maranhão;MA
        1240;Santana do Matos - RN;2411403;1827;Santana do Matos;RN
        579;Santana do Mundaú - AL;2708105;2861;Santana do Mundaú;AL
        5806;Santana do Paraíso - MG;3158953;2673;Santana do Paraíso;MG
        2049;Santana do Piauí - PI;2209351;2281;Santana do Piauí;PI
        5807;Santana do Riacho - MG;3159001;5179;Santana do Riacho;MG
        232;Santana do São Francisco - SE;2806404;2647;Santana do São Francisco;SE
        1241;Santana do Seridó - RN;2411429;1825;Santana do Seridó;RN
        2366;Santana dos Garrotes - PB;2513604;2173;Santana dos Garrotes;PB
        5808;Santana dos Montes - MG;3159100;5181;Santana dos Montes;MG
        3802;Santanópolis - BA;2928307;3867;Santanópolis;BA
        988;Santarém - PA;1506807;535;Santarém;PA
        989;Santarém Novo - PA;1506906;537;Santarém Novo;PA
        4244;Santiago - RS;4317400;8851;Santiago;RS
        2896;Santiago do Sul - SC;4215695;940;Santiago do Sul;SC
        850;Santo Afonso - MT;5107263;115;Santo Afonso;MT
        3803;Santo Amaro - BA;2928604;3871;Santo Amaro;BA
        2897;Santo Amaro da Imperatriz - SC;4215703;8309;Santo Amaro da Imperatriz;SC
        233;Santo Amaro das Brotas - SE;2806602;3231;Santo Amaro das Brotas;SE
        1822;Santo Amaro do Maranhão - MA;2110278;226;Santo Amaro do Maranhão;MA
        4904;Santo Anastácio - SP;3547700;7055;Santo Anastácio;SP
        2368;Santo André - PB;2513851;516;Santo André;PB
        4905;Santo André - SP;3547809;7057;Santo André;SP
        4245;Santo Ângelo - RS;4317509;8853;Santo Ângelo;RS
        1242;Santo Antônio - RN;2411502;1829;Santo Antônio;RN
        4906;Santo Antônio da Alegria - SP;3547908;7059;Santo Antônio da Alegria;SP
        2626;Santo Antônio da Barra - GO;5219712;83;Santo Antônio da Barra;GO
        4246;Santo Antônio da Patrulha - RS;4317608;8855;Santo Antônio da Patrulha;RS
        3391;Santo Antônio da Platina - PR;4124103;7859;Santo Antônio da Platina;PR
        4247;Santo Antônio das Missões - RS;4317707;8857;Santo Antônio das Missões;RS
        2627;Santo Antônio de Goiás - GO;5219738;53;Santo Antônio de Goiás;GO
        3804;Santo Antônio de Jesus - BA;2928703;3873;Santo Antônio de Jesus;BA
        2050;Santo Antônio de Lisboa - PI;2209401;1187;Santo Antônio de Lisboa;PI
        470;Santo Antônio de Pádua - RJ;3304706;5893;Santo Antônio de Pádua;RJ
        4907;Santo Antônio de Posse - SP;3548005;7061;Santo Antônio de Posse;SP
        5809;Santo Antônio do Amparo - MG;3159902;5197;Santo Antônio do Amparo;MG
        4908;Santo Antônio do Aracanguá - SP;3548054;2939;Santo Antônio do Aracanguá;SP
        5810;Santo Antônio do Aventureiro - MG;3160009;5199;Santo Antônio do Aventureiro;MG
        3392;Santo Antônio do Caiuá - PR;4124202;7861;Santo Antônio do Caiuá;PR
        2628;Santo Antônio do Descoberto - GO;5219753;9677;Santo Antônio do Descoberto;GO
        5811;Santo Antônio do Grama - MG;3160108;5201;Santo Antônio do Grama;MG
        157;Santo Antônio do Içá - AM;1303700;273;Santo Antônio do Içá;AM
        5812;Santo Antônio do Itambé - MG;3160207;5203;Santo Antônio do Itambé;MG
        5813;Santo Antônio do Jacinto - MG;3160306;5205;Santo Antônio do Jacinto;MG
        4909;Santo Antônio do Jardim - SP;3548104;7063;Santo Antônio do Jardim;SP
        851;Santo Antônio do Leste - MT;5107792;1098;Santo Antônio do Leste;MT
        852;Santo Antônio do Leverger - MT;5107800;9155;Santo Antônio do Leverger;MT
        5814;Santo Antônio do Monte - MG;3160405;5207;Santo Antônio do Monte;MG
        4248;Santo Antônio do Palma - RS;4317558;5941;Santo Antônio do Palma;RS
        3393;Santo Antônio do Paraíso - PR;4124301;7863;Santo Antônio do Paraíso;PR
        4910;Santo Antônio do Pinhal - SP;3548203;7065;Santo Antônio do Pinhal;SP
        4249;Santo Antônio do Planalto - RS;4317756;5957;Santo Antônio do Planalto;RS
        5815;Santo Antônio do Retiro - MG;3160454;708;Santo Antônio do Retiro;MG
        5816;Santo Antônio do Rio Abaixo - MG;3160504;5209;Santo Antônio do Rio Abaixo;MG
        3394;Santo Antônio do Sudoeste - PR;4124400;7857;Santo Antônio do Sudoeste;PR
        990;Santo Antônio do Tauá - PA;1507003;539;Santo Antônio do Tauá;PA
        1823;Santo Antônio dos Lopes - MA;2110302;903;Santo Antônio dos Lopes;MA
        2051;Santo Antônio dos Milagres - PI;2209450;378;Santo Antônio dos Milagres;PI
        4250;Santo Augusto - RS;4317806;8859;Santo Augusto;RS
        4251;Santo Cristo - RS;4317905;8861;Santo Cristo;RS
        3805;Santo Estêvão - BA;2928802;3875;Santo Estêvão;BA
        4911;Santo Expedito - SP;3548302;7067;Santo Expedito;SP
        4252;Santo Expedito do Sul - RS;4317954;5977;Santo Expedito do Sul;RS
        5817;Santo Hipólito - MG;3160603;5211;Santo Hipólito;MG
        3395;Santo Inácio - PR;4124509;7865;Santo Inácio;PR
        2052;Santo Inácio do Piauí - PI;2209500;1189;Santo Inácio do Piauí;PI
        4912;Santópolis do Aguapeí - SP;3548401;7069;Santópolis do Aguapeí;SP
        4913;Santos - SP;3548500;7071;Santos;SP
        5818;Santos Dumont - MG;3160702;5213;Santos Dumont;MG
        1445;São Benedito - CE;2312304;1547;São Benedito;CE
        1824;São Benedito do Rio Preto - MA;2110401;905;São Benedito do Rio Preto;MA
        1617;São Benedito do Sul - PE;2612901;2557;São Benedito do Sul;PE
        2369;São Bentinho - PB;2513927;518;São Bentinho;PB
        1825;São Bento - MA;2110500;907;São Bento;MA
        2370;São Bento - PB;2513901;2179;São Bento;PB
        5819;São Bento Abade - MG;3160801;5215;São Bento Abade;MG
        1243;São Bento do Norte - RN;2411601;1831;São Bento do Norte;RN
        4914;São Bento do Sapucaí - SP;3548609;7073;São Bento do Sapucaí;SP
        2898;São Bento do Sul - SC;4215802;8311;São Bento do Sul;SC
        716;São Bento do Tocantins - TO;1720101;197;São Bento do Tocantins;TO
        1244;São Bento do Trairí - RN;2411700;1833;São Bento do Trairí;RN
        1618;São Bento do Una - PE;2613008;2559;São Bento do Una;PE
        2899;São Bernardino - SC;4215752;942;São Bernardino;SC
        1826;São Bernardo - MA;2110609;909;São Bernardo;MA
        4915;São Bernardo do Campo - SP;3548708;7075;São Bernardo do Campo;SP
        2900;São Bonifácio - SC;4215901;8313;São Bonifácio;SC
        4253;São Borja - RS;4318002;8863;São Borja;RS
        580;São Brás - AL;2708204;2863;São Brás;AL
        5820;São Brás do Suaçuí - MG;3160900;5217;São Brás do Suaçuí;MG
        2053;São Braz do Piauí - PI;2209559;2263;São Braz do Piauí;PI
        991;São Caetano de Odivelas - PA;1507102;541;São Caetano de Odivelas;PA
        4916;São Caetano do Sul - SP;3548807;7077;São Caetano do Sul;SP
        1619;São Caitano - PE;2613107;2561;São Caitano;PE
        2901;São Carlos - SC;4216008;8315;São Carlos;SC
        4917;São Carlos - SP;3548906;7079;São Carlos;SP
        3396;São Carlos do Ivaí - PR;4124608;7867;São Carlos do Ivaí;PR
        234;São Cristóvão - SE;2806701;3233;São Cristóvão;SE
        2902;São Cristóvão do Sul - SC;4216057;5573;São Cristóvão do Sul;SC
        3806;São Desidério - BA;2928901;3877;São Desidério;BA
        3807;São Domingos - BA;2928950;3029;São Domingos;BA
        2629;São Domingos - GO;5219803;9591;São Domingos;GO
        2371;São Domingos - PB;2513968;522;São Domingos;PB
        2903;São Domingos - SC;4216107;8317;São Domingos;SC
        235;São Domingos - SE;2806800;3235;São Domingos;SE
        5821;São Domingos das Dores - MG;3160959;710;São Domingos das Dores;MG
        992;São Domingos do Araguaia - PA;1507151;381;São Domingos do Araguaia;PA
        1827;São Domingos do Azeitão - MA;2110658;228;São Domingos do Azeitão;MA
        993;São Domingos do Capim - PA;1507201;543;São Domingos do Capim;PA
        2372;São Domingos do Cariri - PB;2513943;520;São Domingos do Cariri;PB
        1828;São Domingos do Maranhão - MA;2110708;911;São Domingos do Maranhão;MA
        308;São Domingos do Norte - ES;3204658;2933;São Domingos do Norte;ES
        5822;São Domingos do Prata - MG;3161007;5219;São Domingos do Prata;MG
        4254;São Domingos do Sul - RS;4318051;7351;São Domingos do Sul;RS
        3808;São Felipe - BA;2929107;3881;São Felipe;BA
        97;São Felipe D'Oeste - RO;1101484;18;São Felipe D'Oeste;RO
        3809;São Félix - BA;2929008;3879;São Félix;BA
        1829;São Félix de Balsas - MA;2110807;913;São Félix de Balsas;MA
        5823;São Félix de Minas - MG;3161056;712;São Félix de Minas;MG
        853;São Félix do Araguaia - MT;5107859;9183;São Félix do Araguaia;MT
        3810;São Félix do Coribe - BA;2929057;3031;São Félix do Coribe;BA
        2054;São Félix do Piauí - PI;2209609;1191;São Félix do Piauí;PI
        717;São Félix do Tocantins - TO;1720150;363;São Félix do Tocantins;TO
        994;São Félix do Xingu - PA;1507300;545;São Félix do Xingu;PA
        1245;São Fernando - RN;2411809;1835;São Fernando;RN
        471;São Fidélis - RJ;3304805;5895;São Fidélis;RJ
        5824;São Francisco - MG;3161106;5221;São Francisco;MG
        2373;São Francisco - PB;2513984;524;São Francisco;PB
        236;São Francisco - SE;2806909;3237;São Francisco;SE
        4918;São Francisco - SP;3549003;7081;São Francisco;SP
        4255;São Francisco de Assis - RS;4318101;8865;São Francisco de Assis;RS
        2055;São Francisco de Assis do Piauí - PI;2209658;380;São Francisco de Assis do Piauí;PI
        2630;São Francisco de Goiás - GO;5219902;9593;São Francisco de Goiás;GO
        472;São Francisco de Itabapoana - RJ;3304755;782;São Francisco de Itabapoana;RJ
        5825;São Francisco de Paula - MG;3161205;5223;São Francisco de Paula;MG
        4256;São Francisco de Paula - RS;4318200;8867;São Francisco de Paula;RS
        5826;São Francisco de Sales - MG;3161304;5225;São Francisco de Sales;MG
        1830;São Francisco do Brejão - MA;2110856;230;São Francisco do Brejão;MA
        3811;São Francisco do Conde - BA;2929206;3883;São Francisco do Conde;BA
        5827;São Francisco do Glória - MG;3161403;5227;São Francisco do Glória;MG
        98;São Francisco do Guaporé - RO;1101492;20;São Francisco do Guaporé;RO
        1831;São Francisco do Maranhão - MA;2110906;915;São Francisco do Maranhão;MA
        1246;São Francisco do Oeste - RN;2411908;1821;São Francisco do Oeste;RN
        995;São Francisco do Pará - PA;1507409;547;São Francisco do Pará;PA
        2056;São Francisco do Piauí - PI;2209708;1193;São Francisco do Piauí;PI
        2904;São Francisco do Sul - SC;4216206;8319;São Francisco do Sul;SC
        3812;São Gabriel - BA;2929255;3989;São Gabriel;BA
        4257;São Gabriel - RS;4318309;8869;São Gabriel;RS
        158;São Gabriel da Cachoeira - AM;1303809;283;São Gabriel da Cachoeira;AM
        309;São Gabriel da Palha - ES;3204708;5693;São Gabriel da Palha;ES
        391;São Gabriel do Oeste - MS;5007695;9809;São Gabriel do Oeste;MS
        5828;São Geraldo - MG;3161502;5229;São Geraldo;MG
        5829;São Geraldo da Piedade - MG;3161601;5231;São Geraldo da Piedade;MG
        996;São Geraldo do Araguaia - PA;1507458;619;São Geraldo do Araguaia;PA
        5830;São Geraldo do Baixio - MG;3161650;714;São Geraldo do Baixio;MG
        473;São Gonçalo - RJ;3304904;5897;São Gonçalo;RJ
        5831;São Gonçalo do Abaeté - MG;3161700;5233;São Gonçalo do Abaeté;MG
        1446;São Gonçalo do Amarante - CE;2312403;1549;São Gonçalo do Amarante;CE
        1247;São Gonçalo do Amarante - RN;2412005;1837;São Gonçalo do Amarante;RN
        2057;São Gonçalo do Gurguéia - PI;2209757;382;São Gonçalo do Gurguéia;PI
        5832;São Gonçalo do Pará - MG;3161809;5235;São Gonçalo do Pará;MG
        2058;São Gonçalo do Piauí - PI;2209807;1195;São Gonçalo do Piauí;PI
        5833;São Gonçalo do Rio Abaixo - MG;3161908;5237;São Gonçalo do Rio Abaixo;MG
        5834;São Gonçalo do Rio Preto - MG;3125507;4509;São Gonçalo do Rio Preto;MG
        5835;São Gonçalo do Sapucaí - MG;3162005;5239;São Gonçalo do Sapucaí;MG
        3813;São Gonçalo dos Campos - BA;2929305;3885;São Gonçalo dos Campos;BA
        5836;São Gotardo - MG;3162104;5241;São Gotardo;MG
        4258;São Jerônimo - RS;4318408;8871;São Jerônimo;RS
        3397;São Jerônimo da Serra - PR;4124707;7869;São Jerônimo da Serra;PR
        1620;São João - PE;2613206;2563;São João;PE
        3398;São João - PR;4124806;7871;São João;PR
        1832;São João Batista - MA;2111003;917;São João Batista;MA
        2905;São João Batista - SC;4216305;8321;São João Batista;SC
        5837;São João Batista do Glória - MG;3162203;5243;São João Batista do Glória;MG
        14;São João da Baliza - RR;1400506;313;São João da Baliza;RR
        474;São João da Barra - RJ;3305000;5899;São João da Barra;RJ
        4919;São João da Boa Vista - SP;3549102;7083;São João da Boa Vista;SP
        2059;São João da Canabrava - PI;2209856;1291;São João da Canabrava;PI
        2060;São João da Fronteira - PI;2209872;384;São João da Fronteira;PI
        5838;São João da Lagoa - MG;3162252;716;São João da Lagoa;MG
        5839;São João da Mata - MG;3162302;5245;São João da Mata;MG
        2631;São João da Paraúna - GO;5220058;9747;São João da Paraúna;GO
        997;São João da Ponta - PA;1507466;64;São João da Ponta;PA
        5840;São João da Ponte - MG;3162401;5247;São João da Ponte;MG
        2061;São João da Serra - PI;2209906;1197;São João da Serra;PI
        4259;São João da Urtiga - RS;4318424;7349;São João da Urtiga;RS
        2062;São João da Varjota - PI;2209955;386;São João da Varjota;PI
        2632;São João d'Aliança - GO;5220009;9597;São João d'Aliança;GO
        4920;São João das Duas Pontes - SP;3549201;7085;São João das Duas Pontes;SP
        5841;São João das Missões - MG;3162450;718;São João das Missões;MG
        4921;São João de Iracema - SP;3549250;2941;São João de Iracema;SP
        475;São João de Meriti - RJ;3305109;5901;São João de Meriti;RJ
        998;São João de Pirabas - PA;1507474;393;São João de Pirabas;PA
        5842;São João del Rei - MG;3162500;5249;São João del Rei;MG
        999;São João do Araguaia - PA;1507508;549;São João do Araguaia;PA
        2063;São João do Arraial - PI;2209971;388;São João do Arraial;PI
        3399;São João do Caiuá - PR;4124905;7873;São João do Caiuá;PR
        2374;São João do Cariri - PB;2514008;2181;São João do Cariri;PB
        1833;São João do Carú - MA;2111029;232;São João do Carú;MA
        2906;São João do Itaperiú - SC;4216354;5551;São João do Itaperiú;SC
        3400;São João do Ivaí - PR;4125001;7875;São João do Ivaí;PR
        1447;São João do Jaguaribe - CE;2312502;1551;São João do Jaguaribe;CE
        5843;São João do Manhuaçu - MG;3162559;2677;São João do Manhuaçu;MG
        5844;São João do Manteninha - MG;3162575;2679;São João do Manteninha;MG
        2907;São João do Oeste - SC;4216255;5753;São João do Oeste;SC
        5845;São João do Oriente - MG;3162609;5251;São João do Oriente;MG
        5846;São João do Pacuí - MG;3162658;720;São João do Pacuí;MG
        1834;São João do Paraíso - MA;2111052;234;São João do Paraíso;MA
        5847;São João do Paraíso - MG;3162708;5253;São João do Paraíso;MG
        4922;São João do Pau d'Alho - SP;3549300;7087;São João do Pau d'Alho;SP
        2064;São João do Piauí - PI;2210003;1199;São João do Piauí;PI
        4260;São João do Polêsine - RS;4318432;5791;São João do Polêsine;RS
        2375;São João do Rio do Peixe - PB;2500700;1913;São João do Rio do Peixe;PB
        1248;São João do Sabugi - RN;2412104;1839;São João do Sabugi;RN
        1835;São João do Soter - MA;2111078;236;São João do Soter;MA
        2908;São João do Sul - SC;4216404;8323;São João do Sul;SC
        2376;São João do Tigre - PB;2514107;2183;São João do Tigre;PB
        3401;São João do Triunfo - PR;4125100;7877;São João do Triunfo;PR
        1836;São João dos Patos - MA;2111102;919;São João dos Patos;MA
        5848;São João Evangelista - MG;3162807;5255;São João Evangelista;MG
        5849;São João Nepomuceno - MG;3162906;5257;São João Nepomuceno;MG
        2909;São Joaquim - SC;4216503;8325;São Joaquim;SC
        4923;São Joaquim da Barra - SP;3549409;7089;São Joaquim da Barra;SP
        5850;São Joaquim de Bicas - MG;3162922;722;São Joaquim de Bicas;MG
        1621;São Joaquim do Monte - PE;2613305;2565;São Joaquim do Monte;PE
        4261;São Jorge - RS;4318440;7347;São Jorge;RS
        3402;São Jorge do Ivaí - PR;4125308;7879;São Jorge do Ivaí;PR
        3403;São Jorge do Patrocínio - PR;4125357;7999;São Jorge do Patrocínio;PR
        3404;São Jorge d'Oeste - PR;4125209;7881;São Jorge d'Oeste;PR
        2910;São José - SC;4216602;8327;São José;SC
        5851;São José da Barra - MG;3162948;724;São José da Barra;MG
        4924;São José da Bela Vista - SP;3549508;7091;São José da Bela Vista;SP
        3405;São José da Boa Vista - PR;4125407;7883;São José da Boa Vista;PR
        1622;São José da Coroa Grande - PE;2613404;2567;São José da Coroa Grande;PE
        2377;São José da Lagoa Tapada - PB;2514206;2185;São José da Lagoa Tapada;PB
        581;São José da Laje - AL;2708303;2865;São José da Laje;AL
        5852;São José da Lapa - MG;3162955;2649;São José da Lapa;MG
        5853;São José da Safira - MG;3163003;5259;São José da Safira;MG
        582;São José da Tapera - AL;2708402;2867;São José da Tapera;AL
        5854;São José da Varginha - MG;3163102;5261;São José da Varginha;MG
        3814;São José da Vitória - BA;2929354;3035;São José da Vitória;BA
        4262;São José das Missões - RS;4318457;5989;São José das Missões;RS
        3406;São José das Palmeiras - PR;4125456;8471;São José das Palmeiras;PR
        2378;São José de Caiana - PB;2514305;2187;São José de Caiana;PB
        2379;São José de Espinharas - PB;2514404;2189;São José de Espinharas;PB
        1249;São José de Mipibu - RN;2412203;1841;São José de Mipibu;RN
        2380;São José de Piranhas - PB;2514503;2191;São José de Piranhas;PB
        2381;São José de Princesa - PB;2514552;528;São José de Princesa;PB
        1837;São José de Ribamar - MA;2111201;889;São José de Ribamar;MA
        476;São José de Ubá - RJ;3305133;784;São José de Ubá;RJ
        5855;São José do Alegre - MG;3163201;5263;São José do Alegre;MG
        4925;São José do Barreiro - SP;3549607;7093;São José do Barreiro;SP
        1623;São José do Belmonte - PE;2613503;2569;São José do Belmonte;PE
        2382;São José do Bonfim - PB;2514602;2193;São José do Bonfim;PB
        2383;São José do Brejo do Cruz - PB;2514651;530;São José do Brejo do Cruz;PB
        310;São José do Calçado - ES;3204807;5695;São José do Calçado;ES
        1250;São José do Campestre - RN;2412302;1843;São José do Campestre;RN
        2911;São José do Cedro - SC;4216701;8329;São José do Cedro;SC
        2912;São José do Cerrito - SC;4216800;8331;São José do Cerrito;SC
        5856;São José do Divino - MG;3163300;5265;São José do Divino;MG
        2065;São José do Divino - PI;2210052;2285;São José do Divino;PI
        1624;São José do Egito - PE;2613602;2571;São José do Egito;PE
        5857;São José do Goiabal - MG;3163409;5267;São José do Goiabal;MG
        4263;São José do Herval - RS;4318465;7345;São José do Herval;RS
        4264;São José do Hortêncio - RS;4318481;7343;São José do Hortêncio;RS
        4265;São José do Inhacorá - RS;4318499;6059;São José do Inhacorá;RS
        3815;São José do Jacuípe - BA;2929370;3033;São José do Jacuípe;BA
        5858;São José do Jacuri - MG;3163508;5269;São José do Jacuri;MG
        5859;São José do Mantimento - MG;3163607;5271;São José do Mantimento;MG
        4266;São José do Norte - RS;4318507;8873;São José do Norte;RS
        4267;São José do Ouro - RS;4318606;8875;São José do Ouro;RS
        2066;São José do Peixe - PI;2210102;1201;São José do Peixe;PI
        2067;São José do Piauí - PI;2210201;1203;São José do Piauí;PI
        854;São José do Povo - MT;5107297;6087;São José do Povo;MT
        855;São José do Rio Claro - MT;5107305;9199;São José do Rio Claro;MT
        4926;São José do Rio Pardo - SP;3549706;7095;São José do Rio Pardo;SP
        4927;São José do Rio Preto - SP;3549805;7097;São José do Rio Preto;SP
        2384;São José do Sabugi - PB;2514701;2195;São José do Sabugi;PB
        1251;São José do Seridó - RN;2412401;1845;São José do Seridó;RN
        4268;São José do Sul - RS;4318614;1170;São José do Sul;RS
        477;São José do Vale do Rio Preto - RJ;3305158;6009;São José do Vale do Rio Preto;RJ
        856;São José do Xingu - MT;5107354;133;São José do Xingu;MT
        4269;São José dos Ausentes - RS;4318622;6015;São José dos Ausentes;RS
        1838;São José dos Basílios - MA;2111250;238;São José dos Basílios;MA
        4928;São José dos Campos - SP;3549904;7099;São José dos Campos;SP
        2385;São José dos Cordeiros - PB;2514800;2197;São José dos Cordeiros;PB
        3407;São José dos Pinhais - PR;4125506;7885;São José dos Pinhais;PR
        857;São José dos Quatro Marcos - MT;5107107;8993;São José dos Quatro Marcos;MT
        2386;São José dos Ramos - PB;2514453;526;São José dos Ramos;PB
        2068;São Julião - PI;2210300;1205;São Julião;PI
        4270;São Leopoldo - RS;4318705;8877;São Leopoldo;RS
        5860;São Lourenço - MG;3163706;5273;São Lourenço;MG
        1625;São Lourenço da Mata - PE;2613701;2573;São Lourenço da Mata;PE
        4929;São Lourenço da Serra - SP;3549953;5447;São Lourenço da Serra;SP
        2913;São Lourenço do Oeste - SC;4216909;8333;São Lourenço do Oeste;SC
        2069;São Lourenço do Piauí - PI;2210359;2265;São Lourenço do Piauí;PI
        4271;São Lourenço do Sul - RS;4318804;8879;São Lourenço do Sul;RS
        2914;São Ludgero - SC;4217006;8335;São Ludgero;SC
        1839;São Luís - MA;2111300;921;São Luís;MA
        2633;São Luís de Montes Belos - GO;5220108;9599;São Luís de Montes Belos;GO
        1448;São Luís do Curu - CE;2312601;1553;São Luís do Curu;CE
        2070;São Luis do Piauí - PI;2210375;390;São Luis do Piauí;PI
        583;São Luís do Quitunde - AL;2708501;2869;São Luís do Quitunde;AL
        1840;São Luís Gonzaga do Maranhão - MA;2111409;805;São Luís Gonzaga do Maranhão;MA
        15;São Luiz - RR;1400605;315;São Luiz;RR
        2634;São Luiz do Norte - GO;5220157;9749;São Luiz do Norte;GO
        4930;São Luiz do Paraitinga - SP;3550001;7101;São Luiz do Paraitinga;SP
        4272;São Luiz Gonzaga - RS;4318903;8881;São Luiz Gonzaga;RS
        2387;São Mamede - PB;2514909;2199;São Mamede;PB
        3408;São Manoel do Paraná - PR;4125555;5515;São Manoel do Paraná;PR
        4931;São Manuel - SP;3550100;7103;São Manuel;SP
        4273;São Marcos - RS;4319000;8883;São Marcos;RS
        4274;São Martinho - RS;4319109;8885;São Martinho;RS
        2915;São Martinho - SC;4217105;8337;São Martinho;SC
        4275;São Martinho da Serra - RS;4319125;5793;São Martinho da Serra;RS
        311;São Mateus - ES;3204906;5697;São Mateus;ES
        1841;São Mateus do Maranhão - MA;2111508;923;São Mateus do Maranhão;MA
        3409;São Mateus do Sul - PR;4125605;7887;São Mateus do Sul;PR
        1252;São Miguel - RN;2412500;1847;São Miguel;RN
        4932;São Miguel Arcanjo - SP;3550209;7105;São Miguel Arcanjo;SP
        2071;São Miguel da Baixa Grande - PI;2210383;392;São Miguel da Baixa Grande;PI
        2916;São Miguel da Boa Vista - SC;4217154;5755;São Miguel da Boa Vista;SC
        3816;São Miguel das Matas - BA;2929404;3887;São Miguel das Matas;BA
        4276;São Miguel das Missões - RS;4319158;7341;São Miguel das Missões;RS
        2388;São Miguel de Taipu - PB;2515005;2201;São Miguel de Taipu;PB
        237;São Miguel do Aleixo - SE;2807006;3239;São Miguel do Aleixo;SE
        5861;São Miguel do Anta - MG;3163805;5275;São Miguel do Anta;MG
        2635;São Miguel do Araguaia - GO;5220207;9601;São Miguel do Araguaia;GO
        2072;São Miguel do Fidalgo - PI;2210391;394;São Miguel do Fidalgo;PI
        1253;São Miguel do Gostoso - RN;2412559;430;São Miguel do Gostoso;RN
        1000;São Miguel do Guamá - PA;1507607;551;São Miguel do Guamá;PA
        99;São Miguel do Guaporé - RO;1100320;45;São Miguel do Guaporé;RO
        3410;São Miguel do Iguaçu - PR;4125704;7889;São Miguel do Iguaçu;PR
        2917;São Miguel do Oeste - SC;4217204;8339;São Miguel do Oeste;SC
        2636;São Miguel do Passa Quatro - GO;5220264;9751;São Miguel do Passa Quatro;GO
        2073;São Miguel do Tapuio - PI;2210409;1207;São Miguel do Tapuio;PI
        718;São Miguel do Tocantins - TO;1720200;199;São Miguel do Tocantins;TO
        584;São Miguel dos Campos - AL;2708600;2871;São Miguel dos Campos;AL
        585;São Miguel dos Milagres - AL;2708709;2873;São Miguel dos Milagres;AL
        4277;São Nicolau - RS;4319208;8887;São Nicolau;RS
        2637;São Patrício - GO;5220280;1064;São Patrício;GO
        4933;São Paulo - SP;3550308;7107;São Paulo;SP
        4278;São Paulo das Missões - RS;4319307;8889;São Paulo das Missões;RS
        159;São Paulo de Olivença - AM;1303908;275;São Paulo de Olivença;AM
        1254;São Paulo do Potengi - RN;2412609;1849;São Paulo do Potengi;RN
        1255;São Pedro - RN;2412708;1851;São Pedro;RN
        4934;São Pedro - SP;3550407;7109;São Pedro;SP
        1842;São Pedro da Água Branca - MA;2111532;240;São Pedro da Água Branca;MA
        478;São Pedro da Aldeia - RJ;3305208;5903;São Pedro da Aldeia;RJ
        858;São Pedro da Cipa - MT;5107404;93;São Pedro da Cipa;MT
        4279;São Pedro da Serra - RS;4319356;6043;São Pedro da Serra;RS
        5862;São Pedro da União - MG;3163904;5277;São Pedro da União;MG
        4280;São Pedro das Missões - RS;4319364;1172;São Pedro das Missões;RS
        2918;São Pedro de Alcântara - SC;4217253;944;São Pedro de Alcântara;SC
        4281;São Pedro do Butiá - RS;4319372;6063;São Pedro do Butiá;RS
        3411;São Pedro do Iguaçu - PR;4125753;5489;São Pedro do Iguaçu;PR
        3412;São Pedro do Ivaí - PR;4125803;7891;São Pedro do Ivaí;PR
        3413;São Pedro do Paraná - PR;4125902;7893;São Pedro do Paraná;PR
        2074;São Pedro do Piauí - PI;2210508;1209;São Pedro do Piauí;PI
        5863;São Pedro do Suaçuí - MG;3164100;5281;São Pedro do Suaçuí;MG
        4282;São Pedro do Sul - RS;4319406;8891;São Pedro do Sul;RS
        4935;São Pedro do Turvo - SP;3550506;7111;São Pedro do Turvo;SP
        1843;São Pedro dos Crentes - MA;2111573;242;São Pedro dos Crentes;MA
        5864;São Pedro dos Ferros - MG;3164001;5279;São Pedro dos Ferros;MG
        1256;São Rafael - RN;2412807;1853;São Rafael;RN
        1844;São Raimundo das Mangabeiras - MA;2111607;925;São Raimundo das Mangabeiras;MA
        1845;São Raimundo do Doca Bezerra - MA;2111631;244;São Raimundo do Doca Bezerra;MA
        2075;São Raimundo Nonato - PI;2210607;1211;São Raimundo Nonato;PI
        1846;São Roberto - MA;2111672;246;São Roberto;MA
        5865;São Romão - MG;3164209;5283;São Romão;MG
        4936;São Roque - SP;3550605;7113;São Roque;SP
        5866;São Roque de Minas - MG;3164308;5285;São Roque de Minas;MG
        312;São Roque do Canaã - ES;3204955;764;São Roque do Canaã;ES
        719;São Salvador do Tocantins - TO;1720259;333;São Salvador do Tocantins;TO
        586;São Sebastião - AL;2708808;2875;São Sebastião;AL
        4937;São Sebastião - SP;3550704;7115;São Sebastião;SP
        3414;São Sebastião da Amoreira - PR;4126009;7895;São Sebastião da Amoreira;PR
        5867;São Sebastião da Bela Vista - MG;3164407;5287;São Sebastião da Bela Vista;MG
        1001;São Sebastião da Boa Vista - PA;1507706;553;São Sebastião da Boa Vista;PA
        4938;São Sebastião da Grama - SP;3550803;7117;São Sebastião da Grama;SP
        5868;São Sebastião da Vargem Alegre - MG;3164431;726;São Sebastião da Vargem Alegre;MG
        2389;São Sebastião de Lagoa de Roça - PB;2515104;2203;São Sebastião de Lagoa de Roça;PB
        479;São Sebastião do Alto - RJ;3305307;5905;São Sebastião do Alto;RJ
        5869;São Sebastião do Anta - MG;3164472;728;São Sebastião do Anta;MG
        4283;São Sebastião do Caí - RS;4319505;8893;São Sebastião do Caí;RS
        5870;São Sebastião do Maranhão - MG;3164506;5289;São Sebastião do Maranhão;MG
        5871;São Sebastião do Oeste - MG;3164605;5291;São Sebastião do Oeste;MG
        5872;São Sebastião do Paraíso - MG;3164704;5293;São Sebastião do Paraíso;MG
        3817;São Sebastião do Passé - BA;2929503;3889;São Sebastião do Passé;BA
        5873;São Sebastião do Rio Preto - MG;3164803;5295;São Sebastião do Rio Preto;MG
        5874;São Sebastião do Rio Verde - MG;3164902;5297;São Sebastião do Rio Verde;MG
        720;São Sebastião do Tocantins - TO;1720309;9603;São Sebastião do Tocantins;TO
        160;São Sebastião do Uatumã - AM;1303957;9845;São Sebastião do Uatumã;AM
        2390;São Sebastião do Umbuzeiro - PB;2515203;2205;São Sebastião do Umbuzeiro;PB
        4284;São Sepé - RS;4319604;8895;São Sepé;RS
        2638;São Simão - GO;5220405;9605;São Simão;GO
        4939;São Simão - SP;3550902;7119;São Simão;SP
        5876;São Tiago - MG;3165008;5299;São Tiago;MG
        5877;São Tomás de Aquino - MG;3165107;5301;São Tomás de Aquino;MG
        3415;São Tomé - PR;4126108;7897;São Tomé;PR
        1257;São Tomé - RN;2412906;1855;São Tomé;RN
        5875;São Tomé das Letras - MG;3165206;5303;São Tomé das Letras;MG
        4285;São Valentim - RS;4319703;8897;São Valentim;RS
        4286;São Valentim do Sul - RS;4319711;5997;São Valentim do Sul;RS
        721;São Valério - TO;1720499;9691;São Valério;TO
        4287;São Valério do Sul - RS;4319737;6075;São Valério do Sul;RS
        4288;São Vendelino - RS;4319752;7293;São Vendelino;RS
        1258;São Vicente - RN;2413003;1857;São Vicente;RN
        4940;São Vicente - SP;3551009;7121;São Vicente;SP
        5878;São Vicente de Minas - MG;3165305;5305;São Vicente de Minas;MG
        2392;São Vicente do Seridó - PB;2515401;2209;São Vicente do Seridó;PB
        4289;São Vicente do Sul - RS;4319802;8675;São Vicente do Sul;RS
        1847;São Vicente Ferrer - MA;2111706;927;São Vicente Ferrer;MA
        1626;São Vicente Férrer - PE;2613800;2575;São Vicente Férrer;PE
        2391;Sapé - PB;2515302;2207;Sapé;PB
        3818;Sapeaçu - BA;2929602;3891;Sapeaçu;BA
        859;Sapezal - MT;5107875;1046;Sapezal;MT
        4290;Sapiranga - RS;4319901;8899;Sapiranga;RS
        3416;Sapopema - PR;4126207;7899;Sapopema;PR
        1101;Sapucaia - PA;1507755;66;Sapucaia;PA
        480;Sapucaia - RJ;3305406;5907;Sapucaia;RJ
        4291;Sapucaia do Sul - RS;4320008;8901;Sapucaia do Sul;RS
        5879;Sapucaí-Mirim - MG;3165404;5307;Sapucaí-Mirim;MG
        481;Saquarema - RJ;3305505;5909;Saquarema;RJ
        3417;Sarandi - PR;4126256;8461;Sarandi;PR
        4292;Sarandi - RS;4320107;8903;Sarandi;RS
        4941;Sarapuí - SP;3551108;7123;Sarapuí;SP
        5880;Sardoá - MG;3165503;5309;Sardoá;MG
        4942;Sarutaiá - SP;3551207;7125;Sarutaiá;SP
        5881;Sarzedo - MG;3165537;730;Sarzedo;MG
        3819;Sátiro Dias - BA;2929701;3893;Sátiro Dias;BA
        587;Satuba - AL;2708907;2877;Satuba;AL
        1848;Satubinha - MA;2111722;248;Satubinha;MA
        3820;Saubara - BA;2929750;3037;Saubara;BA
        3418;Saudade do Iguaçu - PR;4126272;5493;Saudade do Iguaçu;PR
        2919;Saudades - SC;4217303;8341;Saudades;SC
        3821;Saúde - BA;2929800;3895;Saúde;BA
        2920;Schroeder - SC;4217402;8343;Schroeder;SC
        3822;Seabra - BA;2929909;3897;Seabra;BA
        2921;Seara - SC;4217501;8345;Seara;SC
        4943;Sebastianópolis do Sul - SP;3551306;7127;Sebastianópolis do Sul;SP
        2076;Sebastião Barros - PI;2210623;396;Sebastião Barros;PI
        3823;Sebastião Laranjeiras - BA;2930006;3899;Sebastião Laranjeiras;BA
        2077;Sebastião Leal - PI;2210631;398;Sebastião Leal;PI
        4293;Seberi - RS;4320206;8905;Seberi;RS
        4294;Sede Nova - RS;4320230;7335;Sede Nova;RS
        4295;Segredo - RS;4320263;7317;Segredo;RS
        4296;Selbach - RS;4320305;8907;Selbach;RS
        392;Selvíria - MS;5007802;9811;Selvíria;MS
        5882;Sem-Peixe - MG;3165560;734;Sem-Peixe;MG
        51;Sena Madureira - AC;1200500;145;Sena Madureira;AC
        1849;Senador Alexandre Costa - MA;2111748;250;Senador Alexandre Costa;MA
        5883;Senador Amaral - MG;3165578;2689;Senador Amaral;MG
        2639;Senador Canedo - GO;5220454;9753;Senador Canedo;GO
        5884;Senador Cortes - MG;3165602;5311;Senador Cortes;MG
        1259;Senador Elói de Souza - RN;2413102;1859;Senador Elói de Souza;RN
        5885;Senador Firmino - MG;3165701;5313;Senador Firmino;MG
        1260;Senador Georgino Avelino - RN;2413201;1861;Senador Georgino Avelino;RN
        52;Senador Guiomard - AC;1200450;153;Senador Guiomard;AC
        5886;Senador José Bento - MG;3165800;5315;Senador José Bento;MG
        1102;Senador José Porfírio - PA;1507805;555;Senador José Porfírio;PA
        1850;Senador La Rocque - MA;2111763;252;Senador La Rocque;MA
        5887;Senador Modestino Gonçalves - MG;3165909;5317;Senador Modestino Gonçalves;MG
        1449;Senador Pompeu - CE;2312700;1555;Senador Pompeu;CE
        588;Senador Rui Palmeira - AL;2708956;2891;Senador Rui Palmeira;AL
        1450;Senador Sá - CE;2312809;1557;Senador Sá;CE
        4297;Senador Salgado Filho - RS;4320321;1012;Senador Salgado Filho;RS
        3419;Sengés - PR;4126306;7901;Sengés;PR
        3824;Senhor do Bonfim - BA;2930105;3901;Senhor do Bonfim;BA
        5888;Senhora de Oliveira - MG;3166006;5319;Senhora de Oliveira;MG
        5889;Senhora do Porto - MG;3166105;5321;Senhora do Porto;MG
        5890;Senhora dos Remédios - MG;3166204;5323;Senhora dos Remédios;MG
        4298;Sentinela do Sul - RS;4320354;5781;Sentinela do Sul;RS
        3825;Sento Sé - BA;2930204;3903;Sento Sé;BA
        4299;Serafina Corrêa - RS;4320404;8909;Serafina Corrêa;RS
        5891;Sericita - MG;3166303;5325;Sericita;MG
        100;Seringueiras - RO;1101500;699;Seringueiras;RO
        4300;Sério - RS;4320453;6035;Sério;RS
        5892;Seritinga - MG;3166402;5327;Seritinga;MG
        482;Seropédica - RJ;3305554;786;Seropédica;RJ
        313;Serra - ES;3205002;5699;Serra;ES
        2922;Serra Alta - SC;4217550;9989;Serra Alta;SC
        4944;Serra Azul - SP;3551405;7129;Serra Azul;SP
        5893;Serra Azul de Minas - MG;3166501;5329;Serra Azul de Minas;MG
        2393;Serra Branca - PB;2515500;2211;Serra Branca;PB
        1228;Serra Caiada - RN;2410306;1805;Serra Caiada;RN
        2394;Serra da Raiz - PB;2515609;2213;Serra da Raiz;PB
        5894;Serra da Saudade - MG;3166600;5331;Serra da Saudade;MG
        1261;Serra de São Bento - RN;2413300;1863;Serra de São Bento;RN
        1262;Serra do Mel - RN;2413359;1927;Serra do Mel;RN
        30;Serra do Navio - AP;1600055;665;Serra do Navio;AP
        3826;Serra do Ramalho - BA;2930154;3039;Serra do Ramalho;BA
        5895;Serra do Salitre - MG;3166808;5335;Serra do Salitre;MG
        5896;Serra dos Aimorés - MG;3166709;5333;Serra dos Aimorés;MG
        3827;Serra Dourada - BA;2930303;3905;Serra Dourada;BA
        2395;Serra Grande - PB;2515708;2215;Serra Grande;PB
        4945;Serra Negra - SP;3551603;7133;Serra Negra;SP
        1263;Serra Negra do Norte - RN;2413409;1865;Serra Negra do Norte;RN
        860;Serra Nova Dourada - MT;5107883;1100;Serra Nova Dourada;MT
        3828;Serra Preta - BA;2930402;3907;Serra Preta;BA
        2396;Serra Redonda - PB;2515807;2217;Serra Redonda;PB
        1627;Serra Talhada - PE;2613909;2577;Serra Talhada;PE
        4946;Serrana - SP;3551504;7131;Serrana;SP
        5897;Serrania - MG;3166907;5337;Serrania;MG
        1851;Serrano do Maranhão - MA;2111789;254;Serrano do Maranhão;MA
        2640;Serranópolis - GO;5220504;9607;Serranópolis;GO
        5898;Serranópolis de Minas - MG;3166956;736;Serranópolis de Minas;MG
        3420;Serranópolis do Iguaçu - PR;4126355;882;Serranópolis do Iguaçu;PR
        5899;Serranos - MG;3167004;5339;Serranos;MG
        2397;Serraria - PB;2515906;2219;Serraria;PB
        3829;Serrinha - BA;2930501;3909;Serrinha;BA
        1264;Serrinha - RN;2413508;1867;Serrinha;RN
        1265;Serrinha dos Pintos - RN;2413557;432;Serrinha dos Pintos;RN
        1628;Serrita - PE;2614006;2579;Serrita;PE
        5900;Serro - MG;3167103;5341;Serro;MG
        3830;Serrolândia - BA;2930600;3911;Serrolândia;BA
        3421;Sertaneja - PR;4126405;7903;Sertaneja;PR
        1629;Sertânia - PE;2614105;2581;Sertânia;PE
        3422;Sertanópolis - PR;4126504;7905;Sertanópolis;PR
        4301;Sertão - RS;4320503;8911;Sertão;RS
        1002;Sertão Santana - RS;4320552;5761;Sertão Santana;RS
        2398;Sertãozinho - PB;2515930;532;Sertãozinho;PB
        4947;Sertãozinho - SP;3551702;7135;Sertãozinho;SP
        4948;Sete Barras - SP;3551801;7137;Sete Barras;SP
        1003;Sete de Setembro - RS;4320578;1014;Sete de Setembro;RS
        5901;Sete Lagoas - MG;3167202;5343;Sete Lagoas;MG
        393;Sete Quedas - MS;5007703;9813;Sete Quedas;MS
        5902;Setubinha - MG;3165552;732;Setubinha;MG
        1004;Severiano de Almeida - RS;4320602;8913;Severiano de Almeida;RS
        1266;Severiano Melo - RN;2413607;1869;Severiano Melo;RN
        4949;Severínia - SP;3551900;7139;Severínia;SP
        2923;Siderópolis - SC;4217600;8347;Siderópolis;SC
        394;Sidrolândia - MS;5007901;9157;Sidrolândia;MS
        2078;Sigefredo Pacheco - PI;2210656;1379;Sigefredo Pacheco;PI
        483;Silva Jardim - RJ;3305604;5911;Silva Jardim;RJ
        2641;Silvânia - GO;5220603;9609;Silvânia;GO
        722;Silvanópolis - TO;1720655;9659;Silvanópolis;TO
        1005;Silveira Martins - RS;4320651;7315;Silveira Martins;RS
        5903;Silveirânia - MG;3167301;5345;Silveirânia;MG
        4950;Silveiras - SP;3552007;7141;Silveiras;SP
        161;Silves - AM;1304005;277;Silves;AM
        5904;Silvianópolis - MG;3167400;5347;Silvianópolis;MG
        238;Simão Dias - SE;2807105;3241;Simão Dias;SE
        5905;Simão Pereira - MG;3167509;5349;Simão Pereira;MG
        2079;Simões - PI;2210706;1213;Simões;PI
        3831;Simões Filho - BA;2930709;3913;Simões Filho;BA
        2642;Simolândia - GO;5220686;9755;Simolândia;GO
        5906;Simonésia - MG;3167608;5351;Simonésia;MG
        2080;Simplício Mendes - PI;2210805;1215;Simplício Mendes;PI
        1006;Sinimbu - RS;4320677;5767;Sinimbu;RS
        861;Sinop - MT;5107909;8985;Sinop;MT
        3423;Siqueira Campos - PR;4126603;7907;Siqueira Campos;PR
        1630;Sirinhaém - PE;2614204;2583;Sirinhaém;PE
        239;Siriri - SE;2807204;3243;Siriri;SE
        2643;Sítio d'Abadia - GO;5220702;9611;Sítio d'Abadia;GO
        3832;Sítio do Mato - BA;2930758;3041;Sítio do Mato;BA
        3833;Sítio do Quinto - BA;2930766;3043;Sítio do Quinto;BA
        1852;Sítio Novo - MA;2111805;929;Sítio Novo;MA
        1267;Sítio Novo - RN;2413706;1871;Sítio Novo;RN
        723;Sítio Novo do Tocantins - TO;1720804;9613;Sítio Novo do Tocantins;TO
        3834;Sobradinho - BA;2930774;3045;Sobradinho;BA
        1007;Sobradinho - RS;4320701;8917;Sobradinho;RS
        2399;Sobrado - PB;2515971;534;Sobrado;PB
        1451;Sobral - CE;2312908;1559;Sobral;CE
        5907;Sobrália - MG;3167707;5353;Sobrália;MG
        4951;Socorro - SP;3552106;7143;Socorro;SP
        2081;Socorro do Piauí - PI;2210904;1217;Socorro do Piauí;PI
        2400;Solânea - PB;2516003;2221;Solânea;PB
        2401;Soledade - PB;2516102;2223;Soledade;PB
        1008;Soledade - RS;4320800;8919;Soledade;RS
        5908;Soledade de Minas - MG;3167806;5355;Soledade de Minas;MG
        1631;Solidão - PE;2614402;2587;Solidão;PE
        1452;Solonópole - CE;2313005;1561;Solonópole;CE
        2924;Sombrio - SC;4217709;8349;Sombrio;SC
        395;Sonora - MS;5007935;9757;Sonora;MS
        314;Sooretama - ES;3205010;766;Sooretama;ES
        4952;Sorocaba - SP;3552205;7145;Sorocaba;SP
        862;Sorriso - MT;5107925;9907;Sorriso;MT
        2402;Sossêgo - PB;2516151;536;Sossêgo;PB
        1103;Soure - PA;1507904;557;Soure;PA
        2403;Sousa - PB;2516201;2225;Sousa;PB
        3835;Souto Soares - BA;2930808;3915;Souto Soares;BA
        724;Sucupira - TO;1720853;335;Sucupira;TO
        1853;Sucupira do Norte - MA;2111904;931;Sucupira do Norte;MA
        1854;Sucupira do Riachão - MA;2111953;256;Sucupira do Riachão;MA
        4953;Sud Mennucci - SP;3552304;7147;Sud Mennucci;SP
        2925;Sul Brasil - SC;4217758;5595;Sul Brasil;SC
        3424;Sulina - PR;4126652;8477;Sulina;PR
        4954;Sumaré - SP;3552403;7149;Sumaré;SP
        2404;Sumé - PB;2516300;2227;Sumé;PB
        484;Sumidouro - RJ;3305703;5913;Sumidouro;RJ
        1632;Surubim - PE;2614501;2589;Surubim;PE
        2082;Sussuapara - PI;2210938;400;Sussuapara;PI
        4955;Suzanápolis - SP;3552551;2945;Suzanápolis;SP
        4956;Suzano - SP;3552502;7151;Suzano;SP
        1009;Tabaí - RS;4320859;1016;Tabaí;RS
        863;Tabaporã - MT;5107941;125;Tabaporã;MT
        4957;Tabapuã - SP;3552601;7153;Tabapuã;SP
        162;Tabatinga - AM;1304062;9847;Tabatinga;AM
        4958;Tabatinga - SP;3552700;7155;Tabatinga;SP
        1633;Tabira - PE;2614600;2591;Tabira;PE
        4959;Taboão da Serra - SP;3552809;7157;Taboão da Serra;SP
        649;Tabocão - TO;1708254;345;Tabocão;TO
        3836;Tabocas do Brejo Velho - BA;2930907;3917;Tabocas do Brejo Velho;BA
        1268;Taboleiro Grande - RN;2413805;1873;Taboleiro Grande;RN
        5909;Tabuleiro - MG;3167905;5357;Tabuleiro;MG
        1453;Tabuleiro do Norte - CE;2313104;1563;Tabuleiro do Norte;CE
        1634;Tacaimbó - PE;2614709;2593;Tacaimbó;PE
        1635;Tacaratu - PE;2614808;2595;Tacaratu;PE
        4960;Taciba - SP;3552908;7159;Taciba;SP
        2243;Tacima - PB;2516409;2229;Tacima;PB
        396;Tacuru - MS;5007950;9815;Tacuru;MS
        4961;Taguaí - SP;3553005;7161;Taguaí;SP
        725;Taguatinga - TO;1720903;9615;Taguatinga;TO
        4962;Taiaçu - SP;3553104;7163;Taiaçu;SP
        1104;Tailândia - PA;1507953;395;Tailândia;PA
        2926;Taió - SC;4217808;8351;Taió;SC
        5910;Taiobeiras - MG;3168002;5359;Taiobeiras;MG
        726;Taipas do Tocantins - TO;1720937;325;Taipas do Tocantins;TO
        1269;Taipu - RN;2413904;1875;Taipu;RN
        4963;Taiúva - SP;3553203;7165;Taiúva;SP
        727;Talismã - TO;1720978;100;Talismã;TO
        1636;Tamandaré - PE;2614857;558;Tamandaré;PE
        3425;Tamarana - PR;4126678;884;Tamarana;PR
        4964;Tambaú - SP;3553302;7167;Tambaú;SP
        3426;Tamboara - PR;4126702;7909;Tamboara;PR
        1454;Tamboril - CE;2313203;1565;Tamboril;CE
        2083;Tamboril do Piauí - PI;2210953;402;Tamboril do Piauí;PI
        4965;Tanabi - SP;3553401;7169;Tanabi;SP
        1270;Tangará - RN;2414001;1877;Tangará;RN
        2927;Tangará - SC;4217907;8353;Tangará;SC
        864;Tangará da Serra - MT;5107958;9185;Tangará da Serra;MT
        485;Tanguá - RJ;3305752;788;Tanguá;RJ
        3837;Tanhaçu - BA;2931004;3919;Tanhaçu;BA
        589;Tanque d'Arca - AL;2709004;2879;Tanque d'Arca;AL
        2084;Tanque do Piauí - PI;2210979;404;Tanque do Piauí;PI
        3838;Tanque Novo - BA;2931053;3991;Tanque Novo;BA
        3839;Tanquinho - BA;2931103;3921;Tanquinho;BA
        5911;Taparuba - MG;3168051;738;Taparuba;MG
        163;Tapauá - AM;1304104;279;Tapauá;AM
        3427;Tapejara - PR;4126801;7911;Tapejara;PR
        1010;Tapejara - RS;4320909;8921;Tapejara;RS
        1011;Tapera - RS;4321006;8923;Tapera;RS
        3840;Taperoá - BA;2931202;3923;Taperoá;BA
        2405;Taperoá - PB;2516508;2231;Taperoá;PB
        1012;Tapes - RS;4321105;8925;Tapes;RS
        5912;Tapira - MG;3168101;5361;Tapira;MG
        3428;Tapira - PR;4126900;7973;Tapira;PR
        5913;Tapiraí - MG;3168200;5363;Tapiraí;MG
        4966;Tapiraí - SP;3553500;7171;Tapiraí;SP
        3841;Tapiramutá - BA;2931301;3925;Tapiramutá;BA
        4967;Tapiratiba - SP;3553609;7173;Tapiratiba;SP
        865;Tapurah - MT;5108006;9763;Tapurah;MT
        1013;Taquara - RS;4321204;8927;Taquara;RS
        5914;Taquaraçu de Minas - MG;3168309;5365;Taquaraçu de Minas;MG
        4968;Taquaral - SP;3553658;824;Taquaral;SP
        2644;Taquaral de Goiás - GO;5221007;9617;Taquaral de Goiás;GO
        590;Taquarana - AL;2709103;2881;Taquarana;AL
        1014;Taquari - RS;4321303;8929;Taquari;RS
        4969;Taquaritinga - SP;3553708;7175;Taquaritinga;SP
        1637;Taquaritinga do Norte - PE;2615003;2599;Taquaritinga do Norte;PE
        4970;Taquarituba - SP;3553807;7177;Taquarituba;SP
        4971;Taquarivaí - SP;3553856;3063;Taquarivaí;SP
        1015;Taquaruçu do Sul - RS;4321329;7313;Taquaruçu do Sul;RS
        397;Taquarussu - MS;5007976;9817;Taquarussu;MS
        4972;Tarabai - SP;3553906;7179;Tarabai;SP
        53;Tarauacá - AC;1200609;147;Tarauacá;AC
        1455;Tarrafas - CE;2313252;1275;Tarrafas;CE
        31;Tartarugalzinho - AP;1600709;617;Tartarugalzinho;AP
        4973;Tarumã - SP;3553955;7267;Tarumã;SP
        5915;Tarumirim - MG;3168408;5367;Tarumirim;MG
        1855;Tasso Fragoso - MA;2112001;933;Tasso Fragoso;MA
        4974;Tatuí - SP;3554003;7181;Tatuí;SP
        1456;Tauá - CE;2313302;1567;Tauá;CE
        4975;Taubaté - SP;3554102;7183;Taubaté;SP
        2406;Tavares - PB;2516607;2233;Tavares;PB
        1016;Tavares - RS;4321352;8971;Tavares;RS
        164;Tefé - AM;1304203;281;Tefé;AM
        2407;Teixeira - PB;2516706;2235;Teixeira;PB
        3842;Teixeira de Freitas - BA;2931350;3993;Teixeira de Freitas;BA
        3429;Teixeira Soares - PR;4127007;7913;Teixeira Soares;PR
        5916;Teixeiras - MG;3168507;5369;Teixeiras;MG
        101;Teixeirópolis - RO;1101559;22;Teixeirópolis;RO
        1457;Tejuçuoca - CE;2313351;1277;Tejuçuoca;CE
        4976;Tejupá - SP;3554201;7185;Tejupá;SP
        3430;Telêmaco Borba - PR;4127106;7915;Telêmaco Borba;PR
        240;Telha - SE;2807303;3245;Telha;SE
        1271;Tenente Ananias - RN;2414100;1879;Tenente Ananias;RN
        1272;Tenente Laurentino Cruz - RN;2414159;434;Tenente Laurentino Cruz;RN
        1017;Tenente Portela - RS;4321402;8931;Tenente Portela;RS
        2408;Tenório - PB;2516755;538;Tenório;PB
        3843;Teodoro Sampaio - BA;2931400;3927;Teodoro Sampaio;BA
        4977;Teodoro Sampaio - SP;3554300;7187;Teodoro Sampaio;SP
        3844;Teofilândia - BA;2931509;3929;Teofilândia;BA
        5917;Teófilo Otoni - MG;3168606;5371;Teófilo Otoni;MG
        3845;Teolândia - BA;2931608;3931;Teolândia;BA
        591;Teotônio Vilela - AL;2709152;971;Teotônio Vilela;AL
        398;Terenos - MS;5008008;9159;Terenos;MS
        2085;Teresina - PI;2211001;1219;Teresina;PI
        2645;Teresina de Goiás - GO;5221080;9759;Teresina de Goiás;GO
        486;Teresópolis - RJ;3305802;5915;Teresópolis;RJ
        1638;Terezinha - PE;2615102;2601;Terezinha;PE
        2646;Terezópolis de Goiás - GO;5221197;57;Terezópolis de Goiás;GO
        1105;Terra Alta - PA;1507961;373;Terra Alta;PA
        3431;Terra Boa - PR;4127205;7917;Terra Boa;PR
        1018;Terra de Areia - RS;4321436;7333;Terra de Areia;RS
        3846;Terra Nova - BA;2931707;3933;Terra Nova;BA
        1639;Terra Nova - PE;2615201;2603;Terra Nova;PE
        866;Terra Nova do Norte - MT;5108055;9909;Terra Nova do Norte;MT
        3432;Terra Rica - PR;4127304;7919;Terra Rica;PR
        3433;Terra Roxa - PR;4127403;7921;Terra Roxa;PR
        4978;Terra Roxa - SP;3554409;7189;Terra Roxa;SP
        1106;Terra Santa - PA;1507979;637;Terra Santa;PA
        867;Tesouro - MT;5108105;9161;Tesouro;MT
        1019;Teutônia - RS;4321451;9821;Teutônia;RS
        102;Theobroma - RO;1101609;975;Theobroma;RO
        1458;Tianguá - CE;2313401;1569;Tianguá;CE
        3434;Tibagi - PR;4127502;7923;Tibagi;PR
        1273;Tibau - RN;2411056;428;Tibau;RN
        1274;Tibau do Sul - RN;2414209;1881;Tibau do Sul;RN
        4979;Tietê - SP;3554508;7191;Tietê;SP
        2928;Tigrinhos - SC;4217956;946;Tigrinhos;SC
        2929;Tijucas - SC;4218004;8355;Tijucas;SC
        3435;Tijucas do Sul - PR;4127601;7925;Tijucas do Sul;PR
        1640;Timbaúba - PE;2615300;2605;Timbaúba;PE
        1275;Timbaúba dos Batistas - RN;2414308;1883;Timbaúba dos Batistas;RN
        2930;Timbé do Sul - SC;4218103;8393;Timbé do Sul;SC
        1856;Timbiras - MA;2112100;935;Timbiras;MA
        2931;Timbó - SC;4218202;8357;Timbó;SC
        2932;Timbó Grande - SC;4218251;9971;Timbó Grande;SC
        4980;Timburi - SP;3554607;7193;Timburi;SP
        1857;Timon - MA;2112209;937;Timon;MA
        5918;Timóteo - MG;3168705;5373;Timóteo;MG
        1020;Tio Hugo - RS;4321469;1174;Tio Hugo;RS
        5919;Tiradentes - MG;3168804;5375;Tiradentes;MG
        1021;Tiradentes do Sul - RS;4321477;6077;Tiradentes do Sul;RS
        5920;Tiros - MG;3168903;5377;Tiros;MG
        241;Tobias Barreto - SE;2807402;3247;Tobias Barreto;SE
        728;Tocantínia - TO;1721109;9619;Tocantínia;TO
        729;Tocantinópolis - TO;1721208;9621;Tocantinópolis;TO
        5921;Tocantins - MG;3169000;5379;Tocantins;MG
        5922;Tocos do Moji - MG;3169059;740;Tocos do Moji;MG
        5923;Toledo - MG;3169109;5381;Toledo;MG
        3436;Toledo - PR;4127700;7927;Toledo;PR
        242;Tomar do Geru - SE;2807501;3249;Tomar do Geru;SE
        3437;Tomazina - PR;4127809;7929;Tomazina;PR
        5924;Tombos - MG;3169208;5383;Tombos;MG
        1107;Tomé-Açu - PA;1508001;559;Tomé-Açu;PA
        165;Tonantins - AM;1304237;9851;Tonantins;AM
        1641;Toritama - PE;2615409;2607;Toritama;PE
        868;Torixoréu - MT;5108204;9163;Torixoréu;MT
        1022;Toropi - RS;4321493;1018;Toropi;RS
        4981;Torre de Pedra - SP;3554656;3227;Torre de Pedra;SP
        1023;Torres - RS;4321501;8933;Torres;RS
        4982;Torrinha - SP;3554706;7195;Torrinha;SP
        1276;Touros - RN;2414407;1885;Touros;RN
        4983;Trabiju - SP;3554755;826;Trabiju;SP
        1108;Tracuateua - PA;1508035;68;Tracuateua;PA
        1642;Tracunhaém - PE;2615508;2609;Tracunhaém;PE
        592;Traipu - AL;2709202;2883;Traipu;AL
        1109;Trairão - PA;1508050;635;Trairão;PA
        1459;Trairi - CE;2313500;1571;Trairi;CE
        487;Trajano de Moraes - RJ;3305901;5917;Trajano de Moraes;RJ
        1024;Tramandaí - RS;4321600;8935;Tramandaí;RS
        1025;Travesseiro - RS;4321626;6037;Travesseiro;RS
        3847;Tremedal - BA;2931806;3935;Tremedal;BA
        4984;Tremembé - SP;3554805;7197;Tremembé;SP
        1026;Três Arroios - RS;4321634;7331;Três Arroios;RS
        2933;Três Barras - SC;4218301;8359;Três Barras;SC
        3438;Três Barras do Paraná - PR;4127858;7987;Três Barras do Paraná;PR
        1027;Três Cachoeiras - RS;4321667;7329;Três Cachoeiras;RS
        5925;Três Corações - MG;3169307;5385;Três Corações;MG
        1028;Três Coroas - RS;4321709;8937;Três Coroas;RS
        1029;Três de Maio - RS;4321808;8939;Três de Maio;RS
        1030;Três Forquilhas - RS;4321832;5777;Três Forquilhas;RS
        4985;Três Fronteiras - SP;3554904;7199;Três Fronteiras;SP
        399;Três Lagoas - MS;5008305;9165;Três Lagoas;MS
        5926;Três Marias - MG;3169356;4115;Três Marias;MG
        1031;Três Palmeiras - RS;4321857;7327;Três Palmeiras;RS
        1032;Três Passos - RS;4321907;8941;Três Passos;RS
        5927;Três Pontas - MG;3169406;5387;Três Pontas;MG
        2647;Três Ranchos - GO;5221304;9623;Três Ranchos;GO
        488;Três Rios - RJ;3306008;5919;Três Rios;RJ
        2934;Treviso - SC;4218350;948;Treviso;SC
        2935;Treze de Maio - SC;4218400;8361;Treze de Maio;SC
        2936;Treze Tílias - SC;4218509;8363;Treze Tílias;SC
        2648;Trindade - GO;5221403;9625;Trindade;GO
        1643;Trindade - PE;2615607;2611;Trindade;PE
        1033;Trindade do Sul - RS;4321956;7325;Trindade do Sul;RS
        2409;Triunfo - PB;2516805;2237;Triunfo;PB
        1644;Triunfo - PE;2615706;2613;Triunfo;PE
        1034;Triunfo - RS;4322004;8943;Triunfo;RS
        1277;Triunfo Potiguar - RN;2414456;436;Triunfo Potiguar;RN
        1858;Trizidela do Vale - MA;2112233;258;Trizidela do Vale;MA
        2649;Trombas - GO;5221452;9761;Trombas;GO
        2937;Trombudo Central - SC;4218608;8365;Trombudo Central;SC
        2938;Tubarão - SC;4218707;8367;Tubarão;SC
        3848;Tucano - BA;2931905;3937;Tucano;BA
        1110;Tucumã - PA;1508084;397;Tucumã;PA
        1035;Tucunduva - RS;4322103;8945;Tucunduva;RS
        1111;Tucuruí - PA;1508100;561;Tucuruí;PA
        1859;Tufilândia - MA;2112274;260;Tufilândia;MA
        4986;Tuiuti - SP;3554953;2955;Tuiuti;SP
        5928;Tumiritinga - MG;3169505;5389;Tumiritinga;MG
        2939;Tunápolis - SC;4218756;9991;Tunápolis;SC
        1036;Tunas - RS;4322152;7323;Tunas;RS
        3439;Tunas do Paraná - PR;4127882;5455;Tunas do Paraná;PR
        3440;Tuneiras do Oeste - PR;4127908;7931;Tuneiras do Oeste;PR
        1860;Tuntum - MA;2112308;939;Tuntum;MA
        4987;Tupã - SP;3555000;7201;Tupã;SP
        5929;Tupaciguara - MG;3169604;5391;Tupaciguara;MG
        1645;Tupanatinga - PE;2615805;2615;Tupanatinga;PE
        1037;Tupanci do Sul - RS;4322186;5979;Tupanci do Sul;RS
        1038;Tupanciretã - RS;4322202;8947;Tupanciretã;RS
        1039;Tupandi - RS;4322251;7321;Tupandi;RS
        1040;Tuparendi - RS;4322301;8949;Tuparendi;RS
        1646;Tuparetama - PE;2615904;2617;Tuparetama;PE
        3441;Tupãssi - PR;4127957;7993;Tupãssi;PR
        4988;Tupi Paulista - SP;3555109;7203;Tupi Paulista;SP
        730;Tupirama - TO;1721257;102;Tupirama;TO
        731;Tupiratins - TO;1721307;365;Tupiratins;TO
        1861;Turiaçu - MA;2112407;941;Turiaçu;MA
        1862;Turilândia - MA;2112456;262;Turilândia;MA
        4989;Turiúba - SP;3555208;7205;Turiúba;SP
        5930;Turmalina - MG;3169703;5393;Turmalina;MG
        4990;Turmalina - SP;3555307;7207;Turmalina;SP
        1041;Turuçu - RS;4322327;1020;Turuçu;RS
        1460;Tururu - CE;2313559;1279;Tururu;CE
        2650;Turvânia - GO;5221502;9631;Turvânia;GO
        2651;Turvelândia - GO;5221551;9765;Turvelândia;GO
        3442;Turvo - PR;4127965;8453;Turvo;PR
        2940;Turvo - SC;4218806;8369;Turvo;SC
        5931;Turvolândia - MG;3169802;5395;Turvolândia;MG
        1863;Tutóia - MA;2112506;943;Tutóia;MA
        166;Uarini - AM;1304260;9849;Uarini;AM
        3849;Uauá - BA;2932002;3939;Uauá;BA
        5932;Ubá - MG;3169901;5397;Ubá;MG
        5933;Ubaí - MG;3170008;5399;Ubaí;MG
        3850;Ubaíra - BA;2932101;3941;Ubaíra;BA
        3851;Ubaitaba - BA;2932200;3943;Ubaitaba;BA
        1461;Ubajara - CE;2313609;1573;Ubajara;CE
        5934;Ubaporanga - MG;3170057;2671;Ubaporanga;MG
        4991;Ubarana - SP;3555356;2971;Ubarana;SP
        3852;Ubatã - BA;2932309;3945;Ubatã;BA
        4992;Ubatuba - SP;3555406;7209;Ubatuba;SP
        5935;Uberaba - MG;3170107;5401;Uberaba;MG
        5936;Uberlândia - MG;3170206;5403;Uberlândia;MG
        4993;Ubirajara - SP;3555505;7211;Ubirajara;SP
        3443;Ubiratã - PR;4128005;7933;Ubiratã;PR
        1042;Ubiretama - RS;4322343;1022;Ubiretama;RS
        4994;Uchoa - SP;3555604;7213;Uchoa;SP
        3853;Uibaí - BA;2932408;3947;Uibaí;BA
        16;Uiramutã - RR;1400704;38;Uiramutã;RR
        2652;Uirapuru - GO;5221577;59;Uirapuru;GO
        2410;Uiraúna - PB;2516904;2239;Uiraúna;PB
        1112;Ulianópolis - PA;1508126;623;Ulianópolis;PA
        1462;Umari - CE;2313708;1575;Umari;CE
        1278;Umarizal - RN;2414506;1887;Umarizal;RN
        243;Umbaúba - SE;2807600;3251;Umbaúba;SE
        3854;Umburanas - BA;2932457;3047;Umburanas;BA
        5937;Umburatiba - MG;3170305;5405;Umburatiba;MG
        2411;Umbuzeiro - PB;2517001;2241;Umbuzeiro;PB
        1463;Umirim - CE;2313757;9855;Umirim;CE
        3444;Umuarama - PR;4128104;7935;Umuarama;PR
        3855;Una - BA;2932507;3949;Una;BA
        5938;Unaí - MG;3170404;5407;Unaí;MG
        2086;União - PI;2211100;1221;União;PI
        1043;União da Serra - RS;4322350;5999;União da Serra;RS
        3445;União da Vitória - PR;4128203;7937;União da Vitória;PR
        5939;União de Minas - MG;3170438;742;União de Minas;MG
        2941;União do Oeste - SC;4218855;9973;União do Oeste;SC
        869;União do Sul - MT;5108303;1048;União do Sul;MT
        593;União dos Palmares - AL;2709301;2885;União dos Palmares;AL
        4995;União Paulista - SP;3555703;7215;União Paulista;SP
        3446;Uniflor - PR;4128302;7939;Uniflor;PR
        1044;Unistalda - RS;4322376;1024;Unistalda;RS
        1279;Upanema - RN;2414605;1889;Upanema;RN
        3447;Uraí - PR;4128401;7941;Uraí;PR
        3856;Urandi - BA;2932606;3951;Urandi;BA
        4996;Urânia - SP;3555802;7217;Urânia;SP
        1864;Urbano Santos - MA;2112605;945;Urbano Santos;MA
        4997;Uru - SP;3555901;7219;Uru;SP
        2653;Uruaçu - GO;5221601;9633;Uruaçu;GO
        2654;Uruana - GO;5221700;9635;Uruana;GO
        5940;Uruana de Minas - MG;3170479;744;Uruana de Minas;MG
        1113;Uruará - PA;1508159;399;Uruará;PA
        2942;Urubici - SC;4218905;8371;Urubici;SC
        1464;Uruburetama - CE;2313807;1577;Uruburetama;CE
        5941;Urucânia - MG;3170503;5409;Urucânia;MG
        167;Urucará - AM;1304302;285;Urucará;AM
        3857;Uruçuca - BA;2932705;3953;Uruçuca;BA
        2087;Uruçuí - PI;2211209;1223;Uruçuí;PI
        5942;Urucuia - MG;3170529;2699;Urucuia;MG
        168;Urucurituba - AM;1304401;287;Urucurituba;AM
        1045;Uruguaiana - RS;4322400;8951;Uruguaiana;RS
        1465;Uruoca - CE;2313906;1579;Uruoca;CE
        103;Urupá - RO;1101708;977;Urupá;RO
        2943;Urupema - SC;4218954;9975;Urupema;SC
        4998;Urupês - SP;3556008;7221;Urupês;SP
        2944;Urussanga - SC;4219002;8373;Urussanga;SC
        2655;Urutaí - GO;5221809;9637;Urutaí;GO
        3858;Utinga - BA;2932804;3955;Utinga;BA
        1046;Vacaria - RS;4322509;8953;Vacaria;RS
        870;Vale de São Domingos - MT;5108352;1102;Vale de São Domingos;MT
        104;Vale do Anari - RO;1101757;24;Vale do Anari;RO
        105;Vale do Paraíso - RO;1101807;979;Vale do Paraíso;RO
        1047;Vale do Sol - RS;4322533;5769;Vale do Sol;RS
        1048;Vale Real - RS;4322541;6049;Vale Real;RS
        1049;Vale Verde - RS;4322525;1026;Vale Verde;RS
        3859;Valença - BA;2932903;3957;Valença;BA
        489;Valença - RJ;3306107;5921;Valença;RJ
        2088;Valença do Piauí - PI;2211308;1225;Valença do Piauí;PI
        3860;Valente - BA;2933000;3959;Valente;BA
        4999;Valentim Gentil - SP;3556107;7223;Valentim Gentil;SP
        5000;Valinhos - SP;3556206;7225;Valinhos;SP
        5001;Valparaíso - SP;3556305;7227;Valparaíso;SP
        2656;Valparaíso de Goiás - GO;5221858;1066;Valparaíso de Goiás;GO
        1050;Vanini - RS;4322558;7319;Vanini;RS
        2945;Vargeão - SC;4219101;8375;Vargeão;SC
        2946;Vargem - SC;4219150;5563;Vargem;SC
        5002;Vargem - SP;3556354;2957;Vargem;SP
        5943;Vargem Alegre - MG;3170578;746;Vargem Alegre;MG
        315;Vargem Alta - ES;3205036;5727;Vargem Alta;ES
        5944;Vargem Bonita - MG;3170602;5411;Vargem Bonita;MG
        2947;Vargem Bonita - SC;4219176;5565;Vargem Bonita;SC
        1865;Vargem Grande - MA;2112704;947;Vargem Grande;MA
        5945;Vargem Grande do Rio Pardo - MG;3170651;748;Vargem Grande do Rio Pardo;MG
        5003;Vargem Grande do Sul - SP;3556404;7231;Vargem Grande do Sul;SP
        5004;Vargem Grande Paulista - SP;3556453;7273;Vargem Grande Paulista;SP
        5946;Varginha - MG;3170701;5413;Varginha;MG
        2657;Varjão - GO;5221908;9639;Varjão;GO
        5947;Varjão de Minas - MG;3170750;750;Varjão de Minas;MG
        1466;Varjota - CE;2313955;9857;Varjota;CE
        490;Varre-Sai - RJ;3306156;2917;Varre-Sai;RJ
        2412;Várzea - PB;2517100;2243;Várzea;PB
        1280;Várzea - RN;2414704;1891;Várzea;RN
        1467;Várzea Alegre - CE;2314003;1581;Várzea Alegre;CE
        2089;Várzea Branca - PI;2211357;2267;Várzea Branca;PI
        5948;Várzea da Palma - MG;3170800;5415;Várzea da Palma;MG
        3861;Várzea da Roça - BA;2933059;3997;Várzea da Roça;BA
        3862;Várzea do Poço - BA;2933109;3961;Várzea do Poço;BA
        871;Várzea Grande - MT;5108402;9167;Várzea Grande;MT
        2090;Várzea Grande - PI;2211407;1227;Várzea Grande;PI
        3863;Várzea Nova - BA;2933158;3995;Várzea Nova;BA
        5005;Várzea Paulista - SP;3556503;7233;Várzea Paulista;SP
        3864;Varzedo - BA;2933174;3049;Varzedo;BA
        5949;Varzelândia - MG;3170909;5417;Varzelândia;MG
        491;Vassouras - RJ;3306206;5923;Vassouras;RJ
        5950;Vazante - MG;3171006;5419;Vazante;MG
        1051;Venâncio Aires - RS;4322608;8955;Venâncio Aires;RS
        316;Venda Nova do Imigrante - ES;3205069;5729;Venda Nova do Imigrante;ES
        1281;Venha-Ver - RN;2414753;438;Venha-Ver;RN
        3448;Ventania - PR;4128534;5497;Ventania;PR
        1647;Venturosa - PE;2616001;2619;Venturosa;PE
        872;Vera - MT;5108501;9905;Vera;MT
        3865;Vera Cruz - BA;2933208;3963;Vera Cruz;BA
        1282;Vera Cruz - RN;2414803;1895;Vera Cruz;RN
        1052;Vera Cruz - RS;4322707;8957;Vera Cruz;RS
        5006;Vera Cruz - SP;3556602;7235;Vera Cruz;SP
        3449;Vera Cruz do Oeste - PR;4128559;7989;Vera Cruz do Oeste;PR
        2091;Vera Mendes - PI;2211506;406;Vera Mendes;PI
        1053;Veranópolis - RS;4322806;8959;Veranópolis;RS
        1648;Verdejante - PE;2616100;2621;Verdejante;PE
        5951;Verdelândia - MG;3171030;752;Verdelândia;MG
        3450;Verê - PR;4128609;7945;Verê;PR
        3866;Vereda - BA;2933257;3051;Vereda;BA
        5952;Veredinha - MG;3171071;754;Veredinha;MG
        5953;Veríssimo - MG;3171105;5423;Veríssimo;MG
        5954;Vermelho Novo - MG;3171154;756;Vermelho Novo;MG
        1649;Vertente do Lério - PE;2616183;2291;Vertente do Lério;PE
        1650;Vertentes - PE;2616209;2623;Vertentes;PE
        5955;Vespasiano - MG;3171204;5425;Vespasiano;MG
        1054;Vespasiano Corrêa - RS;4322855;1028;Vespasiano Corrêa;RS
        1055;Viadutos - RS;4322905;8961;Viadutos;RS
        1056;Viamão - RS;4323002;8963;Viamão;RS
        317;Viana - ES;3205101;5701;Viana;ES
        1866;Viana - MA;2112803;949;Viana;MA
        2658;Vianópolis - GO;5222005;9641;Vianópolis;GO
        1651;Vicência - PE;2616308;2625;Vicência;PE
        1057;Vicente Dutra - RS;4323101;8965;Vicente Dutra;RS
        400;Vicentina - MS;5008404;9187;Vicentina;MS
        2659;Vicentinópolis - GO;5222054;9657;Vicentinópolis;GO
        594;Viçosa - AL;2709400;2887;Viçosa;AL
        5956;Viçosa - MG;3171303;5427;Viçosa;MG
        1283;Viçosa - RN;2414902;1897;Viçosa;RN
        1468;Viçosa do Ceará - CE;2314102;1583;Viçosa do Ceará;CE
        1058;Victor Graeff - RS;4323200;8969;Victor Graeff;RS
        2948;Vidal Ramos - SC;4219200;8377;Vidal Ramos;SC
        2949;Videira - SC;4219309;8379;Videira;SC
        5957;Vieiras - MG;3171402;5429;Vieiras;MG
        2413;Vieirópolis - PB;2517209;540;Vieirópolis;PB
        1114;Vigia - PA;1508209;563;Vigia;PA
        873;Vila Bela da Santíssima Trindade - MT;5105507;9109;Vila Bela da Santíssima Trindade;MT
        2660;Vila Boa - GO;5222203;67;Vila Boa;GO
        1284;Vila Flor - RN;2415008;1899;Vila Flor;RN
        1059;Vila Flores - RS;4323309;7311;Vila Flores;RS
        1060;Vila Lângaro - RS;4323358;1030;Vila Lângaro;RS
        1061;Vila Maria - RS;4323408;7309;Vila Maria;RS
        2092;Vila Nova do Piauí - PI;2211605;408;Vila Nova do Piauí;PI
        1062;Vila Nova do Sul - RS;4323457;5795;Vila Nova do Sul;RS
        1867;Vila Nova dos Martírios - MA;2112852;264;Vila Nova dos Martírios;MA
        318;Vila Pavão - ES;3205150;2935;Vila Pavão;ES
        2661;Vila Propício - GO;5222302;1068;Vila Propício;GO
        874;Vila Rica - MT;5108600;9897;Vila Rica;MT
        319;Vila Valério - ES;3205176;768;Vila Valério;ES
        320;Vila Velha - ES;3205200;5703;Vila Velha;ES
        106;Vilhena - RO;1100304;13;Vilhena;RO
        5007;Vinhedo - SP;3556701;7237;Vinhedo;SP
        5008;Viradouro - SP;3556800;7239;Viradouro;SP
        5958;Virgem da Lapa - MG;3171600;5433;Virgem da Lapa;MG
        5959;Virgínia - MG;3171709;5435;Virgínia;MG
        5960;Virginópolis - MG;3171808;5437;Virginópolis;MG
        5961;Virgolândia - MG;3171907;5439;Virgolândia;MG
        3451;Virmond - PR;4128658;5483;Virmond;PR
        5962;Visconde do Rio Branco - MG;3172004;5441;Visconde do Rio Branco;MG
        1115;Viseu - PA;1508308;565;Viseu;PA
        1063;Vista Alegre - RS;4323507;7307;Vista Alegre;RS
        5009;Vista Alegre do Alto - SP;3556909;7241;Vista Alegre do Alto;SP
        1064;Vista Alegre do Prata - RS;4323606;7305;Vista Alegre do Prata;RS
        1065;Vista Gaúcha - RS;4323705;7303;Vista Gaúcha;RS
        2414;Vista Serrana - PB;2505501;2011;Vista Serrana;PB
        2950;Vitor Meireles - SC;4219358;9977;Vitor Meireles;SC
        321;Vitória - ES;3205309;5705;Vitória;ES
        5010;Vitória Brasil - SP;3556958;828;Vitória Brasil;SP
        3867;Vitória da Conquista - BA;2933307;3965;Vitória da Conquista;BA
        1066;Vitória das Missões - RS;4323754;6053;Vitória das Missões;RS
        1652;Vitória de Santo Antão - PE;2616407;2627;Vitória de Santo Antão;PE
        32;Vitória do Jari - AP;1600808;70;Vitória do Jari;AP
        1868;Vitória do Mearim - MA;2112902;951;Vitória do Mearim;MA
        1116;Vitória do Xingu - PA;1508357;641;Vitória do Xingu;PA
        3452;Vitorino - PR;4128708;7947;Vitorino;PR
        1869;Vitorino Freire - MA;2113009;953;Vitorino Freire;MA
        5963;Volta Grande - MG;3172103;5443;Volta Grande;MG
        492;Volta Redonda - RJ;3306305;5925;Volta Redonda;RJ
        5011;Votorantim - SP;3557006;7243;Votorantim;SP
        5012;Votuporanga - SP;3557105;7245;Votuporanga;SP
        3868;Wagner - BA;2933406;3967;Wagner;BA
        2093;Wall Ferraz - PI;2211704;410;Wall Ferraz;PI
        732;Wanderlândia - TO;1722081;9665;Wanderlândia;TO
        3869;Wanderley - BA;2933455;3999;Wanderley;BA
        5964;Wenceslau Braz - MG;3172202;5421;Wenceslau Braz;MG
        3453;Wenceslau Braz - PR;4128500;7943;Wenceslau Braz;PR
        3870;Wenceslau Guimarães - BA;2933505;3969;Wenceslau Guimarães;BA
        1067;Westfália - RS;4323770;1176;Westfália;RS
        2951;Witmarsum - SC;4219408;8381;Witmarsum;SC
        733;Xambioá - TO;1722107;9643;Xambioá;TO
        3454;Xambrê - PR;4128807;7949;Xambrê;PR
        1068;Xangri-lá - RS;4323804;5785;Xangri-lá;RS
        2952;Xanxerê - SC;4219507;8383;Xanxerê;SC
        54;Xapuri - AC;1200708;149;Xapuri;AC
        2953;Xavantina - SC;4219606;8385;Xavantina;SC
        2954;Xaxim - SC;4219705;8387;Xaxim;SC
        1653;Xexéu - PE;2616506;2293;Xexéu;PE
        1117;Xinguara - PA;1508407;571;Xinguara;PA
        3871;Xique-Xique - BA;2933604;3971;Xique-Xique;BA
        2415;Zabelê - PB;2517407;542;Zabelê;PB
        5013;Zacarias - SP;3557154;2973;Zacarias;SP
        1870;Zé Doca - MA;2114007;1287;Zé Doca;MA
        2955;Zortéa - SC;4219853;950;Zortéa;SC";
    }
}
