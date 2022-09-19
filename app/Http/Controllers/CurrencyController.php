<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use App\Models\CurrencyRate;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Return list of currencies.
     */
    const CACHE_RETENTION_SECONDS = 3600;


    public function index($sCode)
    {
        $date = CurrencyRate::max('date');

        $currencies = Currency::query()
            ->leftJoin('currency_rates', function ($join) use ($date, $sCode) {
                $join->on('currency_rates.currency_id', '=', 'currencies.id')
                    ->where('currency_rates.date', $date)->where('currency_code','=',$sCode);
            })
            ->selectRaw('currencies.*, currency_rates.rate')
            ->paginate(10)->onEachSide(2);

        return CurrencyResource::collection($currencies);
    }

    public function getAllCurrencies() {
        return response()->json(Currency::get(['symbol']), 200);
    }

    public function handle_controller($sCode)
    {
        $base_currency = $sCode;
        $rates = $this->getRates_controller($base_currency);

        $date = Carbon::parse($rates['date']);
        $currency_code = $base_currency;

        foreach ($rates['rates'] as $symbol => $rate) {
            $currency = Currency::updateOrCreate(['symbol' => $symbol]);

            //$currency->rates()->updateOrCreate(compact('date'), compact('rate'), compact('currency_code'));
            $currency->rates()->where('currency_code','=',$currency_code)->updateOrCreate(['date'=>$date,'rate'=>$rate,'currency_code'=>$currency_code]);
        }

        return response()->json(["status" => "SUCCESS", "response" => "process completed"]);
    }

    private function getRates_controller($base_currency)
    {
        $url = config('services.apilayer.base_uri') . '/fixer/latest?base=' . $base_currency;
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

    public function fetchCurrencyMMA(Request $request){
        $sCode = $request->sCode;
        $date = CurrencyRate::max('date');

        $currencies = Currency::query()
            ->leftJoin('currency_rates', function ($join) use ($date, $sCode) {
                $join->on('currency_rates.currency_id', '=', 'currencies.id')
                    ->where('currency_rates.date', $date)->where('currency_code','=',$sCode);
            })
            ->selectRaw('min(rate) as minRate,max(rate) as maxRate,AVG(rate) as avgRate')->get();

        return response()->json($currencies);
    }


    public function fetchHistory(Request $request){
        $date = $request->date ?? date('Y-m-d');
        $sCode = $request->sCode;

        $url = config('services.apilayer.base_uri') . '/fixer/'.$date.'?base=' . $sCode;
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

        return $body;
    }

    public function fetchCurrencyNominal(Request $request){
        $sCode = $request->sCode;
        $date = CurrencyRate::max('date');

        $currencies = Currency::query()
            ->leftJoin('currency_rates', function ($join) use ($date,$sCode) {
                $join->on('currency_rates.currency_id', '=', 'currencies.id')
                    ->where('currency_rates.date', $date)->where('currency_code','=',$sCode);
            })
            ->selectRaw('currencies.symbol, currency_rates.rate')->paginate(10)->onEachSide(2);
        
        $curArr = array();
        foreach($currencies as $cur){
            $arr = array();
            $rateArr = explode('.',$cur->rate);
            if(strlen($rateArr[0])<4){
                $arr['symbol'] = $cur->symbol;
                $arr['rate'] = $cur->rate;
                $arr['nominal'] = 0;
                array_push($curArr,$arr);
            }
            else if(strlen($rateArr[0])>3 && strlen($rateArr[0])<6){
                $arr['symbol'] = $cur->symbol;
                $arr['rate'] = $rateArr[0]/1000;
                $arr['nominal'] = 1;
                array_push($curArr,$arr);
            }
            else if(strlen($rateArr[0])>5 && strlen($rateArr[0])<9){
                $arr['symbol'] = $cur->symbol;
                $arr['rate'] = $rateArr[0]/1000000;
                $arr['nominal'] = 2;
                array_push($curArr,$arr);
            }
            else if(strlen($rateArr[0])>8){
                $arr['symbol'] = $cur->symbol;
                $arr['rate'] = $rateArr[0]/1000000000;
                $arr['nominal'] = 3;
                array_push($curArr,$arr);
            }
        }

        $currencies['data'] = $curArr;
        return response()->json($currencies);
    }
}
