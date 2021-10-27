<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Retry\RetryStrategyInterface;
use Cake\Http\Client;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use App\Model\Entity\Paciente;

/**
 * Codigo para resultados de exames codigo
 *  1	Detectável
 *  2	Não Detectável
 *  3	Inconclusivo
 */

class RNDSComponent extends Component
{
    public $components = ['ExamesData', 'PacientesData'];

    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->client = new Client();
        $this->BASE_URL_RNDS = env('BASE_URL_RNDS', '');
        $this->BASE_URL_RNDS_TOKEN = env('BASE_URL_RNDS_TOKEN', '');
        $this->PASS_RNDS = env('PASS_RNDS', '');
        $this->Pedidos = TableRegistry::get('Pedidos');
        $this->Configuracoes = TableRegistry::get('Configuracoes');
    }

    private function getToken()
    {

        $options = [
            'headers' => [],
            'hostname' => 'ehr-auth-hmg.saude.gov.br',
            'ssl_local_cert' => CERTIFICATES_AUTH . 'cert.pem',
            'passphrase' => $this->PASS_RNDS,
        ];

        $res = $this->client->get($this->BASE_URL_RNDS_TOKEN, [], $options);

        $res = json_decode($res->getStringBody());

        return $res->access_token;
    }

    private function builderBody($pedido, $config)
    {


        $now = FrozenTime::now();
        $resultado = [
            'Positivo' => '1',
            'Negativo' => '2',
            'Indeterminado' => '3',
        ];

        $body = [
            "resourceType" => "Bundle",
            "meta" => [
                "lastUpdated" => $now
            ],
            "identifier" => [
                "system" => "http://www.saude.gov.br/fhir/r4/NamingSystem/BRRNDS-20431",
                "value" => $pedido->id
            ],
            "type" => "document",
            "timestamp" => "2020-10-20T08:23:56.567-02:00",
            "entry" => [
                [
                    "fullUrl" => "urn:uuid:transient-0",
                    "resource" => [
                        "resourceType" => "Composition",
                        "meta" => [
                            "profile" => [
                                "http://www.saude.gov.br/fhir/r4/StructureDefinition/BRResultadoExameLaboratorial-1.1"
                            ]
                        ],
                        "status" => "final",
                        "type" => [
                            "coding" => [
                                [
                                    "system" => "http://www.saude.gov.br/fhir/r4/CodeSystem/BRTipoDocumento",
                                    "code" => "REL"
                                ]
                            ]
                        ],
                        "subject" => [
                            "identifier" => [
                                "system" => "http://www.saude.gov.br/fhir/r4/StructureDefinition/BRIndividuo-1.0",
                                "value" => "700500572752652"
                            ]
                        ],
                        "date" => "2020-10-20T08:30:12.947-02:00",
                        "author" => [
                            [
                                "identifier" => [
                                    "system" => "http://www.saude.gov.br/fhir/r4/StructureDefinition/BREstabelecimentoSaude-1.0",
                                    "value" => "9846972"
                                ]
                            ]
                        ],
                        "title" => "Resultado de Exame Laboratorial",
                        "section" => [
                            [
                                "entry" => [
                                    [
                                        "reference" => "urn:uuid:transient-1"
                                    ]
                                ]
                            ]
                        ],
                    ],
                ],

                [
                    "fullUrl" => "urn:uuid:transient-1",
                    "resource" => [
                        "resourceType" => "Observation",
                        "meta" => [
                            "profile" => [
                                "http://www.saude.gov.br/fhir/r4/StructureDefinition/BRDiagnosticoLaboratorioClinico-1.0"
                            ]
                        ],
                        "status" => "final",
                        "category" => [
                            [
                                "coding" => [
                                    [
                                        "system" => "http://www.saude.gov.br/fhir/r4/CodeSystem/BRSubgrupoTabelaSUS",
                                        "code" => "0214"
                                    ]
                                ]
                            ]
                        ],
                        "code" => [
                            "coding" => [
                                [
                                    "system" => "http://www.saude.gov.br/fhir/r4/CodeSystem/BRNomeExameGAL",
                                    "code" => "coronavirusnCoV"
                                ]
                            ]
                        ],
                        "subject" => [
                            "identifier" => [
                                "system" => "http://www.saude.gov.br/fhir/r4/StructureDefinition/BRIndividuo-1.0",
                                "value" => "700500572752652"
                            ]
                        ],
                        "issued" => "2020-10-20T08:30:12.947-02:00",
                        "performer" => [
                            [
                                "identifier" => [
                                    "system" => "http://www.saude.gov.br/fhir/r4/StructureDefinition/BREstabelecimentoSaude-1.0",
                                    "value" => "980016282253506-2695294"
                                ]
                            ]
                        ],
                        "valueCodeableConcept" => [
                            "coding" => [
                                [
                                    "system" => "http://www.saude.gov.br/fhir/r4/CodeSystem/BRResultadoQualitativoExame",
                                    "code" => "3"
                                ]
                            ]
                        ],
                        "interpretation" => [
                            [
                                "coding" => [
                                    [
                                        "system" => "http://www.saude.gov.br/fhir/r4/CodeSystem/BRResultadoQualitativoExame",
                                        "code" => "2"
                                    ]
                                ]
                            ]
                        ],
                        "note" => [
                            [
                                "text" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum mauris velit, maximus a vulputate nec, interdum ut mauris. Aenean a."
                            ]
                        ],
                        "method" => [
                            "text" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit."
                        ],
                        "specimen" => [
                            "reference" => "urn:uuid:transient-2"
                        ],
                        "referenceRange" => [
                            [
                                "text" => "Vestibulum mauris velit, maximus a vulputate nec, interdum ut mauris."
                            ]
                        ]
                    ]
                ],
                [
                    "fullUrl" => "urn:uuid:transient-2",
                    "resource" => [
                        "resourceType" => "Specimen",
                        "meta" => [
                            "profile" => [
                                "http://www.saude.gov.br/fhir/r4/StructureDefinition/BRAmostraBiologica-1.0"
                            ]
                        ],
                        "type" => [
                            "coding" => [
                                [
                                    "system" => "http://www.saude.gov.br/fhir/r4/CodeSystem/BRTipoAmostraGAL",
                                    "code" => "SECONF"
                                ]
                            ]
                        ]
                    ]
                ]
            ],
        ];
        return $body;
    }

    public function sendResultExam($pedido_id)
    {

        try {
            $pedido = $this->Pedidos->get($pedido_id, [
                'contain' => ['Anamneses.Pacientes', 'Exames']
            ]);

            if (!empty($pedido->exame)) {
                $pedido->exame = $this->ExamesData->getExamesResult($pedido->exame);
            }
            if (!empty($pedido->anamnese->paciente)) {
                $resPaciente = $this->PacientesData->getByHash($pedido->anamnese->paciente->hash);
                $res = json_decode($resPaciente, true);
                $pedido->anamnese->paciente = new Paciente($res);
            }


            $token = $this->getToken();


            $config = $this->Configuracoes->find('all', [])->first();

            $body = $this->builderBody($pedido, $config);

            $res = $this->client->post($this->BASE_URL_RNDS . '/api/fhir/r4/Bundle', json_encode($body), [
                'headers' => [
                    'Content-Type' => 'application/fhir+json;charset=UTF-8',
                    'X-Authorization-Server' => 'Bearer ' . $token,
                    'Authorization' => $config->cns_profissional_rnds
                ]
            ]);

            // debug($token);
            // debug($res->body);
            // debug($body);
            // die;

            return $res;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
