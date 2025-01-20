<?php

namespace view;

use DOMDocument;
use render\events\RenderReadyEvent;
use render\Render;
use render\TemplateEngine\BladeOne;
use render\TemplateEngine\Data\DataFunction;
use Throwable;

class View extends Render
{
    /**
     * @var string The directory where view files are stored.
     */
    protected string $viewDirectory;

    /**
     * @var string The name of the view to be rendered.
     */
    public string $viewName;

    /**
     * @var DOMDocument The DOM representation of the rendered view page.
     */
    private DOMDocument $page;

    /**
     * Renders the view with the provided data.
     *
     * This method prepares the view by bundling the data, creating the document,
     * and loading the view content from the specified view file.
     *
     * @param array $data The data to be passed to the view.
     * @return View The current instance for method chaining.
     */
    public function view(array $data = []): View
    {
        $this->bundle = $data;

        $this->createBundleObjects();

        $this->createDocument($this->getViewContent());

        return $this;
    }

    /**
     * Loads the content of the view file and returns it as a string.
     *
     * This method uses output buffering to capture the contents of the included
     * view file and return it as a string.
     *
     * @return string The content of the view file.
     */
    protected function getViewContent(): string
    {
        ob_start();
        include $this->viewDirectory . $this->viewName . '.awt.php';
        return ob_get_clean();
    }

    /**
     * Renders the HTML document, processes templates, and dispatches a render-ready event.
     * It applies template parsing for loops, conditions, assets, and variables before returning the final HTML string.
     *
     * @throws Exception If any issues occur during rendering or template loading.
     * @return string The final rendered HTML content as a string.
     */
    public function render(): string
    {
        $this->loadTemplate();
        $ready = new RenderReadyEvent();
        $ready->renderer = $this;

        $this->eventDispatcher->dispatch($ready);
        $this->createBundleObjects();


        try {
            $parser = new BladeOne($this->viewDirectory, null, BladeOne::MODE_AUTO);
            $parser->setFileExtension(".awt.php");

            $parser->addAssetDict(0, $this->localAssetPath);
            $parser->setPackageName($this->packageName);

            $parser->addMethod("compile", "@data" , static function (?array $args) {
                $pn = $this->packageName;
                return HOSTNAME . "awt_data/media/$args[1]/$pn/$args[0]";
            });


            $parser->with($this->bundle);
            return $parser->run($this->viewName);

        } catch (Throwable $e) {
            if(DEBUG)
                $e->getTraceAsString();

            echo "An error has occurred while rendering: " . $e->getMessage();
        }
        return "";
    }

}