<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Amostra Entity
 *
 * @property int $id
 * @property string|null $code_amostra
 * @property string|null $uf
 * @property int|null $idade
 * @property string|null $sexo
 *
 * @property \App\Model\Entity\Exame[] $exames
 */
class Amostra extends Entity
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
        'code_amostra' => true,
        'uf' => true,
        'idade' => true,
        'sexo' => true,
        'exames' => true,
        'created' => true,
        'modified' => true,
    ];
}
