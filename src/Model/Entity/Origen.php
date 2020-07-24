<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Origen Entity
 *
 * @property int $id
 * @property string|null $nome_origem
 * @property string|null $url_request
 */
class Origen extends Entity
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
        'nome_origem' => true,
        'url_request' => true,
        'ativo' => true,
        'equip_tipo' => true,
        'regra_encadeamento' => true,
        'amostra_tipo' => true,
        'IAModelType' => true,
        'IAModelName' => true,
        'DataScience' => true,
    ];
}
