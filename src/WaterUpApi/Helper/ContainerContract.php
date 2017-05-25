<?php
/**
 * Author: Joris Rietveld <jorisrietveld@gmail.com>
 * Created: 25-05-2017 03:08
 * Licence: GNU General Public licence version 3 <https://www.gnu.org/licenses/quick-guide-gplv3.html>
 */
declare(strict_types=1);

namespace WaterUpApi\Helper;


abstract class ContainerContract implements ContainerInterface, \ArrayAccess, \Countable, \IteratorAggregate
{
    protected $parameters    = [];
    protected $containerType = __CLASS__;

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
     * This method saves the the class name of the instance.
     *
     * @return void
     */
    protected function setContainerType(string $containerType = __CLASS__): void
    {
        $this->containerType = $containerType;
    }

    /**
     * This method will get an item from the container or the default value if the key does not exist.
     *
     * @param string $index
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $index, $default = null)
    {
        return $this->has($index) ? $this->parameters[ $index ] : $default;
    }

    /**
     * This method will save an item in the container. If the key already exists it will overwrite it.
     *
     * @param string $index
     * @param mixed  $value
     *
     * @return ContainerInterface
     */
    public function set(string $index, $value = null): ContainerInterface
    {
        $this->parameters[ $index ] = $value;

        return $this;
    }

    /**
     * This method will save an item in the container if the index doesn't already exist.
     *
     * @param string $index
     * @param null   $value
     * @param bool   $throwException
     *
     * @return null|ContainerInterface
     */
    public function saveSet(string $index, $value = null, bool $throwException = false): ?ContainerInterface
    {
        if ($this->has($index) && $throwException)
        {
            throw new \InvalidArgumentException(sprintf(
                "Error: Trying to save an duplicate key to the %s container while using save set. the index %s does already exists.",
                $this->containerType,
                $index
            ));
        }
        elseif (!$this->has($index))
        {
            $this->set($index, $value);

            return $this;
        }

        return null;
    }

    /**
     * This method will check if an item with an certain index exists in the container.
     *
     * @param string $index
     *
     * @return bool|null
     */
    public function has(string $index): ?bool
    {
        return isset( $this->parameters[ $index ] );
    }

    /**
     * This method will return all the values
     * @return array
     */
    public function all(): array
    {
        // TODO: Implement all() method.
    }

    /**
     * @param array $parameters
     *
     * @return ContainerInterface
     */
    public function add(array $parameters): ContainerInterface
    {
        // TODO: Implement add() method.
    }

    /**
     * @param string $index
     * @param mixed  $value
     *
     * @return ContainerInterface
     */
    public function addOne(string $index, $value): ContainerInterface
    {
        // TODO: Implement addOne() method.
    }

    /**
     * @param string $index
     */
    public function remove(string $index): void
    {
        // TODO: Implement remove() method.
    }

    /**
     *
     */
    public function clear(): void
    {
        // TODO: Implement clear() method.
    }

    /**
     * @return array
     */
    public function keys(): array
    {
        // TODO: Implement keys() method.
    }

    /**
     * @param array $parameters
     *
     * @return ContainerInterface
     */
    public function replace(array $parameters): ContainerInterface
    {
        // TODO: Implement replace() method.
    }

    /**
     * @param string $index
     * @param mixed  $default
     * @param int    $filter
     * @param array  $options
     *
     * @return mixed
     */
    public function filter(string $index, $default, int $filter = FILTER_DEFAULT, array $options = [])
    {
        // TODO: Implement filter() method.
    }

    /**
     * @param string         $index
     * @param \DateTime|null $default
     *
     * @return \DateTime|null
     */
    public function getDateTime(string $index, \DateTime $default = null): ?\DateTime
    {
        // TODO: Implement getDateTime() method.
    }

    /**
     * @param string           $index
     * @param \DatePeriod|null $default
     *
     * @return \DatePeriod|null
     */
    public function getDatePeriod(string $index, \DatePeriod $default = null): ?\DatePeriod
    {
        // TODO: Implement getDatePeriod() method.
    }

    /**
     * @param string             $index
     * @param \DateInterval|null $default
     *
     * @return \DateInterval|null
     */
    public function getDateInterval(string $index, \DateInterval $default = null): ?\DateInterval
    {
        // TODO: Implement getDateInterval() method.
    }

    /**
     * @param string      $index
     * @param object|null $default
     *
     * @return null|object
     */
    public function getObject(string $index, object $default = null): ?object
    {
        // TODO: Implement getObject() method.
    }

    /**
     * @param string      $index
     * @param string|null $default
     *
     * @return null|string
     */
    public function getString(string $index, string $default = null): ?string
    {
        // TODO: Implement getString() method.
    }

    /**
     * @param string   $index
     * @param int|null $default
     *
     * @return int|null
     */
    public function getInteger(string $index, int $default = null): ?int
    {
        // TODO: Implement getInteger() method.
    }

    /**
     * @param string    $index
     * @param bool|null $default
     *
     * @return bool|null
     */
    public function getBoolean(string $index, bool $default = null): ?bool
    {
        // TODO: Implement getBoolean() method.
    }

    /**
     * @param string     $index
     * @param float|null $default
     *
     * @return float|null
     */
    public function getFloat(string $index, float $default = null): ?float
    {
        // TODO: Implement getFloat() method.
    }

    /**
     * @param string     $index
     * @param array|null $default
     *
     * @return array|null
     */
    public function getArray(string $index, array $default = null): ?array
    {
        // TODO: Implement getArray() method.
    }

    /**
     * @param string                  $index
     * @param ContainerInterface|null $default
     *
     * @return null|ContainerInterface
     */
    public function getContainer(string $index, ContainerInterface $default = null): ?ContainerInterface
    {
        // TODO: Implement getContainer() method.
    }

    /**
     * @param string        $index
     * @param callable|null $default
     *
     * @return callable|null
     */
    public function getClosure(string $index, callable $default = null): ?callable
    {
        // TODO: Implement getClosure() method.
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        // TODO: Implement offsetExists() method.
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset): mixed
    {
        // TODO: Implement offsetGet() method.
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, mixed $value): void
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        // TODO: Implement offsetUnset() method.
    }

    /**
     * @return \Iterator
     */
    public function getIterator(): \Iterator
    {
        // TODO: Implement getIterator() method.
    }

    /**
     * @return int
     */
    public function count(): int
    {
        // TODO: Implement count() method.
    }

}