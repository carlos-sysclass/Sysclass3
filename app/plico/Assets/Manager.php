<?php
namespace Plico\Assets;

use Phalcon\DI,
    Phalcon\Assets\Manager as AssetsManager;

class Manager extends AssetsManager
{
    public function output($collection, $callback, $type=null){
        /*
        if( is_file($collection->getTargetPath())) {
            if ( $type == 'css' ) {
                return \Phalcon\Tag::stylesheetLink( $collection->getTargetUri() );
            }
            if ( $type == 'js' ) {
                return \Phalcon\Tag::javascriptInclude( $collection->getTargetUri() );
            }
        }else{
        */
            return parent::output($collection, $callback, $type);
        //}
    }

}
