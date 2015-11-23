<?php
/**
 * Module Class File
 * @filesource
 */
namespace Sysclass\Modules\Dropbox;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/dropbox")
 */
class DropboxModule extends \SysclassModule implements \IBlockProvider
{

    public function registerBlocks() {
        return array(
            'dropbox.upload' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putComponent("jquery-file-upload-image");
                $self->putComponent("jquery-file-upload-video");
                $self->putComponent("jquery-file-upload-audio");
                $self->putComponent("bootstrap-confirmation");

                $self->putModuleScript("blocks.dropbox.upload");

                $self->putSectionTemplate("dialogs", "dialogs/image-crop");
                $self->putSectionTemplate("foot", "blocks/dropbox.upload");

                return true;
            }
        );
    }

    public function beforeModelUpdate($evt, $model, $data) {
        // CHECK IF CROP IS NEEDED
        if (array_key_exists("crop", $data)) {
            $stream = $this->storage->getFilestream($model);

            $image = new \Plico\Php\Image();
            $croped = $image->resize($stream, $data['crop'], 150, 150);

            $file_path = $this->storage->getFullFilePath($model);
            $file_full_path = $image->saveAsJpeg($croped, $file_path);

            if ($file_full_path) {

                $path_info = pathinfo($file_full_path);

                $model->name = $path_info['basename'];
                $model->filename = $path_info['basename'];
                $model->type = "image/jpeg";

                $model->size = filesize($file_full_path);

                $model->url = $this->storage->getFullFileUrl($model);

                $path_info = pathinfo($file_path);
            }
        }

        return true;
    }

    /**
     * [add a description]
     *
     * @url GET /item/:model/:id
     */
    /*
    public function getItemRequest($model = "me", $id)
    {
        if ($model == "me") {
            $itemModel = $this->model("dropbox");
            return $itemModel->getItem($id);
        }
        return $this->invalidRequestError();
    }
    */
    /**
     * [ add a description ]
     *
     * @url PUT /item/:model/:id
     */
    /*
    public function setItemRequest($model, $id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            if ($model == "me") {
                $itemModel = $this->model("dropbox");
                $messages = array(
                    'success' => "File updated with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } else {
                return $this->invalidRequestError();
            }

            if ($itemModel->setItem($data, $id) !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate($messages['success']), "success");
                return array_merge($response, $data);
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError($this->translate->translate($messages['error']), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    */
    /**
     * [ add a description ]
     *
     * @url DELETE /item/:model/:id
     * @allow(resource=dropbox, action=delete)
     */
    /*
    public function deleteItemRequest($model = "me", $id)
    {
        if ($userData = $this->getCurrentUser()) {
           if ($model == "me") {
                $itemModel = $this->model("dropbox");
                $messages = array(
                    'success' => "File removed with success",
                    'error' => "There's ocurred a problem when the system tried to remove your file. Please check your data and try again"
                );
            } else {
                return $this->invalidRequestError();
            }

            $data = $this->getHttpData(func_get_args());

            if ($itemModel->deleteItem($id) !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate($messages['success']), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError($this->translate->translate($messages['error']), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    */
    /**
     * [add a description]
     *
     * @Post("/upload")
     * @Post("/upload/{type}")
     * @allow(resource=dropbox, action=edit)
     */
    public function receiveFilesRequest($type = "default")
    {
        $this->response->setContentType('application/json', 'UTF-8');

        $param_name = array_key_exists("name", $_GET) ? $_GET['name'] : "files";

        if (!array_key_exists($param_name, $_FILES)) {
            $param_name = reset(array_keys($_FILES));
        }

        if (!in_array($type, array("video", "lesson", "subtitle", "image", "material", "default"))) {
            $type = "default";
        }

        $helper = $this->helper("file/upload");
        $filewrapper = $this->helper("file/wrapper");

        $upload_dir = $filewrapper->getPublicPath($type);
        $upload_url = $filewrapper->getPublicUrl($type);


        $helper->setOption('upload_dir', $upload_dir . "/");
        $helper->setOption('upload_url', $upload_url . "/");

        $helper->setOption('param_name', $param_name);
        $helper->setOption('print_response', false);

        switch($type) {
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
                $filedata = (array) $fileObject;
                //$filedata['lesson_id'] = $id;
                $filedata['upload_type'] = $type;
                $filedata['filename'] = $filedata['name'];
                $filedata['owner_id'] = $this->user->id;

                // CHECK FOR FILE EXISTENCE
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
                        $filedata['id'] = $this->model("dropbox")->addItem($filedata);
                    }
                } else {
                    $filedata['id'] = $this->model("dropbox")->addItem($filedata);
                }

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

                $filedata = $this->model("dropbox")->getItem($filedata['id']);

                $file_result[$param_name][] = $filedata;
            }
        }
        $this->response->setJsonContent($file_result);



        return $file_result;
    }

    /**
     * [add a description]
     *
     * @Delete("/upload/{lesson_id}/{file_id}")
     * @deprecated
     */
    public function removeFilesRequest($lesson_id, $file_id)
    {
        if ($userData = $this->getCurrentUser()) {
            $itemModel = $this->model("lessons/files");

            $files = $itemModel->clear()->addFilter(array(
                'lesson_id' => $lesson_id,
                'id'        => $file_id
            ))->getItems();

            if (count($files) > 0 && $itemModel->deleteItem($file_id) !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate("File removed with success"), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError($this->translate->translate("There's ocurred a problem when the system tried to remove your data. Please check your data and try again"), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

    protected function isUserAllowed($action, $args) {
        $allowed = parent::isUserAllowed($action);
        if ($allowed) {
            switch($action) {
                case "edit" :
                case "delete" : {
                    // Check if the user is the owner of the file.
                    if (is_object($this->_args['object'])) {
                        return is_null($this->_args['object']->owner_id) || $this->_args['object']->owner_id == $this->user->id;
                    }
                }
            }
        }
        return $allowed;
    }

}
