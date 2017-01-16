<?php
/**
 * Module Class File
 * @filesource
 */
namespace Sysclass\Modules\Storage;
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

                $self->putModuleScript("dialogs.storage.library");

                $self->putSectionTemplate("dialogs", "dialogs/library");

                return true;
            }
        );
    }

}
