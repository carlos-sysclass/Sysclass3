<?php
class BingTranslateModel extends ModelManager {

    protected $langMap      = array();
    protected $clientID     = null;
    protected $clientSecret = null;

    public function __construct() {
        $plicolib = PlicoLib::instance();

        $depinject = \Phalcon\DI::GetDefault();

        $environment = $depinject->get("environment");
        $this->credentials($environment['bing/client_id'], $environment['bing/client_secret']);
        /*
        $this->langMap = array(
            'us'    => 'en',
            'br'    => 'pt'
        );
        */
    }
    public function credentials($clientID, $clientSecret)
    {
        $this->clientID = $clientID;
        $this->clientSecret = $clientSecret;

        return $this;
    }
    /*
     * Get the access token.
     *
     * @param string $clientID     Application client ID.
     * @param string $clientSecret Application client ID.
     * @param string $grantType    Grant type.
     * @param string $scopeUrl     Application Scope URL.
     * @param string $authUrl      Oauth Url.
     *
     * @return string.
     */
    public function token($clientID = null, $clientSecret = null, $grantType = "client_credentials", $scopeUrl = "http://api.microsofttranslator.com", $authUrl = "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/"){
        try {
            $clientID = is_null($clientID) ? $this->clientID : $clientID;
            $clientSecret = is_null($clientSecret) ? $this->clientSecret : $clientSecret;
            //Initialize the Curl Session.
            $ch = curl_init();
            //Create the request Array.
            $paramArr = array (
                 'grant_type'    => $grantType,
                 'scope'         => $scopeUrl,
                 'client_id'     => $clientID,
                 'client_secret' => $clientSecret
            );
            //Create an Http Query.//
            $paramArr = http_build_query($paramArr);
            //Set the Curl URL.
            curl_setopt($ch, CURLOPT_URL, $authUrl);
            //Set HTTP POST Request.
            curl_setopt($ch, CURLOPT_POST, TRUE);
            //Set data to POST in HTTP "POST" Operation.
            curl_setopt($ch, CURLOPT_POSTFIELDS, $paramArr);
            //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
            //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            //Execute the  cURL session.
            $strResponse = curl_exec($ch);
            //Get the Error Code returned by Curl.
            $curlErrno = curl_errno($ch);
            if($curlErrno){
                $curlError = curl_error($ch);
                throw new Exception($curlError);
            }
            //Close the Curl Session.
            curl_close($ch);
            //Decode the returned JSON string.
            $objResponse = json_decode($strResponse);
            if ($objResponse->error){
                throw new Exception($objResponse->error_description);
            }
            $this->_responseToken = $objResponse;
            return $this;
            //return ->access_token;
        } catch (Exception $e) {
            echo "Exception-".$e->getMessage();
        }
    }
    public function getToken() {
        return $this->_responseToken->access_token;
    }

    public function translateText($text, $from, $to) {
        $accessToken = $this->token()->getToken();

        $url = sprintf(
            "http://api.microsofttranslator.com/v2/Http.svc/Translate?text=%s&from=%s&to=%s&appId=&contentType=text/html",
            urlencode($text), $from, $to
        );

        //Initialize the Curl Session.
        $ch = curl_init();
        //Set the Curl url.
        curl_setopt ($ch, CURLOPT_URL, $url);
        //Set the HTTP HEADER Fields.
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $accessToken, "Content-type: text/xml"));
        //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, False);
        //if($postData) {
            //Set HTTP POST Request.
        //    curl_setopt($ch, CURLOPT_POST, TRUE);
            //Set data to POST in HTTP "POST" Operation.
        //    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        //}
        //Execute the  cURL session.
        $curlResponse = curl_exec($ch);

        $xmlObj = simplexml_load_string($curlResponse);
        foreach((array)$xmlObj[0] as $val){
            $translatedStr = $val;
        }
        //Get the Error Code returned by Curl.
        $curlErrno = curl_errno($ch);
        if ($curlErrno) {
            $curlError = curl_error($ch);
            throw new Exception($curlError);
        }
        //Close a cURL session.
        curl_close($ch);
        return $translatedStr;
    }

    public function translateArray($texts, $from, $to) {
        $accessToken = $this->token()->getToken();

        if(count($texts) > 0) {

            $stopIndex = 0;
            while($stopIndex >= 0) {
                list($postData, $stopIndex) = $this->createTranslateArrayXML($from, $to, $texts, $stopIndex);
                $postDatas[] = $postData;
                if ($stopIndex == -1) {
                    break;
                }
            }

            $url = "http://api.microsofttranslator.com/v2/Http.svc/TranslateArray";

            $counter = 0;
            $result = array();

            foreach($postDatas as $postData) {

                //Initialize the Curl Session.
                $ch = curl_init();
                //Set the Curl url.
                curl_setopt ($ch, CURLOPT_URL, $url);
                //Set the HTTP HEADER Fields.
                curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $accessToken, "Content-type: text/xml"));
                //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
                //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
                curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt ($ch, CURLOPT_VERBOSE, TRUE);
                //curl_setopt ($ch, CURLOPT_HEADER, 1);

                //exit;
                //Set HTTP POST Request.
                curl_setopt($ch, CURLOPT_POST, TRUE);
                //Set data to POST in HTTP "POST" Operation.
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

                //Execute the  cURL session.
                $curlResponse = curl_exec($ch);

                //Get the Error Code returned by Curl.
                $curlErrno = curl_errno($ch);
                if ($curlErrno) {
                    $curlError = curl_error($ch);
                    throw new Exception($curlError);
                }

                //Close a cURL session.
                curl_close($ch);

                $xmlObj = simplexml_load_string($curlResponse);

                foreach($xmlObj->TranslateArrayResponse as $translatedArrObj) {
                    $result[$texts[$counter++]] = (string) $translatedArrObj->TranslatedText;
                }
            }

            return $result;
        }

        return false;

    }

    protected function createTranslateArrayXML($fromLanguage,$toLanguage,$inputStrArr,$startIndex, $contentType = 'text/html') {
        //Create the XML string for passing the values.
        $beginRequestXml = "<TranslateArrayRequest>".
            "<AppId/>".
            "<From>$fromLanguage</From>".
            "<Options>" .
             "<Category xmlns=\"http://schemas.datacontract.org/2004/07/Microsoft.MT.Web.Service.V2\" />" .
              "<ContentType xmlns=\"http://schemas.datacontract.org/2004/07/Microsoft.MT.Web.Service.V2\">$contentType</ContentType>" .
              "<ReservedFlags xmlns=\"http://schemas.datacontract.org/2004/07/Microsoft.MT.Web.Service.V2\" />" .
              "<State xmlns=\"http://schemas.datacontract.org/2004/07/Microsoft.MT.Web.Service.V2\" />" .
              "<Uri xmlns=\"http://schemas.datacontract.org/2004/07/Microsoft.MT.Web.Service.V2\" />" .
              "<User xmlns=\"http://schemas.datacontract.org/2004/07/Microsoft.MT.Web.Service.V2\" />" .
            "</Options>" .
            "<Texts>";

        $count = 0;
        $stringAll = "";
        $endRequestXml = "</Texts>".
            "<To>$toLanguage</To>" .
          "</TranslateArrayRequest>";
        $termsRequestXml = "";
        $currentSize = mb_strlen($beginRequestXml) + mb_strlen($endRequestXml);
        $stopIndex = -1;
        foreach ($inputStrArr as $index => $inputStr) {
            if ($index < $startIndex) {
                continue;
            }
            //$count++;
            $stringToAdd = "<string xmlns=\"http://schemas.microsoft.com/2003/10/Serialization/Arrays\">" . $this->wrapXmlString($inputStr) . "</string>" ;
            if ((mb_strlen($stringToAdd) + $currentSize) > 30000) {
                // break here
                $stopIndex = $index;
                break;
            }
            $termsRequestXml .= $stringToAdd;
            //$stringAll .= $this->wrapXmlString($inputStr);
            $currentSize += mb_strlen($stringToAdd);
        }
        $endRequestXml = "</Texts>".
            "<To>$toLanguage</To>" .
          "</TranslateArrayRequest>";

        $requestXml = $beginRequestXml . $termsRequestXml . $endRequestXml;

        return array($requestXml, $stopIndex);
    }

    function wrapXmlString($string) {
        //return preg_replace("/([:cntrl:])/", "", $string);
        return strtr(
            $this->sanitize_for_xml($string),
            array(
                "&" => "&amp;"
            )
        );
    }
    function sanitize_for_xml($input) {
      // Convert input to UTF-8.
      $old_setting = ini_set('mbstring.substitute_character', '"none"');
      $input = mb_convert_encoding($input, 'UTF-8', 'auto');
      ini_set('mbstring.substitute_character', $old_setting);

      // Use fast preg_replace. If failure, use slower chr => int => chr conversion.
      $output = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', '', $input);
      if (is_null($output)) {
        // Convert to ints.
        // Convert ints back into a string.
        $output = $this->ords_to_utfstring($this->utfstring_to_ords($input), TRUE);
      }
      return $output;
    }
    function utfstring_to_ords($input, $encoding = 'UTF-8'){
      // Turn a string of unicode characters into UCS-4BE, which is a Unicode
      // encoding that stores each character as a 4 byte integer. This accounts for
      // the "UCS-4"; the "BE" prefix indicates that the integers are stored in
      // big-endian order. The reason for this encoding is that each character is a
      // fixed size, making iterating over the string simpler.
      $input = mb_convert_encoding($input, "UCS-4BE", $encoding);

      // Visit each unicode character.
      $ords = array();
      for ($i = 0; $i < mb_strlen($input, "UCS-4BE"); $i++) {
        // Now we have 4 bytes. Find their total numeric value.
        $s2 = mb_substr($input, $i, 1, "UCS-4BE");
        $val = unpack("N", $s2);
        $ords[] = $val[1];
      }
      return $ords;
    }
    function ords_to_utfstring($ords, $scrub_XML = FALSE) {
      $output = '';
      foreach ($ords as $ord) {
        // 0: Negative numbers.
        // 55296 - 57343: Surrogate Range.
        // 65279: BOM (byte order mark).
        // 1114111: Out of range.
        if (   $ord < 0
            || ($ord >= 0xD800 && $ord <= 0xDFFF)
            || $ord == 0xFEFF
            || $ord > 0x10ffff) {
          // Skip non valid UTF-8 values.
          continue;
        }
        // 9: Anything Below 9.
        // 11: Vertical Tab.
        // 12: Form Feed.
        // 14-31: Unprintable control codes.
        // 65534, 65535: Unicode noncharacters.
        elseif ($scrub_XML && (
                   $ord < 0x9
                || $ord == 0xB
                || $ord == 0xC
                || ($ord > 0xD && $ord < 0x20)
                || $ord == 0xFFFE
                || $ord == 0xFFFF
                )) {
          // Skip non valid XML values.
          continue;
        }
        // 127: 1 Byte char.
        elseif ( $ord <= 0x007f) {
          $output .= chr($ord);
          continue;
        }
        // 2047: 2 Byte char.
        elseif ($ord <= 0x07ff) {
          $output .= chr(0xc0 | ($ord >> 6));
          $output .= chr(0x80 | ($ord & 0x003f));
          continue;
        }
        // 65535: 3 Byte char.
        elseif ($ord <= 0xffff) {
          $output .= chr(0xe0 | ($ord >> 12));
          $output .= chr(0x80 | (($ord >> 6) & 0x003f));
          $output .= chr(0x80 | ($ord & 0x003f));
          continue;
        }
        // 1114111: 4 Byte char.
        elseif ($ord <= 0x10ffff) {
          $output .= chr(0xf0 | ($ord >> 18));
          $output .= chr(0x80 | (($ord >> 12) & 0x3f));
          $output .= chr(0x80 | (($ord >> 6) & 0x3f));
          $output .= chr(0x80 | ($ord & 0x3f));
          continue;
        }
      }
      return $output;
    }

    public function getTranslations() {
        $accessToken = $this->token()->getToken();

        $url = "http://api.microsofttranslator.com/v2/Http.svc/GetLanguagesForTranslate";

        //Initialize the Curl Session.
        $ch = curl_init();
        //Set the Curl url.
        curl_setopt ($ch, CURLOPT_URL, $url);
        //Set the HTTP HEADER Fields.
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $accessToken, "Content-type: text/xml"));
        //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, False);
        //if($postData) {
            //Set HTTP POST Request.
        //    curl_setopt($ch, CURLOPT_POST, TRUE);
            //Set data to POST in HTTP "POST" Operation.
        //    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        //}
        //Execute the  cURL session.
        $curlResponse = curl_exec($ch);

        $xmlObj = simplexml_load_string($curlResponse);
        foreach((array)$xmlObj[0] as $val){
            $result = $val;
        }

        //Get the Error Code returned by Curl.
        $curlErrno = curl_errno($ch);
        if ($curlErrno) {
            $curlError = curl_error($ch);
            throw new Exception($curlError);
        }
        //Close a cURL session.
        curl_close($ch);
        return $result;
    }

    public function getTranslationsNames($locale = null) {
        $accessToken = $this->token()->getToken();

        $langCodes = $this->getTranslations();

        $locale = is_null($locale) ? $this->model("translate")->getUserLanguageCode() : $locale;

        if (!in_array($locale, $langCodes)) {
            $locale = $this->model("translate")->getSystemLanguageCode();
        }

        $url = sprintf(
            "http://api.microsofttranslator.com/v2/Http.svc/GetLanguageNames?locale=%s",
            //$this->model("translate")->getUserLanguageCode()
            $locale
        );

        //Initialize the Curl Session.
        $ch = curl_init();
        //Set the Curl url.
        curl_setopt ($ch, CURLOPT_URL, $url);
        //Set the HTTP HEADER Fields.
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $accessToken, "Content-type: text/xml"));
        //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, False);

        if($langCodes) {
            $postData = $this->createTranslationNamesXML($langCodes);
            //Set HTTP POST Request.
            curl_setopt($ch, CURLOPT_POST, TRUE);
            //Set data to POST in HTTP "POST" Operation.
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }
        //Execute the  cURL session.
        $curlResponse = curl_exec($ch);
        $xmlObj = simplexml_load_string($curlResponse);
        foreach((array)$xmlObj[0] as $val){
            $result = $val;
        }

        //Get the Error Code returned by Curl.
        $curlErrno = curl_errno($ch);
        if ($curlErrno) {
            $curlError = curl_error($ch);
            throw new Exception($curlError);
        }
        //Close a cURL session.
        curl_close($ch);

        return array_combine($langCodes, $result);

    }

    protected function createTranslationNamesXML($languageCodes) {
        //Create the XML string for passing the values.
        $requestXml = '<ArrayOfstring xmlns="http://schemas.microsoft.com/2003/10/Serialization/Arrays" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">';
        if(count($languageCodes) > 0){
            foreach($languageCodes as $codes)
            $requestXml .= "<string>$codes</string>";
        } else {
            throw new Exception('$languageCodes array is empty.');
        }
        $requestXml .= '</ArrayOfstring>';
        return $requestXml;
    }

}
