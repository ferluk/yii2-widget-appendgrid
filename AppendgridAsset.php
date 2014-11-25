<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace ferrika\appendgrid;
/**
 * Description of AppendGridAsset
 *
 * @author ferrika
 */
class AppendgridAsset extends \yii\web\AssetBundle {
    
    public $depends = [
        'yii\jui\JuiAsset',
    ];
//    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    
    public function init() {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['js/jquery.appendGrid-1.5.0']);
        $this->setupAssets('css', ['css/jquery.appendGrid-1.5.0']);
        parent::init();
    }
    
    /**
     * Set up CSS and JS asset arrays based on the base-file names
     * @param string $type whether 'css' or 'js'
     * @param array $files the list of 'css' or 'js' basefile names
     */
    protected function setupAssets($type, $files = [])
    {
        $srcFiles = [];
        $minFiles = [];
        foreach ($files as $file) {
            $srcFiles[] = "{$file}.{$type}";
            $minFiles[] = "{$file}.min.{$type}";
        }
        if (empty($this->$type)) {
            $this->$type = YII_DEBUG ? $srcFiles : $minFiles;
        }
    }
    
    /**
     * Sets the source path if empty
     * @param string $path the path to be set
     */
    protected function setSourcePath($path)
    {
        if (empty($this->sourcePath)) {
            $this->sourcePath = $path;
        }
    }
}
