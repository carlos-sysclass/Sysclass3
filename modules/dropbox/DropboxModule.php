<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */

class DropboxModule extends SysclassModule /* implements ILinkable, IBreadcrumbable, IActionable, IBlockProvider */
{

    /**
     * Get all users visible to the current user
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
     * Get all users visible to the current user
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
