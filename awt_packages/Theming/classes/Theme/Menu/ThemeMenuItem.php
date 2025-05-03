<?php

namespace Theming\classes\Theme\Menu;

use DOMDocument;
use DOMElement;
use DOMException;

class ThemeMenuItem
{
    public int $id;
    public string $name;
    public string $link;
    public string $target;
    public string $attributes;
    public int $parentId;
    public string $html;
    public ?array $child = null;
    private ?DOMDocument $document;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? '';
        $this->link = $data['link'] ?? '';
        $this->target = $data['target'] ?? '';
        $this->attributes = $data['attributes'] ?? '';
        $this->parentId = $data['parent_item'] ?? 0;
        $this->document = null;
        $this->html = "";
    }

    public function setChild(ThemeMenuItem $child): void
    {
        $this->child[] = $child;
    }

    public function createNode(DOMDocument $document): DOMElement
    {
        $this->document = $document;

        try {

            $item = $this->document->createElement("div");
            if ($this->child === null) {
                $item = $document->createElement("a");
                $item->setAttribute("href", $this->link);

                if ($this->target !== '')
                    $item->setAttribute("target", $this->target);

                $item->setAttribute("rel", "noopener noreferrer");
                $item->textContent = $this->name;
            } else {

                $text = $document->createElement("a");
                $text->setAttribute("href", $this->link);

                if ($text->target !== '')
                    $text->setAttribute("target", $this->target);

                $text->setAttribute("rel", "noopener noreferrer");

                $text->setAttribute("data-visible", "true");

                $text->textContent = $this->name;

                $item->appendChild($text);
            }

            $item->setAttribute("class", "nav-item");
            $item->setAttribute("data-id", (string)$this->id);
            $item->setAttribute("data-parent", (string)$this->parentId);

            if ($this->child !== null) {
                $item->setAttribute("class", "nav-item parent-item");
                $container = $document->createElement("div");
                $container->setAttribute("class", "children");
                foreach ($this->child as $child) {
                    $container->appendChild($child->createNode($document));
                }
                $item->appendChild($container);
            }

            return $item;
        } catch (DOMException $e) {
            die($e->getMessage());
        }
    }

    public function getHtml(): string
    {
        return $this->document ? $this->document->saveHTML() : '';
    }
}
