<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Pedido Entity
 *
 * @property int $id
 * @property int|null $anamnese_id
 * @property int|null $amostra_id
 * @property int|null $cliente_id
 * @property string|null $forma_pagamento
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $created_by
 *
 * @property \App\Model\Entity\Anamnese $anamnese
 * @property \App\Model\Entity\Amostra $amostra
 * @property \App\Model\Entity\Cliente $cliente
 */
class Pedido extends Entity
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
        'anamnese_id' => true,
        'amostra_id' => true,
        'cliente_id' => true,
        'forma_pagamento' => true,
        'created' => true,
        'modified' => true,
        'codigo_pedido' => true,
        'voucher_id' => true,
        'created_by' => true,
        'anamnese' => true,
        'status' => true,
        'amostra' => true,
        'cliente' => true,
        'entrada_exame_id' => true,
    ];
}
