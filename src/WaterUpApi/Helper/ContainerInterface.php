<?php
/**
 * Author: Joris Rietveld <jorisrietveld@gmail.com>
 * Created: 25-05-2017 02:36
 * Licence: GNU General Public licence version 3 <https://www.gnu.org/licenses/quick-guide-gplv3.html>
 */
declare(strict_types=1);

namespace WaterUpApi\Helper;


interface ContainerInterface
{
    public function get( string $index, $default = null );

    public function set( string $index, $value = null ) : self;

    public function safeSet(string $index, $value = null): ?self;

    public function has( string $index ) : ?bool;

    public function all() : array;

    public function keys(): array;

    public function add( array $parameters ) : self;

    public function safeAdd(array $parameters): ?self;

    public function remove( string $index ) : void;

    public function clear() : void;

    public function replace( array $parameters ) : self;

    public function filter( string $index, $default, int $filter = FILTER_DEFAULT, array $options = [] );

    public function getDateTime(string $index, string $dateTimeFormat, \DateTime $default = null): ?\DateTime;

    public function getObject( string $index, object $default = null ) : ?object;

    public function getString( string  $index, string $default = null ) : ?string;

    public function getInteger( string $index, int $default = null ) : ?int;

    public function getBoolean( string  $index, bool $default  = null ) : ?bool;

    public function getFloat( string $index, float $default  = null ) : ?float;

    public function getArray( string $index, array $default  = null ) : ?array;

    public function getContainer(string $index, ContainerInterface $default = null): ?self;

    public function getClosure( string $index, callable $default = null ) : ?callable;

    public function offsetExists($offset);

    public function offsetGet($offset);

    public function offsetSet($offset, $value);

    public function offsetUnset($offset);

    public function getIterator();

    public function count() : int;
}