<?php

/**
 * @copyright [Your company or name]
 * @license   [License of your project]
 */

declare(strict_types=1);

namespace app\view\auto\php;

use actra\yuf\auth\AccessRightCollection;
use actra\yuf\core\BaseView;
use actra\yuf\core\InputParameterCollection;
use actra\yuf\core\ViewContext;
use Override;

// Found by its name for "welcome.html" of the route "/auto/" (ClassNameViewFactory), nothing is registered. The class
// name equals the file title, therefore it is lowercase (see "Deviations from the global standard" in AGENTS.md).
final class welcome extends BaseView
{
    public function __construct(ViewContext $context)
    {
        parent::__construct(
            context: $context,
            requiredViewGroupName: 'auto',
            ipWhitelist: [],
            authUser: null,
            requiredAccessRights: AccessRightCollection::createEmpty(),
            inputParameterCollection: new InputParameterCollection(),
        );
    }

    #[Override]
    public function execute(): void
    {
        $this->getHtmlDocument()->replacements->addText(identifier: 'greeting', text: 'Welcome!');
    }
}
