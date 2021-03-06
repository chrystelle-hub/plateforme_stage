<?php

declare(strict_types=1);

namespace PackageVersions;

/**
 * This class is generated by ocramius/package-versions, specifically by
 * @see \PackageVersions\Installer
 *
 * This file is overwritten at every run of `composer install` or `composer update`.
 */
final class Versions
{
    public const ROOT_PACKAGE_NAME = '__root__';
    /**
     * Array of all available composer packages.
     * Dont read this array from your calling code, but use the \PackageVersions\Versions::getVersion() method instead.
     *
     * @var array<string, string>
     * @internal
     */
    public const VERSIONS          = array (
  'doctrine/annotations' => 'v1.8.0@904dca4eb10715b92569fbcd79e201d5c349b6bc',
  'doctrine/cache' => '1.10.0@382e7f4db9a12dc6c19431743a2b096041bcdd62',
  'doctrine/collections' => '1.6.4@6b1e4b2b66f6d6e49983cebfe23a21b7ccc5b0d7',
  'doctrine/common' => '2.12.0@2053eafdf60c2172ee1373d1b9289ba1db7f1fc6',
  'doctrine/dbal' => 'v2.10.1@c2b8e6e82732a64ecde1cddf9e1e06cb8556e3d8',
  'doctrine/doctrine-bundle' => '2.0.7@6926771140ee87a823c3b2c72602de9dda4490d3',
  'doctrine/doctrine-migrations-bundle' => '2.1.2@856437e8de96a70233e1f0cc2352fc8dd15a899d',
  'doctrine/event-manager' => '1.1.0@629572819973f13486371cb611386eb17851e85c',
  'doctrine/inflector' => '1.3.1@ec3a55242203ffa6a4b27c58176da97ff0a7aec1',
  'doctrine/instantiator' => '1.3.0@ae466f726242e637cebdd526a7d991b9433bacf1',
  'doctrine/lexer' => '1.2.0@5242d66dbeb21a30dd8a3e66bf7a73b66e05e1f6',
  'doctrine/migrations' => '2.2.1@a3987131febeb0e9acb3c47ab0df0af004588934',
  'doctrine/orm' => 'v2.7.1@445796af0e873d9bd04f2502d322a7d5009b6846',
  'doctrine/persistence' => '1.3.6@5dd3ac5eebef2d0b074daa4440bb18f93132dee4',
  'doctrine/reflection' => 'v1.1.0@bc420ead87fdfe08c03ecc3549db603a45b06d4c',
  'egulias/email-validator' => '2.1.17@ade6887fd9bd74177769645ab5c474824f8a418a',
  'jdorn/sql-formatter' => 'v1.2.17@64990d96e0959dff8e059dfcdc1af130728d92bc',
  'nelmio/cors-bundle' => '2.0.1@9683e6d30d000ef998919261329d825de7c53499',
  'ocramius/package-versions' => '1.5.1@1d32342b8c1eb27353c8887c366147b4c2da673c',
  'ocramius/proxy-manager' => '2.2.3@4d154742e31c35137d5374c998e8f86b54db2e2f',
  'psr/cache' => '1.0.1@d11b50ad223250cf17b86e38383413f5a6764bf8',
  'psr/container' => '1.0.0@b7ce3b176482dbbc1245ebf52b181af44c2cf55f',
  'psr/event-dispatcher' => '1.0.0@dbefd12671e8a14ec7f180cab83036ed26714bb0',
  'psr/log' => '1.1.2@446d54b4cb6bf489fc9d75f55843658e6f25d801',
  'sensio/framework-extra-bundle' => 'v5.5.3@98f0807137b13d0acfdf3c255a731516e97015de',
  'swiftmailer/swiftmailer' => 'v6.2.3@149cfdf118b169f7840bbe3ef0d4bc795d1780c9',
  'symfony/cache' => 'v5.0.4@4572116c640a6bc9fc0047180fe7f9362e5923fc',
  'symfony/cache-contracts' => 'v2.0.1@23ed8bfc1a4115feca942cb5f1aacdf3dcdf3c16',
  'symfony/config' => 'v5.0.4@7640c6704f56bf64045066bc5d93fd9d664baa63',
  'symfony/console' => 'v5.0.4@91c294166c38d8c0858a86fad76d8c14dc1144c8',
  'symfony/dependency-injection' => 'v5.0.4@86338f459313525dd95f5a012f8a9ea118002f94',
  'symfony/doctrine-bridge' => 'v5.0.4@63cf745cb01a897c3abfa41cde0b8559295060d9',
  'symfony/dotenv' => 'v5.0.4@8331da80cc35fe903db0ff142376d518804ff1b1',
  'symfony/error-handler' => 'v5.0.4@c263709b4570387f3fe339c4f05aae66740cf2ab',
  'symfony/event-dispatcher' => 'v5.0.4@4a7a8cdca1120c091b4797f0e5bba69c1e783224',
  'symfony/event-dispatcher-contracts' => 'v2.0.1@af23c2584d4577d54661c434446fb8fbed6025dd',
  'symfony/filesystem' => 'v5.0.4@3afadc0f57cd74f86379d073e694b0f2cda2a88c',
  'symfony/finder' => 'v5.0.4@4176e7cb846fe08f32518b7e0ed8462e2db8d9bb',
  'symfony/flex' => 'v1.6.2@e4f5a2653ca503782a31486198bd1dd1c9a47f83',
  'symfony/form' => 'v5.0.5@7d3afc4f0776904bb199317ae71b6a0fc46e5e5d',
  'symfony/framework-bundle' => 'v5.0.4@3dd6c675b45af45ca09aa830240afbe0e376739a',
  'symfony/http-foundation' => 'v5.0.4@2832d8cffc3a91df550ac42bcdce602f8c08be3e',
  'symfony/http-kernel' => 'v5.0.4@1f4179489af4ead692fd375b7d9ac675da4215a7',
  'symfony/inflector' => 'v5.0.4@e375603b6bd12e8e3aec3fc1b640ac18a4ef4cb2',
  'symfony/intl' => 'v5.0.5@2d1fb70e6e1c455a123218bebf6287d025c5bac5',
  'symfony/mime' => 'v5.0.4@2a3c7fee1f1a0961fa9cf360d5da553d05095e59',
  'symfony/options-resolver' => 'v5.0.5@b1ab86ce52b0c0abe031367a173005a025e30e04',
  'symfony/orm-pack' => 'v1.0.8@c9bcc08102061f406dc908192c0f33524a675666',
  'symfony/polyfill-intl-icu' => 'v1.14.0@727b3bb5bfa7ca9eeb86416784cf1c08a6289b86',
  'symfony/polyfill-intl-idn' => 'v1.14.0@6842f1a39cf7d580655688069a03dd7cd83d244a',
  'symfony/polyfill-mbstring' => 'v1.14.0@34094cfa9abe1f0f14f48f490772db7a775559f2',
  'symfony/polyfill-php73' => 'v1.14.0@5e66a0fa1070bf46bec4bea7962d285108edd675',
  'symfony/property-access' => 'v5.0.5@18617a8c26b97a262f816c78765eb3cd91630e19',
  'symfony/routing' => 'v5.0.4@7da33371d8ecfed6c9d93d87c73749661606f803',
  'symfony/security-bundle' => 'v5.0.4@4e3c9cb554053e2b5b56c07b0a22492c2f1be195',
  'symfony/security-core' => 'v5.0.4@7415690201211e7787e751ebcd8c70d275bb1e0d',
  'symfony/security-csrf' => 'v5.0.4@65066f7e0f6e38a8c5507c706e86e7a52fd7ff3e',
  'symfony/security-guard' => 'v5.0.4@5813e1b39d8a1dd46f0b96e6ebe4dd4518d0b302',
  'symfony/security-http' => 'v5.0.4@e063a0a032f81d38b06cda73c1f5ed25cae8538e',
  'symfony/serializer' => 'v5.0.5@4411e7356beda717880da28cdbd32b33c52bb894',
  'symfony/service-contracts' => 'v2.0.1@144c5e51266b281231e947b51223ba14acf1a749',
  'symfony/stopwatch' => 'v5.0.4@5d9add8034135b9a5f7b101d1e42c797e7f053e4',
  'symfony/swiftmailer-bundle' => 'v3.4.0@553d2474288349faed873da8ab7c1551a00d26ae',
  'symfony/translation-contracts' => 'v2.0.1@8cc682ac458d75557203b2f2f14b0b92e1c744ed',
  'symfony/validator' => 'v5.0.5@fb9c52b2fe3a8336b65f85b61dedbcc6c427c37b',
  'symfony/var-dumper' => 'v5.0.4@923591cfb78a935f0c98968fedfad05bfda9d01f',
  'symfony/var-exporter' => 'v5.0.4@960f9ac0fdbd642461ed29d7717aeb2a94d428b9',
  'symfony/yaml' => 'v5.0.4@69b44e3b8f90949aee2eb3aa9b86ceeb01cbf62a',
  'zendframework/zend-code' => '3.4.1@268040548f92c2bfcba164421c1add2ba43abaaa',
  'zendframework/zend-eventmanager' => '3.2.1@a5e2583a211f73604691586b8406ff7296a946dd',
  'doctrine/data-fixtures' => '1.4.2@39e9777c9089351a468f780b01cffa3cb0a42907',
  'doctrine/doctrine-fixtures-bundle' => '3.3.0@8f07fcfdac7f3591f3c4bf13a50cbae05f65ed70',
  'nikic/php-parser' => 'v4.3.0@9a9981c347c5c49d6dfe5cf826bb882b824080dc',
  'symfony/maker-bundle' => 'v1.14.3@c864e7f9b8d1e1f5f60acc3beda11299f637aded',
  'paragonie/random_compat' => '2.*@0357c2f1e0290199f35399dc93e2a34b7ebcb177',
  'symfony/polyfill-ctype' => '*@0357c2f1e0290199f35399dc93e2a34b7ebcb177',
  'symfony/polyfill-iconv' => '*@0357c2f1e0290199f35399dc93e2a34b7ebcb177',
  'symfony/polyfill-php72' => '*@0357c2f1e0290199f35399dc93e2a34b7ebcb177',
  'symfony/polyfill-php71' => '*@0357c2f1e0290199f35399dc93e2a34b7ebcb177',
  'symfony/polyfill-php70' => '*@0357c2f1e0290199f35399dc93e2a34b7ebcb177',
  'symfony/polyfill-php56' => '*@0357c2f1e0290199f35399dc93e2a34b7ebcb177',
  '__root__' => 'dev-master@0357c2f1e0290199f35399dc93e2a34b7ebcb177',
);

    private function __construct()
    {
    }

    /**
     * @throws \OutOfBoundsException If a version cannot be located.
     *
     * @psalm-param key-of<self::VERSIONS> $packageName
     */
    public static function getVersion(string $packageName) : string
    {
        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }

        throw new \OutOfBoundsException(
            'Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files'
        );
    }
}
