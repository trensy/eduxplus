<?php
namespace Eduxplus\CoreBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * SecurityHeadersExtension
 */
class EduxplusCoreExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        foreach ($configs as $subConfig) {
            $config = array_merge($config, $subConfig);
        }

        foreach ($config as $key => $value) {
            $container->setParameter('eduxplus_core.' . $key, $value);
        }
    }


    public function prepend(ContainerBuilder $container)
    {
        //twig
        $namespace = $container->getExtension("twig")->getAlias();
        $container->prependExtensionConfig($namespace, [
                "paths"=>[
                    __DIR__."/../Resources/templates"=>"CoreBundle",
                    __DIR__."/../Lib/Grid/templates"=>"Grid",
                    __DIR__."/../Lib/Form/templates"=>"Form",
                    __DIR__."/../Lib/View/templates"=>"View"
                ]
        ]);

        //doctrine
        $namespace = $container->getExtension("doctrine")->getAlias();
        $container->prependExtensionConfig($namespace, [
            "orm" => [
                "mappings" => [
                    'EduxplusCoreBundle' => [
                        'type' => 'annotation',
                        'dir' => 'Entity',
                        'is_bundle' => true,
                        'prefix' => 'Eduxplus\CoreBundle\Entity',
                        'alias' => 'Core',
                    ],
                ],
                "filters" => [
                    "projectable" => [
                        "class" => "Eduxplus\CoreBundle\Doctrine\ProjectselectFilter"
                    ]
                ],
                "dql" => [
                    "datetime_functions" => [
                        "addtime" => "DoctrineExtensions\Query\Mysql\AddTime", "convert_tz" => "DoctrineExtensions\Query\Mysql\ConvertTz", "date" => "DoctrineExtensions\Query\Mysql\Date", "date_format" => "DoctrineExtensions\Query\Mysql\DateFormat", "dateadd" => "DoctrineExtensions\Query\Mysql\DateAdd", "datesub" => "DoctrineExtensions\Query\Mysql\DateSub", "datediff" => "DoctrineExtensions\Query\Mysql\DateDiff", "day" => "DoctrineExtensions\Query\Mysql\Day", "dayname" => "DoctrineExtensions\Query\Mysql\DayName", "dayofweek" => "DoctrineExtensions\Query\Mysql\DayOfWeek", "dayofyear" => "DoctrineExtensions\Query\Mysql\DayOfYear", "div" => "DoctrineExtensions\Query\Mysql\Div", "from_unixtime" => "DoctrineExtensions\Query\Mysql\FromUnixtime", "hour" => "DoctrineExtensions\Query\Mysql\Hour", "last_day" => "DoctrineExtensions\Query\Mysql\LastDay", "makedate" => "DoctrineExtensions\Query\Mysql\MakeDate", "minute" => "DoctrineExtensions\Query\Mysql\Minute", "now" => "DoctrineExtensions\Query\Mysql\Now", "month" => "DoctrineExtensions\Query\Mysql\Month", "monthname" => "DoctrineExtensions\Query\Mysql\MonthName", "period_diff" => "DoctrineExtensions\Query\Mysql\PeriodDiff", "second" => "DoctrineExtensions\Query\Mysql\Second", "sectotime" => "DoctrineExtensions\Query\Mysql\SecToTime", "strtodate" => "DoctrineExtensions\Query\Mysql\StrToDate", "time" => "DoctrineExtensions\Query\Mysql\Time", "timediff" => "DoctrineExtensions\Query\Mysql\TimeDiff", "timestampadd" => "DoctrineExtensions\Query\Mysql\TimestampAdd", "timestampdiff" => "DoctrineExtensions\Query\Mysql\TimestampDiff", "timetosec" => "DoctrineExtensions\Query\Mysql\TimeToSec", "truncate" => "DoctrineExtensions\Query\Mysql\Truncate", "week" => "DoctrineExtensions\Query\Mysql\Week", "weekday" => "DoctrineExtensions\Query\Mysql\WeekDay", "year" => "DoctrineExtensions\Query\Mysql\Year", "yearmonth" => "DoctrineExtensions\Query\Mysql\YearMonth", "yearweek" => "DoctrineExtensions\Query\Mysql\YearWeek", "unix_timestamp" => "DoctrineExtensions\Query\Mysql\UnixTimestamp", "utc_timestamp" => "DoctrineExtensions\Query\Mysql\UtcTimestamp", "extract" => "DoctrineExtensions\Query\Mysql\Extract"
                    ],
                    "numeric_functions" => [
                        "acos" => "DoctrineExtensions\Query\Mysql\Acos", "asin" => "DoctrineExtensions\Query\Mysql\Asin", "atan2" => "DoctrineExtensions\Query\Mysql\Atan2", "atan" => "DoctrineExtensions\Query\Mysql\Atan", "bit_count" => "DoctrineExtensions\Query\Mysql\BitCount", "bit_xor" => "DoctrineExtensions\Query\Mysql\BitXor", "ceil" => "DoctrineExtensions\Query\Mysql\Ceil", "cos" => "DoctrineExtensions\Query\Mysql\Cos", "cot" => "DoctrineExtensions\Query\Mysql\Cot", "degrees" => "DoctrineExtensions\Query\Mysql\Degrees", "exp" => "DoctrineExtensions\Query\Mysql\Exp", "floor" => "DoctrineExtensions\Query\Mysql\Floor", "json_contains" => "DoctrineExtensions\Query\Mysql\JsonContains", "json_depth" => "DoctrineExtensions\Query\Mysql\JsonDepth", "json_length" => "DoctrineExtensions\Query\Mysql\JsonLength", "log" => "DoctrineExtensions\Query\Mysql\Log", "log10" => "DoctrineExtensions\Query\Mysql\Log10", "log2" => "DoctrineExtensions\Query\Mysql\Log2", "pi" => "DoctrineExtensions\Query\Mysql\Pi", "power" => "DoctrineExtensions\Query\Mysql\Power", "quarter" => "DoctrineExtensions\Query\Mysql\Quarter", "radians" => "DoctrineExtensions\Query\Mysql\Radians", "rand" => "DoctrineExtensions\Query\Mysql\Rand", "round" => "DoctrineExtensions\Query\Mysql\Round", "stddev" => "DoctrineExtensions\Query\Mysql\StdDev", "sin" => "DoctrineExtensions\Query\Mysql\Sin", "std" => "DoctrineExtensions\Query\Mysql\Std", "tan" => "DoctrineExtensions\Query\Mysql\Tan", "variance" => "DoctrineExtensions\Query\Mysql\Variance"
                    ],
                    "string_functions" => [
                        "aes_decrypt" => "DoctrineExtensions\Query\Mysql\AesDecrypt", "aes_encrypt" => "DoctrineExtensions\Query\Mysql\AesEncrypt", "any_value" => "DoctrineExtensions\Query\Mysql\AnyValue", "ascii" => "DoctrineExtensions\Query\Mysql\Ascii", "binary" => "DoctrineExtensions\Query\Mysql\Binary", "cast" => "DoctrineExtensions\Query\Mysql\Cast", "char_length" => "DoctrineExtensions\Query\Mysql\CharLength", "collate" => "DoctrineExtensions\Query\Mysql\Collate", "concat_ws" => "DoctrineExtensions\Query\Mysql\ConcatWs", "countif" => "DoctrineExtensions\Query\Mysql\CountIf", "crc32" => "DoctrineExtensions\Query\Mysql\Crc32", "degrees" => "DoctrineExtensions\Query\Mysql\Degrees", "field" => "DoctrineExtensions\Query\Mysql\Field", "find_in_set" => "DoctrineExtensions\Query\Mysql\FindInSet", "format" => "DoctrineExtensions\Query\Mysql\Format", "from_base64" => "DoctrineExtensions\Query\Mysql\FromBase64", "greatest" => "DoctrineExtensions\Query\Mysql\Greatest", "group_concat" => "DoctrineExtensions\Query\Mysql\GroupConcat", "hex" => "DoctrineExtensions\Query\Mysql\Hex", "ifelse" => "DoctrineExtensions\Query\Mysql\IfElse", "ifnull" => "DoctrineExtensions\Query\Mysql\IfNull", "inet_aton" => "DoctrineExtensions\Query\Mysql\InetAton", "inet_ntoa" => "DoctrineExtensions\Query\Mysql\InetNtoa", "inet6_aton" => "DoctrineExtensions\Query\Mysql\Inet6Aton", "inet6_ntoa" => "DoctrineExtensions\Query\Mysql\Inet6Ntoa", "instr" => "DoctrineExtensions\Query\Mysql\Instr", "is_ipv4" => "DoctrineExtensions\Query\Mysql\IsIpv4", "is_ipv4_compat" => "DoctrineExtensions\Query\Mysql\IsIpv4Compat", "is_ipv4_mapped" => "DoctrineExtensions\Query\Mysql\IsIpv4Mapped", "is_ipv6" => "DoctrineExtensions\Query\Mysql\IsIpv6", "lag" => "DoctrineExtensions\Query\Mysql\Lag", "lead" => "DoctrineExtensions\Query\Mysql\Lead", "least" => "DoctrineExtensions\Query\Mysql\Least", "lpad" => "DoctrineExtensions\Query\Mysql\Lpad", "match" => "DoctrineExtensions\Query\Mysql\MatchAgainst", "md5" => "DoctrineExtensions\Query\Mysql\Md5", "nullif" => "DoctrineExtensions\Query\Mysql\NullIf", "over" => "DoctrineExtensions\Query\Mysql\Over", "radians" => "DoctrineExtensions\Query\Mysql\Radians", "regexp" => "DoctrineExtensions\Query\Mysql\Regexp", "replace" => "DoctrineExtensions\Query\Mysql\Replace", "rpad" => "DoctrineExtensions\Query\Mysql\Rpad", "sha1" => "DoctrineExtensions\Query\Mysql\Sha1", "sha2" => "DoctrineExtensions\Query\Mysql\Sha2", "soundex" => "DoctrineExtensions\Query\Mysql\Soundex", "str_to_date" => "DoctrineExtensions\Query\Mysql\StrToDate", "substring_index" => "DoctrineExtensions\Query\Mysql\SubstringIndex", "unhex" => "DoctrineExtensions\Query\Mysql\Unhex", "uuid_short" => "DoctrineExtensions\Query\Mysql\UuidShort"
                    ]
                ]
            ]
        ]);

        //security
        $namespace = $container->getExtension("security")->getAlias();
        $container->prependExtensionConfig($namespace, [
            "encoders"=>[
                "Eduxplus\CoreBundle\Entity\BaseUser"=>[
                    "algorithm"=>"bcrypt"
                ]
            ],
            "providers"=>[
                "app_user_provider"=>[
                    "entity"=>[
                        "class"=>"Eduxplus\CoreBundle\Entity\BaseUser",
                        "property"=>"uuid"
                    ]
                ]
            ],
            "access_control"=>[
                [
              "path"=>"^/admin/login$",
              "roles"=>"IS_AUTHENTICATED_ANONYMOUSLY"
                ],
                [
            "path"=>"^/admin",
            "roles"=>"ROLE_USER"
                ]
            ],
            "firewalls"=>[
                "admin"=>[
                    "pattern"=>"^/admin",
                    "anonymous"=>"lazy",
                    "provider"=>"app_user_provider",
                    "guard"=>[
                        "authenticators"=>[
                            "Eduxplus\CoreBundle\Security\PasswordLoginAuthenticator"
                        ],
                        "entry_point"=>"Eduxplus\CoreBundle\Security\PasswordLoginAuthenticator"
                    ],
                    "logout"=>[
                        "path"=>"admin_logout",
                        "target"=>"/admin/login"
                    ],
                    "remember_me"=>[
                        "secret"=>"%kernel.secret%",
                        "lifetime"=>604800,
                        "path"=>"/"
                    ]
                ]
            ]
        ]);

    }
}
