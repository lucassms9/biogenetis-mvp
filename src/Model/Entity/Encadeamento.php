<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Encadeamento Entity
 *
 * @property int $id
 * @property int|null $origem_parent_id
 * @property int|null $origem_encadeamento_id
 * @property string|null $regra
 *
 * @property \App\Model\Entity\OrigemParent $origem_parent
 * @property \App\Model\Entity\OrigemEncadeamento $origem_encadeamento
 */
class Encadeamento extends Entity
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
        'origem_parent_id' => true,
        'origem_encadeamento_id' => true,
        'regra' => true,
        'ordem' => true,
        'origem_parent' => true,
        'origem_encadeamento' => true,
    ];
}
