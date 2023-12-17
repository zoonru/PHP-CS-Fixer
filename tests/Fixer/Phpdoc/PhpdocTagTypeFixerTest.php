<?php

declare(strict_types=1);

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpCsFixer\Tests\Fixer\Phpdoc;

use PhpCsFixer\ConfigurationException\InvalidFixerConfigurationException;
use PhpCsFixer\Tests\Test\AbstractFixerTestCase;

/**
 * @internal
 *
 * @covers \PhpCsFixer\Fixer\Phpdoc\PhpdocTagTypeFixer
 */
final class PhpdocTagTypeFixerTest extends AbstractFixerTestCase
{
    /**
     * @param array<string, mixed> $configuration
     *
     * @dataProvider provideFixCases
     */
    public function testFix(string $expected, ?string $input = null, array $configuration = []): void
    {
        $this->fixer->configure($configuration);

        $this->doTest($expected, $input);
    }

    public static function provideFixCases(): iterable
    {
        yield [
            '<?php
/**
 * @api
 * @author
 * @copyright
 * @deprecated
 * @example
 * @global
 * @inheritDoc
 * @internal
 * @license
 * @method
 * @package
 * @param
 * @property
 * @return
 * @see
 * @since
 * @throws
 * @todo
 * @uses
 * @var
 * @version
 */',
            '<?php
/**
 * {@api}
 * {@author}
 * {@copyright}
 * {@deprecated}
 * {@example}
 * {@global}
 * {@inheritDoc}
 * {@internal}
 * {@license}
 * {@method}
 * {@package}
 * {@param}
 * {@property}
 * {@return}
 * {@see}
 * {@since}
 * {@throws}
 * {@todo}
 * {@uses}
 * {@var}
 * {@version}
 */',
        ];

        yield [
            '<?php
/**
 * @api
 * @author
 * @copyright
 * @deprecated
 * @example
 * @global
 * @inheritDoc
 * @internal
 * @license
 * @method
 * @package
 * @param
 * @property
 * @return
 * @see
 * @since
 * @throws
 * @todo
 * @uses
 * @var
 * @version
 */',
            '<?php
/**
 * {@api}
 * {@author}
 * {@copyright}
 * {@deprecated}
 * {@example}
 * {@global}
 * {@inheritDoc}
 * {@internal}
 * {@license}
 * {@method}
 * {@package}
 * {@param}
 * {@property}
 * {@return}
 * {@see}
 * {@since}
 * {@throws}
 * {@todo}
 * {@uses}
 * {@var}
 * {@version}
 */',
            ['tags' => [
                'api' => 'annotation',
                'author' => 'annotation',
                'copyright' => 'annotation',
                'deprecated' => 'annotation',
                'example' => 'annotation',
                'global' => 'annotation',
                'inheritDoc' => 'annotation',
                'internal' => 'annotation',
                'license' => 'annotation',
                'method' => 'annotation',
                'package' => 'annotation',
                'param' => 'annotation',
                'property' => 'annotation',
                'return' => 'annotation',
                'see' => 'annotation',
                'since' => 'annotation',
                'throws' => 'annotation',
                'todo' => 'annotation',
                'uses' => 'annotation',
                'var' => 'annotation',
                'version' => 'annotation',
            ]],
        ];

        yield [
            '<?php
/**
 * {@api}
 * {@author}
 * {@copyright}
 * {@deprecated}
 * {@example}
 * {@global}
 * {@inheritDoc}
 * {@internal}
 * {@license}
 * {@method}
 * {@package}
 * {@param}
 * {@property}
 * {@return}
 * {@see}
 * {@since}
 * {@throws}
 * {@todo}
 * {@uses}
 * {@var}
 * {@version}
 */',
            '<?php
/**
 * @api
 * @author
 * @copyright
 * @deprecated
 * @example
 * @global
 * @inheritDoc
 * @internal
 * @license
 * @method
 * @package
 * @param
 * @property
 * @return
 * @see
 * @since
 * @throws
 * @todo
 * @uses
 * @var
 * @version
 */',
            ['tags' => [
                'api' => 'inline',
                'author' => 'inline',
                'copyright' => 'inline',
                'deprecated' => 'inline',
                'example' => 'inline',
                'global' => 'inline',
                'inheritDoc' => 'inline',
                'internal' => 'inline',
                'license' => 'inline',
                'method' => 'inline',
                'package' => 'inline',
                'param' => 'inline',
                'property' => 'inline',
                'return' => 'inline',
                'see' => 'inline',
                'since' => 'inline',
                'throws' => 'inline',
                'todo' => 'inline',
                'uses' => 'inline',
                'var' => 'inline',
                'version' => 'inline',
            ]],
        ];

        yield [
            '<?php
/** @api */',
            '<?php
/** {@api} */',
        ];

        yield [
            '<?php
/**
 * @deprecated since version X
 */',
            '<?php
/**
 * {@deprecated since version X}
 */',
        ];

        yield [
            '<?php
/**
 * {@deprecated since version X}
 */',
            '<?php
/**
 * @deprecated since version X
 */',
            ['tags' => ['deprecated' => 'inline']],
        ];

        yield [
            '<?php
/** {@deprecated since version X} */',
            '<?php
/** @deprecated since version X */',
            ['tags' => ['deprecated' => 'inline']],
        ];

        yield [
            '<?php
/**
 * @inheritDoc
 */',
            '<?php
/**
 * {@inheritDoc}
 */',
        ];

        yield [
            '<?php
/**
 * @inheritdoc
 */',
            '<?php
/**
 * {@inheritdoc}
 */',
        ];

        yield [
            '<?php
/**
 * {@inheritdoc}
 */',
            '<?php
/**
 * @inheritdoc
 */',
            ['tags' => ['inheritDoc' => 'inline']],
        ];

        yield [
            '<?php
/**
 * Some summary.
 *
 * {@inheritdoc}
 */',
        ];

        yield [
            '<?php
/**
 * Some summary.
 *
 * Some description.
 *
 * {@inheritdoc}
 *
 * More description.
 *
 * @param Foo $foo
 */',
        ];

        yield [
            '<?php
/**
 * {@inheritdoc}
 *
 * More description.
 */',
        ];

        yield [
            '<?php
/**
 *
 * @inheritdoc
 *
 */',
            '<?php
/**
 *
 * {@inheritdoc}
 *
 */',
        ];

        yield [
            '<?php
/**
 * @return array{0: float, 1: int}
 */',
        ];

        yield [
            '<?php
/** @internal Please use {@see Foo} instead */',
            '<?php
/** {@internal Please use {@see Foo} instead} */',
        ];

        yield [
            '<?php
/**
 * @internal Please use {@see Foo} instead
 */',
            '<?php
/**
 * {@internal Please use {@see Foo} instead}
 */',
        ];

        yield [
            '<?php
/**
 *
 * @internal Please use {@see Foo} instead
 *
 */',
            '<?php
/**
 *
 * {@internal Please use {@see Foo} instead}
 *
 */',
        ];

        yield [
            '<?php
/** @internal Foo Bar {@see JsonSerializable} */',
        ];
    }

    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidFixerConfigurationException::class);
        $this->expectExceptionMessageMatches('#^\[phpdoc_tag_type\] Invalid configuration: Unknown tag type "foo"\.#');

        $this->fixer->configure([
            'tags' => ['inheritDoc' => 'foo'],
        ]);
    }
}
