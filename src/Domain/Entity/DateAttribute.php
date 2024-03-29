<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributeDate\Domain\Entity;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeParameterChangeEvent;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\AttributeDate\Domain\ValueObject\DateFormat;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use JMS\Serializer\Annotation as JMS;

/**
 */
class DateAttribute extends AbstractAttribute
{
    public const TYPE = 'DATE';
    public const FORMAT = 'format';

    /**
     * @param AttributeId        $id
     * @param AttributeCode      $code
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param bool               $multilingual
     * @param DateFormat         $format
     */
    public function __construct(
        AttributeId $id,
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        bool $multilingual,
        DateFormat $format
    ) {
        parent::__construct($id, $code, $label, $hint, $placeholder, $multilingual, [self::FORMAT => $format->getFormat()]);
    }

    /**
     * @JMS\VirtualProperty();
     * @JMS\SerializedName("type")
     *
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return DateFormat
     */
    public function getFormat(): DateFormat
    {
        return new DateFormat($this->getParameter(self::FORMAT));
    }

    /**
     * @param DateFormat $new
     */
    public function changeFormat(DateFormat $new): void
    {
        if ($this->getFormat()->getFormat() !== $new->getFormat()) {
            $this->apply(new AttributeParameterChangeEvent(self::FORMAT, $this->getFormat()->getFormat(), $new->getFormat()));
        }
    }
}
