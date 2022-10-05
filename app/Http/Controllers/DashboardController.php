<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;

class DashboardController extends Controller
{
    public function dashsemas(){
       
        $metabaseSiteUrl = 'https://metabase.mesquita.rj.gov.br';
        $metabaseSecretKey = 'e4c1378f92c00cd8aafde1f31f526f110e1c82aa60e6c336bf485a65f2e20a6c';
        
        $signer = new Sha256();
        $token = (new Builder())
        ->withClaim('resource', [
            'dashboard' => 7
        ])
        ->withClaim('params' , (object)[])
        ->getToken($signer, new Key($metabaseSecretKey));

        $iframeUrl = "{$metabaseSiteUrl}/embed/dashboard/{$token}#bordered=true&titled=true";

        return view('dashboard.indexsemas', compact('iframeUrl'));
    }

    public function dashsemus(){
        
        $metabaseSiteUrl = 'https://metabase.mesquita.rj.gov.br';
        $metabaseSecretKey = 'e4c1378f92c00cd8aafde1f31f526f110e1c82aa60e6c336bf485a65f2e20a6c';
        
        $signer = new Sha256();
        $token = (new Builder())
        ->withClaim('resource', [
            'dashboard' => 9
        ])
        ->withClaim('params' , (object)[])
        ->getToken($signer, new Key($metabaseSecretKey));

        $iframeUrl = "{$metabaseSiteUrl}/embed/dashboard/{$token}#bordered=true&titled=true";

        return view('dashboard.indexsemus', compact('iframeUrl'));
    }

    public function dashsemed(){
        
        $metabaseSiteUrl = 'https://metabase.mesquita.rj.gov.br';
        $metabaseSecretKey = 'e4c1378f92c00cd8aafde1f31f526f110e1c82aa60e6c336bf485a65f2e20a6c';
        
        $signer = new Sha256();
        $token = (new Builder())
        ->withClaim('resource', [
            'dashboard' => 8
        ])
        ->withClaim('params' , (object)[])
        ->getToken($signer, new Key($metabaseSecretKey));

        $iframeUrl = "{$metabaseSiteUrl}/embed/dashboard/{$token}#bordered=true&titled=true";

        return view('dashboard.indexsemed', compact('iframeUrl'));
    }
}
