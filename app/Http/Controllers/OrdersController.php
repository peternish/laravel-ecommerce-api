<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
use App\Models\Order;
use App\Models\User;
use App\Traits\CalculateOrderValue;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrdersController extends Controller
{
    use CalculateOrderValue;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->getRules());

        DB::beginTransaction();
        try{
            if (!auth()->check()){
                $password = Str::random(8);
                $hashed = Hash::make($password);
                $user = User::create([
                    'email' => $request->email,
                    'password' => $hashed
                ]);
                $isNewUser = true;
            }
            else {
                $user = auth()->user();
                $isNewUser = false;
            }

            $orderValue = $this->calculateOrderValue($request->products);
            $order = Order::create([
                'user_id' => $user->id,
                'value' => $orderValue
            ]);

            $result = [
                'success' => true,
                'data' => [
                    'order' => new OrderResource($order)
                ]
            ];

            if ($isNewUser){
                $result['data']['user'] = $user;
                $result['data']['password'] = $password;
                $result['data']['token'] = $user->createToken('access_token')->plainTextToken;
            }
            DB::commit();
            return response()->json($result);
        }
        catch (Exception $e){
            DB::rollBack();

            return response()->json([
                'success' => false
            ]);
        }
    }

    /**
     * Validation rules.
     *
     * @return array
     */
    private function getRules(): array
    {
        $rules = [
            'products' => 'required|array',
            'products.*' => 'required|integer|exists:products,id'
        ];

        if (!auth()->check())
            $rules['email'] = 'required|email|unique:users,email';

        return $rules;
    }
}
