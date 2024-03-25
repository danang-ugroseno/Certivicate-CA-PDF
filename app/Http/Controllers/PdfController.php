<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PdfController extends Controller
{
    function signedpdf($request)
    {
        $nik = '3505111509820003';
        $passphrase = 'Esign-101215';

        $basepath = config('app.url');
        $urlpdf = '/asset/tmp/65f296d5e64d4.pdf';

        $post = array(
            "api" => "sign",
            "nik" => $nik,
            "passphrase" => $passphrase,
            "pdf" => $basepath . $urlpdf
        );

        dd($post);

        $url = "https://signed.blitarkota.go.id/";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);

        $resultsign = curl_exec($curl);
        curl_close($curl);

        $jcode = json_decode($resultsign);
        dd($jcode);

        if (trim($jcode->signed) != "") {
            $file_content = file_get_contents($jcode->signed);

            @unlink($pathpdf);
            $fp = fopen($pathpdf, 'wb');
            fwrite($fp, $file_content);
            fclose($fp);

            return true;
        } else {
            return false;
        }
    }
}
