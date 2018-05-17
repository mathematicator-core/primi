<?php

namespace Smuuf\Primi\Handlers;

use \Smuuf\Primi\Context;

class PropertyAccess extends \Smuuf\Primi\StrictObject implements IHandler {

	public static function handle(array $node, Context $context) {
		return $node['text'];
	}

}
