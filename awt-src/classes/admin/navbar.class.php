<?php

namespace admin;

use admin\profiler;

class navbar 
{
    protected array $items;
    protected object $profiler;

    public function __construct()
    {
        $this->profiler = new profiler;
    }

    public function addItem(array $item)
    {
        $this->items[] = $item;
    }

    public function writeItems()
    {
        foreach ($this->items as $item => $value) {

            if(!isset($value['attr'])) $value['attr'] = '';

            $attr = $value['attr'];

            if(isset($value['permission'])) {
                if(!$this->profiler->checkPermissions($value['permission'])) {
                    unset($this->items[$item]);
                    continue;
                }
            }

            if(isset($value['icon'])) {

                $class = 'def-icon';

                if(str_ends_with($value['icon'], '.svg')) $class = 'svg';
                
                echo "<a class='nav-item' href='".$value['link']."'$attr><img class='".$class."' src='".$value['icon']."'/>".$value['name']."</a>";
            } else {
                echo "<a class='nav-item' href='".$value['link']."'$attr>".$value['name']."</a>";
            }
        }
    }
}