<?php declare(strict_types = 1);

namespace Tlapnet\Panelus\Panel;

use Tlapnet\Panelus\UI\Panel\AbstractPanelControl;

interface IPanelControlFactory
{

	/**
	 * @return AbstractPanelControl
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
	 */
	public function createControl();

}
