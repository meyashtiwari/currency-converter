<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ImportCurrencyRates extends Command
{
    const BASE_CURRENCY = 'USD';

    const CACHE_RETENTION_SECONDS = 3600;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $rates = $this->getRates();

        $date = Carbon::parse($rates['date']);

        foreach ($rates['rates'] as $symbol => $rate) {
            $currency = Currency::updateOrCreate(['symbol' => $symbol]);

            $currency->rates()->updateOrCreate(compact('date'), compact('rate'));
        }

        $this->info('Currencies imported.');
    }

    private function getRates()
    {
        $url = config('services.apilayer.base_uri') . '/fixer/latest?base=' . self::BASE_CURRENCY;
        $apiKey = config('services.apilayer.api_key');

        $cacheKey = get_class($this) . ':getRates:' . md5($url . $apiKey);

        $body = Cache::remember($cacheKey, self::CACHE_RETENTION_SECONDS, function () use ($url, $apiKey) {
            $context = stream_context_create([
                'http' => [
                    'header' => "apikey: {$apiKey}\r\n"
                ]
            ]);
    
            return file_get_contents($url, false, $context);
        });


        return json_decode($body, true);
    }
}
