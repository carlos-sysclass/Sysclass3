<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * Manage and control the advertising system strategy
 * @package Sysclass\Modules
 */
class AdvertisingModule extends SysclassModule implements IWidgetContainer, ILinkable, IBlockProvider
{
    protected $_modelRoute = "advertising";
    /* IWidgetContainer */
    public function getWidgets($widgetsIndexes = array()) {

        $leftbar_data = $this->getConfig("widgets\ads.leftbar.banner\context");
        $rightbar_data = $this->getConfig("widgets\ads.rightbar.banner\context");

        return array(
            'ads.leftbar.banner' => array(
                'id'        => 'advertising-leftbar-banner',
                'template'  => $this->template("widgets/leftbar.banner"),
                'data'      => $leftbar_data
            ),
            'ads.rightbar.banner' => array(
                'id'        => 'advertising-rightbar-banner',
                'template'  => $this->template("widgets/rightbar.banner"),
                'data'      => $rightbar_data
            ),
            'advertising' => array(
                //'title'     => self::$t->translate('Advertising'),
                'id'        => 'advertising-panel',
                'template'  => $this->template("advertising.block"),
                'panel'     => true
            )
        );
    }

    /* ILinkable */
    public function getLinks() {

        if ($this->getCurrentUser(true)->getType() == 'administrator') {
            $itemsData = $this->model($this->_modelRoute)->getItems();
            //$items = $this->module("permission")->checkRules($itemsData, "test", 'permission_access_mode');

            return array(
                'communication' => array(
                    array(
                        'count' => count($itemsData),
                        'text'  => self::$t->translate('Advertising'),
                        'icon'  => 'fa fa-money',
                        'link'  => $this->getBasePath() . 'view'
                    )
                )
            );
        }
    }

    // IBlockProvider
    public function registerBlocks() {
        return array(
            'advertising.banners' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putComponent("jquery-file-upload-image");
                $self->putComponent("bootstrap-confirmation");

                $self->putModuleScript("blocks.advertising.banners");

                $self->putSectionTemplate("advertising-banners", "blocks/banners.list");
                //$self->putSectionTemplate("foot", "dialogs/season.add");
                //$self->putSectionTemplate("foot", "dialogs/class.add");

                return true;
            }
        );
    }

    /**
     * [ add a description ]
     *
     * @url GET /add
     */
    public function addPage()
    {
        $placements = array(
            array('id' => 'ads.leftbar.banner', 'name' => self::$t->translate('Left Side')),
            array('id' => 'ads.rightbar.banner', 'name' => self::$t->translate('Right Side'))
        );
        $this->putitem("placements", $placements);

        $view_types = array(
            array('id' => 'serial', 'name' => 'Serial'),
            array('id' => 'carrousel', 'name' => 'Carroussel')
        );
        $this->putitem("view_types", $view_types);

        parent::addPage();
    }

    /**
     * [ add a description ]
     *
     * @url GET /edit/:identifier
     */
    public function editPage($identifier)
    {
        $placements = array(
            array('id' => 'ads.leftbar.banner', 'name' => self::$t->translate('Left Side')),
            array('id' => 'ads.rightbar.banner', 'name' => self::$t->translate('Right Side'))
        );
        $this->putitem("placements", $placements);

        $view_types = array(
            array('id' => 'serial', 'name' => 'Serial'),
            array('id' => 'carrousel', 'name' => 'Carroussel')
        );
        $this->putitem("view_types", $view_types);

        parent::editPage($identifier);
    }
}
