<?php

namespace Theming\classes\Theme;

use controller\Controller;
use database\DatabaseManager;
use DOMDocument;
use DOMElement;
use redirect\Redirect;
use view\View;

abstract class ThemePage extends Controller
{
    private int $themeId;
    private DatabaseManager $database;
    private array $pages = [];

    /**
     * @inheritDoc
     */

    abstract public function index(array|string $params): View|Redirect;

    public function setID(int $themeId): ThemePage
    {
        $this->themeId = $themeId;
        return $this;
    }

    public function addPages(array $pages): self
    {
        $this->pages = array_merge($this->pages, $pages);
        return $this;
    }


    public function view(array $data = []): View
    {
        $db = new DatabaseManager();
        $res = $db->table("theming_custom_page")->select(["quil_page_content.content"])
            ->join("quil_page_content", "theming_custom_page.page_content_id = quil_page_content.id")
            ->where(["name" => $this->matchPageName(), "theme_id" => $this->themeId])
            ->get()[0];

        if (count($res) > 0) {
            $dom = new DOMDocument();
            $content = $this->getViewContent();

            @$dom->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

            $pageElement = $dom->getElementById("page");
            if ($pageElement) {

                $contentDom = new DOMDocument();
                @$contentDom->loadHTML($res["content"], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

                $newContent = $dom->importNode($contentDom->documentElement, true);
                $pageElement->parentNode->replaceChild($newContent, $pageElement);
            }

            $this->bundle = $data;
            $this->createBundleObjects();
            $this->createDocument($dom->saveHTML());

            return $this;
        }



        return parent::view($data);
    }


    private function matchPageName(): string
    {
        foreach ($this->pages as $page) {
            if ($page['viewName'] == $this->viewName) {
                return $page['name'];
            }
        }

        return '';
    }

}