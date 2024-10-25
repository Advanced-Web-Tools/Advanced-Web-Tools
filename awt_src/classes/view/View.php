<?php

namespace view;

use DOMDocument;
use render\Render;

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
    final public function view(array $data = []): View
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
    private function getViewContent(): string
    {
        ob_start();
        include $this->viewDirectory . $this->viewName . '.awt.php';
        return ob_get_clean();
    }

}