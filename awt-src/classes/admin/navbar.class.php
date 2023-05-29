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
        foreach ($this->items as $item) {

            if(!isset($item['attr'])) $item['attr'] = '';

            $attr = $item['attr'];

            if(isset($item['permission'])) {
                if(!$this->profiler->checkPermissions($item['permission'])) {
                    return;
                }
            }
            if(isset($item['icon'])) {

                $class = 'def-icon';

                if(str_ends_with($item['icon'], '.svg')) $class = 'svg';

                echo "<a class='nav-item' href='".$item['link']."'$attr><img class='".$class."' src='".$item['icon']."'/>".$item['name']."</a>";
            } else {
                echo "<a class='nav-item' href='".$item['link']."'$attr>".$item['name']."</a>";
            }
        }
    }
}