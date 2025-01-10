<?php

use Dashboard\classes\dashboard\DashboardPage;
use database\DatabaseManager;
use packages\enums\EPackageType;
use packages\manager\PackageManager;
use Quil\classes\page\PageManager;
use redirect\Redirect;
use Theming\classes\events\EGetThemePages;
use Theming\classes\Theme\ThemeModel;
use view\View;

final class ThemingController extends DashboardPage
{
    private PackageManager $packageManager;

    public function __construct()
    {
        parent::__construct();
    }

    public function index(array|string $params): View
    {
        $this->adminCheck();

        $this->eventDispatcher->dispatch($this->event);

        $this->setTitle("Themes");

        $database = new DatabaseManager();
        $result = $database->table("theming_theme")->select(["*"])->where(["1" => 1])->get();

        $bundle = [];

        $activeTheme = null;

        foreach ($result as $key => $value) {
            $bundle["themes"][$value["id"]] = new ThemeModel(null, $value);

            if ($bundle["themes"][$value["id"]]->status == 1) {
                $activeTheme = $bundle["theme"] = $bundle["themes"][$value["id"]];
            }
        }


        $bundle["settings"] = $database->table("theming_settings")->select(["*"])->where(["theme_id" => $activeTheme->id])->get();

        $pageEvent = new EGetThemePages();

        $this->eventDispatcher->dispatch($pageEvent);

        $bundle["pages"] = $pageEvent->bundle();

        return $this->view($this->getViewBundle($bundle));
    }


    public function customize(array|string $params): Redirect
    {
        $this->adminCheck();
        $check = $this->checkForCustomized($params);

        if($check) {
            return (new Redirect)->redirect("/quil/page_editor/{$check}?id={$check}");
        }

        return $this->enableCustomization($params);
    }

    private function checkForCustomized(array $params): bool|int {
        $db = new DatabaseManager();
        $res = $db->table("theming_custom_page")
            ->select(["quil_page_content.page_id"])
            ->join("quil_page_content", "quil_page_content.id = theming_custom_page.page_content_id")
            ->where([
                "name" => $params["name"],
                "theme_id" => $params["theme_id"]
            ])
            ->get()[0];

        if($res != null && count($res) > 0) {
            return $res["page_id"];
        }

        return false;
    }

    private function enableCustomization(array $params): Redirect {
        $pm = new PageManager();
        $db = new DatabaseManager();

        $package = $db->table("theming_theme")->
        select(["*"])->
        where(["id" => $params["theme_id"]])->
        get()[0];

        $package = $db->table("awt_package")->
            select(["name"])->
            where(["id" => $package["package_id"]])->
            get()[0];

        $pm->createPage($params["name"] . "_themed_page_" . $package["name"]);

        $db->table("theming_custom_page")
            ->insert([
                "theme_id" => $params["theme_id"],
                "page_content_id" => $pm->contentId,
                "name" => $params["name"]
            ])
            ->executeInsert();

        $pageEvent = new EGetThemePages();

        $this->eventDispatcher->dispatch($pageEvent);
        $page = $pageEvent->bundle();

        $page = self::getPageArray($page, $params["name"]);

        $content = self::getPageContent(PACKAGES . $package["name"] . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . $page["viewName"] . ".awt.php");

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($content);
        libxml_clear_errors();

        $content = $dom->getElementById("page");
        $content = $dom->saveHTML($content);

        $pm->savePage(["id" => $pm->pageId, "content" => $content]);
        return (new Redirect)->redirect("/quil/page_editor/{$pm->pageId}?id={$pm->pageId}");
    }

    private static function getPageContent(string $path): string
    {
        ob_start();
        include $path;
        return ob_get_clean();
    }

    private static function getPageArray(array $pages, string $name): array
    {
        foreach ($pages as $page) {
            if($page["name"] == $name) {
                return $page;
            }
        }
        return [];
    }

    private function filterPackages(array $packages): array
    {

        foreach ($packages as $key => $package) {
            $type = $package->packageType;

            if ($type !== EPackageType::Theme)
                unset($packages[$key]);

        }

        return $packages;
    }


}