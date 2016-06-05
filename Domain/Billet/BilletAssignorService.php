<?php

namespace Domain\Billet;

use Domain\Billet\Http\Requests\BilletAssignor\StoreRequest as Request;

class BilletAssignorService
{
    protected $repo;

    public function __construct(BilletAssignorRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Update or create Billet Assignor.
     *
     * @param  Request  $request
     * @return BilletAssignor
     */
    public function updateOrCreate(Request $request)
    {
        $assignor = $this->repo->first();

        if (is_null($assignor)) {
            $assignor = $this->repo->store($request->all());
        } else {
            $assignor = $this->repo->update($request->all(), $assignor->id);
        }

        if ($request->hasFile('logo') && !$request->file('logo')->move(public_path(), 'logo.jpg')) {
            return false;
        }

        return $assignor;
    }
}
