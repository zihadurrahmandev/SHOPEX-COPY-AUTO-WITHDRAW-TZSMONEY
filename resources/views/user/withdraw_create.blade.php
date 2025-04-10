
@if(\App\Models\Setting::where('key', 'tzsmoney_withdraw_status')->first()->value == 1)
<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title mb-3" style="font-size: 16px;">Withdraw Funds</h5>
        <form action="{{ route('TzsmoneyWithdraw') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="amount" class="form-label">Amount (৳)</label>
                <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter amount to withdraw" min="1" step="0.01" required>
            </div>

            <div class="mb-3">
                <label for="method" class="form-label">Withdraw Method</label>
                <select class="form-select" id="method" name="method" required>
                    <option value="">-- Select Method --</option>
                    <option value="bkash">bKash</option>
                    <option value="nagad">Nagad</option>
                    <option value="rocket">Rocket</option>
                    <option value="upay">Upay</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Your Payment Number</label>
                <input type="text" class="form-control" id="address" name="number" placeholder="Enter bKash/Nagad/Bank account number" required>
            </div>

            <button type="submit" class="btn btn-success w-100">Submit Withdrawal Request</button>
        </form>
    </div>
</div>

@else

//Here Current Withdraw System

@endif
