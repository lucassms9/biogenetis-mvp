<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Paciente Entity
 *
 * @property int $id
 * @property string|null $hash
 * @property string|null $nome
 * @property string|null $cpf
 * @property string|null $rg
 * @property string|null $email
 * @property string|null $celular
 * @property string|null $telefone
 * @property string|null $sexo
 * @property \Cake\I18n\FrozenDate|null $data_nascimento
 * @property string|null $endereco
 * @property string|null $bairro
 * @property string|null $cep
 * @property string|null $cidade
 * @property string|null $uf
 * @property string|null $foto_perfil_url
 * @property string|null $foto_doc_url
 * @property string|null $nome_da_mae
 * @property string|null $nacionalidade
 * @property string|null $pais_residencia
 *
 * @property \App\Model\Entity\Exame[] $exames
 */
class Paciente extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'hash' => true,
        'senha' => true,
        'token_mobile' => true,
        /*  , 'nome' => true,
        'cpf' => true,
        'rg' => true,
        'email' => true,
        'celular' => true,
        'telefone' => true,
        'sexo' => true,
        'data_nascimento' => true,
        'endereco' => true,
        'bairro' => true,
        'cep' => true,
        'cidade' => true,
        'uf' => true,
        'foto_perfil_url' => true,
        'foto_doc_url' => true,
        'nome_da_mae' => true,
        'nacionalidade' => true,
        'pais_residencia' => true,
        'exames' => true,*/
    ];
}
