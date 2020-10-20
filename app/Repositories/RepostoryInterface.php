<?php

namespace App\Repositories;



interface RepositoryInterface
{


    public function index();

    public function show($model);

    public function update($model, $data);

    public function destroy($model);
}
