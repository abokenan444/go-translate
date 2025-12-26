<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * List users for authenticated mobile clients.
     */
    public function index(Request $request)
    {
        $authUser = $request->user();

        $users = User::query()
            ->whereKeyNot($authUser->getKey())
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'email',
                'account_type',
                'status',
                'created_at',
            ]);

        return response()->json([
            'success' => true,
            'data' => $users,
        ], 200);
    }
}
