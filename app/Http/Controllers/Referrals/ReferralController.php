<?php

namespace App\Http\Controllers\Referrals;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReferralFormRequest;
use App\Http\Resources\ReferralResourceCollection;
use App\Mail\Referrals\ReferralReceived;
use App\Models\Project;
use App\Models\User;
use App\Rules\NotReferringExisting;
use App\Rules\NotReferringSelf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReferralController extends Controller
{
	public function index(Request $request)
	{
		$referrals = $request->user()->referrals()->orderBy('completed_at', 'asc')->get();

		return new ReferralResourceCollection($referrals);
	}

    public function store(ReferralFormRequest $request, Project $project)
    {
        $this->authorize('create', $project);

        if ($user = User::whereEmail($request->email)->first()){

            return $project->invite( $user );

        };        

    	$referral = $request->user()->referrals()->create($request->only('email'));

    	Mail::to($referral->email)->send(
    		new ReferralReceived($request->user(), $referral)
    	);
    }
}
