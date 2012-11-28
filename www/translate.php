<?php
class Google_Translate_API
{
	protected $apiKey = null;

	public function __construct($apiKey = null)
	{
		$this->apiKey = $apiKey;
	}

	public function translateMultiple($texts, $from, $to)
	{
		$url = 'http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&{%url%}&langpair='.rawurlencode($from.'|'.$to);

		// INJECT TEXTS.. MAX 5K
		// MAX DATA
		$translated = array();
		$term = reset($texts);

		$query = array();
		$size = -1;
		$indexStart = 0;
		$indexEnd = 0;

		$n_requests = 0;
		$i = 0;

		while (true) {
			$size += strlen(http_build_query(array('q'	=> $term))) + 1;
			if ($size >= 1000 || $term === FALSE) {
				// DO REQUEST HERE...
				// sendRequest
				// note how referer is set manually
				$sendUrl = str_replace("{%url%}", implode('&', $query), $url);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $sendUrl);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_REFERER, "http://arcanjo.us");
				$body = curl_exec($ch);
				curl_close($ch);

				// now, process the JSON string
				$resultJson = json_decode($body);
				// now have some fun with the results...
				$indexEnd = $i;

				$n_requests++;

		        echo sprintf("%s -> %s | REQUEST %03d: FROM %04d TO %04d (%d TERMS)\n", $from, $to, $n_requests, $indexStart, $indexEnd, count($query));
		        //echo sprintf("LAST/NEXT TERM %s / %s\n", $lastTerm, $term);

		        if (!is_array($resultJson->responseData) && !is_object($resultJson->responseData)) {
		        	var_dump($resultJson);
		        	var_dump($body);
		        	echo $sendUrl;
		        	exit;
		        }

		        if (count($query) == 1) {
		        	$translated[] = $resultJson->responseData->translatedText;
		        } else {
			        foreach ($resultJson->responseData as $item) {
			        	$translated[] = $item->responseData->translatedText;
			        	//echo "\n";
			        }
		        }
				if ($term === FALSE) {
		        	break;
		        }
		        //if ($n_requests == 2) {
		        //	break;
		        //}
		        // INJECT RESULTS
				$indexStart = $i+1;
				$size = -1;
				$query = array();
			}
			$query[] = http_build_query(array('q'	=> $term));
			$lastTerm = $term;
			$term = next($texts);
			$i++;
		}
		$translated = array_combine(array_keys($texts), $translated);
		return $translated;
	}

	public function translateMultiplev2($texts, $from = '', $to = 'en')
	{
		$url = sprintf(
			'https://www.googleapis.com/language/translate/v2?key=%s&source=%s&target=%s&format=text&prettyprint=false',
			$this->apiKey,
			$from,
			$to
		);

		//https://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q=hello%20world&q=goodbye&langpair=en%7Cit

		// INJECT TEXTS.. MAX 5K
		// MAX DATA
		$translated = array();
		$term = reset($texts);

		$query = array();
		$size = -1;
		$indexStart = 0;
		$indexEnd = 0;

		$n_requests = 0;
		$i = 0;

		while (true) {
			$size += strlen(http_build_query(array('q'	=> $term))) + 1;
			if ($size >= 5120 || $term === FALSE) {
				// DO REQUEST HERE...
				$indexEnd = $i;
				$context = stream_context_create(array(
					'http' => array(
						'method'  => 'POST',
						'header'  => "X-HTTP-Method-Override: GET\r\n",
						'content' => implode('&', $query),
					),
				));

				var_dump($context);

				echo implode('&', $query);

				$response = file_get_contents(
		        	$url,
		        	false,
		        	$context
		        );
				var_dump($response);
				var_dump(json_decode($response));
exit;

				$n_requests++;

		        echo sprintf("REQUEST n %d FROM INDEX %d TO %d\n", $n_requests, $indexStart, $indexEnd);

		        if ($term === FALSE) {
		        	break;
		        }

				$indexStart = $i+1;
				$size = -1;
				$query = array();
			} else {
				$query[] = http_build_query(array('q'	=> $term));
				$term = next($texts);
				$i++;
			}
		}
	}
        /**
         * Translate a piece of text with the Google Translate API
         * @return String
         * @param $text String
         * @param $from String[optional] Original language of $text. An empty String will let google decide the language of origin
         * @param $to String[optional] Language to translate $text to
         */
        function translate($text, $from = '', $to = 'en')
        {
                $url = 'http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q='.rawurlencode($text).'&langpair='.rawurlencode($from.'|'.$to);

                $response = file_get_contents(
                        $url,
                        null,
                        stream_context_create(
                                array(
                                        'http'=>array(
                                                'method'=>"GET"/*,
                                                'header'=>"Referer: http://".$_SERVER['HTTP_HOST']."/\r\n"
                                                */
                                        )
                                )
                        )
                );
                if (preg_match("/{\"translatedText\":\"([^\"]+)\"/i", $response, $matches)) {
                        return self::_unescapeUTF8EscapeSeq($matches[1]);
                }

                return false;
        }

        /**
         * Convert UTF-8 Escape sequences in a string to UTF-8 Bytes. Old version.
         * @return UTF-8 String
         * @param $str String
         */
        /*
        protected function __unescapeUTF8EscapeSeq($str)
        {
                return preg_replace_callback("/\\\u([0-9a-f]{4})/i", create_function('$matches', 'return html_entity_decode(\'&#x\'.$matches[1].\';\', ENT_NOQUOTES, \'UTF-8\');'), $str);
        }
        */
        /**
         * Convert UTF-8 Escape sequences in a string to UTF-8 Bytes
         * @return UTF-8 String
         * @param $str String
         */
        protected function _unescapeUTF8EscapeSeq($str)
        {
                return preg_replace_callback("/\\\u([0-9a-f]{4})/i", create_function('$matches', 'return Google_Translate_API::_bin2utf8(hexdec($matches[1]));'), $str);
        }

        /**
         * Convert binary character code to UTF-8 byte sequence
         * @return String
         * @param $bin Mixed Interger or Hex code of character
         */
        function _bin2utf8($bin)
        {
                if ($bin <= 0x7F) {
                        return chr($bin);
                } elseif ($bin >= 0x80 && $bin <= 0x7FF) {
                        return pack("C*", 0xC0 | $bin >> 6, 0x80 | $bin & 0x3F);
                } elseif ($bin >= 0x800 && $bin <= 0xFFF) {
                        return pack("C*", 0xE0 | $bin >> 11, 0x80 | $bin >> 6 & 0x3F, 0x80 | $bin & 0x3F);
                } elseif ($bin >= 0x10000 && $bin <= 0x10FFFF) {
                        return pack("C*", 0xE0 | $bin >> 17, 0x80 | $bin >> 12 & 0x3F, 0x80 | $bin >> 6& 0x3F, 0x80 | $bin & 0x3F);
                }
        }

}

$languages = array(
	'en'	=> 'english',
	'es'	=> 'spanish',
	'fr'	=> 'french',
	'de'	=> 'german',
	'pt'	=> 'portuguese'
);

if ($argc < 3) {
	echo 'Por favor informe os idiomas a serem traduzidos\n';
	exit;
}
$from = $argv[1];

$to = array();
for ($i = 2; $i < count($argv); $i++ ) {
	$to[] = $argv[$i];
}

$file_template = dirname(__FILE__) . '/../libraries/language/lang-%s.php.inc';

$googleAPI = new Google_Translate_API("AIzaSyCQMaLZI_6vlb9wAAQnZV9DwgK6nh7WvLM");

$fromFile = fopen(sprintf($file_template, $languages[$from]), 'r');

foreach ($to as $lang) {
	if (file_exists(sprintf($file_template, $languages[$lang]))) {
		echo "Arquivo de tradução com a linguagem " . $lang . " já existe\n";
		unlink(sprintf($file_template, $languages[$lang]));
	}

}

while ($line = fgets($fromFile)) {
	$matches = array();

	preg_match_all('/define\(\s*"(?<index>.+)"\s*,\s*"(?<term>.+)"\s*\);/', $line, $matches, PREG_SET_ORDER);

	if (count($matches) > 0) {
		$index = trim($matches[0]['index']);
		$term = trim($matches[0]['term']);

		$terms[$index] = $term;
	}
}
fclose($fromFile);

//var_dump($terms);
$translations = array();
foreach ($to as $lang) {
	echo sprintf("Executando tradução: %s => %s\n", $from, $lang);
	$translations[$lang] = $googleAPI->translateMultiple($terms, $from, $lang);
}

$toFiles = array();

foreach ($translations as $lang => $transLang) {
	echo "Gravando arquivo: " . sprintf($file_template, $languages[$lang]) . "\n";
	$toFile = fopen(sprintf($file_template, $languages[$lang]), 'w');
	fwrite($toFile, "<?php\n");
	foreach ($transLang as $transKey => $transValue) {
		$translatedLine =  sprintf("define(\"%s\", \"%s\");\n", $transKey, $transValue);
		fputs($toFile, $translatedLine);
	}
	fwrite($toFile, "\n?>");
}
