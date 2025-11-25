<?php

namespace object;

/**
 * ObjectCollection
 *
 * A general-purpose collection for storing objects and sorting them flexibly.
 *
 * Features:
 * - Add objects dynamically
 * - Sort by object property key (`sortByKey()`) or by one/multiple property values (`sortByValue()`)
 * - Define ascending/descending order
 * - Optional custom value mapping for ordering
 *
 * Usage example:
 *```
 * $collection = new ObjectCollection();
 * $collection->add((object)['id' => 10, 'status' => 'new']);
 * $collection->add((object)['id' => 12, 'status' => 'processing']);
 * $collection->setKey('id')->setOrder('asc')->sortByKey(); <- sorts by given key
 * $collection->setSortProperties(['status', 'id'])->sortByValue(); <- sorts by given properties
 *```
 */
class ObjectCollection
{
    /** @var array<int, object> Flattened collection */
    public array $collection = [];

    /** @var string Property name used as primary key for sortByKey() */
    public string $key = 'id';

    /** @var string "asc" or "desc" */
    public string $orderSort = 'asc';

    /** @var string[] Ordered list of properties for multi-property sortByValue() */
    private array $sortProperties = [];

    /** @var array<string, array<string|int, int>> Optional custom value -> priority maps per property */
    private array $valueOrder = [];

    /** @var ?string Optional class name of first added object */
    public ?string $className = null;

    /** @var bool Allows only one type of object to be added  */
    public bool $strictType = false;

    /** @var string Type of object to be added works only with `$strictType = true` */
    public string $type = '';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->collection = [];
        $this->key = 'id';
        $this->orderSort = 'asc';
        $this->sortProperties = [];
        $this->valueOrder = [];
        $this->className = null;
    }


    public function setStrictType(string $type): self
    {
        $this->strictType = true;
        $this->type = $type;
        return $this;
    }

    /**
     * Set sort order ("asc" or "desc")
     */
    public function setOrder(string $order): self
    {
        $order = strtolower($order);
        if ($order !== 'asc' && $order !== 'desc') {
            throw new \InvalidArgumentException('Order must be "asc" or "desc".');
        }
        $this->orderSort = $order;
        return $this;
    }

    /**
     * Set property key used for sortByKey()
     */
    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Set properties used for multi-property sortByValue()
     */
    public function setSortProperties(array $properties): self
    {
        $this->sortProperties = array_values($properties);
        return $this;
    }

    /**
     * Set optional custom value maps for ordering
     */
    public function setValueOrder(array $maps): self
    {
        $this->valueOrder = $maps;
        return $this;
    }

    /**
     * Add object to collection
     */
    public function add(object $object): self
    {
        if ($this->strictType) {
            if (!($object instanceof $this->type)) {
                throw new \InvalidArgumentException("Object must be an instance of {$this->type}.");
            }
        }

        if ($this->className === null) {
            $this->className = get_class($object);
        }
        $this->collection[] = $object;
        return $this;
    }

    /**
     * Sort objects by property key (numeric or string)
     */
    public function sortByKey(): void
    {
        $key = $this->key;
        $order = $this->orderSort;

        usort($this->collection, function ($a, $b) use ($key) {
            $av = $this->getPropertyValue($a, $key);
            $bv = $this->getPropertyValue($b, $key);
            return $av <=> $bv;
        });

        if ($order === 'desc') {
            $this->collection = array_reverse($this->collection);
        }
    }

    /**
     * Sort objects by one or multiple properties (multi-level sort)
     */
    public function sortByValue(): void
    {
        $props = !empty($this->sortProperties) ? $this->sortProperties : [$this->key];
        $maps = $this->valueOrder;
        $order = $this->orderSort;

        usort($this->collection, function ($a, $b) use ($props, $maps, $order) {
            foreach ($props as $prop) {
                $av = $this->getPropertyValue($a, $prop);
                $bv = $this->getPropertyValue($b, $prop);

                // check custom map priority first
                if (isset($maps[$prop])) {
                    $aPri = $this->mapPriority($av, $maps[$prop]);
                    $bPri = $this->mapPriority($bv, $maps[$prop]);
                    if ($aPri !== $bPri) {
                        return $order === 'asc' ? ($aPri <=> $bPri) : ($bPri <=> $aPri);
                    }
                }

                // fallback numeric or string comparison
                if ($av === $bv) continue;
                if ($av === null) return $order === 'asc' ? -1 : 1;
                if ($bv === null) return $order === 'asc' ? 1 : -1;

                $isANum = is_int($av) || (is_string($av) && ctype_digit($av));
                $isBNum = is_int($bv) || (is_string($bv) && ctype_digit($bv));

                if ($isANum && $isBNum) {
                    $cmp = ((int)$av) <=> ((int)$bv);
                } else {
                    $cmp = strcmp((string)$av, (string)$bv);
                }

                return $order === 'asc' ? $cmp : -$cmp;
            }

            return 0;
        });
    }

    /**
     * Return current collection
     */
    public function toArray(): array
    {
        return $this->collection;
    }

    /**
     * Helper: get property value, tries direct property then getter method
     */
    private function getPropertyValue(object $o, string $prop)
    {
        if (property_exists($o, $prop)) return $o->{$prop};
        $getter = 'get' . ucfirst($prop);
        if (method_exists($o, $getter)) return $o->{$getter}();
        return null;
    }

    /**
     * Map value to priority using custom value map
     */
    private function mapPriority($value, array $map): int
    {
        if (array_key_exists($value, $map)) return (int)$map[$value];
        $sv = (string)$value;
        if (array_key_exists($sv, $map)) return (int)$map[$sv];
        return PHP_INT_MAX; // unmapped values sort last
    }
}
