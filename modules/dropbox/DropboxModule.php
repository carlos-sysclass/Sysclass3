<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */

class DropboxModule extends SysclassModule implements IBlockProvider /* implements ILinkable, IBreadcrumbable, IActionable, IBlockProvider */
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

                $self->putSectionTemplate("foot", "blocks/dropbox.upload");

                return true;
            }
        );
    }

    /**
     * [add a description]
     *
     * @url GET /item/:model/:id
     */
    public function getItemAction($model = "me", $id)
    {
        if ($model == "me") {
            $itemModel = $this->model("dropbox");
            return $itemModel->getItem($id);
        }
        return $this->invalidRequestError();
    }

    /**
     * [ add a description ]
     *
     * @url PUT /item/:model/:id
     */
    public function setItemAction($model, $id)
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
                $response = $this->createAdviseResponse(self::$t->translate($messages['success']), "success");
                return array_merge($response, $data);
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(self::$t->translate($messages['error']), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * DELETE a news model
     *
     * @url DELETE /item/:model/:id
     */
    public function deleteItemAction($model = "me", $id)
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
                $response = $this->createAdviseResponse(self::$t->translate($messages['success']), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(self::$t->translate($messages['error']), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * [add a description]
     *
     * @url POST /upload/
     * @url POST /upload/:type
     */
    public function receiveFilesAction($type = "default")
    {
        $param_name = array_key_exists("name", $_GET) ? $_GET['name'] : "files";

        if (!in_array($type, array("video", "image", "material", "default"))) {
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

        //} elseif ($type == "material") {
            $file_result = array(
                $param_name => array()
            );
            foreach($result[$param_name] as $fileObject) {
                $filedata = (array) $fileObject;
                //$filedata['lesson_id'] = $id;
                $filedata['upload_type'] = $type;
                $filedata['id'] = $this->model("dropbox")->addItem($filedata);

                $file_result[$param_name][] = $filedata;
            }
        //}
        return $file_result;
    }

    /**
     * [add a description]
     *
     * @url DELETE /upload/:lesson_id/:file_id
     */
    public function removeFilesAction($lesson_id, $file_id)
    {
        if ($userData = $this->getCurrentUser()) {
            $itemModel = $this->model("lessons/files");

            $files = $itemModel->clear()->addFilter(array(
                'lesson_id' => $lesson_id,
                'id'        => $file_id
            ))->getItems();

            if (count($files) > 0 && $itemModel->deleteItem($file_id) !== FALSE) {
                $response = $this->createAdviseResponse(self::$t->translate("File removed with success"), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(self::$t->translate("There's ocurred a problem when the system tried to remove your data. Please check your data and try again"), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

}
