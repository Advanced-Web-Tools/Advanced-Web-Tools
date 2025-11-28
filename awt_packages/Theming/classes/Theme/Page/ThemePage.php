<?php

namespace Theming\classes\Theme\Page;

use controller\Controller;
use database\DatabaseManager;
use DOMDocument;
use redirect\Redirect;
use render\events\RenderReadyEvent;
use render\TemplateEngine\BladeOne;
use Theming\classes\models\ThemingCustomPage;
use Theming\classes\models\ThemingTheme;
use Theming\classes\Theme\Settings\ThemeSettings;
use Throwable;
use view\View;

abstract class ThemePage extends Controller
{
    private int $themeId;
    private DatabaseManager $database;
    protected array $pages = [];

    private bool $edited = false;

    protected ThemeSettings $settings;
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

    public function setSettings(ThemeSettings $settings): self
    {
        $this->settings = $settings;
        return $this;
    }


    public function view(array $data = []): View
    {
        $db = new DatabaseManager();


        $res = $db->table("theming_custom_page")
            ->select(["quil_page_content.content", "theming_custom_page.id"])
            ->join("quil_page_content", "theming_custom_page.page_content_id = quil_page_content.id")
            ->where([
                "name"     => $this->matchPageName(),
                "theme_id" => $this->themeId
            ])
            ->get()[0] ?? null;

        $data["theme"] = $this->settings;

        if ($res !== null && !empty($res["content"])) {
            $dom = new DOMDocument();
            $content = $this->getViewContent();

            @$dom->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

            $pageElement = $dom->getElementById("page");
            if ($pageElement) {

                $this->edited = true;

                $contentDom = new DOMDocument();
                @$contentDom->loadHTML($res["content"], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

                while ($pageElement->firstChild) {
                    $pageElement->removeChild($pageElement->firstChild);
                }


                foreach ($contentDom->childNodes as $child) {
                    $imported = $dom->importNode($child, true);
                    $pageElement->appendChild($imported);
                }
            }

            $this->bundle = $data;
            $this->createBundleObjects();
            $this->createDocument($dom->saveHTML());

            return $this;
        }
        $this->bundle = $data;
        $this->createBundleObjects();
        $this->createDocument($this->getViewContent());

        return parent::view($data);
    }

    public function render(): string
    {
        if(!$this->edited)
            return parent::render();

        $this->loadTemplate();

        $this->createBundleObjects();

        try {
            $parser = new BladeOne($this->viewDirectory, CACHE, BladeOne::MODE_AUTO);
            $parser->setFileExtension(".awt.php");

            $parser->addAssetDict(0, $this->localAssetPath);
            $parser->setPackageName($this->packageName);


            $parser->with($this->bundle);
            $compiled = $parser->runString($this->dom->saveHTML());
            $this->dom->loadHTML($compiled);
            $ready = new RenderReadyEvent();
            $ready->renderer = $this;
            $this->eventDispatcher->dispatch($ready);

            return $this->dom->saveHTML();

        } catch (Throwable $e) {
            if(DEBUG)
                $e->getTraceAsString();

            echo "An error has occurred while rendering: " . $e->getMessage();
        }
        return "";
    }


    protected function matchPageName(): string
    {
        foreach ($this->pages as $page) {
            if ($page['viewName'] == $this->viewName) {
                return $page['name'];
            }
        }

        return '';
    }

}