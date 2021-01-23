<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EncadeamentoResultado Entity
 *
 * @property int $id
 * @property int|null $exame_origem_id
 * @property int|null $escademanto_id
 * @property string|null $resultado
 *
 * @property \App\Model\Entity\ExameOrigem $exame_origem
 * @property \App\Model\Entity\Escademanto $escademanto
 */
class EncadeamentoResultado extends Entity
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
        'exame_origem_id' => true,
        'encadeamento_id' => true,
        'resultado' => true,
    ];
}
