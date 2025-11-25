<?php

namespace object;

/**
 * ObjectCollection
 *
 * Optimized general-purpose collection for storing objects and sorting them flexibly.
 */
class ObjectCollection
{
    /** @var array<int, object> Collection of objects */
    private array $collection = [];

    /** @var string Property name for sortByKey() */
    private string $key = 'id';

    /** @var string "asc" or "desc" */
    private string $orderSort = 'asc';

    /** @var string[] Ordered list of properties for multi-property sortByValue() */
    private array $sortProperties = [];

    /** @var array<string, array<string|int, int>> Optional custom value -> priority maps */
    private array $valueOrder = [];

    /** @var ?string Class name of first added object */
    private ?string $className = null;

    /** @var bool Allow only one object type */
    private bool $strictType = false;

    /** @var string Allowed object type when strictType is true */
    private string $type = '';

    /** @var string|null Custom property accessor */
    private ?string $propertyAccessor = null;

    public function __construct()
    {
        // All defaults already set
    }

    public function setStrictType(string $type): self
    {
        $this->strictType = true;
        $this->type = $type;
        return $this;
    }

    public function setOrder(string $order): self
    {
        $order = strtolower($order);
        if ($order !== 'asc' && $order !== 'desc') {
            throw new \InvalidArgumentException('Order must be "asc" or "desc".');
        }
        $this->orderSort = $order;
        return $this;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    public function setSortProperties(array $properties): self
    {
        $this->sortProperties = array_values($properties);
        return $this;
    }

    public function setValueOrder(array $maps): self
    {
        $this->valueOrder = $maps;
        return $this;
    }

    public function setPropertyAccessor(callable $accessor): self
    {
        $this->propertyAccessor = $accessor;
        return $this;
    }

    public function add(object $object): self
    {
        if ($this->strictType && !($object instanceof $this->type)) {
            throw new \InvalidArgumentException("Object must be an instance of {$this->type}.");
        }
        if ($this->className === null) {
            $this->className = get_class($object);
        }
        $this->collection[] = $object;
        return $this;
    }

    public function toArray(): array
    {
        return $this->collection;
    }

    /**
     * Sort by a single key
     */
    public function sortByKey(): void
    {
        $key = $this->key;
        $order = $this->orderSort;
        $accessor = $this->propertyAccessor;

        // Precompute values to avoid repeated property lookups
        $values = [];
        foreach ($this->collection as $idx => $obj) {
            $values[$idx] = $accessor ? $accessor($obj, $key) : $this->getPropertyValue($obj, $key);
        }

        array_multisort(
            $values,
            $order === 'asc' ? SORT_ASC : SORT_DESC,
            $this->collection
        );
    }

    /**
     * Sort by one or multiple properties
     */
    public function sortByValue(): void
    {
        $props = !empty($this->sortProperties) ? $this->sortProperties : [$this->key];
        $maps = $this->valueOrder;
        $order = $this->orderSort;
        $accessor = $this->propertyAccessor;

        // Precompute values for all properties
        $values = [];
        foreach ($this->collection as $idx => $obj) {
            foreach ($props as $prop) {
                $values[$idx][$prop] = $accessor ? $accessor($obj, $prop) : $this->getPropertyValue($obj, $prop);
            }
        }

        usort($this->collection, function ($a, $b) use ($props, $maps, $order, $values) {
            $idxA = array_search($a, $this->collection, true);
            $idxB = array_search($b, $this->collection, true);

            foreach ($props as $prop) {
                $av = $values[$idxA][$prop];
                $bv = $values[$idxB][$prop];

                if (isset($maps[$prop])) {
                    $aPri = $this->mapPriority($av, $maps[$prop]);
                    $bPri = $this->mapPriority($bv, $maps[$prop]);
                    if ($aPri !== $bPri) return $order === 'asc' ? $aPri <=> $bPri : $bPri <=> $aPri;
                }

                if ($av === $bv) continue;
                if ($av === null) return $order === 'asc' ? -1 : 1;
                if ($bv === null) return $order === 'asc' ? 1 : -1;

                $isANum = is_int($av) || (is_string($av) && ctype_digit($av));
                $isBNum = is_int($bv) || (is_string($bv) && ctype_digit($bv));
                $cmp = ($isANum && $isBNum) ? ((int)$av <=> (int)$bv) : strcmp((string)$av, (string)$bv);
                return $order === 'asc' ? $cmp : -$cmp;
            }

            return 0;
        });
    }

    private function getPropertyValue(object $o, string $prop)
    {
        if (property_exists($o, $prop)) return $o->{$prop};
        $getter = 'get' . ucfirst($prop);
        if (method_exists($o, $getter)) return $o->{$getter}();
        return null;
    }

    private function mapPriority($value, array $map): int
    {
        if (array_key_exists($value, $map)) return (int)$map[$value];
        $sv = (string)$value;
        if (array_key_exists($sv, $map)) return (int)$map[$sv];
        return PHP_INT_MAX;
    }
}
