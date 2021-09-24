<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UrlRotatorList;
use App\Models\StepUrlList;
class SpoofLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spoof:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Spoof Link Checking';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->url_rotator();
        $this->step_url();
        $this->keyword_roator();
    }

    public function url_rotator() {
        $url_rotator = UrlRotatorList::where('spoof_referrer','1')->where('spoof_confirm','0')->get();
        if(count($url_rotator)) {
            $item = $url_rotator[0];
            $request_id = $item->request_id;
            $result = Helper::getGooglUrl($request_id);
            if (!$result['google_url']) {
                $created_req_id = createGoogleSpoof($item->dest_url);
                UrlRotatorList::where('id', $item->id)->update('request_id', $created_req_id);
            }
            else {
                UrlRotatorList::where('id', $item->id)->update('spoof_confirm', '1');
            }
            $this->url_rotator();
        }
    }

    public function step_url() {
        $step_url = StepUrlList::where('spoof_referrer','1')->where('spoof_confirm','0')->get();
        if(count($step_url)) {
            $item = $step_url[0];
            $request_id = $item->request_id;
            $result = Helper::getGooglUrl($request_id);
            if (!$result['google_url']) {
                $created_req_id = createGoogleSpoof($item->dest_url);
                StepUrlList::where('id', $item->id)->update('request_id', $created_req_id);
            }
            else {
                StepUrlList::where('id', $item->id)->update('spoof_confirm', '1');
            }
            $this->step_url();
        }
    }

    public function keyword_roator() {
        $kwd_rotator = KeywordRotatorList::where('spoof_referrer','1')->where('spoof_confirm','0')->get();
        if(count($kwd_rotator)) {
            $item = $kwd_rotator[0];
            $request_id = $item->request_id;
            $result = Helper::getGooglUrl($request_id);
            if (!$result['google_url']) {
                $created_req_id = createGoogleSpoof($item->dest_url);
                KeywordRotatoList::where('id', $item->id)->update('request_id', $created_req_id);
            }
            else {
                KeywordRotatoList::where('id', $item->id)->update('spoof_confirm', '1');
            }
            $this->keyword_roator();
        }
    }
}
