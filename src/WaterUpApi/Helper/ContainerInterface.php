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

    public function saveSet( string $index, $value = null, bool $throwException = false ) : ?ContainerInterface;

    public function has( string $index ) : ?bool;

    public function all() : array;

    public function add( array $parameters ) : self;

    public function addOne( string $index, $value ) : self;

    public function remove( string $index ) : void;

    public function clear() : void;

    public function keys() : array;

    public function replace( array $parameters ) : self;

    public function filter( string $index, $default, int $filter = FILTER_DEFAULT, array $options = [] );

    public function getDateTime( string  $index, \DateTime $default = null ) : ?\DateTime;

    public function getDatePeriod( string  $index, \DatePeriod $default = null ) : ?\DatePeriod;

    public function getDateInterval( string $index, \DateInterval $default = null ) : ?\DateInterval;

    public function getObject( string $index, object $default = null ) : ?object;

    public function getString( string  $index, string $default = null ) : ?string;

    public function getInteger( string $index, int $default = null ) : ?int;

    public function getBoolean( string  $index, bool $default  = null ) : ?bool;

    public function getFloat( string $index, float $default  = null ) : ?float;

    public function getArray( string $index, array $default  = null ) : ?array;

    public function getContainer(string $index, ContainerInterface $default = null ) : ?ContainerInterface;

    public function getClosure( string $index, callable $default = null ) : ?callable;

    public function offsetExists ( $offset ) : bool;

    public function offsetGet ( $offset ) : mixed;

    public function offsetSet ( $offset , $value ) : void;

    public function offsetUnset ( $offset ) : void;

    public function getIterator() : \Iterator;

    public function count() : int;
}