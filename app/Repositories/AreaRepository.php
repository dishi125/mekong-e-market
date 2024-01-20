<?php

namespace App\Repositories;

use App\Models\Area;
use App\Repositories\BaseRepository;

/**
 * Class AreaRepository
 * @package App\Repositories
 * @version February 1, 2020, 5:54 am UTC
*/

class AreaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'state_id',
        'name',
        'status'
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
        return Area::class;
    }
}
