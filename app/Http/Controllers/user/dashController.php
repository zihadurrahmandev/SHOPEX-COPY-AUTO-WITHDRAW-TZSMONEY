public function TzsmoneyWithdraw(Request $request)
{
    // Check if user is active
    if (auth()->user()->user_active == '0') {
        return redirect()->back()->with(['error' => 'Activate your account first.']);
    }

    // Validate request inputs
    $request->validate([
        "method" => "required",
        "number" => "required",
        "amount" => "required|numeric"
    ]);

    $method = $request->method;
    $number = $request->number;
    $amount = $request->amount;

    // Fetch minimum withdraw amount
    $minimum = Setting::where('key', 'tzsmoney_minimum_withdraw')->value('value');
    if ($amount < $minimum) {
        return redirect()->back()->with(['error' => 'Minimum withdrawal amount is ' . $minimum]);
    }

    // Check balance
    $user = auth()->user();
    if ($user->balance < $amount) {
        return redirect()->back()->with(['error' => 'Insufficient balance.']);
    }

    // Fetch API key
    $apiKey = Setting::where('key', 'tzsmoney_api_key')->value('value');

    // Make API call to Tzsmoney
    $response = Http::get("http://tzsmoney.com/api/transfer", [
        'api_key' => $apiKey,
        'method' => $method,
        'number' => $number,
        'amount' => $amount
    ]);

    // Decode response
    $data = $response->json();

    if ($response->successful() && isset($data['status']) && $data['status'] == true) {
        $updatedBalance = $user->balance - $amount;
        $user->update(['balance' => $updatedBalance]);

        Withdraw::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'charges' => 0,
            'wallet' => $number,
            'method' => $method,
            'status' => 'Success',
        ]);

        return redirect()->back()->with(['success' => 'Withdrawal successful.']);
    } else {
        $errorMessage = $data['message'] ?? 'An error occurred with the API.';
        return redirect()->back()->with(['error' => 'Withdrawal failed: ' . $errorMessage]);
    }
}
