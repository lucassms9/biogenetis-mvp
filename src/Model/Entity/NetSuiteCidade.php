<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * NetSuiteCidade Entity
 *
 * @property int $id
 * @property int $internal_id
 * @property string|null $nome
 * @property string|null $code_municipio
 * @property string $municipio_nome
 * @property string $uf
 *
 * @property \App\Model\Entity\Internal $internal
 */
class NetSuiteCidade extends Entity
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
        'internal_id' => true,
        'nome' => true,
        'code_municipio' => true,
        'municipio_nome' => true,
        'uf' => true,
    ];
}
