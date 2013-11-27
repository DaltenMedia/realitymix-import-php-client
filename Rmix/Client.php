<?php

/**
 * Třída na vytváření xml-rpc požadavků.
 *
 * Http požadavky se vytvářejí pomocí rozšíření curl
 *
 * @license MIT
 * @copyright (C) 2013 Dalten s.r.o.
 * @url http://realitymix.centrum.cz/import/documentation/xml-rpc/
 *
 * @method getHash
 * @method login
 * @method logout
 * @method addAdvert
 * @method delAdvert
 * @method listAdvert
 * @method addPhoto
 * @method delPhoto
 * @method listPhoto
 * @method listStat
 * @method addDevel
 * @method delDevel
 * @method listDevel
 * @method addDevelPhoto
 * @method delDevelPhoto
 * @method listDevelPhoto
 * @method addSeller
 * @method delSeller
 * @method listSeller
 * @method version
 */
class Rmix_Client
{
	/**
	 * Instance spojeni.
	 *
	 * @var resource|false
	 */
	private $connection;

	/**
	 * Znacka kodovani.
	 *
	 * @var string
	 */
	private $encoding;

	/**
	 * Instance loggeru.
	 *
	 * @var Rmix_LoggerInterface|null
	 */
	private $logger;

	/**
	 * Nastavi spojeni pres curl a vnitrni kodovani.
	 *
	 * @param string $uri      Adresa xml-rpc serveru.
	 * @param string $encoding Kodovani.
	 * @param bool   $debug    Zapne verbose mode pro http komunikaci.
	 */
	public function __construct($uri, $encoding = 'UTF-8', $debug = false)
	{
		$this->connection = curl_init((string) $uri);
		$this->encoding = (string) $encoding;

		curl_setopt_array($this->connection, array(
			CURLOPT_POST => true,
			CURLOPT_HTTPHEADER => array('Content-Type: text/xml'),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_VERBOSE => $debug
		));
	}

	/**
	 * Preda parametry metode call.
	 *
	 * @uses call
	 *
	 * @param string $method Nazev metody.
	 * @param mixed $args    Parametry metody.
	 *
	 * @return mixed
	 */
	public function __call($method, $args)
	{
		return $this->call($method, $args);
	}

	/**
	 * Zavola vzdalenou metodu na serveru a vrati vysledek.
	 *
	 * @param string $method Nazev pozadovane metody.
	 * @param array  $args   Parametry.
	 *
	 * @return mixed
	 */
	public function call($method, array $args)
	{
		$method = (string) $method;
		$options = array(
			'encoding' => $this->encoding,
			'escaping' => 'markup'
		);

		$oldLocale = setlocale(LC_NUMERIC, 0);
		setlocale(LC_NUMERIC, 'en_US');
		$xml = xmlrpc_encode_request($method, $args, $options);

		if ($this->logger instanceof Rmix_LoggerInterface) {
			$this->logger->log('Request', $xml);
		}

		curl_setopt($this->connection, CURLOPT_POSTFIELDS, $xml);

		$rawResponse = curl_exec($this->connection);

		if ($this->logger instanceof Rmix_LoggerInterface) {
			$this->logger->log('Response', $rawResponse);
		}

		$result = xmlrpc_decode($rawResponse, $this->encoding);
		setlocale(LC_NUMERIC, $oldLocale);

		return $result;
	}

	/**
	 * Nastavi xmlrpc typ, base64 nebo datetime pro PHP retezce.
	 *
	 * @param string $value Odkaz na promenou.
	 * @param string $type Pozadovany typ 'base64' nebo 'datetime'.
	 *
	 * @return string
	 */
	public function setType($value, $type)
	{
		$value = (string) $value;
		xmlrpc_set_type($value, (string) $type);

		return $value;
	}

	/**
	 * Nastaví instanci loggeru.
	 *
	 * @param Rmix_LoggerInterface $logger Instance loggeru.
	 *
	 * @return $this
	 */
	public function setLogger(Rmix_LoggerInterface $logger)
	{
		$this->logger = $logger;

		return $this;
	}
}
 