<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit004fb75ae7e3e8a01b7463da51c0d647
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        '5255c38a0faeba867671b61dfda6d864' => __DIR__ . '/..' . '/paragonie/random_compat/lib/random.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Component\\HttpFoundation\\' => 33,
            'Symfony\\Component\\Finder\\' => 25,
            'Symfony\\Component\\Debug\\' => 24,
            'Symfony\\Component\\Console\\' => 26,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Component\\HttpFoundation\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/http-foundation',
        ),
        'Symfony\\Component\\Finder\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/finder',
        ),
        'Symfony\\Component\\Debug\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/debug',
        ),
        'Symfony\\Component\\Console\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/console',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
    );

    public static $prefixesPsr0 = array (
        'D' => 
        array (
            'Doctrine\\Common\\Inflector\\' => 
            array (
                0 => __DIR__ . '/..' . '/doctrine/inflector/lib',
            ),
        ),
    );

    public static $classMap = array (
        'Hamcrest\\Arrays\\IsArray' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Arrays/IsArray.php',
        'Hamcrest\\Arrays\\IsArrayContaining' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Arrays/IsArrayContaining.php',
        'Hamcrest\\Arrays\\IsArrayContainingInAnyOrder' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Arrays/IsArrayContainingInAnyOrder.php',
        'Hamcrest\\Arrays\\IsArrayContainingInOrder' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Arrays/IsArrayContainingInOrder.php',
        'Hamcrest\\Arrays\\IsArrayContainingKey' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Arrays/IsArrayContainingKey.php',
        'Hamcrest\\Arrays\\IsArrayContainingKeyValuePair' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Arrays/IsArrayContainingKeyValuePair.php',
        'Hamcrest\\Arrays\\IsArrayWithSize' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Arrays/IsArrayWithSize.php',
        'Hamcrest\\Arrays\\MatchingOnce' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Arrays/MatchingOnce.php',
        'Hamcrest\\Arrays\\SeriesMatchingOnce' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Arrays/SeriesMatchingOnce.php',
        'Hamcrest\\AssertionError' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/AssertionError.php',
        'Hamcrest\\BaseDescription' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/BaseDescription.php',
        'Hamcrest\\BaseMatcher' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/BaseMatcher.php',
        'Hamcrest\\Collection\\IsEmptyTraversable' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Collection/IsEmptyTraversable.php',
        'Hamcrest\\Collection\\IsTraversableWithSize' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Collection/IsTraversableWithSize.php',
        'Hamcrest\\Core\\AllOf' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Core/AllOf.php',
        'Hamcrest\\Core\\AnyOf' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Core/AnyOf.php',
        'Hamcrest\\Core\\CombinableMatcher' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Core/CombinableMatcher.php',
        'Hamcrest\\Core\\DescribedAs' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Core/DescribedAs.php',
        'Hamcrest\\Core\\Every' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Core/Every.php',
        'Hamcrest\\Core\\HasToString' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Core/HasToString.php',
        'Hamcrest\\Core\\Is' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Core/Is.php',
        'Hamcrest\\Core\\IsAnything' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Core/IsAnything.php',
        'Hamcrest\\Core\\IsCollectionContaining' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Core/IsCollectionContaining.php',
        'Hamcrest\\Core\\IsEqual' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Core/IsEqual.php',
        'Hamcrest\\Core\\IsIdentical' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Core/IsIdentical.php',
        'Hamcrest\\Core\\IsInstanceOf' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Core/IsInstanceOf.php',
        'Hamcrest\\Core\\IsNot' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Core/IsNot.php',
        'Hamcrest\\Core\\IsNull' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Core/IsNull.php',
        'Hamcrest\\Core\\IsSame' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Core/IsSame.php',
        'Hamcrest\\Core\\IsTypeOf' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Core/IsTypeOf.php',
        'Hamcrest\\Core\\Set' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Core/Set.php',
        'Hamcrest\\Core\\ShortcutCombination' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Core/ShortcutCombination.php',
        'Hamcrest\\Description' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Description.php',
        'Hamcrest\\DiagnosingMatcher' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/DiagnosingMatcher.php',
        'Hamcrest\\FeatureMatcher' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/FeatureMatcher.php',
        'Hamcrest\\Internal\\SelfDescribingValue' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Internal/SelfDescribingValue.php',
        'Hamcrest\\Matcher' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Matcher.php',
        'Hamcrest\\MatcherAssert' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/MatcherAssert.php',
        'Hamcrest\\Matchers' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Matchers.php',
        'Hamcrest\\NullDescription' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/NullDescription.php',
        'Hamcrest\\Number\\IsCloseTo' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Number/IsCloseTo.php',
        'Hamcrest\\Number\\OrderingComparison' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Number/OrderingComparison.php',
        'Hamcrest\\SelfDescribing' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/SelfDescribing.php',
        'Hamcrest\\StringDescription' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/StringDescription.php',
        'Hamcrest\\Text\\IsEmptyString' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Text/IsEmptyString.php',
        'Hamcrest\\Text\\IsEqualIgnoringCase' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Text/IsEqualIgnoringCase.php',
        'Hamcrest\\Text\\IsEqualIgnoringWhiteSpace' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Text/IsEqualIgnoringWhiteSpace.php',
        'Hamcrest\\Text\\MatchesPattern' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Text/MatchesPattern.php',
        'Hamcrest\\Text\\StringContains' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Text/StringContains.php',
        'Hamcrest\\Text\\StringContainsIgnoringCase' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Text/StringContainsIgnoringCase.php',
        'Hamcrest\\Text\\StringContainsInOrder' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Text/StringContainsInOrder.php',
        'Hamcrest\\Text\\StringEndsWith' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Text/StringEndsWith.php',
        'Hamcrest\\Text\\StringStartsWith' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Text/StringStartsWith.php',
        'Hamcrest\\Text\\SubstringMatcher' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Text/SubstringMatcher.php',
        'Hamcrest\\TypeSafeDiagnosingMatcher' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/TypeSafeDiagnosingMatcher.php',
        'Hamcrest\\TypeSafeMatcher' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/TypeSafeMatcher.php',
        'Hamcrest\\Type\\IsArray' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Type/IsArray.php',
        'Hamcrest\\Type\\IsBoolean' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Type/IsBoolean.php',
        'Hamcrest\\Type\\IsCallable' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Type/IsCallable.php',
        'Hamcrest\\Type\\IsDouble' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Type/IsDouble.php',
        'Hamcrest\\Type\\IsInteger' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Type/IsInteger.php',
        'Hamcrest\\Type\\IsNumeric' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Type/IsNumeric.php',
        'Hamcrest\\Type\\IsObject' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Type/IsObject.php',
        'Hamcrest\\Type\\IsResource' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Type/IsResource.php',
        'Hamcrest\\Type\\IsScalar' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Type/IsScalar.php',
        'Hamcrest\\Type\\IsString' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Type/IsString.php',
        'Hamcrest\\Util' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Util.php',
        'Hamcrest\\Xml\\HasXPath' => __DIR__ . '/..' . '/hamcrest/hamcrest-php/hamcrest/Hamcrest/Xml/HasXPath.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit004fb75ae7e3e8a01b7463da51c0d647::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit004fb75ae7e3e8a01b7463da51c0d647::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit004fb75ae7e3e8a01b7463da51c0d647::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit004fb75ae7e3e8a01b7463da51c0d647::$classMap;

        }, null, ClassLoader::class);
    }
}
