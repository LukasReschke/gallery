<?php
/**
 * ownCloud - galleryplus
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Olivier Paroz <owncloud@interfasys.ch>
 * @author Bernhard Posselt <dev@bernhard-posselt.com>
 *
 * @copyright Olivier Paroz 2014-2015
 * @copyright Bernhard Posselt 2012-2015
 */

namespace OCA\GalleryPlus\Middleware;

use OCP\IURLGenerator;
use OCP\ILogger;
use OCP\IRequest;

use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Http\RedirectResponse;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Middleware;

/**
 * Checks that we have a valid token linked to a valid resource and that the
 * user is authorised to access it
 *
 * @package OCA\GalleryPlus\Middleware
 */
abstract class CheckMiddleware extends Middleware {

	/**
	 * @type string
	 */
	protected $appName;
	/**
	 * @type IRequest
	 */
	protected $request;
	/**
	 * @type IURLGenerator
	 */
	private $urlGenerator;
	/**
	 * @type ILogger
	 */
	private $logger;

	/***
	 * Constructor
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param IURLGenerator $urlGenerator
	 * @param ILogger $logger
	 */
	public function __construct(
		$appName,
		IRequest $request,
		IURLGenerator $urlGenerator,
		ILogger $logger
	) {
		$this->appName = $appName;
		$this->request = $request;
		$this->urlGenerator = $urlGenerator;
		$this->logger = $logger;
	}

	/**
	 * If a CheckException is being caught, ajax requests return a JSON
	 * error response and non ajax requests redirect an error page
	 *
	 * @inheritDoc
	 */
	public function afterException(
		$controller, $methodName, \Exception $exception
	) {
		if ($exception instanceof CheckException) {
			$appName = $this->appName;
			$message = $exception->getMessage();
			$code = $exception->getCode();

			$this->logger->debug(
				"[TokenCheckException] {message} ({code})",
				array(
					'app'     => $appName,
					'message' => $message,
					'code'    => $code
				)
			);

			if (stripos($this->request->getHeader('Accept'), 'html') === false
			) {
				$response = new JSONResponse(
					array(
						'message' => $message,
						'success' => false
					),
					$code
				);

				$this->logger->debug(
					"[TokenCheckException] JSON response",
					array(
						'app' => $appName
					)
				);

			} else {
				$this->logger->debug(
					"[CheckException] HTML response",
					array(
						'app' => $appName
					)
				);

				if ($code === 401) {
					$params = $this->request->getParams();

					$this->logger->debug(
						'[CheckException] Unauthorised Request params: {params}',
						array(
							'app'    => $appName,
							'params' => $params
						)
					);

					/**
					 * We need to render a template or we'll have an endless
					 * loop as this is called before the controller can render
					 * a template
					 */
					return new TemplateResponse(
						$appName, 'authenticate', $params,
						'guest'
					);

				} else {
					$url = $this->urlGenerator->linkToRoute(
						$this->appName . '.page.error_page',
						array(
							'message' => $message,
							'code'    => $code
						)
					);
				}

				$response = new RedirectResponse($url);
			}

			return $response;
		}

		throw $exception;
	}

}