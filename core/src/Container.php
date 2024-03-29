<?php

namespace FunctionsPhp\lib;

use \ReflectionClass;


/**
 * Class Container
 * https://gist.githubusercontent.com/MustafaMagdi/2bb27aebf6ab078b1f3e5635c0282fac/raw/38b0a19819d7c8d494fbb4ef5e8683fe6bfb2faa/Container.php
 * https://github.com/MustafaMagdi
 */
class Container {

	/**
	 * @var array
	 */
	protected $instances = [];

	
	/**
	 * @param      $abstract
	 * @param null $concrete
	 */
	public function set( $abstract , $concrete = NULL ) {

		if ( $concrete === NULL ) {
			
			$concrete = $abstract;

		}

		$this->instances[ $abstract ] = $concrete;

	}


	/**
	 * @param       $abstract
	 * @param array $parameters
	 *
	 * @return mixed|null|object
	 * @throws Exception
	 */
	public function get( $abstract , $parameters = [] )
	{
		// if we don't have it, just register it
		if ( ! isset( $this->instances[ $abstract ] ) ) {

			$this->set($abstract);

		}

		return $this->resolve( $this->instances[ $abstract ] , $parameters );
	}


	/**
	 * resolve single
	 *
	 * @param $concrete
	 * @param $parameters
	 *
	 * @return mixed|object
	 * @throws Exception
	 */
	public function resolve( $concrete , $parameters ) {

		if ( $concrete instanceof Closure ) {

			return $concrete( $this , $parameters );

		}

		$reflector = new ReflectionClass( $concrete );

		// check if class is instantiable
		if ( ! $reflector->isInstantiable() ) {

			throw new Exception( "Class {$concrete} is not instantiable" );

		}

		// get class constructor
		$constructor = $reflector->getConstructor();

		if ( is_null( $constructor ) ) {

			// get new instance from class
			return $reflector->newInstance();

		}

		// get constructor params
		$parameters   = $constructor->getParameters();

		$dependencies = $this->getDependencies( $parameters );

		// get new instance with dependencies resolved
		return $reflector->newInstanceArgs($dependencies);
	}


	/**
	 * get all dependencies resolved
	 *
	 * @param $parameters
	 *
	 * @return array
	 * @throws Exception
	 */
	public function getDependencies( $parameters )
	{
		$dependencies = [];

		foreach ( $parameters as $parameter ) {

			// get the type hinted class
			$dependency = $parameter->getClass();

			if ( $dependency === NULL ) {


				### Kijken of we shit kunnen vinden in de arrays in functionsphp.php



				// check if default value for a parameter is available
				if ($parameter->isDefaultValueAvailable()) {

					// get default value of parameter
					$dependencies[] = $parameter->getDefaultValue();

				} else {

					throw new Exception( "Can not resolve class dependency {$parameter->name}" );

				}

			} else {

				// get dependency resolved
				$dependencies[] = $this->get($dependency->name);

			}
		}

		return $dependencies;
	}

}
