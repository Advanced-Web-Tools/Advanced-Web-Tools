<?php

use controller\Controller;
use Dashboard\classes\dashboard\DashboardPage;
use setting\Config;
use setting\Settings;
use view\View;
use redirect\Redirect;

final class SettingsController extends DashboardPage
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    public function index(array|string $params): View
    {
        $this->adminCheck();

        $this->setTitle("Settings");

        $this->eventDispatcher->dispatch($this->event);

        if($params["category"] == null) {
            $bundle["category"] = "General";
        } else {
            $bundle["category"] = $params["category"];
        }

        $set = new Settings();

        $bundle["settings"] = $set->fetchSettings()->getSettings();

        foreach ($bundle["settings"] as $key => $setting) {
            if($setting->category !==  $bundle["category"]) {
                unset($bundle["settings"][$key]);
            }
            $bundle["cats"][] = $setting->category;
            $bundle["cats"] = array_unique($bundle["cats"], SORT_REGULAR);
        }

        return $this->view($this->getViewBundle($bundle));
    }

    public function changeSetting(array|string $params): Redirect {

        $this->adminCheck();
        $set = new Settings();

        $set->fetchSettings();
        $setting = $set->getSetting($_POST["change"]);

        if(!$this->admin->checkPermission($setting->required_permission_level)) {
            $red = new Redirect();
            $red->back();
            $red->redirectTo .= "?error";
            return $red;
        }

        $_POST["change"] = str_replace(" ", "_", $_POST["change"]);

        if($setting->type == "boolean") {
            if(isset($_POST[$_POST["change"]])) {
                $setting->value = true;
            } else {
                $setting->value = false;
            }
        } else {
            $setting->value = $_POST[$_POST["change"]];
        }

        $setting->setModelId($setting->id);
        $setting->model_source = "awt_setting";
        $setting->save();

        $red = new Redirect();
        $red->back();
        return $red;
    }

}