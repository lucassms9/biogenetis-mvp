<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PedidoCroquiValore Entity
 *
 * @property int $id
 * @property int|null $pedido_croqui_id
 * @property string|null $conteudo
 * @property string|null $coluna_linha
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\PedidoCroqui $pedido_croqui
 */
class PedidoCroquiValore extends Entity
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
        'pedido_croqui_id' => true,
        'conteudo' => true,
        'coluna_linha' => true,
        'created' => true,
        'modified' => true,
        'pedido_croqui' => true,
    ];
}
