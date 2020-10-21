<?php

namespace App\Repositories;

use App\Http\Resources\Reviews\ReviewCollection;
use App\Review;
use App\Traits\ApiResponser;


class AdminRepository 
{


    use ApiResponser;


    /**
     * Undocumented function
     *
     * @return void
     */
    public function allReviews()
    {

        return ReviewCollection::collection(Review::paginate(10));

    }



}