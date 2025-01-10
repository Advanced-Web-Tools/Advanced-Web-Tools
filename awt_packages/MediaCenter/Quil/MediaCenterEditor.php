<?php

use Quil\classes\editor\runtime\api\QuilEditorAPI;
class MediaCenterEditor extends QuilEditorAPI
{
    public function setup(): void
    {
        $this->addScriptPath('MediaCenter', 'js/main');
    }
}