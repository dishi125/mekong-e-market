<?php

namespace App\Repositories;

use App\Models\MainCategory;
use App\Repositories\BaseRepository;
use App\Models\SubCategory;

/**
 * Class MainCategoryRepository
 * @package App\Repositories
 * @version January 31, 2020, 6:39 am UTC
*/

class MainCategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
        return MainCategory::class;
    }
    public function getCategoriesData()
    {
        return MainCategory::with('subcategories_dropdown.species_dropdown')->where('status',1)->get();
    }
}
