<?php
namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CustomersVirtualCard;
use Illuminate\Support\Facades\Auth;

class CustomersVirtualCardController extends Controller
{
    /**
     * Store a new virtual card in the database.
     */
    public function store(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'card_name' => 'required|string|max:255',
            'card_type' => 'required|string|in:master_card,visa_card',
            'card_number' => 'required|string|max:16|min:16',
            'card_expiry' => 'required|string|max:5|min:5', // Format MM/YY
            'card_cvv' => 'required|string|max:3|min:3',
            'country' => 'required|string',
        ]);

        // Store the card details
        CustomersVirtualCard::create([
            'user_id' => Auth::id(),
            'card_name' => $request->card_name,
            'card_type' => $request->card_type,
            'card_number' => $request->card_number,
            'card_expiry' => $request->card_expiry,
            'card_cvv' => $request->card_cvv,
            'country' => $request->country,
        ]);

        // Redirect with success message
        return redirect()->back()->with('success', __('Card created successfully.'));
    }
}

?>