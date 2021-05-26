<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Configuraco Entity
 *
 * @property int $id
 * @property string|null $cns_profissional_rnds
 * @property string|null $cnes_rnds
 */
class Configuraco extends Entity
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
        'cns_profissional_rnds' => true,
        'cnes_rnds' => true,
    ];
}
