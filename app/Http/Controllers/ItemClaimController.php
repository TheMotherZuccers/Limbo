<?php

namespace App\Http\Controllers;

use App\ItemClaim;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ItemClaimController extends Controller {

    /**
     * Create a new Item instance
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request) {

        $item_claim = new ItemClaim;

        $item_claim->user_id = Auth::user()->id;
        $item_claim->item_id = $request->item_id;
        $item_claim->note = $request->note;

        $item_claim->save();

        return redirect('/item/'.$request->item_id);
    }

    public function approve_claim(Request $request) {
        $item_claim = ItemClaim::find($request->claim_id);

        $item_claim->claimed = 1;

        $item_claim->save();

        return redirect('/item/'.$request->item_id);
    }
}