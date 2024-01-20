<?php

namespace App\Repositories;

use App\Models\Specie;
use App\Repositories\BaseRepository;

/**
 * Class SpecieRepository
 * @package App\Repositories
 * @version February 1, 2020, 3:40 am UTC
*/

class SpecieRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'main_category_id',
        'sub_category_id',
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
        return Specie::class;
    }
}
