<?php

namespace render;

use DOMDocument;
use DOMXPath;
use event\EventDispatcher;
use Exception;
use render\events\RenderReadyEvent;

/**
 * The Render class provides tools to manipulate HTML documents, load templates, and manage dynamic content rendering.
 * It uses DOM manipulation and integrates an event dispatcher for handling specific events during the render process.
 */
class Render
{
    public array $routes = [];
    public string $localAssetPath;
    public array $bundle;
    protected DOMDocument $dom;
    public EventDispatcher $eventDispatcher;
    public string $packageName;

    /**
     * Creates a new DOMDocument instance and loads HTML content into it.
     *
     * @param string $html The HTML content to load into the DOMDocument. Defaults to a basic HTML structure.
     * @return DOMDocument The created and loaded DOMDocument instance.
     */
    protected function createDocument(string $html = "<html lang=''></html>"): DOMDocument
    {
        try {
            $this->dom = new DOMDocument();
            $this->dom->formatOutput = true;
            $this->dom->loadHTML($html);
            return $this->dom;
        } catch (Exception $exception) {
            die("AWT Renderer: Failed to create document.");
        }
    }

    /**
     * Loads a template from an HTML document if it extends another template.
     * Uses regex to find the "@extends" directive and merges the content with the base template.
     *
     * @throws Exception If the template cannot be loaded or extended.
     */
    protected function loadTemplate(): void
    {
        $htmlContent = $this->dom->saveHTML();
        preg_match('/@extends\s*\(\s*([\'"]?)(.+?)([\'"]?)\s*\)/', $htmlContent, $matches);

        if (isset($matches[2])) {
            $templatePath = $matches[2];
            $templateContent = TemplateParser::extends($templatePath, $htmlContent);
            $this->createDocument($templateContent);
        } else {
            $this->createDocument($htmlContent);
        }
    }


    /**
     * Replaces all elements in the DOM that match the given class name with a new DOM element.
     *
     * @param string $classname The class name to search for in the current DOM.
     * @param DOMDocument $newElement The new element to replace the matching elements.
     */
    public function replaceElementsByClassname(string $classname, DOMDocument $newElement): void
    {
        $xpath = new \DOMXPath($this->dom);
        $elements = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        foreach ($elements as $element) {
            $importedNode = $this->dom->importNode($newElement->documentElement, true);
            $element->parentNode->replaceChild($importedNode, $element);
        }
    }

    /**
     * Inserts a new element in the DOM after the elements that match the given class name.
     *
     * @param string $classname The class name to search for in the current DOM.
     * @param DOMDocument $html The new element to insert after the matching elements.
     */
    public function insertAfterByClassname(string $classname, DOMDocument $html): void
    {
        $xpath = new \DOMXPath($this->dom);
        $elements = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        foreach ($elements as $element) {
            $newNode = $this->dom->importNode($html->documentElement, true);

            if ($element->nextSibling) {
                $element->parentNode->insertBefore($newNode, $element->nextSibling);
            } else {
                $element->parentNode->appendChild($newNode);
            }
        }
    }

    /**
     * Inserts a new element in the DOM before the elements that match the given class name.
     *
     * @param string $classname The class name to search for in the current DOM.
     * @param DOMDocument $html The new element to insert before the matching elements.
     */
    public function insertBeforeByClassname(string $classname, DOMDocument $html): void
    {
        $xpath = new DOMXPath($this->dom);
        $elements = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        foreach ($elements as $element) {
            $newNode = $this->dom->importNode($html->documentElement, true);
            $element->parentNode->insertBefore($newNode, $element);
        }
    }

    /**
     * Appends a new element into elements that match the given class name.
     *
     * @param string $classname The class name to search for in the current DOM.
     * @param DOMDocument $html The new element to append to the matching elements.
     */
    public function insertIntoByClassname(string $classname, DOMDocument $html): void
    {
        $xpath = new DOMXPath($this->dom);
        $elements = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        foreach ($elements as $element) {
            $newNode = $this->dom->importNode($html->documentElement, true);
            $element->appendChild($newNode);
        }
    }

    /**
     * Adds a key-value pair to the bundle. The bundle stores various data to be used in rendering.
     *
     * @param string $key The key associated with the bundle item.
     * @param string $value The value of the bundle item.
     */
    public function addToBundle(string $key, string $value): void
    {
        $this->bundle[$key] = $value;
    }

    /**
     * Converts the bundle key-value pairs into object properties for later use in rendering.
     */
    protected function createBundleObjects(): void
    {
        foreach ($this->bundle as $key => $bundle) {
            $this->{$key} = $bundle;
        }
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

        $html = $this->dom->saveHTML();
        $html = TemplateParser::foreachParser($this, $html);
        $html = TemplateParser::ifParser($this, $html);
        $html = TemplateParser::resource($html);
        $html = TemplateParser::data($this->packageName, $html);
        $html = TemplateParser::urlVar($this, $html);
        $html = TemplateParser::url($html);
        $html = TemplateParser::vars($this, $html);
        $html = TemplateParser::assets($this->localAssetPath, $html);
        return $html;
    }
}
