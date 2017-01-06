<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AppendGrid
 *
 * @author ferrika
 */
use yii\helpers\Json;

namespace ferrika\appendgrid;

class Appendgrid extends \yii\base\Widget {
    
    /**
     * The text as table caption.
     * @var string
     */
    public $caption = 'Untitled';
    
    
    /**
     *
     * @var type
     */
    public $model;
    
    /**
     * Model class name
     * @var string 
     */
    public $idPrefix;
    
    /**
     * The total number of empty rows generated when init the grid.
     * This will be ignored if initData is assigned
     * @var integer 
     */
    public $initRows = 1;
    
    /**
     *
     * @var array 
     */
    public $columns = [];
    
    /**
     *
     * @var array 
     */
    public $options = ['class' => 'grid-view'];

    public $afterRowInserted = null;
    public $afterRowAppended = null;
    public $dataLoaded = null;
    public $rowDataLoaded = null;
    /**
     * appendGrid Column Formats
     */
    const TEXT = 'text';
    const SELECT = 'select';
    
    public function init() {        
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        
        $this->initColumns();
    }
    public function run() {
        $this->registerAssets();
        $this->renderTable();
    }
    
    /**
     * Register client assets
     */
    protected function registerAssets() {
        $id = $this->options['id'];
        $parameters = \yii\helpers\Json::encode($this->getClientParameters());
        $view = $this->getView();
        
        AppendgridAsset::register($view);     
        $view->registerJs("$('#$id').appendGrid($parameters);", \yii\web\View::POS_END);
    }
    
    protected function initColumns() {
        foreach ($this->columns as $i => $column) {
            if ($column['type'] == self::SELECT) {
                $this->array_unshift_assoc($this->columns[$i]['ctrlOptions'], 0, '{Choose}');
            }
            $this->columns[$i]['ctrlClass'] = 'form-control';
        }
    }
    
    public function array_unshift_assoc(&$arr, $key, $val) 
    { 
        $arr = array_reverse($arr, true); 
        $arr[$key] = $val; 
        return array_reverse($arr, true); 
    } 

    protected function initData() {
        $initData = $data = [];
        
        if (!empty($this->model)) {
            foreach ($this->model as $i => $model) {
                foreach ($this->columns as $j => $column) {
                    $data[$column['name']] = $model->attributes[$column['name']];
                }
                $initData[] = $data;
            }
            return $initData;
        }
        return NULL;
    }
    
    protected function getClientParameters() {
        return [
            'caption' => $this->caption,
            'idPrefix' => $this->idPrefix,
            'initRows' => $this->initRows,
            'columns' => $this->columns,
            'afterRowInserted' => $this->afterRowInserted,
            'afterRowAppended' => $this->afterRowAppended,
            'dataLoaded' => $this->dataLoaded,
            'rowDataLoaded' => $this->rowDataLoaded,
            'initData' => $this->initData(),
            'nameFormatter' => new \yii\web\JsExpression(
                "function (idPrefix, name, uniqueIndex) {
                    return idPrefix + '[' + uniqueIndex + '][' + name + ']';
            }"),
            'idFormatter' => new \yii\web\JsExpression(
                "function (idPrefix, name, uniqueIndex) {
                    return idPrefix + '-' + uniqueIndex + '-' + name;
            }"),
            
        ];
    }
    
    protected function renderTable() {
        echo "<table id='{$this->options['id']}' class='table'></table>";
    }
}
