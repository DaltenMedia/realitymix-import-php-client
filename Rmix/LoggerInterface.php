<?php

/**
 * Rozhraní pro logování.
 *
 * @license MIT
 * @copyright (C) 2013 Dalten s.r.o.
 */
interface Rmix_LoggerInterface
{
	/**
	 * Zaloguje data.
	 *
	 * @param string $name    Název logovaných dat.
	 * @param string $content Obsah logovaných dat.
	 */
	public function log($name, $content);
}
 