<?php

namespace App\Repositories;

use App\Models\WeightUnit;
use App\Repositories\BaseRepository;

/**
 * Class WeightUnitRepository
 * @package App\Repositories
 * @version May 15, 2020, 8:16 am UTC
*/

class WeightUnitRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'unit'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return WeightUnit::class;
    }
}
