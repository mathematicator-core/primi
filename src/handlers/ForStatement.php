<?php

namespace Smuuf\Primi\Handlers;

use \Smuuf\Primi\ISupportsIteration;
use \Smuuf\Primi\ContinueException;
use \Smuuf\Primi\BreakException;
use \Smuuf\Primi\HandlerFactory;
use \Smuuf\Primi\Context;

/**
 * Node fields:
 * left: A source iterator.
 * item: Variable name to store the single item in.
 * right: Node representing contents of code to execute while iterating the iterator structure.
 */
class ForStatement extends \Smuuf\Primi\StrictObject implements IHandler {

	public static function handle(array $node, Context $context) {

		// Execute the left-hand node and get its return value.
		$leftHandler = HandlerFactory::get($node['left']['name']);
		$subject = $leftHandler::handle($node['left'], $context);

		if (!$subject instanceof ISupportsIteration) {
			throw new \Smuuf\Primi\ErrorException(
				sprintf("Cannot iterate over '%s'", $subject::TYPE),
				$node
			);
		}

		$iterator = $subject->getIterator();

		$elementVariableName = $node['item']['text'];
		$blockHandler = HandlerFactory::get($node['right']['name']);

		foreach ($iterator as $i) {

			$context->setVariable($elementVariableName, $i);

			try {
				$blockHandler::handle($node['right'], $context);
			} catch (ContinueException $e) {
				continue;
			} catch (BreakException $e) {
				break;
			}

		}

	}

}
