<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellerOnboardingController extends Controller
{
    //
    public function show(){
        // Aleady onboarded - go straight to create listing
        if (auth()->user()->hasCompletedOnboarding()){
            return redirect()->route('listings.create');
        }
        return view('seller.onboarding');
    }
    public function store(Request $request){
        $request->validate([
            'full_name'            => 'required|string|max:255',
            'country'              => 'required|string|max:10',
            'email'                => 'required|email',
            'messenger_type'       => 'nullable|string|max:50',
            'messenger_link'       => 'nullable|string|max:255',
            'phone_country_code'   => 'required|string|max:10',
            'phone_number'         => 'required|string|max:20',
            'seller_games'         => 'required|string|max:500',
            'seller_stock_source'  => 'required|in:self_farmed,resell',
            'seller_sells_elsewhere' => 'required|in:0,1',
            'seller_other_platforms' => 'nullable|string|max:255',
            'agree_rules'          => 'accepted',
        ]);
        // Build messenger value
        $messenger = null;
        if ($request->messenger_type && $request->messenger_link){
            $messenger= $request->messenger_type . ':' . $request->messenger_link;
        }

        auth()->user()->update([
            'full_name' => $request->full_name,
            'country' => $request->country,
            'phone_country_code' => $request->phone_country_code,
            'phone_number' => $request->phone_number,
            'telegram' => $request->messenger_type === 'telegram' ? $request->messenger_link : auth()->user()->telegram,
            'whatsapp' => $request->messenger_type === 'whatsapp' ? $request->messenger_link : auth()->user()->whatsapp,
            'discord' => $request->messenger_type === 'discord' ? $request->messenger_link : auth()->user()->discord,
            'seller_games' => $request->seller_games,
            'seller_stock_source' => $request->seller_stock_source,
            'seller_sells_elsewhere' => $request->seller_sells_elsewhere,
            'seller_other_platforms' => $request->seller_other_platforms,
            'seller_onboarded' => true,
            'profile_completed' => true,
        ]);
        return redirect()
            ->route('listings.create')
            ->with('success', 'Welcome! You are now a verified seller. Create your first listing!');
    }
}
