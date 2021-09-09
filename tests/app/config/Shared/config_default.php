<?php

use Pyz\Shared\Scheduler\SchedulerConfig;
use Pyz\Yves\ShopApplication\YvesBootstrap;
use Pyz\Zed\Application\Communication\ZedBootstrap;
use Spryker\Client\RabbitMq\Model\RabbitMqAdapter;
use Spryker\Glue\Log\Plugin\GlueLoggerConfigPlugin;
use Spryker\Service\FlysystemLocalFileSystem\Plugin\Flysystem\LocalFilesystemBuilderPlugin;
use Spryker\Shared\Acl\AclConstants;
use Spryker\Shared\Agent\AgentConstants;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Application\Log\Config\SprykerLoggerConfig;
use Spryker\Shared\CartsRestApi\CartsRestApiConstants;
use Spryker\Shared\Category\CategoryConstants;
use Spryker\Shared\CmsGui\CmsGuiConstants;
use Spryker\Shared\Customer\CustomerConstants;
use Spryker\Shared\DataImport\DataImportConstants;
use Spryker\Shared\ErrorHandler\ErrorHandlerConstants;
use Spryker\Shared\ErrorHandler\ErrorRenderer\WebHtmlErrorRenderer;
use Spryker\Shared\Event\EventConstants;
use Spryker\Shared\EventBehavior\EventBehaviorConstants;
use Spryker\Shared\FileManager\FileManagerConstants;
use Spryker\Shared\FileManagerGui\FileManagerGuiConstants;
use Spryker\Shared\FileSystem\FileSystemConstants;
use Spryker\Shared\GlueApplication\GlueApplicationConstants;
use Spryker\Shared\Http\HttpConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Log\LogConstants;
use Spryker\Shared\Mail\MailConstants;
use Spryker\Shared\MerchantPortalApplication\MerchantPortalConstants;
use Spryker\Shared\Monitoring\MonitoringConstants;
use Spryker\Shared\Newsletter\NewsletterConstants;
use Spryker\Shared\Oauth\OauthConstants;
use Spryker\Shared\OauthCryptography\OauthCryptographyConstants;
use Spryker\Shared\Oms\OmsConstants;
use Spryker\Shared\ProductConfiguration\ProductConfigurationConstants;
use Spryker\Shared\ProductLabel\ProductLabelConstants;
use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Shared\ProductRelation\ProductRelationConstants;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Shared\Queue\QueueConfig;
use Spryker\Shared\Queue\QueueConstants;
use Spryker\Shared\RabbitMq\RabbitMqEnv;
use Spryker\Shared\Router\RouterConstants;
use Spryker\Shared\Sales\SalesConstants;
use Spryker\Shared\Scheduler\SchedulerConstants;
use Spryker\Shared\SchedulerJenkins\SchedulerJenkinsConfig;
use Spryker\Shared\SchedulerJenkins\SchedulerJenkinsConstants;
use Spryker\Shared\SearchElasticsearch\SearchElasticsearchConstants;
use Spryker\Shared\SecurityBlocker\SecurityBlockerConstants;
use Spryker\Shared\SecuritySystemUser\SecuritySystemUserConstants;
use Spryker\Shared\Session\SessionConfig;
use Spryker\Shared\Session\SessionConstants;
use Spryker\Shared\SessionRedis\SessionRedisConfig;
use Spryker\Shared\SessionRedis\SessionRedisConstants;
use Spryker\Shared\Storage\StorageConstants;
use Spryker\Shared\StorageRedis\StorageRedisConstants;
use Spryker\Shared\Tax\TaxConstants;
use Spryker\Shared\Testify\TestifyConstants;
use Spryker\Shared\Translator\TranslatorConstants;
use Spryker\Shared\User\UserConstants;
use Spryker\Shared\ZedRequest\ZedRequestConstants;
use Spryker\Yves\Log\Plugin\YvesLoggerConfigPlugin;
use Spryker\Zed\Log\Communication\Plugin\ZedLoggerConfigPlugin;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Propel\PropelConfig;
use SprykerEco\Shared\Payone\PayoneConstants;
use SprykerEco\Zed\Payone\PayoneConfig;
use SprykerShop\Shared\CustomerPage\CustomerPageConstants;
use SprykerShop\Shared\ShopUi\ShopUiConstants;
use Symfony\Component\HttpFoundation\Cookie;

$sprykerBackendHost = getenv('SPRYKER_BE_HOST') ?: 'not-configured-host';
$sprykerFrontendHost = getenv('SPRYKER_FE_HOST') ?: 'not-configured-host';

$config[KernelConstants::RESOLVABLE_CLASS_NAMES_CACHE_ENABLED] = false;
$config[KernelConstants::RESOLVED_INSTANCE_CACHE_ENABLED] = false;

$config[KernelConstants::PROJECT_NAMESPACE] = 'Pyz';
$config[KernelConstants::PROJECT_NAMESPACES] = [
    'Pyz',
];
$config[KernelConstants::CORE_NAMESPACES] = [
    'Spryker',
    'SprykerSdk',
];

// TODO: move more here...
$config['PROPEL'] = [
    'database' => getenv('SPRYKER_DB_DATABASE'),
    'paths' => [
        'loaderScriptDir' => ''
    ],
];

$config[PropelConstants::ZED_DB_ENGINE]
    = strtolower(getenv('SPRYKER_DB_ENGINE') ?: '') ?: PropelConfig::DB_ENGINE_MYSQL;
$config[PropelConstants::ZED_DB_HOST] = getenv('SPRYKER_DB_HOST');
$config[PropelConstants::ZED_DB_PORT] = getenv('SPRYKER_DB_PORT');
$config[PropelConstants::ZED_DB_USERNAME] = getenv('SPRYKER_DB_USERNAME');
$config[PropelConstants::ZED_DB_PASSWORD] = getenv('SPRYKER_DB_PASSWORD');
$config[PropelConstants::ZED_DB_DATABASE] = getenv('SPRYKER_DB_DATABASE');
