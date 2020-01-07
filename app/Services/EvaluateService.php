<?php

namespace App\Services;

// use App\Repositories\MemberRepository;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Jobs\Met_Evaluate;

class EvaluateService
{
    // protected $MemberRepo;
    // public function __construct(MemberRepository $MemberRepo)
    // {
    //     $this->MemberRepo = $MemberRepo;
    // }

    // public function GetAll()
    // {
    //     return $this->MemberRepo->GetAll();
    // }


    public function Met_Evaluate($start,$end)
    {
        dispatch(new Met_Evaluate($start,$end));
    }
}
