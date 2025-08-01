<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProviderController extends Controller
{
    function index()
    {
        return view('admin.provider.Index');
    }

    function filter()
    {
        $data['providers'] = Provider::paginate(20);

        return view('admin.provider._table', $data);
    }

    function details(Provider $provider)
    {
        $data['provider'] = $provider;

        return view('admin.provider._form', $data);
    }

    public function toggleFeature(Request $request, Provider $provider)
    {
        $data = $request->validate([
            'feature' => 'required|in:is_default,has_transaction,has_account',
            'value' => 'required',
        ]);

        $feature = $data['feature'];
        $value = $data['value'];

        DB::transaction(function () use ($provider, $feature, $value) {
            if ($feature === 'is_default' && $value) {
                // If setting is_default = true,
                // remove is_default from all other providers
                // that have has_transaction OR has_account true
                Provider::where('id', '!=', $provider->id)
                    ->where(function ($query) {
                        $query->where('has_transaction', true)
                            ->orWhere('has_account', true);
                    })
                    ->update(['is_default' => false]);
            }

            $provider->update([
                $feature => $value
            ]);
        });

        return response()->json(['message' => 'Feature updated successfully.']);
    }

    function update(Request $request) {}
}
