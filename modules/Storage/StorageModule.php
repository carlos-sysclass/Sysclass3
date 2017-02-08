<?php
/**
 * Module Class File
 * @filesource
 */
namespace Sysclass\Modules\Storage;

use Sysclass\Services\Storage\Adapter as StorageAdapter;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/storage")
 */
class StorageModule extends \SysclassModule implements \IBlockProvider
{
    public function registerBlocks() {
        return array(
            'storage.library' => function($data, $self) {
                // CREATE BLOCK CONTEXT

                $self->putComponent("datatables");
                $self->putComponent("jstree");
                //$self->putComponent("jquery-file-upload-video");
                //$self->putComponent("jquery-file-upload-audio");
                //$self->putComponent("bootstrap-confirmation");

                $block_context = $self->getConfig("blocks\\storage.library\\context");
                $self->putItem("storage_library_context", $block_context);

                $self->putComponent("bootstrap-confirmation");
                $self->putComponent("bootstrap-editable");
                $self->putComponent("fileupload");

                $self->putModuleScript("dialogs.storage.library");

                $self->putSectionTemplate("dialogs", "dialogs/library");

                return true;
            }
        );
    }

    /**
     * [add a description]
     *
     * @Post("/send")
     * @allow(resource=dropbox, action=edit)
     */
    public function sendToStorageRequest() {
        $postData = $this->request->getPost();
        /*
        array (size=4)
        'filename' => string 'Todo as Admin (14).docx' (length=23)
        'full_path' => string 'remote_storage/library/Todo as Admin (14).docx' (length=46)
        'storage' => string 'remote_storage' (length=14)
        'directory' => string 'library/' (length=8)
        */
        $filewrapper = $this->helper("file/wrapper");

        $storage_path = $postData['directory'] . $postData['filename'];

        $file_path = $filewrapper->getPublicPath($postData['full_path']);

        $filesize = filesize($file_path);

        if ($filesize > 2*1024*1024) { // IF GREATER THAN 2MB, SEND IN BACKGROUND
            $this->response->setJsonContent($this->createAdviseResponse(
                $this->translate->translate("File received. You will notified when the file is avaliable"),
                "success"
            ));

            // SEND A EVENT TO SEND THE FILE TO THE CLOUD.

        } else {
            $storage = StorageAdapter::getInstance($postData['storage']);

            $status = $storage->addFile($storage_path, $file_path);

            if ($status) {
                @unlink($file_path);
                $this->response->setJsonContent($this->createAdviseResponse(
                    $this->translate->translate("Success"),
                    "success"
                ));
            } else {
                $this->response->setJsonContent($this->invalidRequestError());
            }
        }
    }

    /**
     * [add a description]
     *
     * @Post("/move")
     * @allow(resource=dropbox, action=edit)
     */
    public function moveRequest() {
        $postData = $this->request->getPost();

        try {

            $storage = StorageAdapter::getInstance($postData['storage']);

            $from_path = $postData['from'] . $postData['name'];
            $dest_path = $postData['dest'] . $postData['name'];

            $status = $storage->moveFile($from_path, $dest_path);

            if ($status) {
                $this->response->setJsonContent($this->createAdviseResponse(
                    $this->translate->translate("Success"),
                    "success"
                ));
            } else {
                $this->response->setJsonContent($this->invalidRequestError());
            }
        } catch(\Sysclass\Services\Storage\Exception $e) {
            $this->response->setJsonContent($this->invalidRequestError());
        }
    }

    /**
     * [add a description]
     *
     * @Post("/folder")
     * @allow(resource=dropbox, action=edit)
     */
    public function newFolderRequest() {
        $postData = $this->request->getPost();

        try {

            $storage = StorageAdapter::getInstance($postData['storage']);
            $path = $postData['parent'] . $postData['name'];
            $status = $storage->addFolder($path);

            if ($status) {
                $this->response->setJsonContent($this->createAdviseResponse(
                    $this->translate->translate("Success"),
                    "success"
                ));
            } else {
                $this->response->setJsonContent($this->invalidRequestError());
            }
        } catch(\Sysclass\Services\Storage\Exception $e) {
            $this->response->setJsonContent($this->invalidRequestError());
        }
    }

    /**
     * [add a description]
     *
     * @Post("/delete")
     * @allow(resource=dropbox, action=edit)
     */
    public function deleteRequest() {
        $postData = $this->request->getPost();

        try {

            $storage = StorageAdapter::getInstance($postData['storage']);
            $path = $postData['url'];
            $status = $storage->deleteFile($path);

            if ($status) {
                $this->response->setJsonContent($this->createAdviseResponse(
                    $this->translate->translate("Success"),
                    "success"
                ));
            } else {
                $this->response->setJsonContent($this->invalidRequestError());
            }
        } catch(\Sysclass\Services\Storage\Exception $e) {
            $this->response->setJsonContent($this->invalidRequestError());
        }
    }



    /**
     * [add a description]
     *
     * @Post("/upload")
     * @allow(resource=dropbox, action=edit)
     */
    public function receiveFilesRequest()
    {
        $this->response->setContentType('application/json', 'UTF-8');

        $param_name = array_key_exists("name", $_GET) ? $_GET['name'] : "files";

        if (!array_key_exists($param_name, $_FILES)) {
            $param_name = reset(array_keys($_FILES));
        }

        $postData = $this->request->getPost();

        $full_path = $postData['storage'] . "/" . $postData['filename'];


        /*
        if (!in_array($type, array("remote_storage"))) {
            $type = "default";
        }
        */

        $helper = $this->helper("file/upload");
        $filewrapper = $this->helper("file/wrapper");



        $upload_dir = $filewrapper->getPublicPath($full_path);
        $upload_url = $filewrapper->getPublicUrl($full_path);

        $helper->setOption('upload_dir', $upload_dir . "/");
        $helper->setOption('upload_url', $upload_url . "/");

        $helper->setOption('param_name', $param_name);
        $helper->setOption('print_response', false);

        switch($type) {
            /*
            case 'video' :{
                $helper->setOption('accept_file_types', '/(\.|\/)(mp4|webm)$/i');
                break;
            }
            case 'subtitle' :{
                $helper->setOption('accept_file_types', '/(\.|\/)(vtt|srt)$/i');
                //$helper->setOption('accept_file_content_types', '/(text\/vtt|application\/x-subrip)/i');

                // AFTER UPDLOAD, PARSE AND 
                break;
            }
            case 'image' :{
                $helper->setOption('accept_file_types', '/(\.|\/)(gif|jpe?g|png)$/i');
                $helper->enableThumbnail();
                break;
            }
            */
        }


        $result = $helper->execute();

        //if ($type == "video") {
            /*
            $filedata = (array) reset($result[$param_name]);

            $filedata['lesson_id'] = $id;
            $filedata['upload_type'] = $type;
            $this->model("lessons/files")->setVideo($filedata);
            */
        $file_result = array(
            $param_name => array()
        );

        $content_range_header = @$_SERVER['HTTP_CONTENT_RANGE'];
        $content_range = $content_range_header ? preg_split('/[^0-9]+/', $content_range_header) : null;

        if ($result[$param_name][0]->error) {
            foreach($result[$param_name] as $fileObject) {
                $filedata = (array) $fileObject;
                //$filedata['lesson_id'] = $id;
                $filedata['upload_type'] = $type;

                $file_result[$param_name][] = $filedata;

                $error = $filedata['error'];
            }
            $this->response->setJsonContent(array_merge($filedata, $this->invalidRequestError($error, "warning")));
            return false;
        } else {

            foreach($result[$param_name] as $fileObject) {
                $fileinfo = (array) $fileObject;
                //var_dump($filedata);
                //exit;
                //$filedata['lesson_id'] = $id;
                //$filedata['upload_type'] = $type;
                $filedata = [];
                $filedata['filename'] = $fileinfo['name'];
                $filedata['full_path'] = $full_path . $fileinfo['name'];
                $filedata['storage'] = $postData['storage'];
                $filedata['directory'] = $postData['filename'];
                
                //$filedata['owner_id'] = $this->user->id;

                // CHECK FOR FILE EXISTENCE
                /*
                if (!is_null($content_range)) {
                    $exists = $this->model("dropbox")->addFilter(array(
                        'type'          => $filedata['type'],
                        'name'          => $filedata['name'],
                        'upload_type'   => $filedata['upload_type']
                    ))->getItems();



                    if (count($exists) > 0) {

                        $this->model("dropbox")->setItem($filedata, $exists[0]['id']);
                        $filedata['id'] = $exists[0]['id'];
                    } else {
                        //$filedata['id'] = $this->model("dropbox")->addItem($filedata);
                    }
                } else {
                    //$filedata['id'] = $this->model("dropbox")->debug()->addItem($filedata);

                }
                */
                /*
                switch($type) {
                    case 'subtitle' :{
                        $result = $this->module("lessons")->normatizeSubtitleFile($filedata);

                        if (!$result) {
                            $this->model("dropbox")->deleteItem($filedata['id']);
                            return $this->invalidRequestError(
                                $this->translate->translate("The file appear to be empty or in a invalid format."),
                                "warning"
                            );
                        }
                        break;
                    }
                }
                */

                //$filedata = $this->model("dropbox")->clear()->getItem($filedata['id']);

                $file_result[$param_name][] = $filedata;
            }
        }
        $this->response->setJsonContent($file_result);

        return $file_result;
    }

}
