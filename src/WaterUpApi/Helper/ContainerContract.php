<?php
/**
 * Author: Joris Rietveld <jorisrietveld@gmail.com>
 * Created: 25-05-2017 03:08
 * Licence: GNU General Public licence version 3 <https://www.gnu.org/licenses/quick-guide-gplv3.html>
 */
declare(strict_types=1);

namespace WaterUpApi\Helper;


use WaterUpApi\Exception\InvalidTypeCastException;

abstract class ContainerContract implements ContainerInterface, \ArrayAccess, \Countable, \IteratorAggregate
{
    protected static $containerType = __CLASS__;

    protected $parameters                 = [];
    protected $throwInvalidCastExceptions = false;
    protected $throwSafeModeExceptions    = true;

    /**
     * ContainerContract constructor that initiates the default classes properties.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * This method will save an item in the container if the index doesn't already exist.
     *
     * @param string $index The items index.
     * @param null   $value The value of the item that will be set
     *
     * @throws \InvalidArgumentException
     *
     * @return null|ContainerInterface
     */
    public function safeSet(string $index, $value = null): ?ContainerInterface
    {
        if (!$this->has($index))
        {
            $this->set($index, $value);

            return $this;
        }
        $this->throw(new \InvalidArgumentException(sprintf(
            "Error: Trying to save an duplicate key to the %s container while using save set. the index %s does already exists.",
            self::getContainerType(),
            $index
        )));

        return null;
    }

    /**
     * This method will check if an item with an certain index exists in the container.
     *
     * @param string $index The items index.
     *
     * @return bool|null
     */
    public function has(string $index): ?bool
    {
        return isset($this->parameters[ $index ]);
    }

    /**
     * This method will save an item in the container. If the key already exists it will overwrite it.
     *
     * @param string $index The items index.
     * @param mixed  $value The value of the item that will be set.
     *
     * @return ContainerInterface
     */
    public function set(string $index, $value = null): ContainerInterface
    {
        $this->parameters[ $index ] = $value;

        return $this;
    }

    /**
     * An short helper method that checks if the user wants to throw Exceptions on invalid type casts.
     *
     * @param \Throwable $exception
     *
     * @throws \Throwable
     */
    private final function throw(\Throwable $exception)
    {
        if (is_a("\\WaterUpApi\\Exception\\InvalidTypeCastException", $exception) && $this->getThrowInvalidCastExceptions())
        {
            throw $exception;
        }

        if (is_a("\\InvalidArgumentException", $exception) && $this->getThrowSafeModeExceptions())
        {
            throw $exception;
        }
    }

    /**
     * This method gets the ThrowInvalidCastExceptions property.
     *
     * @return bool
     */
    public function getThrowInvalidCastExceptions(): bool
    {
        return $this->throwInvalidCastExceptions;
    }

    /**
     * This method sets the ThrowInvalidCastExceptions property and returnes an ContainerContract object so you can
     * chain set properties.
     *
     * @param bool $throwInvalidCastExceptions
     *
     * @return ContainerContract
     */
    public function setThrowInvalidCastExceptions(bool $throwInvalidCastExceptions): ContainerContract
    {
        $this->throwInvalidCastExceptions = $throwInvalidCastExceptions;

        return $this;
    }

    /**
     * This method gets the ThrowSafeModeExceptions property.
     *
     * @return bool
     */
    public function getThrowSafeModeExceptions(): bool
    {
        return $this->throwSafeModeExceptions;
    }

    /**
     * This method sets the ThrowSafeModeExceptions property and returnes an ContainerContract object so you can chain
     * set properties.
     *
     * @param bool $throwSafeModeExceptions
     *
     * @return ContainerContract
     */
    public function setThrowSafeModeExceptions(bool $throwSafeModeExceptions): ContainerContract
    {
        $this->throwSafeModeExceptions = $throwSafeModeExceptions;

        return $this;
    }

    /**
     * This method gets the ContainerType property.
     *
     * @return string
     */
    public static function getContainerType(): string
    {
        return self::$containerType;
    }

    /**
     * This method saves the the class name of the instance.
     *
     * @return void
     */
    protected static function setContainerType(string $containerType = __CLASS__): void
    {
        self::$containerType = $containerType;
    }

    /**
     * This method will return all stored values from the container.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->parameters;
    }

    /**
     * This method will add a new list of parameters to the existing ones in the container if the keys are unique.
     *
     * @param array $parameters The new items to add to the container if they are unique.
     * @param bool  $throwException
     *
     * @return null|ContainerInterface
     */
    public function safeAdd(array $parameters): ?ContainerInterface
    {
        $duplicateKeys = array_intersect_key($this->parameters, $parameters);
        if (count($duplicateKeys) == 0)
        {
            $this->add($parameters);
        }

        $this->throw(new \InvalidArgumentException(sprintf(
            "Error: Trying to add duplicate key(s) to the %s container while using save add. The following indexes already exist %s.",
            self::getContainerType(),
            implode(',', array_keys($duplicateKeys))
        )));

        return null;
    }

    /**
     * This method will add an new list of parameters to the existing ones in the container.
     *
     * @param array $parameters The new items to add.
     *
     * @return ContainerInterface
     */
    public function add(array $parameters): ContainerInterface
    {
        $this->parameters = array_merge($this->parameters, $parameters);

        return $this;
    }

    /**
     * This method will clear all stored items from the container.
     */
    public function clear(): void
    {
        $this->parameters = [];
    }

    /**
     * This method will return the index keys of each item in the container.
     *
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->parameters);
    }

    /**
     * This method will replace all items in the container with an new list of items.
     *
     * @param array $parameters The new items that will be set in the container.
     *
     * @return ContainerInterface
     */
    public function replace(array $parameters): ContainerInterface
    {
        $this->parameters = $parameters;
    }

    /**
     * This method will get an stored parameter from the container as an \DateTime object.
     *
     * @param string         $index   The items index.
     * @param \DateTime|null $default The default return value if the item does not exist in the container.
     *
     * @return \DateTime|null
     */
    public function getDateTime(string $index, string $dateTimeFormat = null, \DateTime $default = null): ?\DateTime
    {
        if ($this->has($index))
        {
            $storedItem = $this->get($index, $default);
            if (is_a("\\Datetime", $storedItem))
            {
                return $storedItem;
            }

            try
            {
                return $dateTimeFormat ? \DateTime::createFromFormat($dateTimeFormat, $storedItem) : new \DateTime($storedItem);
            }
            catch (\Exception $malformedDateTimeException)
            {
                $this->throw(new InvalidTypeCastException(sprintf(
                    "Error: malformed datetime, The item %s does not contain an valid \\DateTime or cannot be casted to an \\Datetime. Exception message: %s",
                    $index,
                    $malformedDateTimeException->getMessage()
                )));
            }
        }

        return $default;
    }

    /**
     * This method will get an item from the container or the default value if the key does not exist.
     *
     * @param string $index   The items index.
     * @param mixed  $default The default return value if the item does not exist in the container.
     *
     * @return mixed
     */
    public function get(string $index, $default = null)
    {
        return $this->has($index) ? $this->parameters[ $index ] : $default;
    }

    /**
     * This method will get an stored item from the container as an object.
     *
     * @param string      $index   The items index.
     * @param object|null $default The default return value if the item does not exist in the container.
     *
     * @return null|object
     */
    public function getObject(string $index, object $default = null): ?object
    {
        if ($this->has($index))
        {
            $storedItem = $this->get($index, $default);
            if (is_object($storedItem))
            {
                return $storedItem;
            }
            try
            {
                return (object)$storedItem;
            }
            catch (\Exception $invalidCastException)
            {
                $this->throw(new InvalidTypeCastException(sprintf(
                    "Error: malformed object, The item %s does not contain an valid object or cannot be casted to an object. Exception message: %s",
                    $index,
                    $invalidCastException->getMessage()
                )));
            }
        }

        return $default;
    }

    /**
     * This method will get an stored item from the container as an string.
     *
     * @param string      $index   The items index.
     * @param string|null $default The default return value if the item does not exist in the container.
     *
     * @return null|string
     */
    public function getString(string $index, string $default = null): ?string
    {
        if ($this->has($index))
        {
            $storedItem = $this->get($index, $default);
            try
            {
                return (string)$storedItem;
            }
            catch (\Exception $invalidCastException)
            {
                $this->throw(new InvalidTypeCastException(sprintf(
                    "Error: malformed string, The item %s does not contain an valid string or cannot be casted to an string. Exception message: %s",
                    $index,
                    $invalidCastException->getMessage()
                )));
            }
        }

        return $default;
    }

    /**
     * This method will get an stored item from the container as an integer.
     *
     * @param string   $index   The items index.
     * @param int|null $default The default return value if the item does not exist in the container.
     * @param array    $options Optional switches for the filter_vars function like allowing hexadecimal or octal
     *                          values.
     *
     * @return int|null
     */
    public function getInteger(string $index, int $default = null, array $options = []): ?int
    {
        if ($this->has($index))
        {
            $storedItem = $this->get($index, $default);
            $integer = $this->filter($index, $default, FILTER_VALIDATE_INT, $options);
            if (!is_int($integer) || $integer === false)
            {
                $this->throw(new InvalidTypeCastException(sprintf(
                    "Error: malformed integer, The item %s does not contain an valid integer or cannot be casted to an integer.",
                    $index
                )));
            }

            return $integer;
        }

        return $default;
    }

    /**
     * This method will filter an stored item from the container.
     *
     * @param string $index   The items index.
     * @param mixed  $default The default return value if the item does not exist in the container.
     * @param int    $filter  What filter to use.
     * @param array  $options Optional switches for the filter_var function like FILTER_FLAG_ALLOW_HEX
     *
     * @return mixed
     */
    public function filter(string $index, $default, int $filter = FILTER_DEFAULT, array $options = [])
    {
        $value = $this->get($index, $default);

        if (!is_array($options) && $options)
        {
            $options = [
                'flags' => $options,
            ];
        }

        if (is_array($value) && !isset($options['flags']))
        {
            $options['flags'] = FILTER_REQUIRE_ARRAY;
        }

        return filter_var($value, $filter, $options);
    }

    /**
     * This method will get an stored item from the container as an boolean.
     *
     * @param string    $index   The items index.
     * @param bool|null $default The default return value if the item does not exist in the container.
     *
     * @return bool|null
     */
    public function getBoolean(string $index, bool $default = null): ?bool
    {
        if ($this->has($index))
        {
            $storedItem = $this->get($index, $default);
            $boolean = $this->filter($index, $default, FILTER_VALIDATE_BOOLEAN);
            if ($boolean == null)
            {
                $this->throw(new InvalidTypeCastException(sprintf(
                    "Error: malformed boolean, The item %s does not contain an valid boolean or cannot be casted to an boolean.",
                    $index
                )));
            }

            return $boolean;
        }

        return $default;
    }

    /**
     * This method will get an stored item from the container as an floating point number.
     *
     * @param string     $index   The items index.
     * @param float|null $default The default return value if the item does not exist in the container.
     *
     * @return float|null
     */
    public function getFloat(string $index, float $default = null): ?float
    {
        if ($this->has($index))
        {
            $storedItem = $this->get($index, $default);
            $float = $this->filter($index, $default, FILTER_VALIDATE_FLOAT);
            if ($float === null || $float === false)
            {
                $this->throw(new InvalidTypeCastException(sprintf(
                    "Error: malformed float, The item %s does not contain an valid float or cannot be casted to an float.",
                    $index
                )));
            }

            return $float;
        }

        return $default;
    }

    /**
     * This method will get an stored item from the container as an container.
     *
     * @param string                  $index   The items index.
     * @param ContainerInterface|null $default The default return value if the item does not exist in the container.
     *
     * @return null|ContainerInterface
     */
    public function getContainer(string $index, ContainerInterface $default = null, string $containerType = '\\WaterUpApi\\Helper\\ParameterContainer'): ?ContainerInterface
    {
        if ($this->has($index))
        {
            try
            {
                $storedItem = $this->getArray($index, $default);

                return new $containerType($storedItem);

            }
            catch (\Exception $invalidCastException)
            {
                $this->throw(new InvalidTypeCastException(sprintf(
                    "Error: malformed container, The item %s does not contain an valid container or cannot be casted to an container. Exception message: %s",
                    $index,
                    $invalidCastException->getMessage()
                )));
            }
        }

        return $default;
    }

    /**
     * This method will get an stored item from the container as an array.
     *
     * @param string     $index   The items index.
     * @param array|null $default The default return value if the item does not exist in the container.
     *
     * @return array|null
     */
    public function getArray(string $index, array $default = null): ?array
    {
        if ($this->has($index))
        {
            $storedItem = $this->get($index, $default);
            try
            {
                return (array)$storedItem;
            }
            catch (\Exception $invalidCastException)
            {
                $this->throw(new InvalidTypeCastException(sprintf(
                    "Error: malformed array, The item %s does not contain an valid array or cannot be casted to an array. Exception message: %s",
                    $index,
                    $invalidCastException->getMessage()
                )));
            }
        }

        return $default;
    }

    /**
     * @param string        $index   The items index.
     * @param callable|null $default The default return value if the item does not exist in the container.
     *
     * @return callable|null
     */
    public function getClosure(string $index, callable $default = null): ?callable
    {
        if ($this->has($index))
        {
            $storedItem = $this->get($index, $default);

            if (is_object($storedItem) && ($storedItem instanceof \Closure))
            {
                return $storedItem;
            }
            else
            {
                $this->throw(new InvalidTypeCastException(sprintf(
                    "Error: uncallable closure, The item %s does not contain an callable closure function",
                    $index
                )));
            }
        }

        return $default;
    }

    /**
     * Add an element to the parameter container using array access.
     * Like $myContainerInstance[] = value or $parameterContainer[ 'key' ] = value
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($key, $value)
    {
        $this->set(($key ?? '0'), $value);
    }

    /**
     * Checks if an element exists in the parameter container when using array access.
     * like isset( $myContainerInstance[ 'key' ] )
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * Unset an element from the parameter container when using array access.
     * Like unset( $myContainerInstance[ 'key' ] )
     *
     * @param mixed $offset
     */
    public function offsetUnset($key)
    {
        $this->remove($key);
    }

    /**
     * This method will remove an item from the container.
     *
     * @param string $index The items index.
     */
    public function remove(string $index): void
    {
        unset($this->parameters[ $index ]);
    }

    /**
     * Get an parameter from the container when using array access.
     * like echo $myContainerInstance[ 'key' ]
     *
     * @param mixed $key
     *
     * @return mixed|null
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Get an array iterator so you can iterate through the items stored in the container. This is required when
     * for instance you want to use this container in foreach or while loops.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->parameters);
    }

    /**
     * Count the amount of items stored in the container. This is required if when for instance you want to use the
     * count function to count the amount of items in the container like count( $myContainerInstance ).
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->parameters);
    }

}