<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

//Just for example
class CheckOrdersPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new \GuzzleHttp\Client();
        $orders = Order::open()->get();

        foreach ($orders as $order){
            $response = $client->request('GET', 'https://api.bank_site/transfers', [
                'api_key' => 'XYZ'
            ]);

            if ($response->getStatusCode() == 200){
                $data = $response->getBody();
                $data = json_decode($data);

                foreach ($data['transfers'] as $transfer){
                    if ($transfer->value == $order->value && $transfer->title == 'Payment for order: '.$order->id){
                        $order = $order->first();
                        $order->update(['status' => 1]);
                    }
                }
            }
        }
    }
}
