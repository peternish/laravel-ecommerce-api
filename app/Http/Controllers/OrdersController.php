<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrdersController extends Controller
{
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
                $user = User::create([
                    'email' => $request->email,
                    'password' => $password
                ]);
            }
            else $user = auth()->user();

            $data = $request->only(['value', 'cost']);
            $data['user_id'] = $user->id;
            $order = Order::create($data);

            $result = [
                'success' => true,
                'data' => [
                    'order' => new OrderResource($order)
                ]
            ];

            if (!auth()->check()){
                $result['data']['user'] = [
                    'email' => $user->email,
                    'password' => $password
                ];
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
     * Validation rules
     *
     * @return array
     */
    private function getRules()
    {
        $rules = [
            'value' => 'required|integer'
        ];

        if (!auth()->check())
            $rules['email'] = 'required|email|unique:users,email';

        return $rules;
    }
}
