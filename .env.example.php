<?php

/**
 * @copyright [Your company or name]
 * @license   [License of your project]
 */

declare(strict_types=1);

// Copy to .env.php (done automatically by "composer create-project") and adjust it per environment.
// Never commit .env.php.
return [
    'defaultErrorReporting' => E_ALL,
    'defaultTimeZone' => 'Europe/Zurich',
    // Requests for other host names are rejected
    'allowedDomains' => [
        'my-project.ddev.site',
    ],
    'logEmailRecipient' => 'error@example.com',
    // Shows error details in the browser; never enable it in production
    'debug' => true,
    'robots' => 'noindex,nofollow',
    // Optional, default: the value of debug. Without checks (production), clear app/cache/v*/ on every deployment
    // 'checkTemplateChanges' => false,
];
