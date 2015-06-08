<?php
/**
 * File "Local Backend" Helper File
 * @filesource
 */
/**
 * Provides functions to manipulate files in local backend
 *
 * The interface IFileBackendInterface isn't not created yet, this file will be the based to it.
 * @package Sysclass\Helpers
 */
abstract class AbstractFileBackend extends LoaderManager
{
    protected $mimeTypes = array(
        'text/vtt'  => 'vtt',
    );
    protected function generateRandomFilename($suffix)
    {
        $helper = $this->helper("uuid");
        $uuid = $helper::get();
        if (!is_null($suffix)) {
            return $uuid . "." . $suffix;
        }
        return $uuid;
    }

    protected function getExtensionFromMimeType($mimeType)
    {
        return $this->mimeTypes[$mimeType];
    }
}
