<?php

namespace Quil\classes\page\models;

/**
 * Class PageContent
 *
 * - Part of `Quil` package.
 * - Use with `Dashboard` package.
 *
 * Manages content of custom pages.
 * @property string $content
 */
class PageContent extends PageInfo
{
    public function __construct(int $id)
    {
        parent::__construct($id);
        $this->selectByID((int) $this->getParam("id"), "quil_page_content", "page_id");
    }
}