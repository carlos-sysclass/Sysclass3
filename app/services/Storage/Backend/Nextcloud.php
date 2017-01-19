<?php
namespace Sysclass\Services\Storage\Backend;

use Phalcon\Mvc\User\Component,
    Sysclass\Services\Storage\Interfaces\IStorage,
    Sysclass\Services\Storage\Exception as StorageException,
    Sysclass\Models\Dropbox\File,
    Sabre\DAV\Client as DavClient,
    Sysclass\Services\Utils\CurlRequest;

/*
    Phalcon\Events\EventsAwareInterface,
    Phalcon\Events\Event,
    Phalcon\Mvc\Dispatcher,
    Sysclass\Services\Authentication\Interfaces\IAuthentication,
    Sysclass\Services\Authentication\Exception as AuthenticationException,
    Sysclass\Models\Users\User,
    Sysclass\Models\Users\UsersGroups,
    Sysclass\Models\Users\UserApiTokens,
    Sysclass\Models\Users\UserTimes;
*/
class Nextcloud extends Component implements IStorage
{
    /*
    [remote_storage]
    backend = Sysclass\Services\Storage\Backend\NextCloud
    host = "https://cloud.sysclass.com"
    webdav_path = "remote.php/dav/files"
    user = sysclass
    password = WxubN7Ih
    storage = "remote_storage"
    */

    protected $api_url = null;
    protected $webdav_path = null;  
    protected $webdav_url = null;
    protected $dav_path = null;
    protected $dav_url = null;
    

    //protected $base_url = null;
    protected $user = null;
    protected $password = null;
    protected $config = null;

    protected $storage = null;

    public function initialize($config) {
        $this->api_url = $config->host;

        $this->webdav_path = "/" . $config->webdav_path;
        $this->webdav_url = $config->host . $this->webdav_path;

        $this->dav_path = "/" . $config->dav_path;
        $this->dav_url = $config->host . $this->dav_path;

        $this->user = $config->user;
        $this->password = $config->password;

        $this->config = $config;

        $this->storage = $config->storage; 

    }

    protected function createRequestBody($properties) {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $root = $dom->createElementNS('DAV:', 'd:propfind');
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:oc', 'http://owncloud.org/ns');
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:nc', 'http://nextcloud.org/ns');

        $prop = $dom->createElement('d:prop');

        foreach ($properties as $namespace => $property) {
            $element = $dom->createElement($property);
            $prop->appendChild($element);
        }
        //exit;

        $dom->appendChild($root)->appendChild($prop);
        return $dom->saveXML();

    }

    protected function getFileTags($file_id) {

        $properties = [
            'oc:id',
            'oc:display-name'
        ];

        $body = $this->createRequestBody($properties);

        $request = new CurlRequest();

        $response = $request->setInfo([
            CURLOPT_URL => $this->dav_url . "/" . 'systemtags-relations/files/' . $file_id,
            //CURLOPT_URL => $this->base_url,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_VERBOSE => 1,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PROPFIND",
            CURLOPT_HTTPHEADER => [
                "OCS-APIREQUEST: true",
                "Authorization: Basic " . base64_encode($this->user . ":" . $this->password), 
                //"Cache-Control: no-cache",
                "Content-Type: application/x-www-form-urlencoded",
                "Depth: 7"
            ]
        ])->send();

        //print_r(htmlentities($response['response']));

        $responseData = $this->xml->parseDoc($response['response'], ['d', 'oc', 's']);

        $tags = [];

        if (array_key_exists('propstat', $responseData['response'])) {
            $responseData['response'] = [$responseData['response']];
        }


        foreach($responseData['response'] as $idx1 => $item) {
            if (!array_key_exists('status', $item['propstat'])) {
                // MULTIPLE propstat
                foreach($item['propstat'] as $idx2 => $propstat) {
                    //var_dump($propstat['status']);
                    if ($propstat['status'] == 'HTTP/1.1 200 OK') {
                        //
                        //break;
                    } elseif ($propstat['status'] == 'HTTP/1.1 404 Not Found') {
                        unset($responseData['response'][$idx1]['propstat'][$idx2]);
                    }
                }
                $responseData['response'][$idx1]['propstat'] = reset($responseData['response'][$idx1]['propstat']);
            } elseif ($item['propstat']['status'] == 'HTTP/1.1 404 Not Found') {
                $responseData['response'][$idx1]['propstat'] = [];
            }
        }



        foreach($responseData['response'] as $item) {
            if (count($item['propstat']) > 0) {
                $tags[] = $item['propstat']['prop'];
            }
        }

        return $tags;

    }

    public function getFilesInFolder($folder) {
        $request = new CurlRequest();

        $properties = [
            'd:getlastmodified',
            'd:getcontenttype',
            'd:getcontentlength',
            'd:getetag',
            'oc:fileid',
            'oc:tags'
        ];

        $body = $this->createRequestBody($properties);

        $response = $request->setInfo([
            CURLOPT_URL => $this->webdav_url . "/" . $folder,
            //CURLOPT_URL => $this->base_url,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_VERBOSE => 1,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PROPFIND",
            CURLOPT_HTTPHEADER => [
                "OCS-APIREQUEST: true",
                "Authorization: Basic " . base64_encode($this->user . ":" . $this->password), 
                //"Cache-Control: no-cache",
                "Content-Type: application/x-www-form-urlencoded",
                "Depth: 7"
            ]
        ])->send();

        //echo "<pre>";
        //print_r($response['response']);
        $responseData = $this->xml->parseDoc($response['response'], ['d', 'oc', 's']);
        //$responseMessage = $this->xml->parseDoc($response['response'], 's');

        if (array_key_exists('exception', $responseData)) {
            return [
                'error' => true,
                'message' => $responseData['message'],
                'type' => 'warning',
                'data' => $responseData
            ];
        }

        $treeStruct = [];

        if (array_key_exists('propstat', $responseData['response'])) {
            $responseData['response'] = [$responseData['response']];
        }

        foreach($responseData['response'] as $item) {


            if (!empty($item['propstat']['prop']['fileid'])) { // FILE_ID
                //$tags = $this->getFileTags($item['propstat']['prop']['fileid']);
            }

            $url = urldecode(str_replace($this->webdav_path . "/", "", $item['href']));

            
            /*
            if (!empty($folder)) {
                $url = str_replace($folder . "/", "", $url);
            }
            */

            if (empty($url)) {
                continue;
            }

            $id = str_replace(["/", " "], ["_", "_"], $url);



            $path_info = pathinfo($url);
            
            if (empty($item['propstat']['prop']['getcontenttype'])) { // IS A DIR ?

                $fileEntry = [
                    'id' => $id,
                    'text'  => $path_info['basename'],
                    'url' => $url,
                    //'full_url' => str_replace($this->webdav_path . "/", "", $item['href']),
                    'last_modified' => $item['propstat']['prop']['getlastmodified'],
                    //'mime_type' => $item['propstat']['prop']['getcontenttype'],
                    //'size' => $item['propstat']['prop']['getcontentlength'],
                    'etag' => $item['propstat']['prop']['getetag'],
                    'type' => 'dir',
                    'storage' => $this->storage,
                    'tags' => $tags
                ];


            } else { // IS A FILE
                $fileEntry = [
                    'id' => $id,
                    'text'  => $path_info['basename'],
                    //'url' => $url,
                    'url' => $url,
                    'last_modified' => $item['propstat']['prop']['getlastmodified'],
                    'mime_type' => $item['propstat']['prop']['getcontenttype'],
                    'size' => $item['propstat']['prop']['getcontentlength'],
                    'etag' => $item['propstat']['prop']['getetag'],
                    'type' => 'file',
                    'storage' => $this->storage,
                    'tags' => $tags
                ];

            }
            if ($path_info['dirname'] != ".") {
                $fileEntry['parent'] = str_replace(["/", " "], ["_", "_"], $path_info['dirname'] . "/");
            } else {
                $fileEntry['parent'] = "#";
                $fileEntry['state'] = "opened";
                $fileEntry['type'] = 'root';
            }
            $treeStruct[] = $fileEntry;
        }

        return $treeStruct;
    }

    public function addFile($storage_path, $file_path, $is_stream = false) {
        if (file_exists($file_path)) {
            $request = new CurlRequest();

            $stream = fopen($file_path, 'rb');

            $file_parts = explode("/", $storage_path);
            $file_parts = array_map('rawurlencode', $file_parts);
            $storage_path = implode("/", $file_parts);

            $response = $request->setInfo([
                CURLOPT_URL => $this->webdav_url . "/" . $storage_path,
                CURLOPT_INFILE => $stream,
                CURLOPT_INFILESIZE => filesize($file_path),
                CURLOPT_UPLOAD => 1,
                CURLOPT_RETURNTRANSFER => true,
                //CURLOPT_ENCODING => "",
                //CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_VERBOSE => 1,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_PUT => 1,
                CURLOPT_HTTPHEADER => [
                    "OCS-APIREQUEST: true",
                    "Authorization: Basic " . base64_encode($this->user . ":" . $this->password), 
                    //"Cache-Control: no-cache",
                    //"Content-Type: application/x-www-form-urlencoded"
                ]
            ])->send();

            //echo "<pre>";
            //var_dump($response);

            if (in_array($response['info']['http_code'], [201,204])) {
                // SUCCESS
                return true;
            }
        }
        return false;
    }

    public function moveFile($from_path, $dest_path) {
        $request = new CurlRequest();

        $file_parts = explode("/", $from_path);
        $file_parts = array_map('rawurlencode', $file_parts);
        $from_path = implode("/", $file_parts);

        $file_parts = explode("/", $dest_path);
        $file_parts = array_map('rawurlencode', $file_parts);
        $dest_path = implode("/", $file_parts);

        $response = $request->setInfo([
            CURLOPT_URL => $this->webdav_url . "/" . $from_path,
            CURLOPT_RETURNTRANSFER => true,
            //CURLOPT_ENCODING => "",
            //CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_VERBOSE => 1,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'MOVE',
            CURLOPT_HTTPHEADER => [
                "OCS-APIREQUEST: true",
                "Authorization: Basic " . base64_encode($this->user . ":" . $this->password), 
                "Destination: " . $this->webdav_url . "/" . $dest_path,
                //"Cache-Control: no-cache",
                //"Content-Type: application/x-www-form-urlencoded"
            ]
        ])->send();

        if (in_array($response['info']['http_code'], [201])) {
            // SUCCESS
            return true;
        }
        return false;
    }

    public function deleteFile($path) {
        $request = new CurlRequest();

        $file_parts = explode("/", $path);
        $file_parts = array_map('rawurlencode', $file_parts);
        $path = implode("/", $file_parts);

        $response = $request->setInfo([
            CURLOPT_URL => $this->webdav_url . "/" . $path,
            CURLOPT_RETURNTRANSFER => true,
            //CURLOPT_ENCODING => "",
            //CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_VERBOSE => 1,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => [
                "OCS-APIREQUEST: true",
                "Authorization: Basic " . base64_encode($this->user . ":" . $this->password), 
                //"Cache-Control: no-cache",
                //"Content-Type: application/x-www-form-urlencoded"
            ]
        ])->send();

        if (in_array($response['info']['http_code'], [204])) {
            // SUCCESS
            return true;
        }
        return false;
    }

    



    public function getFilestream(File $struct) {

    }

    public function getFullFilePath(File $struct) {

    }


    protected function getUserShares($options) {
        $request = new CurlRequest();

        if (is_null($options)) {
            $options = [
                'shared_with_me' => false
            ];
        }
        $options['format'] = 'json';

        $url = $this->api_url . "/" . $this->config->shares_api_path . "?" . http_build_query($options);

        $response = $request->setInfo([
            CURLOPT_URL => $url,
            //CURLOPT_URL => $this->base_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_VERBOSE => 1,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "OCS-APIREQUEST: true",
                "Authorization: Basic " . base64_encode($this->user . ":" . $this->password), 
                "Cache-Control: no-cache",
                "Content-Type: application/x-www-form-urlencoded"
            ]
        ])
        ->outputJson()
        ->send();

        return $response['response']['ocs']['data'];
    }

    protected function createUserShare($fields, $options) {
        $request = new CurlRequest();

        if (is_null($options)) {
            $options = [];
        }
        $options['format'] = 'json';

        $url = $this->api_url . "/" . $this->config->shares_api_path . "?" . http_build_query($options);

        $response = $request->setInfo([
            CURLOPT_URL => $url,
            //CURLOPT_URL => $this->base_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_VERBOSE => 1,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($fields),
            CURLOPT_HTTPHEADER => [
                "OCS-APIREQUEST: true",
                "Authorization: Basic " . base64_encode($this->user . ":" . $this->password), 
                "Cache-Control: no-cache",
                "Content-Type: application/x-www-form-urlencoded"
            ]
        ])
        ->outputJson()
        ->send();

        $meta = $response['response']['ocs']['meta'];

        if ($meta['statuscode'] != 100) {
            return false;
        }

        return $response['response']['ocs']['data'];
    }


/*
<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://cloud.sysclass.com/ocs/v1.php/apps/files_sharing/api/v1/shares?format=json",
  CURLOPT_CUSTOMREQUEST => "",
    CURLOPT_POSTFIELDS => "path=video-queue%2Finput.mp4&shareType=3&permissions=1",
  CURLOPT_HTTPHEADER => array(
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}
*/


    public function getFullFileUrl(File $struct) {
        // GET ALL SHARES AND CHECK FOR THIS FILE, IF NOT EXISTS, CREATE THE SHARE

        if (is_numeric($struct->share_id)) {
            return $struct->url;    
        }

        $share = $this->createUserShare([
            'permissions' => 1,
            'shareType' => 3, // shareType = LINK
            'path' => "/" . $struct->filename,
        ]);

        if ($share) {
            $struct->share_id = $share['id'];
            $struct->url = $share['url'] . "/download";

            //$struct->save();
        }

        return $struct->url;

    }

    public function putFilestream(File $struct, $fileStream = null) {

    }


}
