<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LoginToCitoLabJob implements ShouldQueue
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
        $url = 'https://rdp2.citolab.cl/fmi/data/vLatest/databases/DEV/sessions';
        $username = 'fullstack';
        $password = 'laravel';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, []);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); 
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $data = json_decode($result,true);
        if ($status == 200) {
            $token = $data['response']['token'];
            $singleRecord=$this->single_record($token);
            dd($singleRecord);
        } else {
            dd(['errors' => $data["messages"]]);
        }
    }

    public function single_record($token){
        $url = 'https://rdp2.citolab.cl/fmi/data/vLatest/databases/DEV/layouts/contacto/records/1';
        $authorization = "Authorization: Bearer ".$token;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',$authorization));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); 
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $data = json_decode($result,true);
        if ($status == 200) {
            $dataResponse = $data["response"]["data"][0]["fieldData"];

            $texto=$dataResponse["texto"];
            $whatsapp=$dataResponse["whatsapp"];

            return ["error"=>false,"texto"=>$texto,"whatsapp"=>$whatsapp];
        }else{
            return ["error"=>true,"message"=>$data["messages"]];
        }
    }
}
