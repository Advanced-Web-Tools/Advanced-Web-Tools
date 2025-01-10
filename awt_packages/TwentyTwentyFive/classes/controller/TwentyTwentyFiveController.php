<?php

namespace TwentyTwentyFive\classes\controller;
use redirect\Redirect;
use Theming\classes\Theme\ThemePage;
use view\View;

final class TwentyTwentyFiveController extends ThemePage
{
    public function index(array|string $params): View|Redirect
    {
        return $this->view();
    }
}