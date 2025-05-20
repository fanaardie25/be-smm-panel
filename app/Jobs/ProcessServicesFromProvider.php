<?php

namespace App\Jobs;

use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProcessServicesFromProvider implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $profit;

    /**
     * Create a new job instance.
     */
    public function __construct($profit)
    {
        $this->profit = $profit;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $key = env('PROVIDER_API_KEY');

        $response = Http::post('https://indosmm.id/api/v2', [
            'key' => $key,
            'action' => 'services',
        ]);

        if ($response->successful()) {
            $services = $response->json();

            foreach ($services as $serviceData) {
                $finalPrice = $serviceData['rate'] + ($serviceData['rate'] * ($this->profit / 100));

                Service::updateOrCreate(
                    ['service' => $serviceData['service']],
                    [
                        'name' => $serviceData['name'],
                        'type' => $serviceData['type'],
                        'category' => $serviceData['category'],
                        'rate' => $serviceData['rate'],
                        'min' => $serviceData['min'],
                        'max' => $serviceData['max'],
                        'refill' => $serviceData['refill'],
                        'cancel' => $serviceData['cancel'],
                        'final_price' => $finalPrice,
                    ]
                );
            }
        }
    }
}
