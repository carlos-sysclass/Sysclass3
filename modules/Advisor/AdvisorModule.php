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
    	$widgets = array();

        if (in_array('advisor.schedule', $widgetsIndexes)) {
            $widgets['advisor.schedule'] = array(
                'id'        => 'advisor-schedule-widget',
                'template'  => $this->template("widgets/schedule"),
                'header'     => $this->translate->translate("Queue List"),
                'panel'     => true

            );
        }

        if (in_array('advisor.queue.list', $widgetsIndexes)) {
            $this->putModuleScript("widget.queue.list");

            $block_context = $this->getConfig("widgets\advisor.queue.list\context");
            $this->putItem("advisor_queue_list_context", $block_context);

            $this->putComponent("data-tables");

            $this->putBlock("advisor.chat");

            $widgets['advisor.queue.list'] = array(
                'id'        => 'advisor-queue-list',
                'template'  => $this->template("widgets/queue.list"),
                'header'     => $this->translate->translate("Chat List"),
                'body'      => false,
                'icon'      => "fa fa-comment",
                'panel'     => false

            );
        }
        if (in_array('advisor.queue.filter', $widgetsIndexes)) {
            //$this->putModuleScript("widget.queue.list");

            //$block_context = $this->getConfig("widgets\advisor.queue.list\context");
            //$this->putItem("advisor_queue_list_context", $block_context);

            $this->putComponent("data-tables");

            //$this->putBlock("advisor.chat");

            $widgets['advisor.queue.filter'] = array(
                'id'        => 'advisor-queue-filter',
                'template'  => $this->template("widgets/queue.filter"),
                'title'     => $this->translate->translate("Filters"),
                'body'      => false,
                'icon'      => "fa fa-filter",
                'panel'     => false
            );
        }

        return count($widgets) > 0 ? $widgets : false;
    }

    // IBlockProvider
    public function registerBlocks() {
        return array(
            /*
            'chat.views' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putModuleScript("chat.views");

                $self->putSectionTemplate("foot", "blocks/chat.views");

                return true;

            },
            */
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
