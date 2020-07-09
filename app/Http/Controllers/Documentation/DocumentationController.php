<?php

namespace MillionsSaving\Http\Controllers\Documentation;

use MillionsSaving\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate;


class DocumentationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('pages.documentation.userguide');
    }

    public function CompanyDetails()
    {
        return view('pages.documentation.companydetails');
    }
      
      
      function translate()
      {
      	// $from_lan, $to_lan, $text
      	$from_lan = 'English';
      	$to_lan ='English';
       $json = json_decode(file_get_contents('https://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q=' . urlencode($text) . '&langpair=' . $from_lan . '|' . $to_lan));
        $translated_text = $json->responseData->translatedText;

        return $translated_text;
}


}
