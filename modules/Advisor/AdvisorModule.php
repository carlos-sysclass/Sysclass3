<?php
namespace Sysclass\Modules\Advisor;
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/advisor")
 */
class AdvisorModule extends \SysclassModule implements /* ISummarizable, */\IWidgetContainer, \IBlockProvider
{
    // ISummarizable
    public function getSummary()
    {
        $data = array(1); // FAKE, PUT HERE DUE PAYMENTS

        return array(
            'type'  => 'primary',
            'count' => $data[0],
            'text'  => $this->translate->translate('Scheduled Meetings'),
            'link'  => array(
                'text'  => $this->translate->translate('View'),
                'link'  => $this->getBasePath() . 'all'
            )
        );
    }

    // IWidgetContainer
    public function getWidgets($widgetsIndexes = array())
    {
        if (in_array('advisor.chat', $widgetsIndexes) || in_array('advisor.schedule', $widgetsIndexes)) {
        	$widgets = array();

            if (in_array('advisor.chat', $widgetsIndexes)) {
                // START CHART ON CLICK
                //
                $this->putModuleScript("widget.chat.advisor");

                $this->putBlock("advisor.chat");

                $widgets['advisor.chat'] = array(
                    'id'        => 'advisor-chat-widget',
       				'template'	=> $this->template("widgets/chat"),
                    'header'     => $this->translate->translate("Talk to us"),
                    'body'      => false,
                    'icon'      => "fa fa-comment",
                    'panel'     => 'dark-blue'
        		);
            }

            if (in_array('advisor.schedule', $widgetsIndexes)) {
                $widgets['advisor.schedule'] = array(
                    'id'        => 'advisor-schedule-widget',
                    'template'  => $this->template("widgets/schedule"),
                    'panel'     => true

                );
            }

            return $widgets;
        }
        return false;
    }

    // IBlockProvider
    public function registerBlocks() {
        return array(
            'chat.views' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putModuleScript("chat.views");

                $self->putSectionTemplate("foot", "blocks/chat.views");

                return true;

            },
            'advisor.chat' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putComponent("autobahn");
                $self->putModuleScript("chat");
                $self->putSectionTemplate("foot", "blocks/chat");

                return true;

            }
        );
    }
}
