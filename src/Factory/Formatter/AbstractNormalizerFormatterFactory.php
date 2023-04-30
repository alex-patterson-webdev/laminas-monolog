<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Formatter;

use Arp\LaminasFactory\AbstractFactory;
use Monolog\Formatter\NormalizerFormatter;

abstract class AbstractNormalizerFormatterFactory extends AbstractFactory
{
    /**
     * @param array<mixed> $options
     */
    protected function configureNormalizerFormatter(NormalizerFormatter $formatter, array $options): void
    {
        if (isset($options['date_format'])) {
            $formatter->setDateFormat($options['date_format']);
        }

        if (isset($options['max_normalize_depth'])) {
            $formatter->setMaxNormalizeDepth((int)$options['max_normalize_depth']);
        }

        if (isset($options['max_normalize_item_count'])) {
            $formatter->setMaxNormalizeItemCount((int)$options['max_normalize_item_count']);
        }

        if (isset($options['json_pretty_print'])) {
            $formatter->setJsonPrettyPrint((bool)$options['json_pretty_print']);
        }

        if (!empty($options['json_encode_options']) && is_array($options['json_encode_options'])) {
            foreach ($options['json_encode_options'] as $jsonEncodeOption) {
                $formatter->addJsonEncodeOption((int)$jsonEncodeOption);
            }
        }
    }
}
