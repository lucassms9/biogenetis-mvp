<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ExameOrigen Entity
 *
 * @property int $id
 * @property int|null $exame_id
 * @property int|null $origem_id
 *
 * @property \App\Model\Entity\Exame $exame
 * @property \App\Model\Entity\Origem $origem
 */
class ExameOrigen extends Entity
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
        'exame_id' => true,
        'origem_id' => true,
        'exame' => true,
        'resultado' => true,
        'data_request' => true,
        'origem' => true,
    ];
}
