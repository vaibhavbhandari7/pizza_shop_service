<?php

    namespace App\Http\Controllers;

    use App\User;
    use App\Menu;
    use App\Order;
    use JWTAuth;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Http\Request;

    class DataController extends Controller
    {
            public function allMenu() 
            {
                $menu = Menu::all();
                foreach ($menu as $m) {
                    $m['quantity'] = 0;
               }
                return response()->json(compact('menu'));
            }

            public function placeOrder(Request $request) 
            {
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:255',
                    'mobile' => 'required|string|max:255',
                    'total' => 'required|numeric',
                    'address' => 'required|string|max:255',
                    'cart' => 'required'
                ]);

                $order = $request->all();
                $order['cart'] =  json_encode($order['cart']);
                try {
                    $user = JWTAuth::parseToken()->authenticate();
                    $order = array_merge($order, ['user_id' => $user->id ]);
                } catch (Exception $e) {
                    
                }

                $created = Order::create($order);
                return response()->json(compact('created'));
            }
    }