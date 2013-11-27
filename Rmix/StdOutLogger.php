<?php

/**
 * Logger který varcí data rovnou na výstup.
 *
 * @license MIT
 * @copyright (C) 2013 Dalten s.r.o.
 */
class Rmix_StdOutLogger implements Rmix_LoggerInterface
{
	/**
	 * Zaloguje data.
	 *
	 * @param string $name    Název logovaných dat.
	 * @param string $content Obsah logovaných dat.
	 */
	public function log($name, $content)
	{
		$lineWidth = 80;
		echo str_repeat('=', $lineWidth), PHP_EOL;
		$head = '== Start ' . $name;
		echo  $head, str_repeat(' ', $lineWidth - strlen($head) - 2) . '==', PHP_EOL;
		echo $content, PHP_EOL;
		$footer = '== End ' . $name;
		echo  $footer, str_repeat(' ', $lineWidth - strlen($footer) - 2) . '==', PHP_EOL;
		echo str_repeat('=', $lineWidth), PHP_EOL;
	}
}
 