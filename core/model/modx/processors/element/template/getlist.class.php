<?php
require_once (dirname(__DIR__).'/getlist.class.php');
/**
 * Grabs a list of templates.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 20.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.template
 */
class modTemplateGetListProcessor extends modElementGetListProcessor {
    public $classKey = 'modTemplate';
    public $languageTopics = array('template', 'category', 'lexicon:template', 'lexicon:category');
    public $defaultSortField = 'templatename';
    public $permission = 'view_template';

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c = parent::prepareQueryBeforeCount($c);
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                'templatename:LIKE' => "$query%"
            ));
        }
        return $c;
    }

    public function beforeIteration(array $list) {
        if ($this->getProperty('combo',false) && !$this->getProperty('query', false)) {
            $empty = array(
                'id' => 0,
                'templatename' => $this->modx->lexicon('template_empty'),
                'description' => '',
                'editor_type' => 0,
                'icon' => '',
                'template_type' => 0,
                'content' => '',
                'locked' => false,
            );
            $empty['category_name'] = '';
            $list[] = $empty;
        }
        return $list;
    }

    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();

        if ('category.' . $objectArray['category_name'] != ($lexicon = $this->modx->lexicon('category.' . $objectArray['category_name']))) {
            $objectArray['category_name'] = $lexicon;
        }

        if (empty($objectArray['description'])) {
            if ('template.' . $objectArray['templatename'] . '_desc' != ($lexicon = $this->modx->lexicon('template.' . $objectArray['templatename'] . '_desc'))) {
                $objectArray['description'] = $lexicon;
            }
        }

        if ('template.' . $objectArray['templatename'] != ($lexicon = $this->modx->lexicon('template.' . $objectArray['templatename']))) {
            $objectArray['templatename'] = $lexicon;
        }

        unset($objectArray['content']);

        return $objectArray;
    }
}
return 'modTemplateGetListProcessor';
