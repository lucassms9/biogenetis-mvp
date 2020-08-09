<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Cliente Entity
 *
 * @property int $id
 * @property string|null $nome_fantasia
 * @property string|null $razao_social
 * @property string|null $cnpj_cpf
 * @property string|null $cep
 * @property string|null $endereco
 * @property string|null $bairro
 * @property string|null $cidade
 * @property string|null $uf
 * @property string|null $responsavel_nome
 * @property string|null $responsavel_email
 * @property string|null $responsavel_telefone
 * @property string|null $responsavel_financeiro_nome
 * @property string|null $responsavel_financeiro_email
 * @property string|null $responsavel_financeiro_telefone
 * @property string|null $tipo_cobranca
 * @property bool|null $ativo
 *
 * @property \App\Model\Entity\User[] $users
 */
class Cliente extends Entity
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
        'nome_fantasia' => true,
        'razao_social' => true,
        'cnpj_cpf' => true,
        'cep' => true,
        'endereco' => true,
        'bairro' => true,
        'cidade' => true,
        'uf' => true,
        'responsavel_nome' => true,
        'responsavel_email' => true,
        'responsavel_telefone' => true,
        'responsavel_financeiro_nome' => true,
        'responsavel_financeiro_email' => true,
        'responsavel_financeiro_telefone' => true,
        'tipo_cobranca' => true,
        'ativo' => true,
        'users' => true,
    ];
}
