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

    /** @var callable|null Custom property accessor (object, string): mixed */
    private ?string $propertyAccessor = null;

    /** @var bool True when last sort was sortByValue() (multi-property domain-aware sort) */
    private bool $isSortedByValue = false;

    /**
     * Constructor.
     *
     * Initializes a new ObjectCollection with default settings.
     */
    public function __construct()
    {
        // defaults already initialized in property declarations
    }

    /**
     * Enable strict typing for collection items.
     *
     * When strict type is set, only instances of the provided class name
     * will be accepted by add().
     *
     * @param string $type Fully-qualified class name to enforce.
     * @return $this
     */
    public function setStrictType(string $type): self
    {
        $this->strictType = true;
        $this->type = $type;
        return $this;
    }

    /**
     * Set sorting order for subsequent sorts.
     *
     * Accepts "asc" or "desc" (case-insensitive). Throws InvalidArgumentException
     * for any other value.
     *
     * @param string $order "asc" or "desc"
     * @return $this
     * @throws \InvalidArgumentException
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
     * Set the primary property name used by sortByKey() and default sortByValue().
     *
     * @param string $key Property name (e.g. "id", "name")
     * @return $this
     */
    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Define the ordered list of properties for multi-property sorting.
     *
     * The provided array determines priority: first element is highest priority.
     *
     * @param string[] $properties List of property names.
     * @return $this
     */
    public function setSortProperties(array $properties): self
    {
        $this->sortProperties = array_values($properties);
        return $this;
    }

    /**
     * Provide custom mapping(s) from property values to integer priorities.
     *
     * Useful for non-alphabetical or domain-specific orderings (e.g. status => priority).
     * Format: ['propertyName' => ['valueA' => 1, 'valueB' => 2, ...], ...]
     *
     * @param array<string, array<string|int, int>> $maps
     * @return $this
     */
    public function setValueOrder(array $maps): self
    {
        $this->valueOrder = $maps;
        return $this;
    }

    /**
     * Set a custom property accessor.
     *
     * The accessor must be a callable receiving (object $obj, string $prop) and
     * returning the property value. When set, this accessor is used instead of
     * direct property access or getter methods.
     *
     * Example: fn($obj, $prop) => $obj->data[$prop] ?? null
     *
     * @param callable $accessor Callable (object, string): mixed
     * @return $this
     */
    public function setPropertyAccessor(callable $accessor): self
    {
        $this->propertyAccessor = $accessor;
        return $this;
    }

    /**
     * Add an object to the collection.
     *
     * If strict typing is enabled, the object must be an instance of the configured type.
     * The class of the first added object is recorded in $className.
     *
     * @param object $object
     * @return $this
     * @throws \InvalidArgumentException When strict type is enabled and object is not instance of allowed type.
     */
    public function add(object $object): self
    {
        if ($this->strictType && !($object instanceof $this->type)) {
            throw new \InvalidArgumentException("Object must be an instance of {$this->type}.");
        }
        if ($this->className === null) {
            $this->className = get_class($object);
        }
        $this->collection[] = $object;
        // adding an item invalidates previous sort-by-value guarantee
        $this->isSortedByValue = false;
        return $this;
    }

    /**
     * Return the underlying collection as an array of objects.
     *
     * @return object[]
     */
    public function toArray(): array
    {
        return $this->collection;
    }

    /**
     * Sort the collection by a single key (property).
     *
     * Uses array_multisort on a precomputed values array for stability and speed.
     * Note: this method sets isSortedByValue to false because it performs a key-based sort
     * (it is not the multi-property, domain-aware sort used by sortByValue()).
     *
     * @return void
     */
    public function sortByKey(): void
    {
        $key = $this->key;
        $order = $this->orderSort;
        $accessor = $this->propertyAccessor;

        // Precompute values to avoid repeated property lookups
        $values = [];
        foreach ($this->collection as $idx => $obj) {
            $values[$idx] = $accessor ? ($accessor)($obj, $key) : $this->getPropertyValue($obj, $key);
        }

        array_multisort(
            $values,
            $order === 'asc' ? SORT_ASC : SORT_DESC,
            $this->collection
        );

        $this->isSortedByValue = false;
    }

    /**
     * Sort the collection by one or multiple properties.
     *
     * - If sortProperties is empty, the collection is sorted by $this->key.
     * - Supports custom value->priority maps for domain-specific ordering.
     * - Handles numeric and string comparisons and nulls.
     *
     * After this method runs, isSortedByValue is set to true.
     *
     * @return void
     */
    public function sortByValue(): void
    {
        $props = !empty($this->sortProperties) ? $this->sortProperties : [$this->key];
        $maps = $this->valueOrder;
        $order = $this->orderSort;
        $accessor = $this->propertyAccessor;

        // Precompute values keyed by spl_object_id for O(1) lookup in comparator.
        $values = [];
        foreach ($this->collection as $obj) {
            $id = spl_object_id($obj);
            foreach ($props as $prop) {
                $values[$id][$prop] = $accessor ? ($accessor)($obj, $prop) : $this->getPropertyValue($obj, $prop);
            }
        }

        usort($this->collection, function ($a, $b) use ($props, $maps, $order, $values) {
            $idA = spl_object_id($a);
            $idB = spl_object_id($b);

            foreach ($props as $prop) {
                $av = $values[$idA][$prop] ?? null;
                $bv = $values[$idB][$prop] ?? null;

                if (isset($maps[$prop])) {
                    $aPri = $this->mapPriority($av, $maps[$prop]);
                    $bPri = $this->mapPriority($bv, $maps[$prop]);
                    if ($aPri !== $bPri) return $order === 'asc' ? $aPri <=> $bPri : $bPri <=> $aPri;
                    continue; // same priority -> check next property
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

        $this->isSortedByValue = true;
    }

    /**
     * Find an object in the collection.
     *
     * Behavior:
     * - If the collection is not flagged as sorted by value, this performs a direct lookup
     *   using the provided $key as an array index (i.e. $collection[$key] if exists).
     *   NOTE: this preserves previous behaviour but is only meaningful when the collection
     *   has been indexed by integer keys externally.
     *
     * - If the collection is sorted by value (isSortedByValue === true), this performs
     *   a binary search over the collection, comparing the given property values using
     *   the same comparison rules as sortByValue() (including custom value maps).
     *
     * Comparison rules:
     * - If a value map exists for the searched property, numeric priorities from the map are compared.
     * - Nulls are considered lower than any non-null when order is 'asc'.
     * - Numeric-like values are compared numerically; otherwise string comparison is used.
     *
     * @param string $key   Property name to compare (e.g. "id", "username")
     * @param string $value Value to search for (compared using collection's comparison rules)
     * @return object|null  The found object or null if not found
     */
    public function find(string $key, string $value): ?object
    {
        // If not sorted by value, keep legacy/direct-access behavior.
        if (!$this->isSortedByValue) {
            // preserve previous behaviour: allow integer indexes or associative keys if present
            return $this->collection[$key] ?? null;
        }

        $count = count($this->collection);
        if ($count === 0) return null;

        $low = 0;
        $high = $count - 1;
        $maps = $this->valueOrder;
        $order = $this->orderSort;
        $accessor = $this->propertyAccessor;

        while ($low <= $high) {
            $mid = intdiv($low + $high, 2);
            $obj = $this->collection[$mid];
            $midVal = $accessor ? ($accessor)($obj, $key) : $this->getPropertyValue($obj, $key);

            // Compare midVal to search value using same semantics as sorting.
            $cmp = $this->compareValues($midVal, $value, $key, $maps);

            if ($cmp === 0) {
                // found exact match
                return $obj;
            }

            // cmp < 0 means midVal < value
            if ($order === 'asc') {
                if ($cmp < 0) {
                    $low = $mid + 1;
                } else {
                    $high = $mid - 1;
                }
            } else { // desc
                // when sorted descending, direction is reversed
                if ($cmp < 0) {
                    $high = $mid - 1;
                } else {
                    $low = $mid + 1;
                }
            }
        }

        return null;
    }

    /**
     * Retrieve a property value from an object.
     *
     * Resolution order:
     * 1. Direct public property access if property_exists()
     * 2. Getter method named get{Prop} if it exists
     * 3. Returns null if not accessible
     *
     * @param object $o
     * @param string $prop
     * @return mixed|null
     */
    private function getPropertyValue(object $o, string $prop)
    {
        if (property_exists($o, $prop)) return $o->{$prop};
        $getter = 'get' . ucfirst($prop);
        if (method_exists($o, $getter)) return $o->{$getter}();
        return null;
    }

    /**
     * Map a value to an integer priority using a provided map.
     *
     * If the exact key is not found, the string-casted version is checked.
     * When nothing matches, returns PHP_INT_MAX as a very low priority.
     *
     * @param mixed $value
     * @param array<string|int, int> $map
     * @return int
     */
    private function mapPriority($value, array $map): int
    {
        if (array_key_exists($value, $map)) return (int)$map[$value];
        $sv = (string)$value;
        if (array_key_exists($sv, $map)) return (int)$map[$sv];
        return PHP_INT_MAX;
    }

    /**
     * Compare two values using the same rules as sortByValue().
     *
     * Returns:
     *  -1 if $a < $b
     *   0 if $a == $b
     *   1 if $a > $b
     *
     * Supports custom maps (valueOrder), numeric-like comparisons, and string compare.
     *
     * @param mixed $a
     * @param mixed $b
     * @param string $prop property name (used to lookup maps)
     * @param array<string, array<string|int,int>> $maps
     * @return int
     */
    private function compareValues($a, $b, string $prop, array $maps): int
    {
        // If map exists for this property, compare by mapped priority.
        if (isset($maps[$prop])) {
            $ap = $this->mapPriority($a, $maps[$prop]);
            $bp = $this->mapPriority($b, $maps[$prop]);
            return $ap <=> $bp;
        }

        if ($a === $b) return 0;
        if ($a === null) return -1;
        if ($b === null) return 1;

        $isANum = is_int($a) || (is_string($a) && ctype_digit($a));
        $isBNum = is_int($b) || (is_string($b) && ctype_digit($b));

        if ($isANum && $isBNum) {
            return ((int)$a) <=> ((int)$b);
        }

        // fallback to string comparison
        return strcmp((string)$a, (string)$b) <=> 0; // ensure -1/0/1
    }
}
